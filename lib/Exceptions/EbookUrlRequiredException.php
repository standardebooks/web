<?
namespace Exceptions;

class EbookUrlRequiredException extends AppException{
	protected $message = 'Ebook absolute URL is required.';
}
