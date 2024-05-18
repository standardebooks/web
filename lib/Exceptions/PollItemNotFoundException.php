<?
namespace Exceptions;

class PollItemNotFoundException extends AppException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that poll item.';
}
