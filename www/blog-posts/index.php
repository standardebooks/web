<?
if(Session::$User?->Benefits->CanEditBlogPosts){
	$blogPosts = BlogPost::GetAllByCreated();
}
else{
	$blogPosts = BlogPost::GetAllByIsPublished();
}
?>
<?= Template::Header(title: 'Blog', description: 'The Standard Ebooks blog.', css: ['/css/blog.css']) ?>
<main>
	<section class="has-hero">
		<h1>Blog</h1>
		<picture data-caption="Girl in a Hammock. Winslow Homer, 1873">
			<source srcset="/images/girl-in-a-hammock@2x.avif 2x, /images/girl-in-a-hammock.avif 1x" type="image/avif"/>
			<source srcset="/images/girl-in-a-hammock@2x.jpg 2x, /images/girl-in-a-hammock.jpg 1x" type="image/jpeg"/>
			<img src="/images/girl-in-a-hammock@2x.jpg" alt="A girl reclines in a hammock and reads a book."/>
		</picture>
		<? if(Session::$User?->Benefits->CanEditBlogPosts){ ?>
			<ul role="menu">
				<li><a href="/blog-posts/new">Create a blog post</a></li>
			</ul>
		<? } ?>
		<? if(Session::$User?->Benefits->CanEditBlogPosts){ ?>
			<ul>
				<? foreach($blogPosts as $blogPost){ ?>
					<li>
						<p>
							<a href="<?= $blogPost->Url ?>"><?= $blogPost->Title ?></a> • <? if($blogPost->Body === null){ ?>Must be edited in the database<? }else{ ?><a href="<?= $blogPost->EditUrl ?>">Edit</a><? } ?>
						</p>
					</li>
				<? } ?>
			</ul>
		<? }else{ ?>
			<ul class="blog-post-list">
				<? foreach($blogPosts as $blogPost){ ?>
					<li>
						<a class="blog-post-card<? if($blogPost->ImageCacheKey === null){ ?> no-hero-image<? } ?>" href="<?= $blogPost->Url ?>">
							<? if($blogPost->ImageCacheKey !== null){ ?>
								<picture>
									<source srcset="<?= $blogPost->HeroImageAvifUrl ?> 1x, <?= $blogPost->HeroImageAvif2xUrl ?> 2x" type="image/avif" />
									<source srcset="<?= $blogPost->HeroImageUrl ?> 1x, <?= $blogPost->HeroImage2xUrl ?> 2x" type="image/jpeg" />
									<img src="<?= $blogPost->HeroImageUrl ?>" alt="" />
								</picture>
							<? } ?>
							<span class="blog-post-card-label">
								<span class="blog-post-card-title"><?= $blogPost->Title ?></span>
								<span class="blog-post-card-byline">By <?= Formatter::EscapeHtml($blogPost->User->Name) ?></span>
							</span>
						</a>
					</li>
				<? } ?>
			</ul>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
