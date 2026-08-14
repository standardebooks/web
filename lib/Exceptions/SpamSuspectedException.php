<?
namespace Exceptions;

class SpamSuspectedException extends AppException{
	/** @var string $message */
	protected $message = 'Please wait for administrator review.';
}
