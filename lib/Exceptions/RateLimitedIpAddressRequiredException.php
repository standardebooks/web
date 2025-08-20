<?
namespace Exceptions;

class RateLimitedIpAddressRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'RateLimitedIp IpAddress required.';
}
