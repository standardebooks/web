<?
namespace Exceptions;

use Safe\DateTimeImmutable;

class EbookWwwFilesystemPathInvalidException extends AppException{
	/** @var string $message */
	protected $message = 'Invalid WwwFilesystemPath.';

	public function __construct(?string $path){
		$this->message = 'Invalid WwwFilesystemPath. Not readable: ' . $path;
		parent::__construct($this->message);
	}
}
