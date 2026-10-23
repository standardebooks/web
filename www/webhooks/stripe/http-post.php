<?
/**
 * POST		/webhooks/stripe
 *
 * For the testing environment only; dispatch events forwarded by the local Stripe CLI listener.
 */

use Safe\Exceptions\JsonException;
use function Safe\json_decode;

if(SITE_STATUS != SITE_STATUS_DEV){
	http_response_code(Enums\HttpCode::Forbidden->value);
	exit();
}

try{
	$payload = json_decode(Http::$Request->Body->RawBody);

	if(!$payload instanceof stdClass || !is_string($payload->type ?? null)){
		throw new Exceptions\WebhookException('Invalid Stripe event payload.');
	}

	if(is_file(__DIR__ . '/' . $payload->type . '/http-post.php')){
		include(__DIR__ . '/' . $payload->type . '/http-post.php');
	}
	else{
		http_response_code(Enums\HttpCode::NotFound->value);
	}
}
catch(JsonException | Exceptions\WebhookException){
	http_response_code(Enums\HttpCode::BadRequest->value);
}
