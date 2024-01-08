<?
namespace Exceptions;

class MuseumNotFoundException extends AppException{
	protected $message = 'We couldn’t locate that museum.';
}
