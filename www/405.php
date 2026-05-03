<?
http_response_code(Enums\HttpCode::MethodNotAllowed->value);
header('allow: GET,HEAD,OPTIONS');
