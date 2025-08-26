<?
use function Safe\glob;
use function Safe\preg_replace;

// Redirect to the latest version of the manual.

$currentManual = Manual::GetLatestVersion();

$url = HttpInput::Str(GET, 'url') ?? '';

try{
	$url = preg_replace('|^/|ius', '', $url);
	$url = preg_replace('|\.php$|ius', '', $url);
	$url = preg_replace('|/$|ius', '', $url);
}
catch(\Exception){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}

if($url != ''){
	$url = '/' . $url;
}

http_response_code(Enums\HttpCode::Found->value);
header('Location: /manual/' . $currentManual . $url);
