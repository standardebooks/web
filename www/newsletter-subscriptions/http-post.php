<?
/**
 * POST		/newsletter-subscriptions
 */

use function Safe\session_start;
use function Safe\session_unset;
use Ramsey\Uuid\Uuid;

try{
	session_start();

	if(Http::$Request->Body->Get('automation-test')){
		// A bot filled out this form field, which should always be empty. Pretend like we succeeded.
		http_response_code(Enums\HttpCode::SeeOther->value);
		$_SESSION['newsletter-subscription/create/is-bot'] = true;
		$uuid = Uuid::uuid4();
		header('location: /users/' . $uuid->toString() . '/newsletter-subscriptions');
		exit();
	}

	$email = Http::$Request->Body->Get('email') ?? '';
	$newsletterIds = array_unique(Http::$Request->Body->Get('newsletter-ids', 'array') ?? []);
	$newsletters = [];
	foreach($newsletterIds as $newsletterId){
		if(ctype_digit($newsletterId)){
			try{
				$newsletters[] = Newsletter::Get((int)$newsletterId);
			}
			catch(Exceptions\NewsletterNotFoundException){
				// Pass.
			}
		}
	}

	if(sizeof($newsletters) == 0){
		throw new Exceptions\NewsletterRequiredException();
	}

	$expectedCaptcha = Http::$Request->Session->Get('captcha') ?? '';
	$receivedCaptcha = Http::$Request->Body->Get('captcha') ?? '';

	if($expectedCaptcha === '' || $receivedCaptcha === '' || mb_strtolower($expectedCaptcha) !== mb_strtolower($receivedCaptcha)){
		throw new Exceptions\CaptchaInvalidException();
	}

	try{
		$user = User::GetByEmail($email);
	}
	catch(Exceptions\UserNotFoundException){
		$user = new User();
		$uuid = Uuid::uuid4();
		$user->Uuid = $uuid->toString();
		$user->Email = $email;
	}

	$hasEmailBounced = Db::QueryBool('SELECT exists (select * from EmailBounces where Email = ? and IsActive = true)', [$email]);

	if($hasEmailBounced){
		throw new Exceptions\EmailBounceExistsException('An email we sent to this email address bounced back or was marked as spam. We can’t send email to this email address anymore.');
	}

	try{
		$user = User::GetByEmail($email);
	}
	catch(Exceptions\UserNotFoundException){
		try{
			$user->Create();
		}
		catch(Exceptions\UserExistsException | Exceptions\UserInvalidException){
			// `User` exists, pass.
			$user = User::GetByEmail($email);
		}
	}

	$parameters = [];

	foreach($newsletters as $newsletter){
		$newsletterSubscription = new NewsletterSubscription();
		$newsletterSubscription->Newsletter = $newsletter;
		$newsletterSubscription->NewsletterId = $newsletter->NewsletterId;
		$newsletterSubscription->User = $user;
		$newsletterSubscription->UserId = $user->UserId;
		$newsletterSubscription->Created = NOW;
		$newsletterSubscription->Validate();

		$parameters[] = $newsletterSubscription->UserId;
		$parameters[] = $newsletterSubscription->NewsletterId;
		$parameters[] = $newsletterSubscription->IsConfirmed;
		$parameters[] = $newsletterSubscription->IsVisible;
		$parameters[] = $newsletterSubscription->Created;
	}

	Db::MultiInsert('INSERT ignore into NewsletterSubscriptions (UserId, NewsletterId, IsConfirmed, IsVisible, Created) values (?, ?, ?, ?, ?)', $parameters);

	if(Db::$LastQueryAffectedRowCount > 0){
		// Send the double opt-in confirmation email.
		$user->SendNewsletterSubscriptionConfirmationEmail();
	}

	session_unset();

	http_response_code(Enums\HttpCode::SeeOther->value);
	$_SESSION['newsletter-subscription/create/is-created'] = true;
	header('location: ' . $user->UuidUrl . '/newsletter-subscriptions');
}
catch(Exceptions\InvalidNewsletterSubscription | Exceptions\EmailBounceExistsException | Exceptions\CaptchaInvalidException | Exceptions\NewsletterRequiredException $ex){
	$_SESSION['newsletter-ids'] = $newsletterIds;
	$_SESSION['email'] = $email;
	$_SESSION['newsletter-subscription/create/exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /newsletter');
}
