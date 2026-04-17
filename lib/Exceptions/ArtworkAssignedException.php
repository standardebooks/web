<?
namespace Exceptions;

class ArtworkAssignedException extends AppException{
	/** @var string $message */
	protected $message = 'That ebook already has an artwork assigned.';
}
