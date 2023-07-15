<?
$ebooks = [];
$author = '';
$authorUrl = '';

try{
	$urlPath = trim(str_replace('.', '', HttpInput::Str(GET, 'url-path', true) ?? ''), '/'); // Contains the portion of the URL (without query string) that comes after https://standardebooks.org/ebooks/
	$wwwFilesystemPath = EBOOKS_DIST_PATH . $urlPath; // Path to the deployed WWW files for this ebook

	if($urlPath == '' || mb_stripos($wwwFilesystemPath, EBOOKS_DIST_PATH) !== 0 || !is_dir($wwwFilesystemPath)){
		// Ensure the path exists and that the root is in our www directory
		throw new Exceptions\InvalidAuthorException();
	}

	$ebooks = Library::GetEbooksByAuthor($wwwFilesystemPath);

	if(sizeof($ebooks) == 0){
		throw new Exceptions\InvalidAuthorException();
	}

	$author =  strip_tags($ebooks[0]->AuthorsHtml);
	$authorUrl = Formatter::ToPlainText($ebooks[0]->AuthorsUrl);
}
catch(Exceptions\InvalidAuthorException){
	Template::Emit404();
}
?><?= Template::Header(['title' => 'Ebooks by ' . $author, 'feedUrl' => str_replace('/ebooks/', '/authors/', $authorUrl), 'feedTitle' => 'Standard Ebooks - Ebooks by ' . $author, 'highlight' => 'ebooks', 'description' => 'All of the Standard Ebooks ebooks by ' . $author]) ?>
<main class="ebooks">
	<h1 class="is-collection">Ebooks by <?= $ebooks[0]->AuthorsHtml ?></h1>
	<p class="ebooks-toolbar">
		<a class="button" href="<?= $authorUrl ?>/downloads">Download collection</a>
		<a class="button" href="<?= $authorUrl ?>/feeds">Feeds for this author</a>
	</p>
	<?= Template::EbookGrid(['ebooks' => $ebooks, 'view' => VIEW_GRID]) ?>
	<p class="feeds-alert">We also have <a href="/bulk-downloads">bulk ebook downloads</a> and a <a href="/collections">list of collections</a> available, as well as <a href="/feeds">ebook catalog feeds</a> for use directly in your ereader app or RSS reader.</p>
	<?= Template::ContributeAlert() ?>
</main>
<?= Template::Footer() ?>
