<?
try{
	$canEditBlogPosts = Session::$User?->Benefits->CanEditBlogPosts ?? false;
	$page = Http::$Request->QueryString->Get('page', 'int') ?? 1;
	$perPage = 10;

	$result = BlogPost::GetAllByPage($page, $perPage, $canEditBlogPosts);
	$blogPosts = $result['blogPosts'];
	$pages = $result['totalPages'];
}
catch(Exceptions\PageOutOfBoundsException $ex){
	header('location: /blog?page=' . $ex->TotalPages);
	exit();
}
?>
<?= Template::Header(title: 'Blog', highlight: 'blog', description: 'The Standard Ebooks blog.', css: ['/css/blog.css']) ?>
<main>
	<section class="has-hero">
		<h1>Blog</h1>
		<picture data-caption="Girl in a Hammock. Winslow Homer, 1873">
			<source srcset="/images/girl-in-a-hammock@2x.avif 2x, /images/girl-in-a-hammock.avif 1x" type="image/avif"/>
			<source srcset="/images/girl-in-a-hammock@2x.jpg 2x, /images/girl-in-a-hammock.jpg 1x" type="image/jpeg"/>
			<img src="/images/girl-in-a-hammock@2x.jpg" alt="A girl reclines in a hammock and reads a book."/>
		</picture>
		<? if($canEditBlogPosts){ ?>
			<ul role="menu">
				<li><a href="/blog-posts/new">Create a blog post</a></li>
			</ul>
		<? } ?>
		<? if($canEditBlogPosts){ ?>
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
		<? if(sizeof($blogPosts) > 0){ ?>
			<nav class="pagination" aria-label="Pagination">
				<a<? if($page > 1){ ?> href="/blog?page=<?= $page - 1 ?>" rel="prev"<? }else{ ?> aria-disabled="true"<? } ?>>Back</a>
				<ol>
					<? for($i = 1; $i < $pages + 1; $i++){ ?>
						<li>
							<a <? if($page == $i){ ?>aria-current="page" href="#"<? }else{ ?>href="/blog?page=<?= $i ?>"<? } ?>><?= $i ?></a>
						</li>
					<? } ?>
				</ol>
				<a<? if($page < $pages){ ?> href="/blog?page=<?= $page + 1 ?>" rel="next"<? }else{ ?> aria-disabled="true"<? } ?>>Next</a>
			</nav>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
