<?
/**
 * POST		/blog-posts
 */

use function Safe\session_start;

try{
	session_start();

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditBlogPosts){
		throw new Exceptions\PermissionsInvalidException();
	}

	$blogPost = new BlogPost();

	$userIdentifier = Http::$Request->Body->Get('blog-post-user-identifier');
	$ebookIdentifiers = Http::$Request->Body->Get('blog-post-ebook-identifiers');
	$hasHeroImage = Http::$Request->Body->Get('blog-has-hero-image', 'bool') ?? false;
	$heroImagePath = $hasHeroImage ? Http::$Request->Files->Get('blog-post-hero-image') : null;

	$blogPost->FillFromRequestBody();

	$blogPost->Create($userIdentifier, $ebookIdentifiers, $heroImagePath, $hasHeroImage);

	$_SESSION['blog-post/create/is-created'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $blogPost->Url);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\BlogPostInvalidException | Exceptions\BlogPostExistsException | Exceptions\ImageUploadInvalidException | Exceptions\FileUploadInvalidException | Exceptions\FileUploadTooLargeException $ex){
	$_SESSION['blog-post'] = $blogPost;
	$_SESSION['blog-post/create/exception'] = $ex;
	$_SESSION['blog-post-user-identifier'] = $userIdentifier;
	$_SESSION['blog-post-ebook-identifiers'] = $ebookIdentifiers;
	$_SESSION['blog-post-has-hero-image'] = $hasHeroImage;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /blog-posts/new');
}
