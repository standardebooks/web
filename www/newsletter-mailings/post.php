<?
use function Safe\session_start;

try{
	session_start();
	$httpMethod = HttpInput::ValidateRequestMethod([Enums\HttpMethod::Post, Enums\HttpMethod::Patch]);
	$exceptionRedirectUrl = '/newsletter-mailings/new';
	$newsletterMailing = new NewsletterMailing();

	// POSTing a `NewsletterMailing`.
	if($httpMethod == Enums\HttpMethod::Post){
		if(Session::$User === null){
			throw new Exceptions\LoginRequiredException();
		}

		if(!Session::$User->Benefits->CanCreateNewsletterMailings){
			throw new Exceptions\InvalidPermissionsException();
		}

		$addFooter = HttpInput::Bool(POST, 'add-footer') ?? true;
		$addEbooks = HttpInput::Bool(POST, 'add-ebooks') ?? true;

		$newsletterMailing->FillFromHttpPost();

		$newsletterMailing->Create($addFooter, $addEbooks);

		$_SESSION['newsletter-mailing'] = $newsletterMailing;
		$_SESSION['is-newsletter-mailing-created'] = true;

		http_response_code(Enums\HttpCode::SeeOther->value);
		header('Location: /newsletter-mailings');
	}

	// PATCHing a `NewsletterMailing`.
	if($httpMethod == Enums\HttpMethod::Patch){
		$newsletterMailing = NewsletterMailing::Get(HttpInput::Int(GET, 'newsletter-mailing-id'));

		if(Session::$User === null){
			throw new Exceptions\LoginRequiredException();
		}

		if(!Session::$User->Benefits->CanEditNewsletterMailings){
			throw new Exceptions\InvalidPermissionsException();
		}

		$exceptionRedirectUrl = $newsletterMailing->EditUrl;

		$addFooter = HttpInput::Bool(POST, 'add-footer') ?? true;
		$addEbooks = HttpInput::Bool(POST, 'add-ebooks') ?? true;

		$newsletterMailing->FillFromHttpPost();
		$newsletterMailing->Save($addFooter, $addEbooks);

		$_SESSION['newsletter-mailing'] = $newsletterMailing;
		$_SESSION['is-newsletter-mailing-saved'] = true;

		http_response_code(Enums\HttpCode::SeeOther->value);
		header('Location: /newsletter-mailings');
	}
}
catch(Exceptions\NewsletterMailingNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\InvalidNewsletterMailingException $ex){
	$_SESSION['add-footer'] = $addFooter;
	$_SESSION['add-ebooks'] = $addEbooks;

	$_SESSION['newsletter-mailing'] = $newsletterMailing;
	$_SESSION['exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: ' . $exceptionRedirectUrl);
}
