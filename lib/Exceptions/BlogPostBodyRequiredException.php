<?
namespace Exceptions;

class BlogPostBodyRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'A body is required.';
}
