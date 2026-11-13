<?
/**
 * GET /contributors?query=IDENTIFIER
 * GET /contributors/:url-name
 */

Http::$Request->Route(allowedHttpMethods: [Enums\HttpMethod::Get]);
