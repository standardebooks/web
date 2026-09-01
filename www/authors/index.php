<?
use function Safe\preg_match;

$authors = Contributor::GetAllByMarcRole(Enums\MarcRole::Author);
$authorsByLetter = [];

// Group authors by the first letter of their sort name.
foreach($authors as $author){
	$letter = strtolower(substr(Formatter::RemoveDiacritics($author->SortName ?? ''), 0, 1));
	if($letter != ''){
		$authorsByLetter[$letter][] = $author;
	}
}

?><?= Template::Header(title: 'Authors', description: 'Browse all Standard Ebooks authors.') ?>
<main>
	<section class="has-hero columns-layout">
		<h1>Authors</h1>
		<picture data-caption="Leo Tolstoy in His Study. Ilya Repin, 1891">
			<source srcset="/images/leo-tolstoy-in-his-study@2x.avif 2x, /images/leo-tolstoy-in-his-study.avif 1x" type="image/avif"/>
			<source srcset="/images/leo-tolstoy-in-his-study@2x.jpg 2x, /images/leo-tolstoy-in-his-study.jpg 1x" type="image/jpeg"/>
			<img src="/images/leo-tolstoy-in-his-study@2x.jpg" alt="Leo Tolstoy sitting at a desk and writing."/>
		</picture>
		<nav aria-label="Authors by letter">
			<ul>
				<? foreach(range('a', 'z') as $letter){ ?>
					<li><? if(isset($authorsByLetter[$letter])){ ?><a href="#<?= $letter ?>"><? } ?><?= $letter ?><? if(isset($authorsByLetter[$letter])){ ?></a><? } ?></li>
				<? } ?>
			</ul>
		</nav>
		<? foreach($authorsByLetter as $letter => $letterAuthors){ ?>
			<section id="<?= $letter ?>">
				<h2><?= Formatter::EscapeHtml(strtoupper($letter)) ?><a class="heading-permalink" href="#<?= $letter ?>" aria-label="Permalink"></a></h2>
				<ul>
					<? foreach($letterAuthors as $author){ ?>
						<li>
							<p><a href="<?= $author->Url ?>"><?= Formatter::EscapeHtml($author->DisplayName) ?></a></p>
						</li>
					<? } ?>
				</ul>
			</section>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
