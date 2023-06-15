<?php

namespace Exceptions;

class InvalidImageUploadException extends SeException {
	protected $message = 'The image you tried to upload is not valid.';
}
