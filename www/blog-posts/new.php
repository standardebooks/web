<?
/**
 * GET		/blog-posts/new
 */

use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditBlogPosts){
		throw new Exceptions\PermissionsInvalidException();
	}

	unset($_SESSION['blog-post/create/image-token-secret']);

	$exception = Http::$Request->Session->Get('blog-post/create/exception', Exceptions\AppException::class);
	$blogPost = Http::$Request->Session->Get('blog-post/create/blog-post', BlogPost::class) ?? new BlogPost();
	$userIdentifier = Http::$Request->Session->Get('blog-post/create/user-identifier');
	$ebookIdentifiers = Http::$Request->Session->Get('blog-post/create/ebook-identifiers') ?? $blogPost->EbookIdentifiers;
	$hasHeroImage = Http::$Request->Session->Get('blog-post/create/has-hero-image', 'bool') ?? true;
	$stagedImagePath = Http::$Request->Session->Get('blog-post/create/image-path');
	$stagedImageToken = null;

	if($exception){
		// We got here because an operation had errors and the user has to try again.
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
		if($stagedImagePath !== null && is_file($stagedImagePath)){
			$imageTokenSecret = bin2hex(random_bytes(32));
			try{
				$stagedImageToken = ImageTempDirectory::CreateToken($stagedImagePath, $imageTokenSecret);
				$_SESSION['blog-post/create/image-token-secret'] = $imageTokenSecret;
			}
			catch(Exceptions\TempDirectoryException){
				// The staged image is not available for reuse.
			}
		}
	}
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
?>
<?= Template::Header(
		title: 'Create a Blog Post',
		highlight: 'blog',
		css: ['/css/blog.css'],
		description: 'Create a blog post to the Standard Ebooks blog.'
) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
			<a href="/blog">Blog</a> →
		</nav>
		<h1>Create a Blog Post</h1>

		<?= Template::Error(exception: $exception) ?>

		<form class="blog-post-form" method="<?= Enums\HttpMethod::Post->value ?>" action="/blog-posts" enctype="multipart/form-data" autocomplete="off">
			<?= Template::BlogPostForm(blogPost: $blogPost, userIdentifier: $userIdentifier, ebookIdentifiers: $ebookIdentifiers, hasHeroImage: $hasHeroImage, stagedImageToken: $stagedImageToken) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
