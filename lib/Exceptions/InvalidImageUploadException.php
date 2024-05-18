<?php

namespace Exceptions;

class InvalidImageUploadException extends AppException{
	/** @var string $message */
	protected $message = 'Uploaded image is invalid.';
}
