<?
namespace Exceptions;

class InvalidDiscussionUrlException extends InvalidUrlException{
	/** @var string $message */
	protected $message = 'Invalid discussion URL.';
}
