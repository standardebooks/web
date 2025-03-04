<?
$ebooks = [];
$author = '';
$authorUrl = '';
$showLinks = false;

try{
	$urlPath = trim(str_replace('.', '', HttpInput::Str(GET, 'url-path') ?? ''), '/'); // Contains the portion of the URL (without query string) that comes after `https://standardebooks.org/ebooks/`.

	if($urlPath == ''){
		throw new Exceptions\AuthorNotFoundException();
	}

	$ebooks = Ebook::GetAllByAuthor($urlPath);

	if(sizeof($ebooks) == 0){
		throw new Exceptions\AuthorNotFoundException();
	}

	// If all of the author's ebooks are placeholders, don't show download/feed links.
	foreach($ebooks as $ebook){
		if(!$ebook->IsPlaceholder()){
			$showLinks = true;
			break;
		}
	}

	$author =  strip_tags($ebooks[0]->AuthorsHtml);
	$authorUrl = $ebooks[0]->AuthorsUrl;
}
catch(Exceptions\AuthorNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
?><?= Template::Header(title: 'Ebooks by ' . $author, feedUrl: str_replace('/ebooks/', '/authors/', $authorUrl), feedTitle: 'Standard Ebooks - Ebooks by ' . $author, highlight: 'ebooks', description: 'All of the Standard Ebooks ebooks by ' . $author, canonicalUrl: SITE_URL . $authorUrl) ?>
<main class="ebooks">
	<h1 class="is-collection">Ebooks by <?= $ebooks[0]->AuthorsHtml ?></h1>
	<? if($showLinks){ ?>
		<p class="ebooks-toolbar">
			<a class="button" href="<?= Formatter::EscapeHtml($authorUrl) ?>/downloads">Download collection</a>
			<a class="button" href="<?= Formatter::EscapeHtml($authorUrl) ?>/feeds">Feeds for this author</a>
		</p>
	<? } ?>
	<?= Template::EbookGrid(ebooks: $ebooks, view: Enums\ViewType::Grid) ?>
	<p class="feeds-alert">We also have <a href="/bulk-downloads">bulk ebook downloads</a> and a <a href="/collections">list of collections</a> available, as well as <a href="/feeds">ebook catalog feeds</a> for use directly in your ereader app or RSS reader.</p>
	<?= Template::ContributeAlert() ?>
</main>
<?= Template::Footer() ?>
