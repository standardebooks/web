<?
use Safe\DateTimeImmutable;
use function Safe\preg_match;

final class Payment{
	use Traits\FromRow;
	use Traits\PropertyFromRequest;

	public int $PaymentId;
	public ?int $UserId = null;
	public ?int $SubscriptionId = null;
	/** Whether this payment restored a Fractured Atlas `Subscription` with a late `Payment`. */
	public bool $HasReopenedSubscription = false;
	public DateTimeImmutable $CreatedAt;
	public Enums\PaymentProcessorType $Processor;
	public string $TransactionId;
	public float $Amount;
	/** May be `null` if a Stripe transaction hasn't reconciled its fee yet. */
	public ?float $Fee = null;
	/** Mostly for reconciling Fractured Atlas transactions since we don't get a unifying subscription ID for them. */
	public bool $IsRecurring;
	/** Whether Fractured Atlas metadata suggests this payment came through a donor advised fund. */
	public bool $IsDonorAdvisedFund = false;
	public bool $IsMatchingDonation = false;
	public bool $IsRecordedExternally = false;
	public ?DateTimeImmutable $RefundedAt = null;

	public ?User $User = null{
		/** @throws Exceptions\UserNotFoundException If the `User` can't be found. */
		get{
			if(!isset($this->User) && $this->UserId !== null){
				$this->User = User::Get($this->UserId);
			}

			return $this->User;
		}
	}

	public ?Subscription $Subscription = null{
		/** @throws Exceptions\SubscriptionNotFoundException If the `Subscription` can't be found. */
		get{
			if(!isset($this->Subscription) && $this->SubscriptionId !== null){
				$this->Subscription = Subscription::Get($this->SubscriptionId);
			}

			return $this->Subscription;
		}
	}

	public string $ProcessorUrl{
		get{
			if(!isset($this->ProcessorUrl)){
				switch($this->Processor){
					case Enums\PaymentProcessorType::FracturedAtlas:
						// This is not a permalink per se, because the FA permalink shows us the donor-facing receipt, without useful information like attribution, etc. However if we search by donation ID, we *do* get that information.
						$this->ProcessorUrl = 'https://fundraising.fracturedatlas.org/admin/general_support/donations?query=' . rawurlencode($this->TransactionId);
						break;
					case Enums\PaymentProcessorType::Stripe:
						$this->ProcessorUrl = 'https://dashboard.stripe.com/' . (SITE_STATUS == SITE_STATUS_DEV ? 'test/' : '') . 'payments/' . rawurlencode($this->TransactionId);
						break;
					default:
						$this->ProcessorUrl = '';
				}
			}

			return $this->ProcessorUrl;
		}
	}


	// *******
	// METHODS
	// *******

	/**
	 * Fill this `Payment` with values from the HTTP request body.
	 */
	public function FillFromRequestBody(): void{
		$this->PropertyFromRequest('CreatedAt');
		$this->PropertyFromRequest('Processor');
		$this->PropertyFromRequest('TransactionId');
		$this->PropertyFromRequest('Amount');
		$this->PropertyFromRequest('Fee');
		$this->PropertyFromRequest('IsRecurring');
		$this->PropertyFromRequest('IsMatchingDonation');
	}

