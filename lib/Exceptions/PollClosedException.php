<?
namespace Exceptions;

class PollClosedException extends SeException{
	protected $message = 'This poll is not open to voting right now.';
}
