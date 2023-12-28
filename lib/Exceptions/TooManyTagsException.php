<?
namespace Exceptions;

class TooManyTagsException extends AppException{
	protected $message = 'Too many tags; the maximum is ' . COVER_ARTWORK_MAX_TAGS . '.';
}
