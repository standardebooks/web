<?php

namespace Exceptions;

class InvalidImageUploadException extends AppException{
	protected $message = 'Uploaded image is invalid.';
}
