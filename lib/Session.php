<?

use Exceptions\InvalidLoginException;
use Ramsey\Uuid\Uuid;
use Safe\DateTime;
use function Safe\strtotime;

/**
 * @property User $User
 * @property string $Url
 */
class Session extends Accessor{
	public int $UserId;
	protected ?User $_User = null;
	public DateTime $Created;
	public string $SessionId;
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

	public function Create(?string $email = null, ?string $password = null): void{
		try{
			$this->User = User::GetIfRegistered($email, $password);
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

			self::SetSessionCookie($this->SessionId);
		}
		catch(Exceptions\UserNotFoundException){
			throw new InvalidLoginException();
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
					', [$sessionId], 'User');

			if(sizeof($result) > 0){
				self::SetSessionCookie($sessionId);
				return $result[0];
			}
		}

		return null;
	}

	public static function SetSessionCookie(string $sessionId): void{
		setcookie('sessionid', $sessionId, ['expires' => strtotime('+1 week'), 'path' => '/', 'domain' => SITE_DOMAIN, 'secure' => true, 'httponly' => false, 'samesite' => 'Lax']); // Expires in two weeks
	}

	public static function Get(?string $sessionId): Session{
		if($sessionId === null){
			throw new Exceptions\SessionNotFoundException();
		}

		$result = Db::Query('
					SELECT *
					from Sessions
					where SessionId = ?
				', [$sessionId], 'Session');

		if(sizeof($result) == 0){
			throw new Exceptions\SessionNotFoundException();
		}

		return $result[0];
	}
}
