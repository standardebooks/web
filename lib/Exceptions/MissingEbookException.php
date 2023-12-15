<?
namespace Exceptions;

class MissingEbookException extends AppException{
	protected $message = 'Status `in_use` requires EbookWwwFilesystemPath.';
}
