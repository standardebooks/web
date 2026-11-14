<?
namespace Exceptions;

/** A contributor's NACOAF URL conflicts with an existing contributor. */
class ContributorExistsException extends AppException{
	/** @var string $message */
	protected $message = 'A contributor already exists with this NACOAF URL.';
}
