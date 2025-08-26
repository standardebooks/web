<?
use Safe\DateTimeImmutable;

$httpMethod = HttpInput::ValidateRequestMethod([Enums\HttpMethod::Patch]);

// PATCHing settings.
if($httpMethod == Enums\HttpMethod::Patch){
	$hideDonationAlert = HttpInput::Bool(POST, 'hide-donation-alert');
	$hidePublicDomainDayBanner = HttpInput::Bool(POST, 'hide-public-domain-day-banner');
	$colorScheme = Enums\ColorSchemeType::tryFrom(HttpInput::Str(POST, 'color-scheme') ?? '');

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

	/** @var string $redirect */
	$redirect = $_SERVER['HTTP_REFERER'] ?? '/';
	header('Location: ' . $redirect);
}
