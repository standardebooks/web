<?
use Safe\DateTimeImmutable;

final class Subscription{
	use Traits\FromRow;

	public int $SubscriptionId;
	public int $UserId;
	public DateTimeImmutable $CreatedAt;
	public ?DateTimeImmutable $NextPaymentAt = null;
	public ?DateTimeImmutable $EndedAt = null;
	public Enums\PaymentProcessorType $Processor;
	public ?string $ProcessorSubscriptionId = null;
	public float $Amount;
	public Enums\CycleType $CycleType;

	/** Whether any payment in this subscription came through a donor advised fund. */
	public bool $IsDonorAdvisedFund{
		get{
			return Db::QueryBool('select exists(select * from Payments where SubscriptionId = ? and IsDonorAdvisedFund = true)', [$this->SubscriptionId]);
		}
	}

	public User $User{
		/** @throws Exceptions\UserNotFoundException If the `User` can't be found. */
		get{
			return $this->User ??= User::Get($this->UserId);
		}
	}

	public string $EditUrl{
		/** @throws Exceptions\UserNotFoundException If the `User` can't be found. */
		get{
			return $this->User->Url . '/subscriptions/' . $this->SubscriptionId . '/edit';
		}
	}

	public string $DeleteUrl{
		/** @throws Exceptions\UserNotFoundException If the `User` can't be found. */
		get{
			return $this->User->Url . '/subscriptions/' . $this->SubscriptionId . '/delete';
		}
	}

	/** Whether this subscription funds an active Patrons Circle membership. */
	public bool $HasActivePatron{
		get{
			$this->HasActivePatron ??= Db::QueryBool('select exists(select * from Patrons where SubscriptionId = ? and EndedAt is null)', [$this->SubscriptionId]);
			return $this->HasActivePatron;
		}
	}

