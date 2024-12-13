<?
namespace Exceptions;

class AppException extends \Exception{
	public \Enums\ExceptionMessageType $MessageType = \Enums\ExceptionMessageType::Text;
}
