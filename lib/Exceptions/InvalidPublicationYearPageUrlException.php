<?
namespace Exceptions;

class InvalidPublicationYearPageUrlException extends InvalidUrlException{
	/** @var string $message */
	protected $message = 'Invalid link to page with year of publication.';
}
