<?
namespace Exceptions;

class InvalidHathiTrustUrlException extends InvalidUrlException{
	protected $message = 'Invalid HathiTrust URL. HathiTrust URLs begin with “https://babel.hathitrust.org/cgi/pt”. An example of a valid HathiTrust URL is “https://babel.hathitrust.org/cgi/pt?id=hvd.32044034383265&seq=13”.';
}
