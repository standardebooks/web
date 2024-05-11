<?
use function Safe\strtotime;

$hideDonationAlert = HttpInput::Bool(HttpVariableSource::Post, 'hide-donation-alert');
$colorScheme = HttpInput::Str(HttpVariableSource::Post, 'color-scheme');

if($hideDonationAlert !== null){
	setcookie('hide-donation-alert', $hideDonationAlert ? 'true' : 'false', ['expires' => strtotime('+1 month'), 'path' => '/', 'domain' => SITE_DOMAIN, 'secure' => true, 'httponly' => true, 'samesite' => 'Lax']);
}

if($colorScheme !== null){
	if($colorScheme !== 'dark' && $colorScheme !== 'light' && $colorScheme !== 'auto'){
		$colorScheme = 'auto';
	}

	if($colorScheme == 'auto'){
		// Delete the cookie; auto is the default
		setcookie('color-scheme', '', ['expires' => 0, 'path' => '/', 'domain' => SITE_DOMAIN, 'secure' => true, 'httponly' => true, 'samesite' => 'Lax']);
	}
	else{
		setcookie('color-scheme', $colorScheme, ['expires' => strtotime('+1 year'), 'path' => '/', 'domain' => SITE_DOMAIN, 'secure' => true, 'httponly' => true, 'samesite' => 'Lax']);
	}
}

// HTTP 303, See other
http_response_code(303);

$redirect = $_SERVER['HTTP_REFERER'] ?? '/';
header('Location: ' . $redirect);
?>
