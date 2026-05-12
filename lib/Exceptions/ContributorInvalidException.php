<?
namespace Exceptions;

class ContributorInvalidException extends ValidationException{
	/** @var string $message */
	protected $message = 'Contributor is invalid.';
}
