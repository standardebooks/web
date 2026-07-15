<?
/**
 * GET		/blog-posts/:blog-post-url-title
 */

use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	/** @var BlogPost $blogPost The `BlogPost` for this request, passed in from the router. */
	$blogPost = $resource ?? throw new Exceptions\BlogPostNotFoundException();

	$isCreated = Http::$Request->Session->Get('blog-post/create/is-created', 'bool') ?? false;
	$isSaved = Http::$Request->Session->Get('blog-post/edit/is-saved', 'bool') ?? false;

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
		<nav class="breadcrumbs" aria-label="Breadcrumbs"><a href="/blog">Blog</a> →</nav>
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

		<? if($isCreated){ ?>
			<p class="message success">Blog post created!</p>
		<? } ?>

		<? if($isSaved){ ?>
			<p class="message success">Blog post saved!</p>
		<? } ?>

		<? if($blogPost->ImageCacheKey !== null){ ?>
			<picture class="hero-image"<? if($blogPost->HeroImageCaption !== null){ ?> data-caption="<?= Formatter::EscapeHtml($blogPost->HeroImageCaption) ?>"<? } ?>>
				<source srcset="<?= $blogPost->HeroImageAvifUrl ?> 1x, <?= $blogPost->HeroImageAvif2xUrl ?> 2x" type="image/avif" />
				<source srcset="<?= $blogPost->HeroImageUrl ?> 1x, <?= $blogPost->HeroImage2xUrl ?> 2x" type="image/jpeg" />
				<img src="<?= $blogPost->HeroImageUrl ?>" alt="" width="880" height="250" />
			</picture>
		<? } ?>

		<p class="byline">By <?= Formatter::EscapeHtml($blogPost->User->Name) ?></p>

		<? if($blogPost->Published > NOW){ ?>
			<p class="message info">This blog post is scheduled to be published on <?= date_format($blogPost->Published->setTimezone(SITE_TZ), Enums\DateTimeFormat::FullDateTime->value) ?> <?= SITE_TZ_STRING ?>.</p>
		<? } ?>

		<?= Template::DonationDrive() ?>
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
