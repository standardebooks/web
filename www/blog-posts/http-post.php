<?
/**
 * POST		/blog-posts
 */

use function Safe\session_start;

$stagedImagePath = null;

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
	$stagedImageToken = Http::$Request->Body->Get('blog-post-hero-image-token');
	$imageTokenSecret = Http::$Request->Session->Get('blog-post/create/image-token-secret');
	$stagedImagePath = ImageTempDirectory::GetStagedImagePath($stagedImageToken, $imageTokenSecret);
	$uploadedImagePath = $hasHeroImage ? Http::$Request->Files->Get('blog-post-hero-image') : null;

	unset($_SESSION['blog-post/create/image-token-secret']);

	if($uploadedImagePath !== null){
		$stagedImagePath = ImageTempDirectory::ReplaceImage($stagedImagePath, $uploadedImagePath);
	}
	elseif(!$hasHeroImage && $stagedImagePath !== null){
		ImageTempDirectory::RemoveImage($stagedImagePath);
		$stagedImagePath = null;
	}

	if($stagedImagePath !== null){
		$_SESSION['blog-post/create/image-path'] = $stagedImagePath;
	}

	$blogPost->FillFromRequestBody();

	$blogPost->Create($userIdentifier, $ebookIdentifiers, $stagedImagePath, $hasHeroImage);

	ImageTempDirectory::RemoveImage($stagedImagePath);

	unset($_SESSION['blog-post/create/image-path']);

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
	if($stagedImagePath !== null && ($ex instanceof Exceptions\ImageUploadInvalidException || ($ex instanceof Exceptions\BlogPostInvalidException && $ex->Has(Exceptions\ImageUploadInvalidException::class)))){
		ImageTempDirectory::RemoveImage($stagedImagePath);

		unset($_SESSION['blog-post/create/image-path']);
	}

	$_SESSION['blog-post/create/blog-post'] = $blogPost;
	$_SESSION['blog-post/create/exception'] = $ex;
	$_SESSION['blog-post/create/user-identifier'] = $userIdentifier;
	$_SESSION['blog-post/create/ebook-identifiers'] = $ebookIdentifiers;
	$_SESSION['blog-post/create/has-hero-image'] = $hasHeroImage;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /blog-posts/new');
}
