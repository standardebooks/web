<?
namespace Exceptions;

class InvalidCaptchaException extends AppException{
	/** @var string $message */
	protected $message = 'We couldn’t validate your CAPTCHA response.';
}
