<?
require_once('Core.php');

$colorScheme = $_POST['color-scheme'] ?? 'auto';

if($colorScheme !== 'dark' && $colorScheme !== 'light' && $colorScheme !== 'auto'){
	$colorScheme = 'auto';
}

if($colorScheme == 'auto'){
	// Delete the cookie; auto is the default
	setcookie('color-scheme', null, ['expires' => 0, 'path' => '/', 'secure' => true, 'httponly' => true]);
}
else{
	setcookie('color-scheme', $colorScheme, ['expires' => strtotime('+10 years'), 'path' => '/', 'secure' => true, 'httponly' => true]);
}

// HTTP 303, See other
http_response_code(303);
header('Location: /settings');
?>
