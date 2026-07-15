<?
if(Session::$User?->Benefits->CanEditBlogPosts){
	$blogPosts = BlogPost::GetAllByCreated();
}
else{
	$blogPosts = BlogPost::GetAllByIsPublished();
}
?>
<?= Template::Header(title: 'Blog', description: 'The Standard Ebooks blog.') ?>
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
		<ul>
			<? foreach($blogPosts as $blogPost){ ?>
				<li>
					<p>
						<a href="<?= $blogPost->Url ?>"><?= $blogPost->Title ?></a><? if(Session::$User?->Benefits->CanEditBlogPosts){ ?> • <? if($blogPost->Body === null){ ?>Must be edited in the database<? }else{ ?><a href="<?= $blogPost->EditUrl ?>">Edit</a><? } ?><? } ?>
					</p>
				</li>
			<? } ?>
		</ul>
	</section>
</main>
<?= Template::Footer() ?>
