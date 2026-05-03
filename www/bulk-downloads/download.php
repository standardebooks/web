<?
/**
 * GET		/bulk-downloads/:path
 *
 * `:path` must end in `.zip`.
 */

use function Safe\preg_match;
use function Safe\preg_replace;

try{
	$path = '/bulk-downloads/' . HttpInput::Str(GET, 'path');

	// Remove `./` and `../` from the path.
	$path = preg_replace('/\.\.?\//u', '', $path);
	$relativePath = $path;
	$path = WEB_ROOT . $path;

	if(!is_file($path) || !preg_match('/^' . preg_quote(WEB_ROOT . '/bulk-downloads/', '/') . '.+\.zip$/iu', $path)){
		throw new Exceptions\InvalidFileException();
	}

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanBulkDownload){
		throw new Exceptions\InvalidPermissionsException();
	}

	// Everything OK, serve the file using Apache.
	// The `xsendfile` Apache module tells Apache to serve the file, including `not-modified` or `etag` headers.
	// Much more efficient than reading it in PHP and outputting it that way.
	header('x-sendfile: ' . $path);
	header('content-type: application/zip');
	header('content-disposition: attachment; filename="' . basename($path) . '"');
	exit();
}
catch(Exceptions\InvalidFileException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	if(isset($_SERVER['HTTP_REFERER'])){
		/** @var string $httpReferer */
		$httpReferer = $_SERVER['HTTP_REFERER'];
		Template::RedirectToLogin(true, $httpReferer);
	}
	else{
		preg_match('|(^/bulk-downloads/[^/]+?)/|ius', $relativePath, $matches);
		if(sizeof($matches) == 2){
			// If we arrived from the bulk downloads page, make the login form redirect to the bulk download root, instead of refreshing directly into a download.
			Template::RedirectToLogin(true, $matches[1]);
		}
		else{
			Template::RedirectToLogin();
		}
	}
}
catch(Exceptions\InvalidPermissionsException){
	// Output an HTTP code and show the explanation on this page.
	http_response_code(Enums\HttpCode::Forbidden->value);
}
?><?= Template::Header(title: 'Downloading Ebook Collections', description: 'Download zip files containing all of the Standard Ebooks released in a given month.') ?>
<main>
	<section class="narrow">
		<h1>Downloading Ebook Collections</h1>
		<p><a href="/about#patrons-circle">Patrons circle members</a> can download zip files containing all of the ebooks that were released in a given month of Standard Ebooks history. You can <a href="/donate#patrons-circle">join the Patrons Circle</a> with a small donation in support of our continuing mission to create free, beautiful digital literature.</p>
	</section>
</main>
<?= Template::Footer() ?>
