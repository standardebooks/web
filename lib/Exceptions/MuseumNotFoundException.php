<?
namespace Exceptions;

class MuseumNotFoundException extends NotFoundException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that museum.';
}
