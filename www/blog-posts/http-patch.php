<?
/**
 * PATCH	/blog-posts/:blog-post-url-title
 */

use function Safe\session_start;

$stagedImagePath = null;

try{
	session_start();

	/** @var BlogPost $blogPost The `BlogPost` for this request, passed in from the router. */
	$blogPost = $resource ?? throw new Exceptions\BlogPostNotFoundException();

	$originalEditUrl = $blogPost->EditUrl;

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditBlogPosts){
		throw new Exceptions\PermissionsInvalidException();
	}

	$userIdentifier = Http::$Request->Body->Get('blog-post-user-identifier');
	$ebookIdentifiers = Http::$Request->Body->Get('blog-post-ebook-identifiers');
	$hasHeroImage = Http::$Request->Body->Get('blog-has-hero-image', 'bool') ?? false;
	$stagedImageToken = Http::$Request->Body->Get('blog-post-hero-image-token');
	$imageTokenSecret = Http::$Request->Session->Get('blog-post/edit/image-token-secret');
	try{
		$stagedImagePath = UploadTempDirectory::GetStagedUploadPath($stagedImageToken, $imageTokenSecret);
	}
	catch(Exceptions\FileUploadInvalidException){
		throw new Exceptions\ImageUploadInvalidException('Please re-upload the image.');
	}
	$uploadedImagePath = $hasHeroImage ? Http::$Request->Files->Get('blog-post-hero-image') : null;

	unset($_SESSION['blog-post/edit/image-token-secret']);

	if($uploadedImagePath !== null){
		try{
			$stagedImagePath = UploadTempDirectory::ReplaceUpload($stagedImagePath, $uploadedImagePath);
		}
		catch(Exceptions\FileUploadInvalidException){
			throw new Exceptions\ImageUploadInvalidException('Failed to save uploaded image.');
		}
	}
	elseif(!$hasHeroImage && $stagedImagePath !== null){
		UploadTempDirectory::RemoveUpload($stagedImagePath);
		$stagedImagePath = null;
	}

	if($stagedImagePath !== null){
		$_SESSION['blog-post/edit/image-path'] = $stagedImagePath;
	}

	$blogPost->FillFromRequestBody();

	$blogPost->Save($userIdentifier, $ebookIdentifiers, $stagedImagePath, $hasHeroImage);

	UploadTempDirectory::RemoveUpload($stagedImagePath);

	unset($_SESSION['blog-post/edit/image-path']);

	$_SESSION['blog-post/edit/is-saved'] = true;
	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $blogPost->Url);
}
catch(Exceptions\BlogPostNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\BlogPostInvalidException | Exceptions\BlogPostExistsException | Exceptions\ImageUploadInvalidException | Exceptions\FileUploadInvalidException | Exceptions\FileUploadTooLargeException $ex){
	if($stagedImagePath !== null && ($ex instanceof Exceptions\ImageUploadInvalidException || ($ex instanceof Exceptions\BlogPostInvalidException && $ex->Has(Exceptions\ImageUploadInvalidException::class)))){
		UploadTempDirectory::RemoveUpload($stagedImagePath);

		unset($_SESSION['blog-post/edit/image-path']);
	}

	$_SESSION['blog-post/edit/blog-post'] = $blogPost;
	$_SESSION['blog-post/edit/exception'] = $ex;
	$_SESSION['blog-post/edit/user-identifier'] = $userIdentifier;
	$_SESSION['blog-post/edit/ebook-identifiers'] = $ebookIdentifiers;
	$_SESSION['blog-post/edit/has-hero-image'] = $hasHeroImage;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $originalEditUrl);
}
