<?
namespace Exceptions;

class BlogPostTitleRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'A title is required.';
}
