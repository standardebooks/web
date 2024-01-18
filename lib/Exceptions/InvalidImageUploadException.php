<?php

namespace Exceptions;

class InvalidImageUploadException extends AppException{
	public function __construct(?string $message = null){
		if($message === null){
			$message = 'Uploaded image is invalid.';
		}

		parent::__construct($message);
	}
}
