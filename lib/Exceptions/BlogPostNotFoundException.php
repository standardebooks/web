<?
namespace Exceptions;

class BlogPostNotFoundException extends AppException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that blog post.';
}
