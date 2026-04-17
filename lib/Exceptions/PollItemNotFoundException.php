<?
namespace Exceptions;

class PollItemNotFoundException extends NotFoundException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that poll item.';
}
