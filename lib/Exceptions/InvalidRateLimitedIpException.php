<?
namespace Exceptions;

class InvalidRateLimitedIpException extends ValidationException{
	/** @var string $message */
	protected $message = 'RateLimitedIp is invalid.';
}
