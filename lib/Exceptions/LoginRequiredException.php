<?
namespace Exceptions;

class LoginRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'Please log in to continue.';
}
