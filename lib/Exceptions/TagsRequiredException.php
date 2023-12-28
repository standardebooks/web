<?
namespace Exceptions;

class TagsRequiredException extends AppException{
	protected $message = 'At least one tag is required.';
}
