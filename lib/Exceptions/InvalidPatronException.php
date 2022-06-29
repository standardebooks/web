<?
namespace Exceptions;

class InvalidPatronException extends SeException{
	protected $message = 'We couldn’t locate you in the Patrons Circle.';
}
