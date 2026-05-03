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
		throw new Exceptions\InvalidPermissionsException();
	}

	$userIdentifier = HttpInput::Str(POST, 'blog-post-user-identifier');
	$ebookIdentifiers = HttpInput::Str(POST, 'blog-post-ebook-identifiers');

	$blogPost = new BlogPost();
	$blogPost->FillFromHttpPost();

	$blogPost->Create($userIdentifier, $ebookIdentifiers);

	$_SESSION['is-blog-post-created'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $blogPost->Url);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\InvalidBlogPostException | Exceptions\BlogPostExistsException $ex){
	$_SESSION['blog-post'] = $blogPost;
	$_SESSION['exception'] = $ex;
	$_SESSION['blog-post-user-identifier'] = $userIdentifier;
	$_SESSION['blog-post-ebook-identifiers'] = $ebookIdentifiers;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /blog-posts/new');
}
