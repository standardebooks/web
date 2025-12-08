<?
namespace Exceptions;

class InvalidBlogPostException extends ValidationException{
	/** @var string $message */
	protected $message = 'Blog post is invalid.';
}
