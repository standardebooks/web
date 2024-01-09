<?
namespace Exceptions;

class InvalidMuseumUrlException extends InvalidUrlException{
	protected $message = 'Invalid link to an approved museum page.';
}
