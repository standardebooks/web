<?
use Safe\DateTimeImmutable;

class Patron{
	use Traits\PropertyFromRequest;

	public int $PatronId;
	public int $UserId;
	/** The `Subscription` currently granting this `Patron` status. If this `Patron` status was granted via a one-time payment, `QualifyingPaymentId` is set instead. */
	public ?int $SubscriptionId = null;
	/** The one-time `Payment` currently granting this `Patron` status. If this `Patron` status was granted via a recurring subscription, `SubscriptionId` is set instead. */
	public ?int $QualifyingPaymentId = null;
	public bool $IsAnonymous;
	public ?string $AlternateName = null;
	public DateTimeImmutable $CreatedAt;
	public ?DateTimeImmutable $EndedAt = null;

	/** The `CycleType` of the `Patron`'s funding source, or unlimited when it has no funding source. */
	public Enums\CycleType $CycleType{
		/** @throws Exceptions\SubscriptionNotFoundException If the `Subscription` can't be found. */
		get{
			$cycleType = $this->Subscription?->CycleType;

			if($cycleType === null){
				// No subscription, so if we have a qualifying `Payment`, then we must be yearly Fractured Atlas subscription.
				$cycleType = $this->QualifyingPaymentId !== null ? Enums\CycleType::Yearly : Enums\CycleType::Unlimited;
			}

			return $cycleType;
		}
	}

	public User $User{
		/** @throws Exceptions\UserNotFoundException If the user no longer exists. */
		get{
			return $this->User ??= User::Get($this->UserId);
		}
	}

	public ?Subscription $Subscription = null{
		/** @throws Exceptions\SubscriptionNotFoundException If the `Subscription` can't be found. */
		get{
			if($this->SubscriptionId === null){
				return null;
			}

			return $this->Subscription ??= Subscription::Get($this->SubscriptionId);
		}
	}

