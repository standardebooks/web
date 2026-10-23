<?
namespace Exceptions;

/**
 * Collect validation errors for a Patron before granting benefits.
 */
class PatronInvalidException extends ValidationException{
	/** @var string The default description of the invalid Patron. */
	protected $message = 'This Patron is invalid.';
}
