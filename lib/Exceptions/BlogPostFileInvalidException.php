<?
namespace Exceptions;

class BlogPostFileInvalidException extends AppException{
	/** @var string $message */
	protected $message = 'Blog post is empty, but corresponding file doesn’t exist in the file system.';
}
