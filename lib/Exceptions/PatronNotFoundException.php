<?
namespace Exceptions;

class PatronNotFoundException extends AppException{
	/** @var string $message */
	protected $message = 'We couldn’t locate you in the Patrons Circle.';
}
