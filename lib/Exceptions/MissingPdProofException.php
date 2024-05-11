<?
namespace Exceptions;

class MissingPdProofException extends AppException{
	/** @var string $message */
	protected $message = 'Missing proof of U.S. public domain status.';
}
