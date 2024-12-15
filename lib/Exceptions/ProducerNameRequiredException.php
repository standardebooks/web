<?
namespace Exceptions;

class ProducerNameRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'Producer name is required.';
}
