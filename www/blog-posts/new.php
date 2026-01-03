<?
use function Safe\session_start;
use function Safe\session_unset;

try{
	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditBlogPosts){
		throw new Exceptions\InvalidPermissionsException();
	}

	session_start();

	$exception = HttpInput::SessionObject('exception', Exceptions\AppException::class);
	$blogPost = HttpInput::SessionObject('blog-post', BlogPost::class) ?? new BlogPost();
	$userIdentifier = HttpInput::Str(SESSION, 'blog-post-user-identifier');
	$ebookIdentifiers = HttpInput::Str(SESSION, 'blog-post-ebook-identifiers') ?? $blogPost->EbookIdentifiers;

	if($exception){
		// We got here because a `BlogPost` submission had errors and the user has to try again.
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
	}
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
?>
<?= Template::Header(
		title: 'Create a Blog Post',
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

		<form class="blog-post-form" method="<?= Enums\HttpMethod::Post->value ?>" action="/blog-posts" autocomplete="off">
			<?= Template::BlogPostForm(blogPost: $blogPost, userIdentifier: $userIdentifier, ebookIdentifiers: $ebookIdentifiers) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
