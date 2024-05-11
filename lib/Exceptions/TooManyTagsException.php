<?
namespace Exceptions;

class TooManyTagsException extends AppException{
	/** @var string $message */
	protected $message = 'Too many tags; the maximum is ' . ARTWORK_MAX_TAGS . '.';
}
