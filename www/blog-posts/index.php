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
	<section class="narrow">
		<h1>Blog</h1>
		<? if(Session::$User?->Benefits->CanEditBlogPosts){ ?>
			<p>
				<a href="/blog-posts/new">Create a blog post</a>
			</p>
		<? } ?>
		<ul>
			<? foreach($blogPosts as $blogPost){ ?>
				<li>
					<p>
						<a href="<?= $blogPost->Url ?>"><?= $blogPost->Title ?></a><? if(Session::$User?->Benefits->CanEditBlogPosts){ ?> • <? if($blogPost->Body === null){ ?>Must be edited in the database<? }else{ ?><a href="<?= $blogPost->EditUrl ?>">Edit</a><? } ?><? } ?>
					</p>
				</li>
			<? } ?>
		</ul>
	</section>
</main>
<?= Template::Footer() ?>
