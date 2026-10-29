<?
/**
 * POST		/checkout-sessions
 */

use function Safe\preg_replace;
use function Safe\session_start;

try{
	session_start();

	// Is this IP address banned?
	if(Http::$Request->RemoteAddress === null || Http::$Request->RemoteAddress->IsBanned()){
		throw new \Exceptions\SpamSuspectedException('Donations aren’t available right now; please try again later.');
	}

	$frequency = Http::$Request->Body->Get('frequency');
	$membership = Http::$Request->Body->Get('membership');
	$oneTimeAmountChoice = Http::$Request->Body->Get('amount-once-choice');
	$isCustomAmount = $frequency === 'once' && $oneTimeAmountChoice === 'custom';
	$amount = null;
	if($frequency === 'once' && !$isCustomAmount){
		$amount = Http::$Request->Body->Get('amount-once-choice', 'int');
	}
	else{
		$amount = Http::$Request->Body->Get('amount-' . $frequency . '-' . $membership, 'int');
	}
	$isPatron = $frequency != 'once' && $membership == 'join';
	$isAnonymous = !$isPatron || $membership != 'join' || Http::$Request->Body->Get('list-name') != 'yes';
	$email = trim(Session::$User !== null ? (string)Session::$User->Email : (Http::$Request->Body->Get('email') ?? ''));
	// Only use the name field when the donor has selected a public masthead listing.
	$name = '';
	if(!$isAnonymous){
		$name = preg_replace('/^[\s\p{Z}]+|[\s\p{Z}]+$/u', '', Http::$Request->Body->Get('name') ?? '');
	}
	$productId = STRIPE_DONATION_PRODUCT_ID;
	if($isPatron){
		$productId = STRIPE_PATRONS_CIRCLE_PRODUCT_ID;
	}

	if(
		!in_array($frequency, ['once', 'month', 'year'], true)
		||
		($frequency != 'once' && !in_array($membership, ['donate', 'join'], true))
		||
		($amount === null && !$isCustomAmount)
		||
		(
			($frequency === 'once' && !$isCustomAmount && !in_array($amount, STRIPE_ONE_TIME_DONATION_AMOUNTS, true))
			||
			($frequency !== 'once' && !in_array($amount, STRIPE_DONATION_AMOUNTS[$frequency][$membership], true))
		)
		||
		$email === ''
		||
		mb_strlen($email, 'UTF-8') > 80
		||
		filter_var($email, FILTER_VALIDATE_EMAIL) === false
		||
		mb_strlen($name, 'UTF-8') > 80
	){
		throw new Exceptions\DonationInvalidException();
	}

	if(!$isAnonymous && $name === ''){
		throw new Exceptions\DonationInvalidException('Please enter your name to be listed on the masthead.');
	}

	if($frequency != 'once' && Db::QueryBool('
		select exists(
			select * from Subscriptions s
			inner join Users u on u.UserId = s.UserId
			where u.Email = ? and s.EndedAt is null
			and (s.Processor = ? or exists (
				select * from Payments p
				where p.SubscriptionId = s.SubscriptionId and p.RefundedAt is null
				and p.CreatedAt > utc_timestamp() - interval 45 day
			))
		)
	', [$email, Enums\PaymentProcessorType::Stripe])){
		throw new Exceptions\DonationInvalidException('You already have an active recurring donation.');
	}

	$metadata = [
		'frequency' => $frequency,
		'is_anonymous' => $isAnonymous ? 'true' : 'false',
	];

	if($name !== ''){
		$metadata['name'] = $name;
	}

	$parameters = [
		'mode' => $frequency == 'once' ? 'payment' : 'subscription',
		'customer_email' => $email,
		'success_url' => SITE_URL . '/donations/thanks' . ($isPatron ? '?is-patrons-circle-membership' : ''),
		'cancel_url' => SITE_URL . '/donations/new',
		'billing_address_collection' => 'required',
		'metadata' => $metadata,
	];
	if(Http::$Request->Body->Get('join-patrons-circle', 'bool')){
		$parameters['cancel_url'] .= '?join-patrons-circle';
	}

	if($frequency == 'once'){
		$parameters['customer_creation'] = 'always';
	}
	else{
		$parameters['subscription_data'] = ['metadata' => $metadata];
	}

	$stripe = new \Stripe\StripeClient(['api_key' => STRIPE_API_KEY, 'stripe_version' => STRIPE_API_VERSION]);

	// Use the customer-chosen price for custom amounts, or match a fixed amount and billing interval.
	$priceParameters = ['product' => $productId, 'active' => true, 'currency' => 'usd', 'type' => 'one_time', 'limit' => 100];
	if($frequency !== 'once'){
		$priceParameters['type'] = 'recurring';
		$priceParameters['recurring'] = ['interval' => $frequency];
	}

	$priceId = null;
	foreach($stripe->prices->all($priceParameters)->autoPagingIterator() as $price){
		if($isCustomAmount){
			if($price->custom_unit_amount !== null){
				$priceId = $price->id;
				break;
			}
			continue;
		}

		if($price->unit_amount === $amount * 100 && ($frequency === 'once' || ($price->recurring?->interval_count === 1 && $price->recurring->usage_type === 'licensed'))){
			$priceId = $price->id;
			break;
		}
	}

	if($priceId === null){
		throw new Exceptions\DonationInvalidException('The product is unavailable.');
	}

	$parameters['line_items'] = [['price' => $priceId, 'quantity' => 1]];

	// Reuse the most recent customer with this email, preserving their separate billing name.
	$customer = $stripe->customers->all(['email' => $email, 'limit' => 1])->data[0] ?? null;
	if($customer !== null){
		unset($parameters['customer_email'], $parameters['customer_creation']);
		$parameters['customer'] = $customer->id;
		$parameters['customer_update'] = ['name' => 'never'];
	}

	$session = $stripe->checkout->sessions->create($parameters);

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $session->url);
}
catch(Exceptions\DonationInvalidException | Exceptions\SpamSuspectedException $ex){
	$_SESSION['donation/create/exception'] = $ex;
	$_SESSION['donation/create/form'] = Http::$Request->Body;
	http_response_code(Enums\HttpCode::SeeOther->value);
	$redirectUrl = '/donations/new';
	if(Http::$Request->Body->Get('join-patrons-circle', 'bool')){
		$redirectUrl .= '?join-patrons-circle';
	}
	header('location: ' . $redirectUrl);
}
