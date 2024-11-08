<?
namespace Exceptions;

class InvalidFileUploadException extends AppException{
	/** @var string $message */
	protected $message = 'Uploaded file is invalid.';
}
