<?
$authors = Contributor::GetAllByMarcRole(Enums\MarcRole::Author);

?><?= Template::Header(title: 'Authors', description: 'Browse all Standard Ebooks authors.') ?>
<main>
	<section class="narrow has-hero">
		<h1>Authors</h1>
		<picture data-caption="Leo Tolstoy in His Study. Ilya Repin, 1891">
			<source srcset="/images/leo-tolstoy-in-his-study@2x.avif 2x, /images/leo-tolstoy-in-his-study.avif 1x" type="image/avif"/>
			<source srcset="/images/leo-tolstoy-in-his-study@2x.jpg 2x, /images/leo-tolstoy-in-his-study.jpg 1x" type="image/jpg"/>
			<img src="/images/leo-tolstoy-in-his-study@2x.jpg" alt="Leo Tolstoy sitting at a desk and writing."/>
		</picture>
		<ul>
			<? foreach($authors as $author){ ?>
				<li>
					<p><a href="<?= $author->Url ?>"><?= Formatter::EscapeHtml($author->Name) ?></a></p>
				</li>
			<? } ?>
		</ul>
	</section>
</main>
<?= Template::Footer() ?>
