<?
use function Safe\session_start;
use function Safe\session_unset;

try{
	$originalBlogPost = BlogPost::GetByUrlTitle(HttpInput::Str(GET, 'url-title'));

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditBlogPosts){
		throw new Exceptions\InvalidPermissionsException();
	}

	session_start();

	$exception = HttpInput::SessionObject('exception', Exceptions\AppException::class);
	$blogPost = HttpInput::SessionObject('blog-post', BlogPost::class) ?? $originalBlogPost;
	$userIdentifier = HttpInput::Str(SESSION, 'blog-post-user-identifier');
	$ebookIdentifiers = HttpInput::Str(SESSION, 'blog-post-ebook-identifiers') ?? $blogPost->EbookIdentifiers;

	if($exception){
		// We got here because a `BlogPost` submission had errors and the user has to try again.
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
	}
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
?>
<?= Template::Header(
		title: 'Edit - ' . strip_tags($originalBlogPost->Title),
		css: ['/css/blog.css'],
		description: 'Edit a blog post on the Standard Ebooks blog.'
) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs">
			<a href="/blog">Blog</a> → <a href="<?= $originalBlogPost->Url ?>"><?= $originalBlogPost->Title ?></a> →
		</nav>
		<h1>Edit</h1>

		<?= Template::Error(exception: $exception) ?>

		<form class="blog-post-form" method="<?= Enums\HttpMethod::Post->value ?>" action="<?= $originalBlogPost->Url ?>" autocomplete="off">
			<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Patch->value ?>" />
			<?= Template::BlogPostForm(blogPost: $blogPost, userIdentifier: $userIdentifier, ebookIdentifiers: $ebookIdentifiers, isEditForm: true) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
