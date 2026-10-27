<?
use Safe\DateTimeImmutable;

final class SessionLoginCode{
	use Traits\FromRow;

	public string $Code;
	public int $UserId;
	public DateTimeImmutable $CreatedAt;
	public DateTimeImmutable $ExpiresAt;
	public ?DateTimeImmutable $EnteredAt = null;
	public ?string $IpAddress = null;

	public User $User{
		/** @throws Exceptions\UserNotFoundException If this `SessionLoginCode`'s `User` can't be found. */
		get{
			return $this->User ??= User::Get($this->UserId);
		}
	}

	public function __construct(?User $user = null){
		if($user !== null){
			$this->UserId = $user->UserId;
			$this->User = $user;
		}
	}

	/**
	 * Create and queue a session login code for this user.
	 *
	 * @throws Exceptions\UserNotFoundException If the `SessionLoginCode`'s `User` can't be found.
	 * @throws Exceptions\EmailRequiredException If the user has no email address.
	 * @throws Exceptions\SessionLoginCodeRequestLimitException If the remote address is missing, the user or IP address has requested too many session login codes recently, or another request from this IP address is still being processed.
	 */
	public function Create(string $redirect): void{
		if(Http::$Request->RemoteAddress === null){
			throw new Exceptions\SessionLoginCodeRequestLimitException();
		}

		$emailAddress = $this->User->Email;
		if($emailAddress === null){
			throw new Exceptions\EmailRequiredException();
		}

		$ipAddress = (string)Http::$Request->RemoteAddress;
		$lockName = 'session-login-code:' . $ipAddress;

		// Serialize requests from the same IP address, including requests for different accounts.
		if(!Db::QueryBool('select get_lock(?, 5)', [$lockName])){
			throw new Exceptions\SessionLoginCodeRequestLimitException();
		}

		try{
			Db::Transaction(function() use ($ipAddress, $redirect, $emailAddress): void{
				// Lock the account row so simultaneous requests cannot exceed the email limit.
				Db::Query('select UserId from Users where UserId = ? for update', [$this->UserId]);
				$recentCount = Db::QueryInt('select count(*) from SessionLoginCodes where UserId = ? and CreatedAt > ?', [$this->UserId, NOW->modify('-24 hours')]);
				$recentlySent = Db::QueryBool('select exists(select * from SessionLoginCodes where UserId = ? and CreatedAt > ?)', [$this->UserId, NOW->modify('-15 minutes')]);
				$recentIpCount = Db::QueryInt('select count(*) from SessionLoginCodes where IpAddress = ? and CreatedAt > ?', [$ipAddress, NOW->modify('-24 hours')]);
				$recentlySentFromIp = Db::QueryBool('select exists(select * from SessionLoginCodes where IpAddress = ? and CreatedAt > ?)', [$ipAddress, NOW->modify('-15 minutes')]);

				if($recentCount >= 3 || $recentlySent || $recentIpCount >= 3 || $recentlySentFromIp){
					throw new Exceptions\SessionLoginCodeRequestLimitException();
				}

				while(true){
					try{
						$this->Code = Db::Query('insert into SessionLoginCodes (Code, UserId, ExpiresAt, IpAddress) values (uuid_v7(), ?, ?, ?) returning Code', [$this->UserId, NOW->modify('+10 minutes'), $ipAddress])[0]->Code;
						break;
					}
					catch(Exceptions\DuplicateDatabaseKeyException){
						// A generated UUID collided, so ask the database to generate another one.
					}
				}

				$email = new QueuedEmailMessage(true);
				$email->To = $emailAddress;
				$email->ToName = $this->User->Name;
				$email->Subject = 'Log in to Standard Ebooks';
				$url = $this->GetUrl($redirect);
				$email->BodyHtml = Template::EmailSessionLoginCode(url: $url);
				$email->BodyText = Template::EmailSessionLoginCodeText(url: $url);
				$email->Create();
			});
		}
		finally{
			Db::Query('select release_lock(?)', [$lockName]);
		}
	}

	/**
	 * Get the URL with which to complete login.
	 *
	 * @throws Exceptions\UserNotFoundException If the `User` can't be found.
	 */
	public function GetUrl(?string $redirect = null): string{
		$url = SITE_URL . $this->User->Url . '/session-login-codes/' . $this->Code . '?_method=PATCH';

		$redirect = trim($redirect ?? '');
		if($redirect != ''){
			$url .= '&redirect=' . urlencode(Template::SanitizeRedirectUrl($redirect));
		}

		return $url;
	}

	/**
	 * Return a session login code belonging to the given user.
	 *
	 * @throws Exceptions\SessionLoginCodeNotFoundException If the code is invalid or does not belong to the user.
	 */
	public static function Get(?string $code, int $userId): SessionLoginCode{
		if($code === null){
			throw new Exceptions\SessionLoginCodeNotFoundException();
		}

		return Db::Query('select * from SessionLoginCodes where Code = ? and UserId = ?', [strtolower($code), $userId], SessionLoginCode::class)[0] ?? throw new Exceptions\SessionLoginCodeNotFoundException();
	}
}
