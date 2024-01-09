<?
namespace Exceptions;

class InvalidInternetArchiveUrlException extends InvalidUrlException{
	protected $message = 'Invalid Internet Archive URL. Internet Archive URLs begin with “https://archive.org/details/” and must be in single-page view. An example of a valid Internet Archive URL is “https://archive.org/details/royalacademypict1902roya/page/n9/mode/1up”.';
}
