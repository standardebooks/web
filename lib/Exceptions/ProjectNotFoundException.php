<?
namespace Exceptions;

class ProjectNotFoundException extends AppException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that project.';
}
