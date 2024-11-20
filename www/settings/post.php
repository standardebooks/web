<?
use Safe\DateTimeImmutable;

$hideDonationAlert = HttpInput::Bool(POST, 'hide-donation-alert');
$colorScheme = HttpInput::Str(POST, 'color-scheme');

if($hideDonationAlert !== null){
	setcookie('hide-donation-alert', $hideDonationAlert ? 'true' : 'false', ['expires' => intval((new DateTimeImmutable('+1 month'))->format(Enums\DateTimeFormat::UnixTimestamp->value)), 'path' => '/', 'domain' => SITE_DOMAIN, 'secure' => true, 'httponly' => true, 'samesite' => 'Lax']);
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
		setcookie('color-scheme', $colorScheme, ['expires' => intval((new DateTimeImmutable('+1 year'))->format(Enums\DateTimeFormat::UnixTimestamp->value)), 'path' => '/', 'domain' => SITE_DOMAIN, 'secure' => true, 'httponly' => true, 'samesite' => 'Lax']);
	}
}

// HTTP 303, See other
http_response_code(Enums\HttpCode::SeeOther->value);

$redirect = $_SERVER['HTTP_REFERER'] ?? '/';
header('Location: ' . $redirect);
?>
