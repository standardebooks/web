<?
/**
 * POST		/newsletter-subscriptions
 */

use function Safe\session_start;
use function Safe\session_unset;
use Ramsey\Uuid\Uuid;

try{
	session_start();

	$uuid = Uuid::uuid4();

	if(Http::$Request->Body->Get('automation-test')){
		// A bot filled out this form field, which should always be empty. Pretend like we succeeded.
		http_response_code(Enums\HttpCode::SeeOther->value);
		$_SESSION['newsletter-subscription/create/is-bot'] = true;
		header('location: /users/' . $uuid->toString() . '/newsletter-subscriptions');
		exit();
	}

	$email = Http::$Request->Body->Get('email') ?? '';
	$newsletterIds = array_unique(Http::$Request->Body->Get('newsletter-ids', 'array') ?? []);
	$newsletters = [];
	foreach($newsletterIds as $newsletterId){
		if(ctype_digit($newsletterId)){
			try{
				$newsletter = Newsletter::Get((int)$newsletterId);
				if($newsletter->IsVisible){
					$newsletters[] = $newsletter;
				}
			}
			catch(Exceptions\NewsletterNotFoundException){
				// Pass.
			}
		}
	}

	if(sizeof($newsletters) == 0){
		throw new Exceptions\NewsletterRequiredException();
	}

	$expectedCaptcha = Http::$Request->Session->Get('newsletter-subscription/create/captcha') ?? '';
	$receivedCaptcha = Http::$Request->Body->Get('captcha') ?? '';

	if($expectedCaptcha === '' || $receivedCaptcha === '' || mb_strtolower($expectedCaptcha, 'utf-8') !== mb_strtolower($receivedCaptcha, 'utf-8')){
		throw new Exceptions\CaptchaInvalidException();
	}

	if(Http::$Request->RemoteAddress === null || Http::$Request->RemoteAddress->IsBanned() !== null){
		http_response_code(Enums\HttpCode::SeeOther->value);
		$_SESSION['newsletter-subscription/create/is-created'] = true;
		header('location: /users/' . $uuid->toString() . '/newsletter-subscriptions');
		exit();
	}

	$user = new User();
	$user->Uuid = $uuid->toString();
	$user->Email = $email;
	$isAnyNewsletterSubscriptionCreated = false;

	Db::Query('start transaction');
	try{
		foreach($newsletters as $newsletter){
			$newsletterSubscription = new NewsletterSubscription();
			$newsletterSubscription->Newsletter = $newsletter;
			$newsletterSubscription->NewsletterId = $newsletter->NewsletterId;
			$newsletterSubscription->User = $user;

			try{
				// The unique email and IP address keys prevent duplicate concurrent attempts and enforce the rate limit.
				Db::Query('insert into NewsletterSignupAttempts (NewsletterId, Email, IpAddress, CreatedAt) values (?, ?, ?, ?)', [$newsletter->NewsletterId, $user->Email, Http::$Request->RemoteAddress->Binary, NOW]);
			}
			catch(Exceptions\DuplicateDatabaseKeyException){
				throw new Exceptions\SpamSuspectedException();
			}

			try{
				$newsletterSubscription->Create();
				// We may have fetched a different user into the `NewsletterSubscription` while creating, so update it here just in case.
				$user = $newsletterSubscription->User;

				$isAnyNewsletterSubscriptionCreated = true;
			}
			catch(Exceptions\NewsletterSubscriptionExistsException){
				$user = $newsletterSubscription->User;
				continue;
			}
		}

		Db::Query('commit');
	}
	catch(\Throwable $ex){
		try{
			Db::Query('rollback');
		}
		catch(\Throwable){
			// Preserve the original exception.
		}

		throw $ex;
	}

	if($isAnyNewsletterSubscriptionCreated){
		// Send the double opt-in confirmation email.
		$user->SendNewsletterSubscriptionConfirmationEmail();
	}

	session_unset();

	http_response_code(Enums\HttpCode::SeeOther->value);
	$_SESSION['newsletter-subscription/create/is-created'] = true;
	header('location: ' . $user->UuidUrl . '/newsletter-subscriptions');
}
catch(Exceptions\SpamSuspectedException){
	// Pretend the subscription was created.
	http_response_code(Enums\HttpCode::SeeOther->value);
	$_SESSION['newsletter-subscription/create/is-bot'] = true;
	header('location: /users/' . $uuid->toString() . '/newsletter-subscriptions');
}
catch(Exceptions\InvalidNewsletterSubscription | Exceptions\EmailBounceExistsException | Exceptions\CaptchaInvalidException | Exceptions\NewsletterRequiredException $ex){
	$_SESSION['newsletter-subscription/create/newsletter-ids'] = $newsletterIds;
	$_SESSION['newsletter-subscription/create/email'] = $email;
	$_SESSION['newsletter-subscription/create/exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /newsletter');
}
