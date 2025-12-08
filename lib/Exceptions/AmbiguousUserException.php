<?
namespace Exceptions;

class AmbiguousUserException extends AppException{
	/** @var string $message */
	protected $message = 'More than one user exists with that name.';

	/** @var array<\User> */
	public array $Users;

	/**
	 * @param array<\User> $users
	 */
	public function __construct(array $users){
		$this->Users = $users;

		parent::__construct($this->message);
	}
}
