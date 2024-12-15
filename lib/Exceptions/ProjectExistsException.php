<?
namespace Exceptions;

class ProjectExistsException extends AppException{
	/** @var string $message */
	protected $message = 'An active project already exists for this ebook.';
}
