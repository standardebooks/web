<?
namespace Exceptions;

class FileUploadInvalidException extends AppException{
	/** @var string $message */
	protected $message = 'Uploaded file is invalid.';
}
