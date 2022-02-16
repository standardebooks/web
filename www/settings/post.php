<?
require_once('Core.php');

use function Safe\strtotime;

$hideDonationAlert = HttpInput::Bool(POST, 'hide-donation-alert');
$colorScheme = HttpInput::Str(POST, 'color-scheme');

if($hideDonationAlert !== null){
	setcookie('hide-donation-alert', $hideDonationAlert ? 'true' : 'false', strtotime('+1 month'), '/', '', true, true);
}

if($colorScheme !== null){
	if($colorScheme !== 'dark' && $colorScheme !== 'light' && $colorScheme !== 'auto'){
		$colorScheme = 'auto';
	}

	if($colorScheme == 'auto'){
		// Delete the cookie; auto is the default
		setcookie('color-scheme', '', 0, '/', '', true, true);
	}
	else{
		setcookie('color-scheme', $colorScheme, strtotime('+1 year'), '/', '', true, true);
	}
}

// HTTP 303, See other
http_response_code(303);

$redirect = $_SERVER['HTTP_REFERER'] ?? '/';
header('Location: ' . $redirect);
?>
