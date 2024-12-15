<?
namespace Exceptions;

class InvalidContributorException extends ValidationException{
	/** @var string $message */
	protected $message = 'Contributor is invalid.';
}
