<?
namespace Exceptions;

class TooManyTagsException extends AppException{
	protected $message = 'Too many tags; the maximum is ' . ARTWORK_MAX_TAGS . '.';
}
