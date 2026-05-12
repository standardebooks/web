<?
/**
 * PATCH	/newsletter-mailings/:newsletter-mailing-id
 */

use function Safe\session_start;

try{
	session_start();

	/** @var NewsletterMailing $newsletterMailing The `NewsletterMailing` for this request, passed in from the router. */
	$newsletterMailing = $resource ?? throw new Exceptions\NewsletterMailingNotFoundException();

	$originalNewsletterMailing = $newsletterMailing;

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditNewsletterMailings){
		throw new Exceptions\PermissionsInvalidException();
	}

	$addFooter = Http::$Request->Body->Get('add-footer', 'bool') ?? true;
	$addEbooks = Http::$Request->Body->Get('add-ebooks', 'bool') ?? true;

	$newsletterMailing->FillFromHttpPost();
	$newsletterMailing->Save($addFooter, $addEbooks);

	$_SESSION['newsletter-mailing'] = $newsletterMailing;
	$_SESSION['is-newsletter-mailing-saved'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /newsletter-mailings');
}
catch(Exceptions\NewsletterMailingNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\NewsletterMailingInvalidException $ex){
	$_SESSION['add-footer'] = $addFooter;
	$_SESSION['add-ebooks'] = $addEbooks;

	$_SESSION['newsletter-mailing'] = $newsletterMailing;
	$_SESSION['exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $originalNewsletterMailing->EditUrl);
}
