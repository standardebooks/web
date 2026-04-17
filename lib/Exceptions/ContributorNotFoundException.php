<?
namespace Exceptions;

class ContributorNotFoundException extends NotFoundException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that author.';
}
