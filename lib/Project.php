<?
use function Safe\curl_exec;
use function Safe\curl_getinfo;
use function Safe\curl_init;
use function Safe\curl_setopt;
use function Safe\json_decode;
use function Safe\preg_match;
use function Safe\preg_replace;

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

		try{
			$this->FetchLatestCommit();
		}
		catch(Exceptions\AppException){
			// Pass; it's OK if this fails during creation.
		}

		// Don't let the started date be later than the first commit date. This can happen if the producer starts to commit before their project is approved on the mailing list.
		if($this->LastCommitTimestamp !== null && $this->LastCommitTimestamp > $this->Started){
			$this->Started = $this->LastCommitTimestamp;
		}

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

	/**
	 * @throws Exceptions\AppException If the operation faile.d
	 */
	public function FetchLatestCommit(?string $apiKey = null): void{
		$headers = [
					'Accept: application/vnd.github+json',
					'X-GitHub-Api-Version: 2022-11-28',
					'User-Agent: Standard Ebooks' // Required by GitHub.
				];

		if($apiKey !== null){
			$headers[] = 'Authorization: Bearer ' . $apiKey;
		}

		// First, we check if the repo has been renamed. If so, update the repo now.
		$curl = curl_init($this->VcsUrl);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, Enums\HttpMethod::Head->value); // Only perform HTTP HEAD.
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_exec($curl);

		/** @var string $finalUrl */
		$finalUrl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
		// Were we redirected?
		if($finalUrl != $this->VcsUrl){
			$this->VcsUrl = $finalUrl;
		}

		// Now check the actual commits.
		$url = preg_replace('|^https://github.com/|iu', 'https://api.github.com/repos/', $this->VcsUrl . '/commits');

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		try{
			$response = curl_exec($curl);
			/** @var int $httpCode */
			$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			if(!is_string($response)){
				throw new Exceptions\AppException('Response from GitHub was not a string: ' . $response);
			}

			if($httpCode != Enums\HttpCode::Ok->value){
				throw new Exception('HTTP code from GitHub was: ' . $httpCode);
			}

			/** @var array<stdClass> $commits */
			$commits = json_decode($response);

			if(sizeof($commits) > 0){
				$this->LastCommitTimestamp = new DateTimeImmutable($commits[0]->commit->committer->date);
			}
		}
		catch(Exception $ex){
			throw new Exceptions\AppException('Error in update-project-commits for URL <' . $url . '>: ' . $ex->getMessage(), 0, $ex);
		}
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