<?
namespace Exceptions;

class PollItemNotFoundException extends AppException{
	protected $message = 'We couldn’t locate that poll item.';
}
