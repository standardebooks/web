<?
namespace Exceptions;

class ContributorNameRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'Contributor name required.';
}
