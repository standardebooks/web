<?
namespace Exceptions;

class ManagerRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'Manager is required.';
}
