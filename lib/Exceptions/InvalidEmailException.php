<?
namespace Exceptions;

class InvalidEmailException extends SeException{
	protected $message = 'We couldn’t understand your email address.';
}
