<?
use function Safe\preg_match;
use Safe\DateTimeImmutable;

/**
 * @property Ebook $Ebook
 * @property User $ManagerUser
 * @property User $ReviewerUser
 * @property string $Url
 */
class Project{
	use Traits\Accessor;
	use Traits\PropertyFromHttp;

	public int $ProjectId;
	public int $EbookId;
	public Enums\ProjectStatusType $Status = Enums\ProjectStatusType::InProgress;
	public string $ProducerName;
	public ?string $ProducerEmail = null;
	public ?string $DiscussionUrl = null;
	public string $VcsUrl;
	public DateTimeImmutable $Created;
	public DateTimeImmutable $Updated;
	public DateTimeImmutable $Started;
	public ?DateTimeImmutable $Ended = null;
	public int $ManagerUserId;
	public int $ReviewerUserId;
	public ?DateTimeImmutable $LastCommitTimestamp = null;

	protected Ebook $_Ebook;
	protected User $_ManagerUser;
	protected User $_ReviewerUser;
	protected string $_Url;


	// *******
	// GETTERS
	// *******

	protected function GetUrl(): string{
		if(!isset($this->_Url)){
			$this->_Url = '/projects/' . $this->ProjectId;
		}

		return $this->_Url;
	}


	// *******
	// METHODS
	// *******

	/**
	 * @throws Exceptions\InvalidProjectException If the `Project` is invalid.
	 */
	public function Validate(): void{
		$error = new Exceptions\InvalidProjectException();

		if(!isset($this->EbookId)){
			$error->Add(new Exceptions\EbookRequiredException());
		}

		$this->ProducerEmail = trim($this->ProducerEmail ?? '');
		if($this->ProducerEmail == ''){
			$this->ProducerEmail = null;
		}

		// If we have an email address, try to see if we have a matching `User` in the database that we can pull the name from.
		if($this->ProducerEmail !== null){
			try{
				$user = User::GetByEmail($this->ProducerEmail);
				if($user->Name !== null){
					$this->ProducerName = $user->Name;
				}
			}
			catch(Exceptions\UserNotFoundException){
				// Pass.
			}
		}

		$this->ProducerName = trim($this->ProducerName ?? '');
		if($this->ProducerName == ''){
			$error->Add(new Exceptions\ProducerNameRequiredException());
		}

		$this->DiscussionUrl = trim($this->DiscussionUrl ?? '');
		if($this->DiscussionUrl == ''){
			$this->DiscussionUrl = null;
		}

		$this->VcsUrl = rtrim(trim($this->VcsUrl ?? ''), '/');
		if($this->VcsUrl == ''){
			$error->Add(new Exceptions\VcsUrlRequiredException());
		}
		elseif(!preg_match('|^https://github.com/[^/]+/[^/]+|ius', $this->VcsUrl)){
			$error->Add(new Exceptions\InvalidVcsUrlException());
		}

		if(!isset($this->ManagerUserId)){
			$error->Add(new Exceptions\ManagerRequiredException());
		}
		else{
			try{
				$this->_ManagerUser = User::Get($this->ManagerUserId);
			}
			catch(Exceptions\UserNotFoundException){
				$error->Add(new Exceptions\UserNotFoundException('Manager user not found.'));
			}
		}

		if(!isset($this->ReviewerUserId)){
			$error->Add(new Exceptions\ManagerRequiredException());
		}
		else{
			try{
				$this->_ReviewerUser = User::Get($this->ReviewerUserId);
			}
			catch(Exceptions\UserNotFoundException){
				$error->Add(new Exceptions\UserNotFoundException('Reviewer user not found.'));
			}
		}

		if(!isset($this->Started)){
			$error->Add(new Exceptions\StartedTimestampRequiredException());
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * @throws Exceptions\InvalidProjectException If the `Project` is invalid.
	 * @throws Exceptions\EbookIsNotAPlaceholderException If the `Project`'s `Ebook` is not a placeholder.
	 * @throws Exceptions\ProjectExistsException If the `Project`'s `Ebook` already has an active `Project`.
	 */
	public function Create(): void{
		$this->Validate();

		// Is this ebook already released?
		if(!$this->Ebook->IsPlaceholder()){
			throw new Exceptions\EbookIsNotAPlaceholderException();
		}

		// Does this `Ebook` already has an active `Project`?
		if($this->Ebook->ProjectInProgress !== null){
			throw new Exceptions\ProjectExistsException();
		}

		Db::Query('
				INSERT into Projects
				(
					EbookId,
					Status,
					ProducerName,
					ProducerEmail,
					DiscussionUrl,
					VcsUrl,
					Created,
					Updated,
					Started,
					Ended,
					ManagerUserId,
					ReviewerUserId,
					LastCommitTimestamp
				)
				values
				(
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?
				)
			', [$this->EbookId, $this->Status, $this->ProducerName, $this->ProducerEmail, $this->DiscussionUrl, $this->VcsUrl, NOW, NOW, $this->Started, $this->Ended, $this->ManagerUserId, $this->ReviewerUserId, $this->LastCommitTimestamp]);

		$this->ProjectId = Db::GetLastInsertedId();
	}

	/**
	 * @throws Exceptions\InvalidProjectException If the `Project` is invalid.
	 */
	public function Save(): void{
		$this->Validate();

		Db::Query('
			UPDATE
			Projects
			set
			Status = ?,
			ProducerName = ?,
			ProducerEmail = ?,
			DiscussionUrl = ?,
			VcsUrl = ?,
			Started = ?,
			Ended = ?,
			ManagerUserId = ?,
			ReviewerUserId = ?,
			LastCommitTimestamp = ?
			where
			ProjectId = ?
		', [$this->Status, $this->ProducerName, $this->ProducerEmail, $this->DiscussionUrl, $this->VcsUrl, $this->Started, $this->Ended, $this->ManagerUserId, $this->ReviewerUserId, $this->LastCommitTimestamp, $this->ProjectId]);

		if($this->Status == Enums\ProjectStatusType::Abandoned){
			Db::Query('
				UPDATE
				EbookPlaceholders
				set
				IsInProgress = false
				where
				EbookId = ?
			', [$this->EbookId]);
		}
	}

	public function FillFromHttpPost(): void{
		$this->PropertyFromHttp('ProducerName');
		$this->PropertyFromHttp('ProducerEmail');
		$this->PropertyFromHttp('DiscussionUrl');
		$this->PropertyFromHttp('Status');
		$this->PropertyFromHttp('VcsUrl');
		$this->PropertyFromHttp('Started');
		$this->PropertyFromHttp('Ended');
		$this->PropertyFromHttp('ManagerUserId');
		$this->PropertyFromHttp('ReviewerUserId');
	}


	// ***********
	// ORM METHODS
	// ***********

	/**
	 * @throws Exceptions\ProjectNotFoundException If the `Project` can't be found.
	 */
	public static function Get(?int $projectId): Project{
		if($projectId === null){
			throw new Exceptions\ProjectNotFoundException();
		}

		return Db::Query('SELECT * from Projects where ProjectId = ?', [$projectId], Project::class)[0] ?? throw new Exceptions\ProjectNotFoundException();
	}

	/**
	 * @return array<Project>
	 */
	public static function GetAllByStatus(Enums\ProjectStatusType $status): array{
		return Db::Query('SELECT * from Projects where Status = ? order by Started desc', [$status], Project::class);
	}
}
