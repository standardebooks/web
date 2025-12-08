<?
use function Safe\session_start;
use function Safe\session_unset;
use Ramsey\Uuid\Uuid;

try{
	HttpInput::ValidateRequestMethod([Enums\HttpMethod::Post]);

	session_start();

	if(HttpInput::Str(POST, 'automation-test')){
		// A bot filled out this form field, which should always be empty. Pretend like we succeeded.
		http_response_code(Enums\HttpCode::SeeOther->value);
		$_SESSION['is-bot'] = true;
		$uuid = Uuid::uuid4();
		header('Location: /users/' . $uuid->toString() . '/newsletter-subscriptions');
		exit();
	}

	$email = HttpInput::Str(POST, 'email');
	$newsletterIds = array_unique(HttpInput::Array(POST, 'newsletter-ids') ?? []);
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

	$expectedCaptcha = HttpInput::Str(SESSION, 'captcha') ?? '';
	$receivedCaptcha = HttpInput::Str(POST, 'captcha') ?? '';

	if($expectedCaptcha === '' || $receivedCaptcha === '' || mb_strtolower($expectedCaptcha) !== mb_strtolower($receivedCaptcha)){
		throw new Exceptions\InvalidCaptchaException();
	}

	$sendConfirmationEmail = false;
	try{
		$user = User::GetByEmail($email);
	}
	catch(Exceptions\UserNotFoundException){
		$user = new User();
		$uuid = Uuid::uuid4();
		$user->Uuid = $uuid->toString();
		$user->Email = $email;
	}

	foreach($newsletters as $newsletter){
		$newsletterSubscription = new NewsletterSubscription();
		$newsletterSubscription->Newsletter = $newsletter;
		$newsletterSubscription->NewsletterId = $newsletter->NewsletterId;
		$newsletterSubscription->User = $user;
		try{
			$newsletterSubscription->Create();
			$sendConfirmationEmail = true;
		}
		catch(Exceptions\NewsletterSubscriptionExistsException){
			// Subscription exists, pass.
		}
	}

	if($sendConfirmationEmail){
		// Send the double opt-in confirmation email.
		$user->SendNewsletterSubscriptionConfirmationEmail();
	}

	session_unset();

	http_response_code(Enums\HttpCode::SeeOther->value);
	$_SESSION['is-newsletter-subscription-created'] = true;
	header('Location: /users/' . $user->Uuid . '/newsletter-subscriptions');
}
catch(Exceptions\InvalidNewsletterSubscription | Exceptions\EmailBounceExistsException | Exceptions\InvalidCaptchaException | Exceptions\NewsletterRequiredException $ex){
	$_SESSION['newsletter-ids'] = $newsletterIds;
	$_SESSION['email'] = $email;
	$_SESSION['exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: /newsletter');
}
