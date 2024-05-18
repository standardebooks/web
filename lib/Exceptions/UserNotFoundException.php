<?
namespace Exceptions;

class UserNotFoundException extends AppException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that user.';
}
