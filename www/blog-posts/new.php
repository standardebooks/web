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

	$exception = Http::$Request->Session->Get('blog-post/create/exception', Exceptions\AppException::class);
	$blogPost = Http::$Request->Session->Get('blog-post', BlogPost::class) ?? new BlogPost();
	$userIdentifier = Http::$Request->Session->Get('blog-post-user-identifier');
	$ebookIdentifiers = Http::$Request->Session->Get('blog-post-ebook-identifiers') ?? $blogPost->EbookIdentifiers;
	$hasHeroImage = Http::$Request->Session->Get('blog-post-has-hero-image', 'bool') ?? true;

	if($exception){
		// We got here because an operation had errors and the user has to try again.
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
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
			<?= Template::BlogPostForm(blogPost: $blogPost, userIdentifier: $userIdentifier, ebookIdentifiers: $ebookIdentifiers, hasHeroImage: $hasHeroImage) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
