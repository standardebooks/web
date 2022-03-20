<?
namespace Exceptions;

class InvalidCaptchaException extends SeException{
	protected $message = 'We couldn’t validate your CAPTCHA response.';
}
