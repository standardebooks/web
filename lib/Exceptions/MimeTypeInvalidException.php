<?
namespace Exceptions;

class MimeTypeInvalidException extends AppException{
	/** @var string $message */
	protected $message = 'Uploaded image must be a JPG, BMP, PNG, or TIFF file.';
}
