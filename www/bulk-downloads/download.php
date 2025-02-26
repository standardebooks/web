<?
use function Safe\preg_match;

$path = HttpInput::Str(GET, 'path') ?? '';

try{
	$path = '/bulk-downloads/' . $path;

	if(!is_file(WEB_ROOT . $path)){
		throw new Exceptions\InvalidFileException();
	}

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!preg_match('/\.zip$/ius', $path)){
		throw new Exceptions\InvalidPermissionsException();
	}

	if(!Session::$User->Benefits->CanBulkDownload){
		throw new Exceptions\InvalidPermissionsException();
	}

	// Everything OK, serve the file using Apache.
	// The `xsendfile` Apache module tells Apache to serve the file, including `not-modified` or `etag` headers.
	// Much more efficient than reading it in PHP and outputting it that way.
	header('X-Sendfile: ' . WEB_ROOT . $path);
	header('Content-Type: application/zip');
	header('Content-Disposition: attachment; filename="' . basename($path) . '"');
	exit();
}
catch(Exceptions\LoginRequiredException){
	if(isset($_SERVER['HTTP_REFERER'])){
		/** @var string $httpReferer */
		$httpReferer = $_SERVER['HTTP_REFERER'];
		Template::RedirectToLogin(true, $httpReferer);
	}
	else{
		preg_match('|(^/bulk-downloads/[^/]+?)/|ius', $path, $matches);
		if(sizeof($matches) == 2){
			// If we arrived from the bulk-downloads page make the login form redirect to the bulk download root, instead of refreshing directly into a download.
			Template::RedirectToLogin(true, $matches[1]);
		}
		else{
			Template::RedirectToLogin();
		}
	}
}
catch(Exceptions\InvalidPermissionsException){
	http_response_code(Enums\HttpCode::Forbidden->value);
}
catch(Exceptions\InvalidFileException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}

?><?= Template::Header(['title' => 'Downloading Ebook Collections', 'highlight' => '', 'description' => 'Download zip files containing all of the Standard Ebooks released in a given month.']) ?>
<main>
	<section class="narrow">
		<h1>Downloading Ebook Collections</h1>
		<p><a href="/about#patrons-circle">Patrons circle members</a> can download zip files containing all of the ebooks that were released in a given month of Standard Ebooks history. You can <a href="/donate#patrons-circle">join the Patrons Circle</a> with a small donation in support of our continuing mission to create free, beautiful digital literature.</p>
	</section>
</main>
<?= Template::Footer() ?>
