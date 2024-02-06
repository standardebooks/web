<?
namespace Exceptions;

class EbookUrlRequiredException extends AppException{
	protected $message = 'Ebook identifier is required.';
}
