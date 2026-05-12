<?
namespace Exceptions;

class CopyrightPageUrlInvalidException extends UrlInvalidException{
	/** @var string $message */
	protected $message = 'Invalid link to page with copyright details.';
}
