<?
require_once('Core.php');

try{
	$urlPath = trim(str_replace('.', '', HttpInput::Str(GET, 'url-path', true, '')), '/'); // Contains the portion of the URL (without query string) that comes after https://standardebooks.org/ebooks/
	$wwwFilesystemPath = EBOOKS_DIST_PATH . $urlPath; // Path to the deployed WWW files for this ebook

	if($urlPath == '' || mb_stripos($wwwFilesystemPath, EBOOKS_DIST_PATH) !== 0 || !is_dir($wwwFilesystemPath)){
		// Ensure the path exists and that the root is in our www directory
		throw new Exceptions\InvalidAuthorException();
	}

	$ebooks = Library::GetEbooksByAuthor($wwwFilesystemPath);

	if(sizeof($ebooks) == 0){
		throw new Exceptions\InvalidAuthorException();
	}
}
catch(Exceptions\InvalidAuthorException $ex){
	http_response_code(404);
	include(WEB_ROOT . '/404.php');
	exit();
}
?><?= Template::Header(['title' => 'Ebooks by ' . strip_tags($ebooks[0]->AuthorsHtml), 'highlight' => 'ebooks', 'description' => 'All of the Standard Ebooks ebooks by ' . strip_tags($ebooks[0]->AuthorsHtml)]) ?>
<main class="ebooks">
	<h1>Ebooks by <?= $ebooks[0]->AuthorsHtml ?></h1>
	<?= Template::EbookGrid(['ebooks' => $ebooks, 'view' => VIEW_GRID]) ?>
	<?= Template::ContributeAlert() ?>
</main>
<?= Template::Footer() ?>
