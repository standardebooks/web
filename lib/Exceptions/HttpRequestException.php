<?
namespace Exceptions;

class HttpRequestException extends AppException{
	public string $Url;
	/** @var array<string, mixed>|string|\stdClass $Data */
	public array|string|\stdClass $Data;
	public \Enums\HttpMethod $Method;

	/** @var string $message */
	protected $message = 'Error making HTTP request.';
}
