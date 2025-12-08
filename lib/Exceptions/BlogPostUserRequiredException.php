<?
namespace Exceptions;

class BlogPostUserRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'An author is required.';
}
