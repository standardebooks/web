<?
namespace Exceptions;

class BlogPostInvalidException extends ValidationException{
	/** @var string $message */
	protected $message = 'Blog post is invalid.';
}
