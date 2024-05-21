<?
namespace Exceptions;

use Safe\DateTimeImmutable;

class InvalidEbookRepoFilesystemPathException extends AppException{
	/** @var string $message */
	protected $message = 'Invalid RepoFilesystemPath.';

	public function __construct(?string $path){
		$this->message = 'Invalid RepoFilesystemPath. Not readable: ' . $path;
	}
}
