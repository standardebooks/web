<?
namespace Exceptions;

class ContributorNotFoundException extends AppException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that author.';
}
