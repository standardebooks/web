<?
use function Safe\session_start;

$blogPost = new BlogPost();

try{
	session_start();
	$httpMethod = HttpInput::ValidateRequestMethod([Enums\HttpMethod::Post, Enums\HttpMethod::Patch]);
	$exceptionRedirectUrl = '/blog-posts/new';

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditBlogPosts){
		throw new Exceptions\InvalidPermissionsException();
	}

	$userIdentifier = HttpInput::Str(POST, 'blog-post-user-identifier');
	$ebookIdentifiers = HttpInput::Str(POST, 'blog-post-ebook-identifiers');

	// POSTing a `BlogPost`.
	if($httpMethod == Enums\HttpMethod::Post){
		$blogPost->FillFromHttpPost();

		$blogPost->Create($userIdentifier, $ebookIdentifiers);

		$_SESSION['is-blog-post-created'] = true;

		http_response_code(Enums\HttpCode::SeeOther->value);
		header('Location: ' . $blogPost->Url);
	}

	// PATCH a `BlogPost`.
	if($httpMethod == Enums\HttpMethod::Patch){
		$originalBlogPost = BlogPost::GetByUrlTitle(HttpInput::Str(GET, 'url-title'));
		$exceptionRedirectUrl = $originalBlogPost->EditUrl;

		$blogPost->FillFromHttpPost();
		$blogPost->BlogPostId = $originalBlogPost->BlogPostId;

		$blogPost->Save($userIdentifier, $ebookIdentifiers);

		$_SESSION['is-blog-post-saved'] = true;
		http_response_code(Enums\HttpCode::SeeOther->value);
		header('Location: ' . $blogPost->Url);
	}
}
catch(Exceptions\HttpMethodNotAllowedException){
	Template::ExitWithCode(Enums\HttpCode::MethodNotAllowed);
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
	header('Location: ' . $exceptionRedirectUrl);
}
