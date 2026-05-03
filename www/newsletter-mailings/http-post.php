<?
/**
 * POST		/newsletter-mailings
 */

use function Safe\session_start;

try{
	session_start();

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanCreateNewsletterMailings){
		throw new Exceptions\InvalidPermissionsException();
	}

	$newsletterMailing = new NewsletterMailing();

	$addFooter = HttpInput::Bool(POST, 'add-footer') ?? true;
	$addEbooks = HttpInput::Bool(POST, 'add-ebooks') ?? true;

	$newsletterMailing->FillFromHttpPost();

	$newsletterMailing->Create($addFooter, $addEbooks);

	$_SESSION['newsletter-mailing'] = $newsletterMailing;
	$_SESSION['is-newsletter-mailing-created'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /newsletter-mailings');
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
	header('location: /newsletter-mailings/new');
}
