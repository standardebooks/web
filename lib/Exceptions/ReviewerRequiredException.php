<?
namespace Exceptions;

class ReviewerRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'Reviewer is required.';
}
