<?
namespace Exceptions;

class UserNotFoundException extends AppException{
	protected $message = 'We couldn’t locate that user.';
}
