<?php

namespace Exceptions;

class InvalidImageUploadException extends SeException{
	protected $message = 'Uploaded image is invalid.';
}
