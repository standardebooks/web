<?php

namespace Exceptions;

class InvalidMimeTypeException extends AppException{
	/** @var string $message */
	protected $message = 'Uploaded image must be a JPG, BMP, PNG, or TIFF file.';
}
