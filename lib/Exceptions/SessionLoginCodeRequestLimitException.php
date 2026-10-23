<?
namespace Exceptions;

class SessionLoginCodeRequestLimitException extends AppException{
	/** @var string $message */
	protected $message = 'You can request one login link every fifteen minutes, up to three times in twenty-four hours.';
}
