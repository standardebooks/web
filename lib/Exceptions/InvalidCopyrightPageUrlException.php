<?
namespace Exceptions;

class InvalidCopyrightPageUrlException extends InvalidUrlException{
	/** @var string $message */
	protected $message = 'Invalid link to page with copyright details.';
}