	/**
	 * The last successful `Payment` for this `Subscription`.
	 *
	 * @throws Exceptions\PaymentNotFoundException If no matching `Payment` can be found.
	 */
	public private(set) Payment $LastPayment{
		/**
		 * @throws Exceptions\PaymentNotFoundException If no matching `Payment` can be found.
		 */
		get{
			try{
				return $this->LastPayment ??= Db::Query('
					select * from Payments
					where SubscriptionId = ? and RefundedAt is null
					order by CreatedAt desc
					limit 1
				', [$this->SubscriptionId], Payment::class)[0];
			}
			catch(Exception){
				throw new Exceptions\PaymentNotFoundException();
			}
		}
	}

	/**
	 * Record a subscription, updating an existing Stripe subscription with the same `ProcessorSubscriptionId`.
	 */
	public function Create(): void{
		if($this->Processor == Enums\PaymentProcessorType::Stripe){
			$this->SubscriptionId = Db::QueryInt('
				insert into Subscriptions (UserId, CreatedAt, NextPaymentAt, EndedAt, Processor, ProcessorSubscriptionId, Amount, CycleType)
				values (?, ?, ?, ?, ?, ?, ?, ?)
				on duplicate key update Amount = values(Amount), CycleType = values(CycleType), NextPaymentAt = values(NextPaymentAt)
				returning SubscriptionId
			', [$this->UserId, $this->CreatedAt, $this->NextPaymentAt, $this->EndedAt, $this->Processor, $this->ProcessorSubscriptionId, $this->Amount, $this->CycleType]);
		}
		else{
			$this->SubscriptionId = Db::QueryInt('
				insert into Subscriptions (UserId, CreatedAt, EndedAt, Processor, Amount, CycleType)
				values (?, ?, ?, ?, ?, ?)
				returning SubscriptionId
			', [$this->UserId, $this->CreatedAt, $this->EndedAt, $this->Processor, $this->Amount, $this->CycleType]);
		}
	}

	/**
	 * @throws Exceptions\StripeApiException If an error occurred when cancelling a Stripe subscription.
	 */
	public function Cancel(): void{
		if($this->EndedAt !== null){
			return;
		}

		if($this->Processor == Enums\PaymentProcessorType::Stripe && $this->ProcessorSubscriptionId !== null){
			try{
				$stripe = new \Stripe\StripeClient(['api_key' => STRIPE_API_KEY, 'stripe_version' => STRIPE_API_VERSION]);
				$stripe->subscriptions->cancel($this->ProcessorSubscriptionId);
			}
			catch(Stripe\Exception\ApiErrorException $ex){
				throw new Exceptions\StripeApiException(previous: $ex);
			}
		}

		$this->EndedAt = NOW;
		$this->NextPaymentAt = null;

		Db::Query('
			update Subscriptions
			set EndedAt = ?, NextPaymentAt = null
			where ProcessorSubscriptionId = ? and EndedAt is null
		', [$this->EndedAt, $this->ProcessorSubscriptionId]);
	}

	public function SendYearlyRenewalEmail(int $ebookCount): void{
		try{
			if($this->User->Email === null){
				return;
			}

			$em = new QueuedEmailMessage();
			$em->To = $this->User->Email;
			$em->ToName = $this->User->Name;
			$em->From = EDITOR_IN_CHIEF_EMAIL_ADDRESS;
			$em->FromName = 'Standard Ebooks';
			$em->Subject = '[Action required] Your yearly donation will recur soon';
			$em->BodyHtml = Template::EmailYearlyRenewal(subscription: $this, ebookCount: $ebookCount);
			$em->BodyText = Template::EmailYearlyRenewalText(subscription: $this, ebookCount: $ebookCount);
			$em->Send();
		}
		catch(Exception){
			// Pass.
		}
	}

	/**
	 * @throws Exceptions\SubscriptionNotFoundException If the `Subscription` can't be found.
	 */
	public static function Get(?int $subscriptionId): Subscription{
		if($subscriptionId === null || $subscriptionId < 0){
			throw new Exceptions\SubscriptionNotFoundException();
		}

		return Db::Query('select * from Subscriptions where SubscriptionId = ?', [$subscriptionId], Subscription::class)[0] ?? throw new Exceptions\SubscriptionNotFoundException();
	}

	/**
	 * Fill this subscription from a Stripe subscriptionobject.
	 *
	 * @throws Exceptions\SubscriptionInvalidException If the `Subscription` is invalid.
	 */
	public static function FromStripe(\Stripe\Subscription $stripeSubscription): Subscription{
		$object = new Subscription();

		if(sizeof($stripeSubscription->items->data) !== 1){
			throw new Exceptions\SubscriptionInvalidException('Stripe subscription does not have exactly one price.');
		}

		$price = $stripeSubscription->items->data[0]->price;
		if($price->currency !== 'usd' || $price->unit_amount === null || $price->unit_amount <= 0 || $stripeSubscription->items->data[0]->quantity !== 1 || $price->recurring === null || $price->recurring->interval_count !== 1 || !in_array($price->recurring->interval, ['month', 'year'], true)){
			throw new Exceptions\SubscriptionInvalidException('Stripe subscription has an unsupported recurring price.');
		}
		if(!is_string($price->product) || !in_array($price->product, [STRIPE_DONATION_PRODUCT_ID, STRIPE_PATRONS_CIRCLE_PRODUCT_ID], true)){
			throw new Exceptions\SubscriptionInvalidException('Stripe subscription has an unsupported product.');
		}
		try{
			$object->CreatedAt = new DateTimeImmutable('@' . $stripeSubscription->created);
			$object->Processor = Enums\PaymentProcessorType::Stripe;
			$object->ProcessorSubscriptionId = $stripeSubscription->id;
			$object->Amount = $price->unit_amount / 100;
			$object->CycleType = $price->recurring->interval === 'month' ? Enums\CycleType::Monthly : Enums\CycleType::Yearly;
			if($stripeSubscription->ended_at === null && !$stripeSubscription->cancel_at_period_end){
				$object->NextPaymentAt = new DateTimeImmutable('@' . $stripeSubscription->items->data[0]->current_period_end);
			}
			if($stripeSubscription->ended_at !== null){
				$object->EndedAt = new DateTimeImmutable('@' . $stripeSubscription->ended_at);
			}
		}
		catch(\Exception $ex){
			throw new Exceptions\SubscriptionInvalidException(message: 'Couldn\'t parse timestamp.', previous: $ex);
		}

		return $object;
	}
}
