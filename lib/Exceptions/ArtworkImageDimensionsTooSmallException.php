<?
namespace Exceptions;

class ArtworkImageDimensionsTooSmallException extends AppException{
	// This has to be initialized in a constructor because we use the number_format() function.
	public function __construct(){
		$this->message = 'Image dimensions are too small. The minimum image size is ' . number_format(ARTWORK_IMAGE_MINIMUM_WIDTH) . ' × ' . number_format(ARTWORK_IMAGE_MINIMUM_HEIGHT) . '.';

		parent::__construct($this->message);
	}
}
