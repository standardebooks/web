<?
namespace Exceptions;

class PollNotFoundException extends AppException{
	protected $message = 'We couldn’t locate that poll.';
}
