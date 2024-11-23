<?
namespace Exceptions;

class BenefitsRequirePasswordException extends AppException{
	/** @var string $message */
	protected $message = 'One or more of the selected benefits require that the user have a password set.';
}
