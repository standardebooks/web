<?
namespace Exceptions;

use Safe\DateTimeImmutable;

class InvalidEbookWwwFilesystemPathException extends AppException{
	protected $message = 'Invalid WwwFilesystemPath.';

	public function __construct(?string $path){
		$this->message = 'Invalid WwwFilesystemPath. Not readable: ' . $path;
	}
}
