<?
namespace Exceptions;

class InvalidCaptchaException extends AppException{
	protected $message = 'We couldn’t validate your CAPTCHA response.';
}
