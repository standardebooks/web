<?
namespace Exceptions;

class BlogPostNotFoundException extends NotFoundException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that blog post.';
}
