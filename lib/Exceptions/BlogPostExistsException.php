<?
namespace Exceptions;

class BlogPostExistsException extends AppException{
	/** @var string $message */
	protected $message = 'There’s already a blog post with that title.';
}
