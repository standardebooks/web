<?
namespace Exceptions;

class InvalidPatronException extends AppException{
	protected $message = 'We couldn’t locate you in the Patrons Circle.';
}
