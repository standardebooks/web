<?
require_once('Core.php');

?><?= Template::Header(['title' => 'This Ebook Is No Longer Available', 'highlight' => '', 'description' => 'This ebook is unavailable due to legal reasons.']) ?>
<main>
	<section class="narrow has-hero">
		<hgroup>
			<h1>This Ebook Is No Longer Available</h1>
			<h2>This is 451 error.</h2>
		</hgroup>
		<picture data-caption="The Course of Empire: Destruction. Thomas Cole, 1836">
			<source srcset="/images/the-course-of-empire-destruction@2x.avif 2x, /images/the-course-of-empire-destruction.avif 1x" type="image/avif"/>
			<source srcset="/images/the-course-of-empire-destruction@2x.jpg 2x, /images/the-course-of-empire-destruction.jpg 1x" type="image/jpg"/>
			<img src="/images/the-course-of-empire-destruction@2x.jpg" alt="A classical city is aflame as people scramble in the foreground."/>
		</picture>
		<p>This ebook is no longer available, due to legal reasons. This may occur if the book was erroneously thought to be in the U.S. public domain, but new evidence changed that determination.</p>
	</section>
</main>
<?= Template::Footer() ?>
