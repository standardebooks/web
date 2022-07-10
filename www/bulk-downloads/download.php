<?
require_once('Core.php');

use function Safe\preg_match;

$path = HttpInput::Str(GET, 'path', false) ?? '';

try{
	$path = '/bulk-downloads/' . $path;

	if(!is_file(WEB_ROOT . $path)){
		throw new Exceptions\InvalidFileException();
	}

	if($GLOBALS['User'] === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!preg_match('/\.zip$/ius', $path)){
		throw new Exceptions\InvalidPermissionsException();
	}

	if(!$GLOBALS['User']->Benefits->CanBulkDownload){
		throw new Exceptions\InvalidPermissionsException();
	}

	// Everything OK, serve the file using Apache.
	// The xsendfile Apache module tells Apache to serve the file, including not-modified or etag headers.
	// Much more efficien than reading it in PHP and outputting it that way.
	header('X-Sendfile: ' . WEB_ROOT . $path);
	header('Content-Type: application/zip');
	header('Content-Disposition: attachment; filename="' . basename($path) . '"');
	exit();
}
catch(Exceptions\LoginRequiredException $ex){
	if(isset($_SERVER['HTTP_REFERER'])){
		Template::RedirectToLogin(true, $_SERVER['HTTP_REFERER']);
	}
	else{
		preg_match('|(^/bulk-downloads/[^/]+?)/|ius', $path, $matches);
		if(sizeof($matches) == 2){
			// If we arrived from the bulk-downloads page,
			// Make the login form redirect to the bulk download root, instead of refreshing directly into a download
			Template::RedirectToLogin(true, $matches[1]);
		}
		else{
			Template::RedirectToLogin();
		}
	}
}
catch(Exceptions\InvalidPermissionsException $ex){
	http_response_code(403);
}
catch(Exceptions\InvalidFileException $ex){
	Template::Emit404();
}

?><?= Template::Header(['title' => 'Download ', 'highlight' => '', 'description' => 'Download zip files containing all of the Standard Ebooks released in a given month.']) ?>
<main>
	<section class="narrow">
		<h1>Downloading ebook collections</h1>
		<p><a href="/about#patrons-circle">Patrons circle members</a> can download zip files containing all of the ebooks that were released in a given month of Standard Ebooks history. You can <a href="/donate#patrons-circle">join the Patrons Circle</a> with a small donation in support of our continuing mission to create free, beautiful digital literature.</p>
	</section>
</main>
<?= Template::Footer() ?>
