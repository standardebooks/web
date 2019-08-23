<?
require_once('Core.php');

try{
	$urlPath = trim(str_replace('.', '', HttpInput::GetString('url-path') ?? ''), '/'); // Contains the portion of the URL (without query string) that comes after https://standardebooks.org/ebooks/
	$wwwFilesystemPath = EBOOKS_DIST_PATH . $urlPath; // Path to the deployed WWW files for this ebook

	if($urlPath == '' || mb_stripos($wwwFilesystemPath, EBOOKS_DIST_PATH) !== 0 || !is_dir($wwwFilesystemPath)){
		// Ensure the path exists and that the root is in our www directory
		throw new InvalidAuthorException();
	}

	$ebooks = Library::GetEbooksByAuthor($wwwFilesystemPath);

	if(sizeof($ebooks) == 0){
		throw new InvalidAuthorException();
	}
}
catch(\Exception $ex){
	http_response_code(404);
	include(SITE_ROOT . '/www/404.php');
	exit();
}
?><?= Template::Header(['title' => 'Ebooks by ' . strip_tags($ebooks[0]->AuthorsHtml), 'highlight' => 'ebooks', 'description' => 'All of the Standard Ebooks ebooks by ' . strip_tags($ebooks[0]->AuthorsHtml)]) ?>
<main class="ebooks">
	<h1>Ebooks by <?= $ebooks[0]->AuthorsHtml ?></h1>
	<?= Template::SearchForm() ?>
	<?= Template::EbookGrid(['ebooks' => $ebooks]) ?>
	<?= Template::ContributeAlert() ?>
</main>
<?= Template::Footer() ?>
