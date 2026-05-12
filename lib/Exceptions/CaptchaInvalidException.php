<?
namespace Exceptions;

class CaptchaInvalidException extends AppException{
	/** @var string $message */
	protected $message = 'We couldn’t validate your CAPTCHA response.';
}
