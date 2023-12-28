<?php

namespace Exceptions;

class InvalidMimeTypeException extends AppException{
	protected $message = 'Uploaded image must be a JPG, BMP, PNG, or TIFF file.';
}
