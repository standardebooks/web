<?
namespace Exceptions;

class ImageUploadInvalidException extends AppException{
	/** @var string $message */
	protected $message = 'Uploaded image is invalid.';
}
