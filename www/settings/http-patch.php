<?
use Safe\DateTimeImmutable;

$hideDonationAlert = Http::$Request->Body->Get('hide-donation-alert', 'bool');
$hidePublicDomainDayBanner = Http::$Request->Body->Get('hide-public-domain-day-banner', 'bool');
$colorScheme = Enums\ColorSchemeType::tryFrom(Http::$Request->Body->Get('color-scheme') ?? '');

if($hideDonationAlert !== null){
	setcookie('hide-donation-alert', $hideDonationAlert ? 'true' : 'false', ['expires' => intval((new DateTimeImmutable('+1 month'))->format(Enums\DateTimeFormat::UnixTimestamp->value)), 'path' => '/', 'domain' => SITE_DOMAIN, 'secure' => true, 'httponly' => true, 'samesite' => 'Lax']);
}

if($hidePublicDomainDayBanner !== null){
	setcookie('hide-public-domain-day-banner', $hidePublicDomainDayBanner ? 'true' : 'false', ['expires' => intval((new DateTimeImmutable('+1 month'))->format(Enums\DateTimeFormat::UnixTimestamp->value)), 'path' => '/', 'domain' => SITE_DOMAIN, 'secure' => true, 'httponly' => true, 'samesite' => 'Lax']);
}

if($colorScheme !== null){
	if($colorScheme == Enums\ColorSchemeType::Auto){
		// Delete the cookie; auto is the default.
		setcookie('color-scheme', '', ['expires' => 0, 'path' => '/', 'domain' => SITE_DOMAIN, 'secure' => true, 'httponly' => true, 'samesite' => 'Lax']);
	}
	else{
		setcookie('color-scheme', $colorScheme->value, ['expires' => intval((new DateTimeImmutable('+1 year'))->format(Enums\DateTimeFormat::UnixTimestamp->value)), 'path' => '/', 'domain' => SITE_DOMAIN, 'secure' => true, 'httponly' => true, 'samesite' => 'Lax']);
	}
}

http_response_code(Enums\HttpCode::SeeOther->value);

$redirect = Template::SanitizeRedirectUrl(Http::$Request->Headers['http-referer'] ?? '/');
header('location: ' . $redirect);