	public private(set) ?Payment $LastPayment = null{
		get{
			return $this->LastPayment ??= Db::Query('
						select *
						from Payments
						where UserId = ?
						order by CreatedAt desc
						limit 1
					', [$this->UserId], Payment::class)[0] ?? null;
		}
	}


	// *******
	// METHODS
	// *******

	/**
	 * Update this `Patron`'s `Subscription` to the most recent eligible `Subscription`.
	 *
	 * @param ?Payment $payment A Stripe `Payment` that is understood to qualify this `Patron` for Patrons Circle membership, or `null` if the relevant payment isn't a Stripe payment or doesn't qualify.
	 */
	public function ReconcileFunding(?Payment $payment = null): bool{
		// An unlimited grant has no funding to replace, and additional one-time gifts do not extend an existing grant.
		if($this->EndedAt !== null || ($this->SubscriptionId === null && $this->QualifyingPaymentId === null)){
			return false;
		}

		$stripeSubscriptionId = null;
		if(
			$payment?->Processor === Enums\PaymentProcessorType::Stripe
			&&
			$payment->UserId === $this->UserId
			&&
			$payment->IsRecurring
			&&
			$payment->RefundedAt === null
		){
			$stripeSubscriptionId = $payment->SubscriptionId;
		}

		$subscription = Db::Query('
			select s.* from Subscriptions s
			inner join Payments p on p.SubscriptionId = s.SubscriptionId and p.UserId = s.UserId
			where s.UserId = ? and p.RefundedAt is null and p.IsMatchingDonation = false
			and (
				exists (select * from Patrons historical where historical.SubscriptionId = s.SubscriptionId and historical.UserId = s.UserId)
				or (s.Processor = ? and s.SubscriptionId = ?)
				or (s.Processor = ? and exists (
					select * from Payments first
					where first.PaymentId = (select initial.PaymentId from Payments initial where initial.SubscriptionId = s.SubscriptionId order by initial.CreatedAt, initial.PaymentId limit 1)
					and first.Amount >= ? and first.IsMatchingDonation = false
				))
			)
			and (
				(s.Processor = ? and p.CreatedAt > ? - interval 45 day)
				or (s.Processor = ? and (
					(s.CycleType = ? and ? < p.CreatedAt + interval 1 month + interval 15 day)
					or (s.CycleType = ? and ? < p.CreatedAt + interval 1 year + interval 15 day)
				))
			)
			order by p.CreatedAt desc, p.PaymentId desc
			limit 1
		', [$this->UserId, Enums\PaymentProcessorType::Stripe, $stripeSubscriptionId, Enums\PaymentProcessorType::FracturedAtlas, PATRONS_CIRCLE_MONTHLY_COST, Enums\PaymentProcessorType::FracturedAtlas, NOW, Enums\PaymentProcessorType::Stripe, Enums\CycleType::Monthly, NOW, Enums\CycleType::Yearly, NOW], Subscription::class)[0] ?? null;

		if($subscription === null){
			return false;
		}

		// Preserve the grant and its benefits instead of sending another welcome or counting another signup.
		Db::Query('update Patrons set SubscriptionId = ?, QualifyingPaymentId = null where PatronId = ? and EndedAt is null', [$subscription->SubscriptionId, $this->PatronId]);
		$this->SubscriptionId = $subscription->SubscriptionId;
		$this->QualifyingPaymentId = null;
		$this->Subscription = $subscription;
		return true;
	}

	/**
	 * Fill this Patron with values from the HTTP request body.
	 */
	public function FillFromRequestBody(): void{
		$this->PropertyFromRequest('IsAnonymous');
		$this->PropertyFromRequest('AlternateName');
	}

	/**
	 * Validate this patron and, when supplied, the payment funding its benefits.
	 *
	 * @throws Exceptions\PatronInvalidException If the user, alternate name, or funding payment is invalid.
	 */
	public function Validate(?Payment $payment = null): void{
		$error = new Exceptions\PatronInvalidException();
		$this->IsAnonymous ??= false;

		if(!isset($this->UserId) || $this->UserId <= 0){
			$error->Add(new Exceptions\UserNotFoundException());
		}
		if(mb_strlen($this->AlternateName ?? '') > 80){
			$error->Add(new Exceptions\StringTooLongException('alternate name'));
		}
		if($payment !== null && $payment->IsRecurring && $payment->SubscriptionId === null){
			$error->Add(new Exceptions\AppException('A recurring payment must belong to a subscription.'));
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * Create this patron and grant their benefits.
	 *
	 * @throws Exceptions\UserNotFoundException If the patron user no longer exists.
	 * @throws Exceptions\PatronInvalidException If the patron fields are invalid.
	 */
	public function Create(bool $sendWelcomeEmail = true): void{
		$this->Validate();

		$existingPatrons = Db::Query('select EndedAt from Patrons where UserId = ?', [$this->UserId]);
		$isReturning = sizeof($existingPatrons) > 0;

		// We can only have one Patron status at a time.
		foreach($existingPatrons as $existingPatron){
			if($existingPatron->EndedAt === null){
				return;
			}
		}

		$this->CreatedAt ??= NOW;
		$this->PatronId = Db::QueryInt('
			insert into Patrons (CreatedAt, UserId, SubscriptionId, QualifyingPaymentId, IsAnonymous, AlternateName)
			values(?, ?, ?, ?, ?, ?)
			returning PatronId
		', [$this->CreatedAt, $this->UserId, $this->SubscriptionId, $this->QualifyingPaymentId, $this->IsAnonymous, $this->AlternateName]);

		Db::Query('
			insert into Benefits (UserId, CanVote, CanAccessFeeds, CanBulkDownload)
			values (?,
			        true,
			        true,
			        true) on duplicate key
			update CanVote = true,
			       CanAccessFeeds = true,
			       CanBulkDownload = true
		', [$this->UserId]);

		// If requested, send either the first-time or returning Patron email.
		if($sendWelcomeEmail){
			$this->SendWelcomeEmail($isReturning);
		}

		if(!$isReturning){
			DonationDrive::AddCountToIsActive(Enums\DonationTargetType::NewPatrons);
		}

		$newsletterSubscription = new NewsletterSubscription();
		$newsletterSubscription->NewsletterId = PATRONS_CIRCLE_NEWS_NEWSLETTER_ID;
		$newsletterSubscription->UserId = $this->UserId;
		$newsletterSubscription->User = $this->User;
		$newsletterSubscription->IsConfirmed = true;
		try{
			$newsletterSubscription->Create();
		}
		catch(Exceptions\AppException){
			// Pass.
		}
	}

	/**
	 * Send the welcome email for this patron.
	 *
	 * @throws Exceptions\UserNotFoundException If the patron user no longer exists.
	 */
	private function SendWelcomeEmail(bool $isReturning): void{
		if(isset($this->User)){
			if($this->User->Email !== null && $this->User->CanReceiveEmail){
				$hasRecurringSubscription = isset($this->SubscriptionId);

				$em = new QueuedEmailMessage();
				$em->To = $this->User->Email;
				$em->ToName = $this->User->Name;
				$em->From = EDITOR_IN_CHIEF_EMAIL_ADDRESS;
				$em->FromName = EDITOR_IN_CHIEF_NAME;
				$em->Subject = 'Thank you for supporting Standard Ebooks!';
				$em->BodyHtml = Template::EmailPatronsCircleWelcome(isAnonymous: $this->IsAnonymous, isReturning: $isReturning, hasRecurringSubscription: $hasRecurringSubscription);
				$em->BodyText = Template::EmailPatronsCircleWelcomeText(isAnonymous: $this->IsAnonymous, isReturning: $isReturning, hasRecurringSubscription: $hasRecurringSubscription);
				$em->Send();
			}

			// Notify admins for every new Stripe-funded patron, including returning patrons.
			if(!$isReturning || $this->User->Payments[0]->Processor === Enums\PaymentProcessorType::Stripe){
				$em = new QueuedEmailMessage();
				$em->To = ACCOUNTING_EMAIL_ADDRESS;
				$em->From = ADMIN_EMAIL_ADDRESS;
				$em->FromName = ACCOUNTING_FROM_NAME;
				$em->Subject = 'New Patrons Circle member';
				try{
					if($this->CycleType !== Enums\CycleType::Unlimited){
						$em->Subject .= ' (' . ucfirst($this->CycleType->value) . ')';
					}
				}
				catch(Exceptions\SubscriptionNotFoundException){
					// Leave the subject unchanged if the subscription can't be found.
				}
				$em->BodyHtml = Template::EmailAdminNewPatron(patron: $this, payment: $this->User->Payments[0]);
				$em->BodyText = Template::EmailAdminNewPatronText(patron: $this, payment: $this->User->Payments[0]);;
				$em->Send();
			}
		}
	}

	/**
	 * End this patron's membership and send the completion email.
	 *
	 * @throws Exceptions\UserNotFoundException If the patron user no longer exists.
	 * @throws Exceptions\SubscriptionNotFoundException If the linked subscription no longer exists.
	 */
	public function End(?int $ebooksThisYear): void{
		if($ebooksThisYear === null){
			$ebooksThisYear = Db::QueryInt('select count(*) from Ebooks where EbookCreatedAt >= ? - interval 1 year', [NOW]);
		}

		Db::Query('
				update Patrons
				set EndedAt = ?
				where PatronId = ? and EndedAt is null
			', [NOW, $this->PatronId]);

		if(Db::$LastQueryAffectedRowCount === 0){
			return;
		}

		Db::Query('
				update Benefits
				set CanAccessFeeds = false,
				    CanVote = false,
				    CanBulkDownload = false
				where UserId = ? and not exists (select * from Patrons where UserId = ? and EndedAt is null)
			', [$this->UserId, $this->UserId]);

		// Unsubscribe them from the Patrons News newsletter.
		Db::Query('
				delete from NewsletterSubscriptions
				where UserId = ?
				and NewsletterId = ?
				and not exists (select * from Patrons where UserId = ? and EndedAt is null)
			', [$this->UserId, PATRONS_CIRCLE_NEWS_NEWSLETTER_ID, $this->UserId]);

		// Email the patron to notify them their term has ended.
		if($this->LastPayment !== null && $this->User->Email !== null && $this->User->CanReceiveEmail){
			$em = new QueuedEmailMessage();
			$em->To = $this->User->Email;
			$em->ToName = $this->User->Name;
			$em->From = EDITOR_IN_CHIEF_EMAIL_ADDRESS;
			$em->FromName = EDITOR_IN_CHIEF_NAME;
			$em->Subject = 'Will you continue to help us make free, beautiful digital literature?';

			if($this->CycleType == Enums\CycleType::Monthly){
				// Email recurring donors who have lapsed.
				$em->BodyHtml = Template::EmailPatronsCircleRecurringCompleted();
				$em->BodyText = Template::EmailPatronsCircleRecurringCompletedText();
			}
			else{
				// Email one time donors who have expired after one year.
				$em->BodyHtml = Template::EmailPatronsCircleCompleted(ebooksThisYear: $ebooksThisYear);
				$em->BodyText = Template::EmailPatronsCircleCompletedText(ebooksThisYear: $ebooksThisYear);
			}

			$em->Send();
		}
	}


	// ***********
	// ORM METHODS
	// ***********

	/**
	 * @throws Exceptions\PatronNotFoundException
	 */
	public static function Get(?int $userId): Patron{
		if($userId === null){
			throw new Exceptions\PatronNotFoundException();
		}

		$result = Db::Query('
			select *
			from Patrons
			where UserId = ?
			order by CreatedAt desc
			limit 1
			', [$userId], Patron::class);

		return $result[0] ?? throw new Exceptions\PatronNotFoundException();;
	}

	/**
	 * @throws Exceptions\PatronNotFoundException
	 */
	public static function GetByEmail(?string $email): Patron{
		if($email === null){
			throw new Exceptions\PatronNotFoundException();
		}

		$result = Db::Query('
			select p.*
			from Patrons p
			inner join Users u using(UserId)
			where u.Email = ?
			order by p.CreatedAt desc
			limit 1
		', [$email], Patron::class);

		return $result[0] ?? throw new Exceptions\PatronNotFoundException();
	}

	/**
	 * Return the number of `Patrons` that were active at the end of each day in the given date range.
	 *
	 * @return array<array{date: DateTimeImmutable, monthlyCount: int, yearlyCount: int}>
	 */
	public static function GetActivePatronCountsByDay(DateTimeImmutable $from, DateTimeImmutable $to): array{
		if($from > $to){
			[$from, $to] = [$to, $from];
		}

		// Use `set statement max_recursive_iterations` to allow for very wide date ranges. Otherwise, MariaDB's default of 1,000 might cause the result set to end prematurely.
		$result = Db::Query('
			set statement max_recursive_iterations = 100000 for
			with recursive Days as (
				select date(?) as Day
				union all
				select cast(date_add(Day, interval 1 day) as date)
				from Days
				where Day < date(?)
			)
			select
				Days.Day,
				sum(case when Subscriptions.CycleType = ? then 1 else 0 end) as MonthlyCount,
				sum(case when (Patrons.SubscriptionId is null and Patrons.QualifyingPaymentId is not null) or Subscriptions.CycleType = ? then 1 else 0 end) as YearlyCount
			from Days
			left join Patrons on
				date(Patrons.CreatedAt) <= Days.Day
				and (Patrons.EndedAt is null or date(Patrons.EndedAt) > Days.Day)
			left join Subscriptions on Subscriptions.SubscriptionId = Patrons.SubscriptionId
			group by Days.Day
			order by Days.Day
		', [$from, $to, Enums\CycleType::Monthly, Enums\CycleType::Yearly]);

		$output = [];
		foreach($result as $row){
			$output[] = [
				'date' => $row->Day,
				'monthlyCount' => $row->MonthlyCount,
				'yearlyCount' => $row->YearlyCount,
			];
		}

		return $output;
	}
}
