<?
namespace Exceptions;

class InvalidGoogleBooksUrlException extends InvalidUrlException{
	protected $message = 'Invalid Google Books URL. Google Books URLs begin with “https://www.google.com/books/edition/_/” and must be in single-page view. An example of a valid Google Books URL is “https://www.google.com/books/edition/_/mZpAAAAAYAAJ?gbpv=1&pg=PA70-IA2”.';
}
