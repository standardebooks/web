<?
use function Safe\session_start;
use function Safe\session_unset;
session_start();

try{
	$blogPost = BlogPost::GetByUrlTitle(HttpInput::Str(GET, 'url-title'));

	$isCreated = HttpInput::Bool(SESSION, 'is-blog-post-created') ?? false;
	$isSaved = HttpInput::Bool(SESSION, 'is-blog-post-saved') ?? false;

	if($isCreated){
		// We got here because a `BlogPost` was successfully submitted.
		http_response_code(Enums\HttpCode::Created->value);
	}

	if($isCreated || $isSaved){
		session_unset();
	}
}
catch(Exceptions\BlogPostNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
?>
<?= Template::Header(title: strip_tags($blogPost->Title), css: ['/css/blog.css']) ?>
<main>
	<section class="narrow blog<? if(isset($blogPost->Subtitle)){ ?> has-hero<? } ?>">
		<nav class="breadcrumbs"><a href="/blog">Blog</a> →</nav>
		<? if(isset($blogPost->Subtitle)){ ?>
			<hgroup>
				<h1><?= $blogPost->Title ?></h1>
				<p><?= $blogPost->Subtitle ?></p>
			</hgroup>
		<? }else{ ?>
			<h1><?= $blogPost->Title ?></h1>
		<? } ?>

		<? if(Session::$User?->Benefits->CanEditBlogPosts){ ?>
			<ul role="menu">
				<li><a href="<?= $blogPost->EditUrl ?>">Edit blog post</a></li>
			</ul>
		<? } ?>

		<p class="byline">By <?= Formatter::EscapeHtml($blogPost->User->Name) ?></p>

		<? if($isCreated){ ?>
			<p class="message success">Blog post created!</p>
		<? } ?>

		<? if($isSaved){ ?>
			<p class="message success">Blog post saved!</p>
		<? } ?>

		<?= Template::DonationCounter() ?>
		<?= Template::DonationProgress() ?>
		<?= $blogPost->Body ?>
		<? if(sizeof($blogPost->Ebooks) > 0){ ?>
			<section id="ebooks-in-this-newsletter">
				<h2>Free ebooks in this article</h2>
				<?= Template::EbookCarousel(ebooks: $blogPost->Ebooks) ?>
			</section>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
