<?
namespace Exceptions;

class UserNotFoundException extends NotFoundException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that user.';
}
