<?
require_once('Core.php');

$colorScheme = $_POST['color-scheme'] ?? 'auto';

if($colorScheme !== 'dark' && $colorScheme !== 'light' && $colorScheme !== 'auto'){
	$colorScheme = 'auto';
}

if($colorScheme == 'auto'){
	// Delete the cookie; auto is the default
	setcookie('color-scheme', null, 0, '/', '', true, true);
}
else{
	setcookie('color-scheme', $colorScheme, strtotime('+10 years'), '/', '', true, true);
}

// HTTP 303, See other
http_response_code(303);
header('Location: /settings');
?>
