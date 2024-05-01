<?
namespace Exceptions;

class PatronNotFoundException extends AppException{
	protected $message = 'We couldn’t locate you in the Patrons Circle.';
}
