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
		throw new Exceptions\PermissionsInvalidException();
	}

	$newsletterMailing = new NewsletterMailing();

	$addFooter = Http::$Request->Body->Get('add-footer', 'bool') ?? true;
	$addEbooks = Http::$Request->Body->Get('add-ebooks', 'bool') ?? true;

	$newsletterMailing->FillFromRequestBody();

	$newsletterMailing->Create($addFooter, $addEbooks);

	$_SESSION['newsletter-mailing'] = $newsletterMailing;
	$_SESSION['is-newsletter-mailing-created'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /newsletter-mailings');
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
	header('location: /newsletter-mailings/new');
}
