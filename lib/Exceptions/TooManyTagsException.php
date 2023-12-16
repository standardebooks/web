<?
namespace Exceptions;

class TooManyTagsException extends AppException{
	protected $message = 'Number of tags exceeds ' . COVER_ARTWORK_MAX_TAGS;
}
