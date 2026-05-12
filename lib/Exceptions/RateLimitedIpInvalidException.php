<?
namespace Exceptions;

class RateLimitedIpInvalidException extends ValidationException{
	/** @var string $message */
	protected $message = 'RateLimitedIp is invalid.';
}
