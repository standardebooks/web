<?
namespace Exceptions;

class InvalidCopyrightPageUrlException extends InvalidUrlException{
	protected $message = 'Invalid link to page with copyright details.';
}
