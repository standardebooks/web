<?
/**
 * PATCH		/blog-posts/:blog-post-url-title
 */

use function Safe\session_start;

try{
	session_start();

	/** @var BlogPost $blogPost The `BlogPost` for this request, passed in from the router. */
	$blogPost = $resource ?? throw new Exceptions\BlogPostNotFoundException();

	$originalBlogPost = new $blogPost;

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditBlogPosts){
		throw new Exceptions\InvalidPermissionsException();
	}

	$userIdentifier = HttpInput::Str(POST, 'blog-post-user-identifier');
	$ebookIdentifiers = HttpInput::Str(POST, 'blog-post-ebook-identifiers');

	$blogPost->FillFromHttpPost();

	$blogPost->Save($userIdentifier, $ebookIdentifiers);

	$_SESSION['is-blog-post-saved'] = true;
	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $blogPost->Url);
}
catch(Exceptions\BlogPostNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
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
	header('location: ' . $originalBlogPost->EditUrl);
}
