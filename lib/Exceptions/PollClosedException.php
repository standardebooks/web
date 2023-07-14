<?
namespace Exceptions;

class PollClosedException extends AppException{
	protected $message = 'This poll is not open to voting right now.';
}
