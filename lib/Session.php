<?
use Safe\DateTimeImmutable;

class Session{
	public static ?User $User = null;

	public int $UserId;
	public DateTimeImmutable $CreatedAt;
	public string $SessionId;
	public static string $Url;


	// *******
	// METHODS
	// *******

	/**
	 * Create a new login session for a user who has verified an email code.
	 */
	public function Create(User $user): void{
		$this->UserId = $user->UserId;
		$this->CreatedAt = NOW;
		while(true){
			try{
				$this->SessionId = Db::Query('insert into Sessions (UserId, SessionId, CreatedAt) values (?, uuid_v7(), ?) returning SessionId', [$this->UserId, $this->CreatedAt])[0]->SessionId;
				break;
			}
			catch(Exceptions\DuplicateDatabaseKeyException){
				// A generated UUID collided, so ask the database to generate another one.
			}
		}
		self::SetSessionCookie($this->SessionId);
		self::$User = $user;
		self::$Url = '/sessions/' . $this->SessionId;
	}

	/**
	 * Delete a login session, if its ID is present, and clear the session cookie.
	 */
	public static function Delete(?string $sessionId): void{
		if($sessionId !== null){
			Db::Query('delete from Sessions where SessionId = ?', [$sessionId]);
		}

		setcookie('sessionid', '', ['expires' => 1, 'path' => '/', 'domain' => SITE_DOMAIN, 'secure' => true, 'httponly' => true, 'samesite' => 'Lax']);
		self::$User = null;
	}

	private static function SetSessionCookie(string $sessionId): void{
		/** @throws void */
		setcookie('sessionid', $sessionId, ['expires' => intval((new DateTimeImmutable('+3 month'))->format(Enums\DateTimeFormat::UnixTimestamp->value)), 'path' => '/', 'domain' => SITE_DOMAIN, 'secure' => true, 'httponly' => true, 'samesite' => 'Lax']); // Expires in two weeks.
	}


	// ***********
	// ORM METHODS
	// ***********

	/**
	 * @throws Exceptions\SessionNotFoundException
	 */
	public static function Get(?string $sessionId): Session{
		if($sessionId === null){
			throw new Exceptions\SessionNotFoundException();
		}

		$result = Db::Query('
					select *
					from Sessions
					where SessionId = ?
				', [$sessionId], Session::class);

		return $result[0] ?? throw new Exceptions\SessionNotFoundException();
	}

	/**
	 * Consume a `SessionLoginCodee` and initialize a `Session` for its `User`.
	 *
	 * @throws Exceptions\SessionLoginCodeNotFoundException If the code has expired or has already been used.
	 * @throws Exceptions\UserNotFoundException If the code's user cannot be found.
	 */
	public static function InitializeFromCode(SessionLoginCode $code): void{
		Db::Transaction(function() use ($code): void{
			Db::Query('update SessionLoginCodes set EnteredAt = ? where Code = ? and UserId = ? and EnteredAt is null and ExpiresAt > ?', [NOW, $code->Code, $code->UserId, NOW]);

			if(Db::$LastQueryAffectedRowCount != 1){
				throw new Exceptions\SessionLoginCodeNotFoundException();
			}

			// Session creation sets a cookie and global state that cannot be rolled back.
			Db::SetTransactionRetriesEnabled(false);
			$session = new Session();
			$session->Create($code->User);
		});

		$code->EnteredAt = NOW;
	}

	public static function InitializeFromCookie(): void{
		$sessionId = Http::$Request->Cookies->Get('sessionid');

		if($sessionId !== null){
			$result = Db::Query('
						select u.*
						from Users u
						inner join Sessions s using (UserId)
						where s.SessionId = ?
					', [$sessionId], User::class);

			if(sizeof($result) > 0){
				self::SetSessionCookie($sessionId);
				self::$User = $result[0];
				self::$Url = '/sessions/' . $sessionId;
			}
		}
	}
}
