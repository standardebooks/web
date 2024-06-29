<?
use Ramsey\Uuid\Uuid;
use Safe\DateTimeImmutable;

/**
 * @property User $User
 * @property string $Url
 */
class Session{
	use Traits\Accessor;

	public int $UserId;
	public DateTimeImmutable $Created;
	public string $SessionId;

	protected ?User $_User = null;
	public ?string $_Url = null;


	// *******
	// GETTERS
	// *******

	protected function GetUrl(): string{
		if($this->_Url === null){
			$this->_Url = '/sessions/' . $this->SessionId;
		}

		return $this->_Url;
	}


	// *******
	// METHODS
	// *******

	/**
	 * @param ?string $identifier Either the email, or the UUID, of the user attempting to log in.
	 *
	 * @throws Exceptions\InvalidLoginException
	 * @throws Exceptions\PasswordRequiredException
	 */
	public function Create(?string $identifier = null, ?string $password = null): void{
		try{
			$this->User = User::GetIfRegistered($identifier, $password);
			$this->UserId = $this->User->UserId;

			$existingSessions = Db::Query('
							SELECT SessionId,
							       Created
							from Sessions
							where UserId = ?
						', [$this->UserId]);

			if(sizeof($existingSessions) > 0){
				$this->SessionId = $existingSessions[0]->SessionId;
				$this->Created = $existingSessions[0]->Created;
			}
			else{
				$uuid = Uuid::uuid4();
				$this->SessionId = $uuid->toString();

				/** @throws void */
				$this->Created = new DateTimeImmutable();
				Db::Query('
						INSERT into Sessions (UserId, SessionId, Created)
						values (?,
						        ?,
						        ?)
					', [$this->UserId, $this->SessionId, $this->Created]);
			}

			self::SetSessionCookie($this->SessionId);
		}
		catch(Exceptions\UserNotFoundException){
			throw new Exceptions\InvalidLoginException();
		}
	}

	public static function GetLoggedInUser(): ?User{
		$sessionId = HttpInput::Str(COOKIE, 'sessionid');

		if($sessionId !== null){
			$result = Db::Query('
						SELECT u.*
						from Users u
						inner join Sessions s using (UserId)
						where s.SessionId = ?
					', [$sessionId], User::class);

			if(sizeof($result) > 0){
				self::SetSessionCookie($sessionId);
				return $result[0];
			}
		}

		return null;
	}

	public static function SetSessionCookie(string $sessionId): void{
		/** @throws void */
		setcookie('sessionid', $sessionId, ['expires' => intval((new DateTimeImmutable('+1 week'))->format('U')), 'path' => '/', 'domain' => SITE_DOMAIN, 'secure' => true, 'httponly' => false, 'samesite' => 'Lax']); // Expires in two weeks
	}

	/**
	 * @throws Exceptions\SessionNotFoundException
	 */
	public static function Get(?string $sessionId): Session{
		if($sessionId === null){
			throw new Exceptions\SessionNotFoundException();
		}

		$result = Db::Query('
					SELECT *
					from Sessions
					where SessionId = ?
				', [$sessionId], Session::class);

		return $result[0] ?? throw new Exceptions\SessionNotFoundException();
	}
}
