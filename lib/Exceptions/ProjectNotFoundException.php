<?
namespace Exceptions;

class ProjectNotFoundException extends NotFoundException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that project.';
}
