<?
namespace Exceptions;

class DonationInvalidException extends AppException{
	/** @var string $message */
	protected $message = 'Please enter your email address and name and select one of the displayed donation amounts. For a custom one-time donation, enter a whole dollar amount from $5 to $999.';
}