	/**
	 * @throws Exceptions\PaymentInvalidException If `Payment` is invalid.
	 */
	public function Validate(): void{
		$error = new Exceptions\PaymentInvalidException();
		$this->IsRecurring ??= false;

		if(!isset($this->CreatedAt) || intval($this->CreatedAt->format('Y')) < 1000 || intval($this->CreatedAt->format('Y')) > 9999){
			$error->Add(new Exceptions\AppException('Enter a valid payment date and time in UTC.'));
		}

		if(!isset($this->Processor)){
			$error->Add(new Exceptions\AppException('Select a valid payment processor.'));
		}

		if(isset($this->Processor)){
			$transactionIdPattern = match($this->Processor){
				Enums\PaymentProcessorType::FracturedAtlas => '/\A[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}\z/',
				Enums\PaymentProcessorType::Stripe => '/\Api_[A-Za-z0-9]+\z/'
			};

			if(!isset($this->TransactionId) || !preg_match($transactionIdPattern, $this->TransactionId)){
				$error->Add(new Exceptions\AppException(match($this->Processor){
					Enums\PaymentProcessorType::FracturedAtlas => 'Enter a Fractured Atlas transaction ID in UUID format.',
					Enums\PaymentProcessorType::Stripe => 'Enter a Stripe PaymentIntent ID starting with pi_ and followed by letters and numbers.'
				}));
			}
		}

		if(isset($this->TransactionId) && mb_strlen($this->TransactionId) > 80){
			$error->Add(new Exceptions\StringTooLongException('transaction ID'));
		}

		if(!isset($this->Amount) || !is_finite($this->Amount) || $this->Amount <= 0 || $this->Amount > 999999.99 || round($this->Amount, 2) !== $this->Amount){
			$error->Add(new Exceptions\AppException('Enter a positive payment amount up to 999999.99, with at most two decimal places.'));
		}

		if($this->Fee !== null && (!is_finite($this->Fee) || $this->Fee < 0 || $this->Fee > 999999.99 || round($this->Fee, 2) !== $this->Fee || (isset($this->Amount) && $this->Fee > $this->Amount))){
			$error->Add(new Exceptions\AppException('Enter a nonnegative fee no greater than the payment amount, with at most two decimal places.'));
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * Fill this payment from a successful Stripe `PaymentIntent` and its donation source.
	 *
	 * @throws Exceptions\PaymentInvalidException If `Payment` is invalid.
	 */
	public static function FromStripeWebhook(\Stripe\PaymentIntent $paymentIntent, \Stripe\Checkout\Session|\Stripe\Subscription $checkoutSession, ?\Stripe\Customer $customer = null): Payment{
		$payment = new Payment();

		$charge = $paymentIntent->latest_charge;
		if($paymentIntent->status !== 'succeeded'){
			throw new Exceptions\PaymentInvalidException('Stripe PaymentIntent has not succeeded.');
		}
		if($paymentIntent->currency !== 'usd'){
			throw new Exceptions\PaymentInvalidException('Stripe PaymentIntent currency is not USD.');
		}
		if(!$charge instanceof \Stripe\Charge){
			throw new Exceptions\PaymentInvalidException('Stripe PaymentIntent has no expanded charge.');
		}
		if(!$charge->paid || $charge->status !== 'succeeded'){
			throw new Exceptions\PaymentInvalidException('Stripe charge has not succeeded.');
		}
		if($charge->balance_transaction !== null && !$charge->balance_transaction instanceof \Stripe\BalanceTransaction){
			throw new Exceptions\PaymentInvalidException('Stripe charge has no expanded balance transaction.');
		}

		$payment->Processor = Enums\PaymentProcessorType::Stripe;
		$payment->TransactionId = $paymentIntent->id;
		try{
			$payment->CreatedAt = new DateTimeImmutable('@' . $charge->created);
		}
		catch(\Exception $ex){
			throw new Exceptions\PaymentInvalidException(previous: $ex);
		}

		$payment->Amount = $charge->amount / 100;
		// Asynchronous capture can supply the balance transaction in a later `charge.updated` event.
		$payment->Fee = $charge->balance_transaction instanceof \Stripe\BalanceTransaction ? $charge->balance_transaction->fee / 100 : null;

		$email = null;
		// Prefer the masthead name stored on the session or subscription.
		/** @var ?string $name */
		$name = $checkoutSession->metadata['name'] ?? null;

		if($checkoutSession instanceof \Stripe\Checkout\Session){
			$payment->IsRecurring = false;
			$email = trim($checkoutSession->customer_details->email ?? '');
		}
		else{
			$payment->IsRecurring = true;
		}

		// Fall back to Stripe's name when no masthead name was supplied.
		if(trim($name ?? '') === ''){
			$name = $customer?->name;
			if($checkoutSession instanceof \Stripe\Checkout\Session){
				$name = $checkoutSession->customer_details->name ?? null;
			}
		}
		$name = trim($name ?? '');
		if($name === ''){
			$name = null;
		}

		if(($email === null || $email == '') && $customer?->email !== null){
			$email = trim($customer->email);
		}

		if(is_string($email) && $email != ''){
			$user = new User();
			$user->Email = $email;

			$user->Name = $name;

			$payment->User = $user;
		}

		return $payment;
	}

	/**
	 * If this is a donor-advised fund `Payment`, then decide if this `Payment` is actually a recurring DAF subscription. If so, find an existing `Subscription` to link this to, or create a new one.
	 *
	 * @throws Exceptions\SubscriptionNotFoundException If a linked subscription no longer exists.
	 */
	public function InferRecurringFundSubscription(): void{
		if($this->Processor !== Enums\PaymentProcessorType::FracturedAtlas || !$this->IsDonorAdvisedFund || $this->UserId === null || $this->IsMatchingDonation || $this->RefundedAt !== null || !isset($this->PaymentId)){
			return;
		}

		$userId = $this->UserId;
		Db::Transaction(function() use ($userId): void{
			// Serialize inference for a donor so simultaneous imports cannot create duplicate streams.
			Db::Query('select UserId from Users where UserId = ? for update', [$userId]);
			$subscription = Db::Query('
				select s.* from Subscriptions s
				where s.UserId = ? and s.Processor = ? and s.Amount = ? and s.CycleType = ?
				and exists (select * from Payments p where p.SubscriptionId = s.SubscriptionId and p.IsDonorAdvisedFund = true)
				order by s.CreatedAt desc, s.SubscriptionId desc limit 1
			', [$userId, $this->Processor, $this->Amount, Enums\CycleType::Monthly], Subscription::class)[0] ?? null;

			if($subscription === null){
				// Check the immediately adjacent successful donations on both sides to accommodate out-of-order imports.
				// Apply fund, amount, and timing requirements after selecting neighbors, so unrelated gifts cannot be skipped.
				$otherPayment = Db::Query('
					select * from (
						(select * from Payments where UserId = ? and Processor = ?
							and IsMatchingDonation = false and RefundedAt is null
							and (CreatedAt, PaymentId) < (?, ?)
							order by CreatedAt desc, PaymentId desc limit 1)
						union all
						(select * from Payments where UserId = ? and Processor = ?
							and IsMatchingDonation = false and RefundedAt is null
							and (CreatedAt, PaymentId) > (?, ?)
							order by CreatedAt, PaymentId limit 1)
					) adjacent
					where IsDonorAdvisedFund = true and Amount = ?
					and ((CreatedAt between ? - interval 75 day and ? - interval 15 day)
						or (CreatedAt between ? + interval 15 day and ? + interval 75 day))
					order by CreatedAt, PaymentId limit 1
				', [$userId, $this->Processor, $this->CreatedAt, $this->PaymentId, $userId, $this->Processor, $this->CreatedAt, $this->PaymentId, $this->Amount, $this->CreatedAt, $this->CreatedAt, $this->CreatedAt, $this->CreatedAt], Payment::class)[0] ?? null;

				if($otherPayment === null){
					return;
				}

				$subscription = $otherPayment->Subscription;
				if($subscription === null){
					$subscription = new Subscription();
					$subscription->UserId = $userId;
					$subscription->Processor = $this->Processor;
					$subscription->Amount = $this->Amount;
					$subscription->CycleType = Enums\CycleType::Monthly;
					$subscription->CreatedAt = min($this->CreatedAt, $otherPayment->CreatedAt);
					$subscription->Create();
				}

				Db::Query('update Payments set SubscriptionId = ?, IsRecurring = true where PaymentId = ?', [$subscription->SubscriptionId, $otherPayment->PaymentId]);
			}

			$this->SubscriptionId = $subscription->SubscriptionId;
			$this->Subscription = $subscription;
			$this->IsRecurring = true;
			Db::Query('update Payments set SubscriptionId = ?, IsRecurring = true where PaymentId = ?', [$this->SubscriptionId, $this->PaymentId]);

			// Re-open the `Subscription` if necessary.
			Db::Query('update Subscriptions set EndedAt = null where SubscriptionId = ? and EndedAt is not null and exists (select * from Payments where SubscriptionId = ? and RefundedAt is null and CreatedAt > ? - interval 75 day)', [$this->SubscriptionId, $this->SubscriptionId, NOW]);

			$this->HasReopenedSubscription = Db::$LastQueryAffectedRowCount > 0;
		});
	}

	/**
	 * Create this `Payment` and its `User`, if necessary.
	 *
	 * @throws Exceptions\PaymentExistsException If a `Payment` with this transaction ID already exists.
	 * @throws Exceptions\PaymentInvalidException If the payment fields are invalid.
	 * @throws Exceptions\UserNotFoundException If this `Payment`'s `UserId` is set but the `User` can't be found.
	 * @throws Exceptions\SubscriptionNotFoundException If a linked fund subscription no longer exists.
	 */
	public function Create(): void{
		$this->CreatedAt ??= NOW;
		$this->Validate();

		if($this->UserId === null){
			// Check if we have to create a new `User` in the database.

			// If the `User` isn't `null`, then check if we already have this `User` in our system.
			if($this->User !== null && $this->User->Email !== null){
				try{
					$user = User::GetByEmail($this->User->Email);
					$name = $this->User->Name;					
					$this->User = $user;

					// Update an existing user's name when the payment supplies a different name.
					if($name !== null && $name !== '' && $this->User->Name !== $name){
						$this->User->Name = $name;
						Db::Query('
							update Users
							set Name = ?
							where UserId = ?
						', [$this->User->Name, $this->User->UserId]);
					}
				}
				catch(Exceptions\UserNotFoundException){
					// User doesn't exist, create it now.
					// Don't require an email address because we might be an anonymous `User`, or a matching donation from a fund.
					try{
						$this->User->Create(requireEmail: false);
					}
					catch(Exceptions\UserExistsException | Exceptions\UserInvalidException){
						// `User` already exists, pass.
					}
				}

				$this->UserId = $this->User->UserId;
			}
		}

		if($this->Processor == Enums\PaymentProcessorType::FracturedAtlas){
			$this->IsRecordedExternally = true;
		}

		$subscriptionId = $this->SubscriptionId;
		$isRecurring = $this->IsRecurring;
		Db::Transaction(function() use ($subscriptionId, $isRecurring): void{
			// Reset state if a deadlock requires a re-try.
			$this->SubscriptionId = $subscriptionId;
			$this->Subscription = null;
			$this->IsRecurring = $isRecurring;
			$this->HasReopenedSubscription = false;

			if($this->UserId !== null){
				// Lock the row during this transaction.
				Db::Query('select UserId from Users where UserId = ? for update', [$this->UserId]);
			}

			try{
				$this->PaymentId = Db::QueryInt('
					insert into Payments (UserId, SubscriptionId, CreatedAt, Processor, TransactionId, Amount, Fee, IsRecurring, IsMatchingDonation, RefundedAt, IsRecordedExternally, IsDonorAdvisedFund)
					values(?,
					       ?,
					       ?,
					       ?,
					       ?,
					       ?,
					       ?,
					       ?,
					       ?,
					       ?,
					       ?,
					       ?)
					returning PaymentId
				', [$this->UserId, $this->SubscriptionId, $this->CreatedAt, $this->Processor, $this->TransactionId, $this->Amount, $this->Fee, $this->IsRecurring, $this->IsMatchingDonation, $this->RefundedAt, $this->IsRecordedExternally, $this->IsDonorAdvisedFund]);
			}
			catch(Exceptions\DuplicateDatabaseKeyException){
				throw new Exceptions\PaymentExistsException();
			}

			$this->InferRecurringFundSubscription();

			if($this->Processor == Enums\PaymentProcessorType::FracturedAtlas && $this->IsRecurring && $this->UserId !== null && $this->SubscriptionId === null){
				// A changed amount or a gap longer than 45 days from this `User`'s last `Payment` starts a new inferred stream.
				$previousPayment = Db::MultiTableSelect('
					select * from Payments
					left join Subscriptions
					on Payments.SubscriptionId = Subscriptions.SubscriptionId
					where Payments.UserId = ? and Payments.Processor = ? and Payments.IsRecurring = true
					and Payments.IsDonorAdvisedFund = ?
					and Payments.PaymentId != ? and Payments.CreatedAt <= ? and Payments.CreatedAt > ? - interval 45 day
					order by Payments.CreatedAt desc, Payments.PaymentId desc
					limit 1
				', [$this->UserId, $this->Processor, $this->IsDonorAdvisedFund, $this->PaymentId, $this->CreatedAt, $this->CreatedAt], Payment::class)[0] ?? null;

				if($previousPayment !== null && $previousPayment->Amount === $this->Amount && $previousPayment->SubscriptionId !== null){
					$this->SubscriptionId = $previousPayment->SubscriptionId;
					/** @throws void */
					$this->Subscription = $previousPayment->Subscription;

					// If the old `Subscription` had been marked as ended, return it to active status.
					if($this->CreatedAt > NOW->sub(new DateInterval('P45D'))){
						Db::Query('update Subscriptions set EndedAt = null where SubscriptionId = ? and EndedAt is not null', [$this->SubscriptionId]);
						$this->HasReopenedSubscription = Db::$LastQueryAffectedRowCount > 0;
					}
				}
				else{
					// No previous match, start a new `Subscription`.
					$subscription = new Subscription();
					$subscription->UserId = $this->UserId;
					$subscription->CreatedAt = $this->CreatedAt;
					$subscription->Processor = $this->Processor;
					$subscription->Amount = $this->Amount;
					$subscription->CycleType = Enums\CycleType::Monthly;
					$subscription->Create();
					$this->SubscriptionId = $subscription->SubscriptionId;
					$this->Subscription = $subscription;

					// A changed amount replaces the previous inferred stream, without discarding its payment history.
					if($previousPayment?->SubscriptionId !== null){
						Db::Query('update Subscriptions set EndedAt = ? where SubscriptionId = ? and EndedAt is null and not exists (select * from Payments where SubscriptionId = ? and CreatedAt > ?)', [$this->CreatedAt, $previousPayment->SubscriptionId, $previousPayment->SubscriptionId, $this->CreatedAt]);
					}
				}

				Db::Query('update Payments set SubscriptionId = ? where PaymentId = ?', [$this->SubscriptionId, $this->PaymentId]);
			}
		});

		if(!$this->IsRecurring && !$this->IsMatchingDonation){
			// Add any one-time payments to any active `DonationCounter`s.
			DonationCounter::AddCountToIsActive();
		}
	}

	/**
	 * Send this payment to the accounting server if it is a Stripe transaction and mark it recorded after a successful response.
	 *
	 * @throws Exceptions\HttpRequestException If the accounting server cannot be reached.
	 * @throws Exceptions\UserNotFoundException If this payment's user no longer exists.
	 * @throws Exceptions\ConnectionErrorException If the accounting server rejects the request.
	 */
	public function SendToExternalServer(): void{
		if($this->Processor !== Enums\PaymentProcessorType::Stripe){
			return;
		}

		$organization = $this->User->Email ?? 'Anonymous donor';

		if($this->User !== null){
			$organization .= ' (#' . $this->User->UserId . ')';
		}

		$product = 'One-time Donation';
		if($this->IsRecurring){
			$product = 'Recurring Donation';
		}

		// Include ended memberships so historical payments and refunds retain their product label.
		if(Db::QueryBool('select exists(select * from Patrons where SubscriptionId = ? or QualifyingPaymentId = ?)', [$this->SubscriptionId, $this->PaymentId])){
			$product = 'Patrons Circle Membership';
		}

		try{
			if($this->IsRecurring && $this->Subscription !== null){
				$product .= ' (' . ucfirst($this->Subscription->CycleType->value) . ')';
			}
		}
		catch(Exceptions\SubscriptionNotFoundException){
			// Pass.
		}

		$response = HttpRequest::Execute(Enums\HttpMethod::Post, 'https://turkeysandwichindustries.com/accounting-line-items', [
				'amount' => $this->Amount,
				'fee' => $this->Fee,
				'created-at' => ($this->RefundedAt ?? $this->CreatedAt)->format(Enums\DateTimeFormat::Sql->value),
				'type' => $this->RefundedAt === null ? Enums\TransactionType::Credit->value : Enums\TransactionType::Debit->value,
				'product' => $product,
				'organization' => $organization,
				'transaction-id' => $this->TransactionId,
				'branch' => 'standard_ebooks',
				'account' => TSI_ACCOUNT_NAME,
				'processor' => $this->Processor->value,
				'is-test-request' => SITE_STATUS == SITE_STATUS_DEV ? 'true' : 'false'
		], ['Authorization' => 'Basic ' . base64_encode(TSI_API_KEY . ':')], false);

		if(!$response->HttpCode->IsSuccess()){
			throw new Exceptions\ConnectionErrorException('Server responded HTTP ' . $response->HttpCode->value . ' with message: ' . $response->Body);
		}

		Db::Query('update Payments set IsRecordedExternally = true where PaymentId = ?', [$this->PaymentId]);
		$this->IsRecordedExternally = true;
	}

	/**
	 * Apply Patrons Circle benefits for an eligible payment, and send a thank-you message for a donation.
	 *
	 * @throws Exceptions\PaymentNotFoundException If a recurring subscription has no successful payment.
	 * @throws Exceptions\SubscriptionNotFoundException If a linked subscription no longer exists.
	 * @throws Exceptions\PatronInvalidException If an eligible payment would create an invalid patron.
	 * @throws Exceptions\UserNotFoundException If the `User` no longer exists.
	 */
	public function ProcessPatronsCircleBenefits(Enums\CycleType $cycleType, bool $isAnonymous, ?string $alternateName, bool $isPatronsCircleProduct = false): void{
		$alternateName = trim($alternateName ?? '');
		$alternateName = $alternateName === '' ? null : $alternateName;
		$isEligible = false;
		$graceInterval = new DateInterval('P45D');
		$lastPaidAt = $this->CreatedAt;
		if($this->Processor === Enums\PaymentProcessorType::FracturedAtlas && $this->IsRecurring && $this->Subscription !== null){
			if($this->Subscription->IsDonorAdvisedFund){
				$graceInterval = new DateInterval('P75D');
			}
			$lastPaidAt = $this->Subscription->LastPayment->CreatedAt;
		}

		switch($this->Processor){
			case Enums\PaymentProcessorType::Stripe:
				$isEligible = $isPatronsCircleProduct;
				break;

			case Enums\PaymentProcessorType::FracturedAtlas:
				$isEligible =	(
							(
								$cycleType == Enums\CycleType::Monthly
								&&
								$this->Amount >= PATRONS_CIRCLE_MONTHLY_COST
							)
							||
							(
								$cycleType == Enums\CycleType::Yearly
								&&
								$this->Amount >= PATRONS_CIRCLE_YEARLY_COST
							)
						)
						&&
						(
							($this->IsRecurring && $lastPaidAt > NOW->sub($graceInterval))
							||
							(!$this->IsRecurring && $this->CreatedAt >= NOW->sub(new DateInterval('P1Y')))
						)
						;
				break;
		}

		if($isEligible && $this->IsRecurring){
			// Recurring DAF payments require at least 2 payments to be inferred, so we check that here.
			$isEligible = $this->SubscriptionId !== null && ($this->Subscription?->IsDonorAdvisedFund === true || Db::QueryInt('select count(*) from Payments where SubscriptionId = ?', [$this->SubscriptionId]) === 1);
		}

		if($this->HasReopenedSubscription && $this->Subscription?->IsDonorAdvisedFund !== true && $this->SubscriptionId !== null){
			// A late payment can restore membership previously funded by this subscription.
			$isEligible = Db::QueryBool('select exists(select * from Patrons where SubscriptionId = ? and EndedAt is not null)', [$this->SubscriptionId]);
		}

		$isPatron = false;
		if($this->UserId !== null){
			$isPatron = Db::QueryBool('select exists(select * from Patrons where UserId = ? and EndedAt is null)', [$this->UserId]);
			if($isPatron && $this->IsRecurring){
				$patron = Db::Query('select * from Patrons where UserId = ? and EndedAt is null order by PatronId desc limit 1', [$this->UserId], Patron::class)[0] ?? null;
				// Stripe webhooks compare the configured product IDs before supplying a membership payment.
				$patron?->ReconcileFunding($this->Processor === Enums\PaymentProcessorType::Stripe && $isPatronsCircleProduct ? $this : null);
			}
		}

		$user = $this->User;
		if($isEligible && $this->UserId !== null && $user !== null && !$isPatron){
			$patron = new Patron();
			$patron->UserId = $this->UserId;
			$patron->User = $user;
			$patron->User->Payments = [$this];
			$patron->IsAnonymous = $isAnonymous || ($this->Processor == Enums\PaymentProcessorType::Stripe && $user->Name === null && $alternateName === null);
			$patron->AlternateName = $alternateName;
			$patron->SubscriptionId = $this->SubscriptionId;
			$patron->QualifyingPaymentId = $this->IsRecurring ? null : $this->PaymentId;
			if($this->Processor == Enums\PaymentProcessorType::Stripe){
				$patron->CreatedAt = $this->CreatedAt;
			}
			$patron->Create();
		}
		elseif($isEligible && $this->Processor == Enums\PaymentProcessorType::FracturedAtlas && ($this->UserId === null || $user === null) && !$this->IsRecurring && !$this->IsMatchingDonation){
			// Notify admins about a fully anonymous one-time donor who qualifies for the Patrons Circle.
			$patron = new Patron();
			$patron->User = new User();

			$em = new QueuedEmailMessage();
			$em->To = ACCOUNTING_EMAIL_ADDRESS;
			$em->From = ADMIN_EMAIL_ADDRESS;
			$em->FromName = ACCOUNTING_FROM_NAME;
			$em->Subject = 'New Patrons Circle member (' . ucfirst($cycleType->value) . ')';
			$em->BodyHtml = Template::EmailAdminNewPatron(patron: $patron, payment: $this);
			$em->BodyText = Template::EmailAdminNewPatronText(patron: $patron, payment: $this);
			$em->Send();

			DonationDrive::AddCountToIsActive(Enums\DonationTargetType::NewPatrons);
		}

		if(
			$user !== null
			&&
			$user->Email !== null
			&&
			(
				(
					!$isEligible
					&&
					(
						!$this->IsRecurring
						||
						Db::QueryInt('select count(*) from Payments where UserId = ? and IsRecurring = true', [$this->UserId]) == 1
					)
				)
				||
				(
					$isEligible
					&&
					$isPatron
					&&
					!$this->IsRecurring
					&&
					!$this->IsMatchingDonation
				)
			)
		){
			// Thank non-patron donors and existing patrons making an additional one-time donation.
			$em = new QueuedEmailMessage();
			$em->To = $user->Email;
			$em->ToName = $user->Name;
			$em->From = EDITOR_IN_CHIEF_EMAIL_ADDRESS;
			$em->FromName = EDITOR_IN_CHIEF_NAME;
			$em->Subject = 'Thank you for supporting Standard Ebooks!';
			$donationCounter = DonationCounter::GetByIsActive();
			$em->BodyHtml = Template::EmailDonationThankYou(donationCounter: $donationCounter);
			$em->BodyText = Template::EmailDonationThankYouText(donationCounter: $donationCounter);
			$em->Send();
		}

		if(!$this->IsRecurring && !$this->IsMatchingDonation && (!$isEligible || $isPatron)){
			$em = new QueuedEmailMessage();
			$em->To = ACCOUNTING_EMAIL_ADDRESS;
			$em->From = ADMIN_EMAIL_ADDRESS;
			$em->FromName = ACCOUNTING_FROM_NAME;
			$em->Subject = 'New one-time donation';
			$em->BodyHtml = Template::EmailAdminNewOneTimeDonation(payment: $this);
			$em->BodyText = Template::EmailAdminNewOneTimeDonationText(payment: $this);
			$em->Send();
		}
		elseif($this->IsRecurring && !$this->IsMatchingDonation && !$isEligible && $this->SubscriptionId !== null && Db::QueryBool('select count(*) = (case when ? then 2 else 1 end) and max(PaymentId) = ? from Payments where SubscriptionId = ?', [$this->IsDonorAdvisedFund, $this->PaymentId, $this->SubscriptionId])){
			// Notify accounting on the second imported DAF payment establishing a `Subscription`, or the first payment of another recurring `Subscription`.
			$em = new QueuedEmailMessage();
			$em->To = ACCOUNTING_EMAIL_ADDRESS;
			$em->From = ADMIN_EMAIL_ADDRESS;
			$em->FromName = ACCOUNTING_FROM_NAME;
			$em->Subject = 'New recurring donation';
			$em->BodyHtml = Template::EmailAdminNewRecurringDonation(payment: $this, cycleType: $cycleType);
			$em->BodyText = Template::EmailAdminNewRecurringDonationText(payment: $this, cycleType: $cycleType);
			$em->Send();
		}
	}

	/**
	 * Get net payment totals grouped by month, split into the datasets used by the payments received graph.
	 *
	 * @return array{0: array<string, float>, 1: array<string, float>, 2: array<string, float>} Key `0` contains recurring net payments, key `1` contains one-time net payments from users who are or were Patrons, and key `2` contains other one-time net payments. Inner keys are month labels in `Y-m` format, and inner values are the total net payment amount for that month.
	 */
	public static function GetNetByMonth(DateTimeImmutable $from, DateTimeImmutable $to): array{
		if($from > $to){
			[$from, $to] = [$to, $from];
		}

		$queryFrom = $from->modify('first day of this month')->setTime(0, 0);
		$queryTo = $to->add(new DateInterval('P1D'))->setTime(0, 0);
		$queryToMonth = $to->modify('first day of this month')->setTime(0, 0);
		$recurringValues = [];
		$patronValues = [];
		$otherValues = [];

		// Use `set statement max_recursive_iterations` to allow for very wide date ranges. Otherwise, MariaDB's default of 1,000 might cause the result set to end prematurely.
		$result = Db::Query('
			set statement max_recursive_iterations = 100000 for
			with recursive Months as (
				select cast(? as date) as Month
				union all
				select cast(date_add(Month, interval 1 month) as date)
				from Months
				where Month < ?
			)
			select
				date_format(Months.Month, "%Y-%m") as Month,
				coalesce(sum(case when Payments.IsRecurring = true then Payments.Amount - Payments.Fee else 0 end), 0) as RecurringAmount,
				coalesce(sum(case when Payments.IsRecurring = false and PatronUsers.UserId is not null then Payments.Amount - Payments.Fee else 0 end), 0) as PatronAmount,
				coalesce(sum(case when Payments.IsRecurring = false and PatronUsers.UserId is null then Payments.Amount - Payments.Fee else 0 end), 0) as OtherAmount
			from Months
			left join Payments on
				Payments.CreatedAt >= Months.Month
				and Payments.CreatedAt < date_add(Months.Month, interval 1 month)
				and Payments.CreatedAt >= ?
				and Payments.CreatedAt < ?
			left join (
				select distinct UserId
				from Patrons
			) PatronUsers on Payments.UserId = PatronUsers.UserId
			group by Months.Month
			order by Months.Month
		', [$queryFrom, $queryToMonth, $from, $queryTo]);

		foreach($result as $row){
			$recurringValues[$row->Month] = $row->RecurringAmount;
			$patronValues[$row->Month] = $row->PatronAmount;
			$otherValues[$row->Month] = $row->OtherAmount;
		}

		return [$recurringValues, $patronValues, $otherValues];
	}

	/**
	 * Creates a `Payment` from a multi table array containing a `Payment` and a `Subscription`.
	 *
	 * @param array<string, stdClass> $row
	 */
	public static function FromMultiTableRow(array $row): Payment{
		$object = Payment::FromRow($row['Payments']);

		if($row['Payments']->SubscriptionId !== null){
			$object->Subscription = Subscription::FromRow($row['Subscriptions']);
		}

		return $object;
	}
}
