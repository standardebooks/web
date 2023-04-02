<?
use Ramsey\Uuid\Uuid;
use Safe\DateTime;

/**
 * @property User $User
 * @property string $Url
 */
class Session extends PropertiesBase{
	public $UserId;
	protected $_User = null;
	public $Created;
	public $SessionId;
	public $_Url;


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

	public function Create(?string $email = null): void{
		$this->User = User::GetIfRegistered($email);
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
			$this->Created = new DateTime();
			Db::Query('
					INSERT into Sessions (UserId, SessionId, Created)
					values (?,
					        ?,
					        ?)
				', [$this->UserId, $this->SessionId, $this->Created]);
		}

		$this->SetSessionCookie($this->SessionId);
	}

	public static function GetLoggedInUser(): ?User{
		$sessionId = HttpInput::Str(COOKIE, 'sessionid');

		if($sessionId !== null){
			$result = Db::Query('
						SELECT u.*
						from Users u
						inner join Sessions s using (UserId)
						where s.SessionId = ?
					', [$sessionId], 'User');

			if(sizeof($result) > 0){
				self::SetSessionCookie($sessionId);
				return $result[0];
			}
		}

		return null;
	}

	public static function SetSessionCookie(string $sessionId): void{
		setcookie('sessionid', $sessionId, time() + 60 * 60 * 24 * 14 * 1, '/', SITE_DOMAIN, true, false); // Expires in two weeks
	}

	public static function Get(?string $sessionId): Session{
		if($sessionId === null){
			throw new Exceptions\InvalidSessionException();
		}

		$result = Db::Query('
					SELECT *
					from Sessions
					where SessionId = ?
				', [$sessionId], 'Session');

		if(sizeof($result) == 0){
			throw new Exceptions\InvalidSessionException();
		}

		return $result[0];
	}
}
