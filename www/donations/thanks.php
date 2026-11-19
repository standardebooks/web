<?
/**
 * GET		/donations/thanks
 */

use Safe\DateTimeImmutable;
use function Safe\file_get_contents;

$isPatronsCircleMembership = false;
$isWaitingForUser = false;
$checkoutSessionId = Http::$Request->QueryString->Get('checkout-session-id');

if($checkoutSessionId !== null){
	try{
		$stripe = new \Stripe\StripeClient(['api_key' => STRIPE_API_KEY, 'stripe_version' => STRIPE_API_VERSION]);
		$checkoutSession = $stripe->checkout->sessions->retrieve($checkoutSessionId, ['expand' => ['line_items', 'customer']]);

		$lineItems = $checkoutSession->line_items;
		$isPatronsCircleMembership = $lineItems !== null && !$lineItems->has_more && sizeof($lineItems->data) === 1 && $lineItems->data[0]->price?->product === STRIPE_PATRONS_CIRCLE_PRODUCT_ID;

		// Hide general donation messages for one month after a donation or subscription checkout.
		setcookie('hide-donation-messages', 'true', ['expires' => intval((new DateTimeImmutable('+1 month'))->format(Enums\DateTimeFormat::UnixTimestamp->value)), 'path' => '/', 'domain' => SITE_DOMAIN, 'secure' => true, 'httponly' => true, 'samesite' => 'Lax']);

		// Verify the completed purchase with Stripe before creating a login session.
		if($checkoutSession->status === 'complete' && $checkoutSession->payment_status === 'paid'){
			$customer = $checkoutSession->customer;

			if($customer instanceof \Stripe\Customer && $customer->email !== null){
				try{
					$user = User::GetByEmail($customer->email);

					if(Session::$User === null){
						$session = new Session();
						$session->Create($user);
					}
				}
				catch(Exceptions\UserNotFoundException){
					// We got here before Stripe called the webhook to create the user. Try again in 3 seconds.
					$retry = max(0, Http::$Request->QueryString->Get('retry', 'int') ?? 0);
					if($retry < 2){
						$isWaitingForUser = true;
						header('refresh: 3; url=/donations/thanks?checkout-session-id=' . rawurlencode($checkoutSessionId) . '&retry=' . ($retry + 1));
					}
				}
			}
		}
	}
	catch(\Stripe\Exception\InvalidRequestException){
		Template::ExitWithCode(Enums\HttpCode::UnprocessableContent);
	}
	catch(\Stripe\Exception\ApiErrorException $ex){
		(new Log())->Write('Stripe checkout session retrieval failed: ' . $ex->getMessage());
		Template::ExitWithCode(Enums\HttpCode::UnprocessableContent, new Exceptions\StripeApiException('We couldn’t confirm your donation information.'));
	}
}
?>
<?= Template::Header(title: $isWaitingForUser ? 'Please wait...' : 'Thank You', description: 'Thank you for supporting Standard Ebooks!', css: ['/css/donations.css']) ?>
<main>
	<section class="narrow has-hero">
		<? if($isWaitingForUser){ ?>
			<p class="please-wait">Please wait while we process your donation...</p>
			<?= file_get_contents(WEB_ROOT . '/images/book-open-turn.svg') ?>
		<? }else{ ?>
			<hgroup>
				<h1>Thank You</h1>
				<p>for supporting the literate arts!</p>
			</hgroup>
			<picture data-caption="Hip, Hip, Hurrah! Peder Severin Krøyer, 1888">
				<source srcset="/images/hip-hip-hurrah@2x.avif 2x, /images/hip-hip-hurrah.avif 1x" type="image/avif"/>
				<source srcset="/images/hip-hip-hurrah@2x.jpg 2x, /images/hip-hip-hurrah.jpg 1x" type="image/jpeg"/>
				<img src="/images/hip-hip-hurrah@2x.jpg" alt="People raising their glasses around a table at an outdoor celebration."/>
			</picture>
			<p>Your donation has been received, and will go towards continuing our work of creating beautiful ebook editions of the world’s finest literature, to distribute for free.</p>

			<? if($isPatronsCircleMembership){ ?>
				<section id="benefits">
					<h2>Your Patrons Circle benefits<? if(Session::$User !== null){ ?> are active!<? } ?></h2>
					<ul>
						<li>
							<? if(Session::$User !== null){ ?>
								<p>Access <a href="/bulk-downloads">bulk ebook downloads</a> and <a href="/polls">vote in our polls</a>.</p>
								<? if(!Session::$User->Patron?->IsAnonymous && Session::$User->Patron?->EndedAt !== null){ ?>
									<p>You’re listed on our <a href="/about#patrons-circle">masthead</a> as a supporter of the literate arts.</p>
								<? } ?>
							<? }else{ ?>
								<p><a href="/sessions/new">Log in</a> to activate your Patrons Circle benefits. Once logged in, you can access <a href="/bulk-downloads">bulk ebook downloads</a> and <a href="/polls">vote in our polls</a>.</p>
							<? } ?>
						</li>
						<li>
							<p>To access our <a href="/feeds">ebook feeds</a> in an ereading app or device, or an RSS reader, enter your email address when prompted and leave the password field blank.</p>
						</li>
					</ul>
				</section>
			<? } ?>

			<p class="button-row center">
				<? if(Session::$User === null && $isPatronsCircleMembership){ ?>
					<a class="button" href="/sessions/new">Log in to the Patrons Circle</a>
				<? } ?>
				<a class="button next" href="/ebooks">Back to the ebooks</a>
			</p>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
