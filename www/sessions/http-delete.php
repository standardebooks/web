<?
/**
 * DELETE	/sessions/:session-id
 */

Session::Delete(Http::$Request->QueryString->Get('session-id'));

http_response_code(Enums\HttpCode::SeeOther->value);
header('location: /');
