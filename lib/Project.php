<?
use function Safe\parse_url;
use function Safe\get_cfg_var;
use function Safe\preg_match;
use function Safe\preg_match_all;
use function Safe\preg_replace;

use Enums\ProjectStatusType;
use Safe\DateTimeImmutable;

/**
 * @property Ebook $Ebook
 * @property User $Producer
 * @property User $Manager
 * @property User $Reviewer
 * @property-read string $Url
 * @property-read string $EditUrl
 * @property DateTimeImmutable $LastActivityTimestamp The timestamp of the latest activity, whether it's a commit, a discussion post, or simply the started timestamp.
 * @property array<ProjectReminder> $Reminders
 * @property-read ?string $VcsUrlDomain
 * @property-read ?string $DiscussionUrlDomain
 */
final class Project{
	use Traits\Accessor;
	use Traits\FromRow;
	use Traits\PropertyFromRequest;

	public int $ProjectId;
	public int $EbookId;
	public Enums\ProjectStatusType $Status = Enums\ProjectStatusType::InProgress;
	public int $ProducerUserId;
	public ?string $DiscussionUrl = null;
	public ?string $VcsUrl;
	public DateTimeImmutable $Created;
	public DateTimeImmutable $Updated;
	public DateTimeImmutable $Started;
	public ?DateTimeImmutable $Ended = null;
	public int $ManagerUserId;
	public int $ReviewerUserId;
	public ?DateTimeImmutable $LastCommitTimestamp = null;
	public ?DateTimeImmutable $LastDiscussionTimestamp = null;
	public bool $IsStatusAutomaticallyUpdated = true;
	public bool $HasReviewerBeenNotified = false;
	public bool $AreDiscussionMessagesComplete = false;

	protected Ebook $_Ebook;
	protected User $_Producer;
	protected User $_Manager;
	protected User $_Reviewer;
	protected string $_Url;
	protected string $_EditUrl;
	protected DateTimeImmutable $_LastActivityTimestamp;
	/** @var array<ProjectReminder> $_Reminders */
	protected array $_Reminders;
	protected ?string $_VcsUrlDomain;
	protected ?string $_DiscussionUrlDomain;

	/** Have we already checked that the `$VcsUrl` is current? */
	private bool $IsVcsUrlUpdated = false;


	// *******
	// GETTERS
	// *******

	protected function GetVcsUrlDomain(): ?string{
		if(!isset($this->_VcsUrlDomain)){
			if($this->VcsUrl === null){
				$this->_VcsUrlDomain = null;
			}
			else{
				try{
					$domain = parse_url($this->VcsUrl, PHP_URL_HOST);

					if(is_string($domain)){
						$this->_VcsUrlDomain = strtolower($domain);
					}
					else{
						$this->_VcsUrlDomain = null;
					}
				}
				catch(\Exception){
					$this->_VcsUrlDomain = null;
				}
			}
		}

		return $this->_VcsUrlDomain;
	}

	protected function GetDiscussionUrlDomain(): ?string{
		if(!isset($this->_DiscussionUrlDomain)){
			if($this->DiscussionUrl === null){
				$this->_DiscussionUrlDomain = null;
			}
			else{
				try{
					$domain = parse_url($this->DiscussionUrl, PHP_URL_HOST);

					if(is_string($domain)){
						$this->_DiscussionUrlDomain = strtolower($domain);
					}
					else{
						$this->_DiscussionUrlDomain = null;
					}
				}
				catch(\Exception){
					$this->_DiscussionUrlDomain = null;
				}
			}
		}

		return $this->_DiscussionUrlDomain;
	}

	protected function GetUrl(): string{
		return $this->_Url ??= '/projects/' . $this->ProjectId;
	}

	protected function GetEditUrl(): string{
		return $this->_EditUrl ??= $this->Url . '/edit';
	}

	protected function GetLastActivityTimestamp(): DateTimeImmutable{
		if(!isset($this->_LastActivityTimestamp)){
			$dates = [
				(int)($this->LastCommitTimestamp?->format(Enums\DateTimeFormat::UnixTimestamp->value) ?? 0) => $this->LastCommitTimestamp ?? NOW,
				(int)($this->LastDiscussionTimestamp?->format(Enums\DateTimeFormat::UnixTimestamp->value) ?? 0) => $this->LastDiscussionTimestamp ?? NOW,
				(int)($this->Started->format(Enums\DateTimeFormat::UnixTimestamp->value)) => $this->Started,
			];

			ksort($dates);

			$this->_LastActivityTimestamp = end($dates);
		}

		return $this->_LastActivityTimestamp;
	}

	/**
	 * @throws Exceptions\UserNotFoundException If the `User` can't be found.
	 */
	protected function GetProducer(): User{
		return $this->_Producer ??= User::Get($this->ProducerUserId);
	}

	/**
	 * @throws Exceptions\UserNotFoundException If the `User` can't be found.
	 */
	protected function GetManager(): User{
		return $this->_Manager ??= User::Get($this->ManagerUserId);
	}

	/**
	 * @throws Exceptions\UserNotFoundException If the `User` can't be found.
	 */
	protected function GetReviewer(): User{
		return $this->_Reviewer ??= User::Get($this->ReviewerUserId);
	}

	/**
	 * @return array<ProjectReminder>
	 */
	protected function GetReminders(): array{
		return $this->_Reminders ??= Db::Query('SELECT * from ProjectReminders where ProjectId = ? order by Created asc', [$this->ProjectId], ProjectReminder::class);
	}


	// *******
	// METHODS
	// *******

	/**
	 * Validate this `Project`, and also create the producer `User` if they are not yet a registered `User`.
	 *
	 * @throws Exceptions\ProjectInvalidException If the `Project` is invalid.
	 */
	public function Validate(bool $allowUnsetEbookId = false, bool $allowUnsetRoles = false): void{
		$error = new Exceptions\ProjectInvalidException();

		if(!$allowUnsetEbookId && !isset($this->EbookId)){
			$error->Add(new Exceptions\EbookRequiredException());
		}

		// Validate the producer.
		if(!isset($this->Producer->Uuid)){
			$this->Producer->GenerateUuid();
		}

		try{
			$this->Producer->Validate(requireEmail: false);
		}
		catch(Exceptions\UserInvalidException $ex){
			$error->Add($ex);
		}

		$createProducer = false;
		if($this->Producer->Email !== null){
			try{
				$this->Producer = User::GetByEmail($this->Producer->Email);
				$this->ProducerUserId = $this->Producer->UserId;
			}
			catch(Exceptions\UserNotFoundException){
				$createProducer = true;
			}
		}
		else{
			$users = User::GetAllByName($this->Producer->Name);

			if(sizeof($users) == 1){
				$this->Producer = $users[0];
				$this->ProducerUserId = $this->Producer->UserId;
			}
			elseif(sizeof($users) > 1){
				$error->Add(new Exceptions\AmbiguousUserException($users));
			}
			else{
				$createProducer = true;
			}
		}

		$this->DiscussionUrl = trim($this->DiscussionUrl ?? '');
		if($this->DiscussionUrl == ''){
			$this->DiscussionUrl = null;
		}
		elseif(preg_match('|^https://groups\.google\.com/|iu', $this->DiscussionUrl)){
			// Strip any query strings.
			$this->DiscussionUrl = preg_replace('/\?.+$/iu', '', $this->DiscussionUrl);

			// Strip stray periods from the URL that may have been copied/pasted in.
			$this->DiscussionUrl = trim($this->DiscussionUrl, '.');
		}

		$this->VcsUrl = trim($this->VcsUrl ?? '');
		if($this->VcsUrl == ''){
			$this->VcsUrl = null;
		}
		elseif(preg_match('|^https?://(www\.)?github\.com/|ius', $this->VcsUrl)){
			$this->VcsUrl = rtrim($this->VcsUrl, '/');
			// Remove query strings.
			$this->VcsUrl = preg_replace('/\?[^\?]+$/iu', '', $this->VcsUrl);
			if(!preg_match('|^https://github\.com/[^/]+/[^/]+$|ius', $this->VcsUrl)){
				$error->Add(new Exceptions\VcsUrlInvalidException($this->VcsUrl));
			}
		}

		if(!$allowUnsetRoles && !isset($this->ManagerUserId)){
			$error->Add(new Exceptions\ManagerRequiredException());
		}
		elseif(isset($this->ManagerUserId)){
			try{
				$this->_Manager = User::Get($this->ManagerUserId);
			}
			catch(Exceptions\UserNotFoundException){
				$error->Add(new Exceptions\UserNotFoundException('Manager user not found.'));
			}
		}

		if(!$allowUnsetRoles && !isset($this->ReviewerUserId)){
			$error->Add(new Exceptions\ReviewerRequiredException());
		}
		elseif(isset($this->ReviewerUserId)){
			try{
				$this->_Reviewer = User::Get($this->ReviewerUserId);
			}
			catch(Exceptions\UserNotFoundException){
				$error->Add(new Exceptions\UserNotFoundException('Reviewer user not found.'));
			}
		}

		if(!isset($this->Started)){
			$this->Started = NOW;
		}

		if($createProducer){
			try{
				$this->Producer->Create(null, false);
				$this->ProducerUserId = $this->Producer->UserId;
			}
			catch(Exceptions\UserExistsException){
				// Pass.
			}
			catch(Exceptions\UserInvalidException $ex){
				$error->Add($ex);
			}
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * Creates a new `Project`. If `Project::$Manager` or `Project::$Reviewer` are unassigned, they will be automatically assigned.
	 *
	 * @throws Exceptions\ProjectInvalidException If the `Project` is invalid.
	 * @throws Exceptions\EbookIsNotAPlaceholderException If the `Project`'s `Ebook` is not a placeholder.
	 * @throws Exceptions\ProjectExistsException If the `Project`'s `Ebook` already has an active `Project`.
	 * @throws Exceptions\UserNotFoundException If a manager or reviewer could not be auto-assigned.
	 */
	public function Create(): void{
		$this->Validate(false, true);

		if(!isset($this->ManagerUserId)){
			try{
				$this->Manager = User::GetByAvailableForProjectAssignment(Enums\ProjectRoleType::Manager, [$this->ProducerUserId]);
			}
			catch(Exceptions\UserNotFoundException){
				throw new Exceptions\UserNotFoundException('Could not auto-assign a suitable manager.');
			}

			$this->ManagerUserId = $this->Manager->UserId;
		}

		if(!isset($this->ReviewerUserId)){
			try{
				$this->Reviewer = User::GetByAvailableForProjectAssignment(Enums\ProjectRoleType::Reviewer, [$this->Manager->UserId, $this->ProducerUserId]);
			}
			catch(Exceptions\UserNotFoundException){
				unset($this->Manager);
				unset($this->ManagerUserId);
				throw new Exceptions\UserNotFoundException('Could not auto-assign a suitable reviewer.');
			}

			$this->ReviewerUserId = $this->Reviewer->UserId;
		}

		try{
			$this->FetchLastCommitTimestamp();
		}
		catch(Exceptions\AppException){
			// Pass; it's OK if this fails during creation.
		}

		// Don't let the started date be later than the first commit date. This can happen if the producer starts to commit before their project is approved on the mailing list.
		if($this->LastCommitTimestamp !== null && $this->Started > $this->LastCommitTimestamp){
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

		$this->ProjectId = Db::QueryInt('
				INSERT into Projects
				(
					EbookId,
					Status,
					ProducerUserId,
					DiscussionUrl,
					VcsUrl,
					Created,
					Updated,
					Started,
					Ended,
					ManagerUserId,
					ReviewerUserId,
					LastCommitTimestamp,
					LastDiscussionTimestamp,
					IsStatusAutomaticallyUpdated,
					HasReviewerBeenNotified,
					AreDiscussionMessagesComplete
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
					?,
					?,
					?,
					?
				)
				returning ProjectId
			', [$this->EbookId, $this->Status, $this->ProducerUserId, $this->DiscussionUrl, $this->VcsUrl, NOW, NOW, $this->Started, $this->Ended, $this->ManagerUserId, $this->ReviewerUserId, $this->LastCommitTimestamp, $this->LastDiscussionTimestamp, $this->IsStatusAutomaticallyUpdated, $this->HasReviewerBeenNotified, $this->AreDiscussionMessagesComplete]);

		$discussionMessageId = $this->GetRootDiscussionMessageId();
		if($discussionMessageId !== null){
			$this->SaveDiscussionMessageIds([$discussionMessageId]);
		}

		// Notify the manager and reviewer.
		if($this->Status == Enums\ProjectStatusType::InProgress){
			// The manager is also the reviewer, just send one email.
			if($this->ManagerUserId == $this->ReviewerUserId){
				if($this->Manager->Email !== null && $this->ManagerUserId != $this->ProducerUserId){
					$em = new QueuedEmailMessage();
					$em->From = ADMIN_EMAIL_ADDRESS;
					$em->To = $this->Manager->Email;
					$em->Subject = 'New ebook project to manage and review';
					$em->BodyHtml = Template::EmailManagerNewProject(project: $this, role: 'manage and review', user: $this->Manager);
					$em->BodyText = Template::EmailManagerNewProjectText(project: $this, role: 'manage and review', user: $this->Manager);
					$em->Send();
				}
			}
			else{
				// Notify the manager.
				if($this->Manager->Email !== null){
					$em = new QueuedEmailMessage();
					$em->From = ADMIN_EMAIL_ADDRESS;
					$em->To = $this->Manager->Email;
					$em->Subject = 'New ebook project to manage';
					$em->BodyHtml = Template::EmailManagerNewProject(project: $this, role: 'manage', user: $this->Manager);
					$em->BodyText = Template::EmailManagerNewProjectText(project: $this, role: 'manage', user: $this->Manager);
					$em->Send();
				}

				// Notify the reviewer.
				if($this->Reviewer->Email !== null){
					$em = new QueuedEmailMessage();
					$em->From = ADMIN_EMAIL_ADDRESS;
					$em->To = $this->Reviewer->Email;
					$em->Subject = 'New ebook project to review';
					$em->BodyHtml = Template::EmailManagerNewProject(project: $this, role: 'review', user: $this->Reviewer);
					$em->BodyText = Template::EmailManagerNewProjectText(project: $this, role: 'review', user: $this->Reviewer);
					$em->Send();
				}
			}
		}
	}

	/**
	 * @throws Exceptions\ProjectInvalidException If the `Project` is invalid.
	 */
	public function Save(): void{
		/** @var ?string $originalDiscussionUrl */
		$originalDiscussionUrl = Db::Query('SELECT DiscussionUrl from Projects where ProjectId = ?', [$this->ProjectId])[0]->DiscussionUrl ?? null;

		$this->Validate();
		$this->SendReviewerReadyNotification();

		if($originalDiscussionUrl !== $this->DiscussionUrl){
			$this->AreDiscussionMessagesComplete = false;
		}

		Db::Query('
			UPDATE
			Projects
			set
			Status = ?,
			ProducerUserId = ?,
			DiscussionUrl = ?,
			VcsUrl = ?,
			Started = ?,
			Ended = ?,
			ManagerUserId = ?,
			ReviewerUserId = ?,
			LastCommitTimestamp = ?,
			LastDiscussionTimestamp = ?,
			IsStatusAutomaticallyUpdated = ?,
			HasReviewerBeenNotified = ?,
			AreDiscussionMessagesComplete = ?
			where
			ProjectId = ?
		', [$this->Status, $this->ProducerUserId, $this->DiscussionUrl, $this->VcsUrl, $this->Started, $this->Ended, $this->ManagerUserId, $this->ReviewerUserId, $this->LastCommitTimestamp, $this->LastDiscussionTimestamp, $this->IsStatusAutomaticallyUpdated, $this->HasReviewerBeenNotified, $this->AreDiscussionMessagesComplete, $this->ProjectId]);

		Db::Query('
			UPDATE
			EbookPlaceholders
			set
			IsInProgress = ?
			where
			EbookId = ?
		', [$this->Status != Enums\ProjectStatusType::Abandoned, $this->EbookId]);

		$discussionMessageId = $this->GetRootDiscussionMessageId();
		if($discussionMessageId !== null){
			$this->SaveDiscussionMessageIds([$discussionMessageId]);
		}
	}

	/**
	 * Send a ready-for-review email to this `Project`'s reviewer if it has not already been sent.
	 */
	public function SendReviewerReadyNotification(): void{
		if(
			$this->Status != Enums\ProjectStatusType::AwaitingReview
			||
			$this->HasReviewerBeenNotified
			||
			$this->Reviewer->Email === null
		){
			return;
		}

		$em = new QueuedEmailMessage();
		$em->From = ADMIN_EMAIL_ADDRESS;
		$em->To = $this->Reviewer->Email;
		$em->Subject = 'Ebook project ready for review';
		$em->BodyHtml = Template::EmailReviewerProjectReady(project: $this, user: $this->Reviewer);
		$em->BodyText = Template::EmailReviewerProjectReadyText(project: $this, user: $this->Reviewer);
		$em->Send();

		$this->HasReviewerBeenNotified = true;
	}

	public function Delete(): void{
		Db::Query('
			DELETE
			from ProjectDiscussionMessages
			where ProjectId = ?
		', [$this->ProjectId]);

		Db::Query('
			DELETE
			from ProjectReminders
			where ProjectId = ?
		', [$this->ProjectId]);

		Db::Query('
			DELETE
			from Projects
			where ProjectId = ?
		', [$this->ProjectId]);
	}

	/**
	 * Extract this `Project`'s root discussion message ID from the `DiscussionUrl`.
	 */
	protected function GetRootDiscussionMessageId(): ?string{
		if($this->DiscussionUrl === null){
			return null;
		}

		if(preg_match('|^https://groups\.google\.com/d/msgid/standardebooks/([^/\?]+)$|iu', $this->DiscussionUrl, $matches)){
			$messageId = trim(urldecode($matches[1]), '<>');

			if(preg_match('/^[^<>\s@]+?@[^<>\s@]+$/iu', $messageId)){
				return $messageId;
			}
		}

		return null;
	}

	/**
	 * Save an array of discussion message IDs relating to this `Project`.
	 *
	 * @param array<string> $discussionMessageIds
	 */
	public function SaveDiscussionMessageIds(array $discussionMessageIds): void{
		if(sizeof($discussionMessageIds) == 0){
			return;
		}

		$parameters = [];

		foreach($discussionMessageIds as $discussionMessageId){
			$parameters[] = $this->ProjectId;
			$parameters[] = $discussionMessageId;
			$parameters[] = NOW;
		}

		Db::MultiInsert('
			INSERT ignore into ProjectDiscussionMessages
			(
				ProjectId,
				MessageId,
				Created
			)
			values (?, ?, ?)
		', $parameters);
	}

	/**
	 * Return the discussion message IDs already associated with this `Project`.
	 *
	 * @return array<string>
	 */
	public function GetDiscussionMessageIds(): array{
		/** @var array<object{MessageId: string}> $rows */
		$rows = Db::Query('
			SELECT
				MessageId
			from
				ProjectDiscussionMessages
			where
				ProjectId = ?
		', [$this->ProjectId]);

		return array_map(fn($row) => $row->MessageId, $rows);
	}

	/**
	 * Return discussion message IDs found in the IMAP mailing list archive.
	 *
	 * @return array<string>
	 *
	 * @throws \Exception If the IMAP account could not be queried.
	 */
	public function FetchDiscussionMessageIdsFromImap(?string $discussionMessageId = null): array{
		$discussionMessageId ??= $this->GetRootDiscussionMessageId();

		if($discussionMessageId === null){
			return [];
		}

		/** @var string $username */
		$username = get_cfg_var('se.secrets.zoho.mail.projects_username');
		/** @var string $password */
		$password = get_cfg_var('se.secrets.zoho.mail.projects_password');
		$mailbox = new PhpImap\Mailbox('{imappro.zoho.com:993/imap/ssl}Standard Ebooks Mailing List', $username, $password);
		$mailbox->setConnectionArgs(OP_READONLY);
		$mailbox->setExpungeOnDisconnect(false);

		$knownMessageIds = [$discussionMessageId];
		/** @var array<int, array<string>> $archivedEmailMessageIds */
		$archivedEmailMessageIds = [];
		$date = NOW->sub(new \DateInterval('P14D'))->format('d-M-Y');

		$emailIds = $mailbox->searchMailbox('SINCE ' . $date);

		if(sizeof($emailIds) == 0){
			return $knownMessageIds;
		}

		foreach($mailbox->getMailsInfo($emailIds) as $emailInfo){
			$messageIds = self::ExtractDiscussionMessageIdsFromHeaderString((string)($emailInfo->message_id ?? ''), (string)($emailInfo->references ?? ''), (string)($emailInfo->in_reply_to ?? ''));

			if(sizeof($messageIds) > 0){
				$archivedEmailMessageIds[] = $messageIds;
			}
		}

		$hasMatchedEmail = true;

		while($hasMatchedEmail){
			$hasMatchedEmail = false;

			foreach($archivedEmailMessageIds as $key => $messageIds){
				if(array_intersect($knownMessageIds, $messageIds) !== []){
					$knownMessageIds = array_values(array_unique(array_merge($knownMessageIds, $messageIds)));
					$hasMatchedEmail = true;
					unset($archivedEmailMessageIds[$key]);
				}
			}
		}

		return $knownMessageIds;
	}

	/**
	 * Extract all discussion message IDs present in header strings.
	 *
	 * @return array<string>
	 */
	public static function ExtractDiscussionMessageIdsFromHeaderString(string $messageId, string $references, string $inReplyTo): array{
		$headerString = trim($messageId . ' ' . $references . ' ' . $inReplyTo);
		preg_match_all('/<?([^<>\s@]+@[^<>\s@]+)>?/iu', $headerString, $matches);

		return array_values(array_unique($matches[1]));
	}

	public function FillFromRequestBody(): void{
		$this->PropertyFromRequest('EbookId');
		if(!isset($this->Producer)){
			$this->Producer = new User();
			$this->Producer->GenerateUuid();
		}
		$this->Producer->PropertyFromRequest('Name', Enums\HttpVariableSource::Body, 'project-producer-name');
		$producerEmail = Http::$Request->Body->Get('project-producer-email', 'empty-string');
		if($producerEmail !== null){
			$this->Producer->Email = $producerEmail;
		}
		$this->PropertyFromRequest('DiscussionUrl');
		$this->PropertyFromRequest('Status');
		$this->PropertyFromRequest('VcsUrl');
		$this->PropertyFromRequest('Started');
		$this->PropertyFromRequest('Ended');
		$this->PropertyFromRequest('ManagerUserId');
		$this->PropertyFromRequest('ReviewerUserId');
		$this->PropertyFromRequest('IsStatusAutomaticallyUpdated');
	}

	/**
	 * Update this `Project`'s `$Status` to `reviewed` if there is a GitHub issue containing the word `review`.
	 *
	 * @throws Exceptions\AppException If the operation failed.
	 */
	public function FetchReviewStatus(?string $apiKey = null): void{
		if($this->Status == Enums\ProjectStatusType::Reviewed){
			return;
		}

		if(!preg_match('|^https://github\.com/|iu', $this->VcsUrl ?? '')){
			return;
		}

		$this->UpdateVcsUrl();

		$url = preg_replace('|^https://github\.com/|iu', 'https://api.github.com/repos/', $this->VcsUrl . '/issues');

		try{
			$response = HttpRequest::Execute(Enums\HttpMethod::Get, $url, $this->GenerateGitHubApiRequestHeaders($apiKey));

			if(!$response->HttpCode->IsSuccess()){
				throw new Exceptions\AppException('Server responded with HTTP ' . $response->HttpCode->value . '.');
			}

			if(is_array($response->Json)){
				foreach($response->Json as $issue){
					if(preg_match('/\breview|issues/iu', $issue->title)){
						$this->Status = Enums\ProjectStatusType::Reviewed;
						return;
					}
				}
			}
			else{
				throw new Exceptions\AppException('Couldn\'t understand response: ' . vds($response->Body));
			}
		}
		catch(Exception $ex){
			throw new Exceptions\AppException(message: 'Error when fetching issues for URL <' . $url . '>: ' . $ex->getMessage(), previous: $ex);
		}
	}

	/**
	 * Generate an array of HTTP headers to use for authenticating to the GitHub API.
	 *
	 * @return array<string, string>
	 */
	private function GenerateGitHubApiRequestHeaders(?string $apiKey): array{
		$headers = [
					'Accept' => 'application/vnd.github+json',
					'X-GitHub-Api-Version' => '2022-11-28'
				];

		if($apiKey !== null){
			$headers['Authorization'] = 'Bearer ' . $apiKey;
		}

		return $headers;
	}

	/**
	 * Check if the `$VcsUrl` has been renamed since we stored it, and if it has, update it.
	 */
	private function UpdateVcsUrl(): void{
		if(
			$this->IsVcsUrlUpdated
			||
			$this->VcsUrl === null
			||
			!preg_match('|^https://github\.com/|iu', $this->VcsUrl)){
			return;
		}

		try{
			$response = HttpRequest::Execute(Enums\HttpMethod::Head, $this->VcsUrl);
			$this->VcsUrl = $response->FinalUrl;
			$this->IsVcsUrlUpdated = true;
		}
		catch(Exceptions\HttpRequestException){
			// Probably a temporary failure, just continue but don't mark the URL as having been updated.
		}
	}

	/**
	 * Update this object's `$LastCommitTimestamp` with data from its GitHub repo.
	 *
	 * @throws Exceptions\AppException If the operation failed.
	 */
	public function FetchLastCommitTimestamp(?string $apiKey = null): void{
		if(!preg_match('|^https://github\.com/|iu', $this->VcsUrl ?? '')){
			return;
		}

		$this->UpdateVcsUrl();

		$url = preg_replace('|^https://github\.com/|iu', 'https://api.github.com/repos/', $this->VcsUrl . '/commits');

		try{
			$response = HttpRequest::Execute(Enums\HttpMethod::Get, $url, $this->GenerateGitHubApiRequestHeaders($apiKey));

			// GitHub API returns `HTTP 409 Conflict` if the repository is empty.
			if($response->HttpCode == Enums\HttpCode::Conflict){
				return;
			}

			if(!$response->HttpCode->IsSuccess()){
				throw new Exceptions\AppException('Server responded with HTTP ' . $response->HttpCode->value . '.');
			}

			/** @var array<stdClass> $commits */
			$commits = $response->Json;

			if(sizeof($commits) > 0){
				$this->LastCommitTimestamp = new DateTimeImmutable($commits[0]->commit->committer->date);
			}
		}
		catch(Exception $ex){
			throw new Exceptions\AppException(message: 'Error when fetching commits for URL <' . $url . '>: ' . $ex->getMessage(), previous: $ex);
		}
	}

	public function GetReminder(Enums\ProjectReminderType $type): ?ProjectReminder{
		foreach($this->Reminders as $reminder){
			if($reminder->Type == $type){
				return $reminder;
			}
		}

		return null;
	}

	/**
	 * Send an email reminder to the producer notifying them about their project status, but only if they're not an editor.
	 */
	public function SendReminder(Enums\ProjectReminderType $type): void{
		if($this->Producer->Email === null || $this->GetReminder($type) !== null){
			return;
		}

		if($this->Producer->Benefits->IsEditor){
			return;
		}

		$reminder = new ProjectReminder();
		$reminder->ProjectId = $this->ProjectId;
		$reminder->Type = $type;
		$reminder->Create();

		$em = new QueuedEmailMessage();
		$em->From = EDITOR_IN_CHIEF_EMAIL_ADDRESS;
		$em->FromName = EDITOR_IN_CHIEF_NAME;
		$em->To = $this->Producer->Email;
		$em->Subject = 'Your Standard Ebooks ebook';

		switch($type){
			case Enums\ProjectReminderType::Stalled:
				$em->BodyHtml = Template::EmailProjectStalled();
				$em->BodyText = Template::EmailProjectStalledText();
				break;

			case Enums\ProjectReminderType::Abandoned:
				$em->BodyHtml = Template::EmailProjectAbandoned();
				$em->BodyText = Template::EmailProjectAbandonedText();
				break;
		}

		$em->Send();
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
	 * @throws Exceptions\ProjectNotFoundException If the `Project` can't be found.
	 */
	public static function GetByIdentifierAndIsActive(?string $identifier): Project{
		if($identifier === null){
			throw new Exceptions\ProjectNotFoundException();
		}

		return Db::Query('SELECT Projects.* from Ebooks inner join Projects using (EbookId) where Ebooks.Identifier = ? and Projects.Status in (?, ?, ?)', [$identifier, Enums\ProjectStatusType::InProgress, Enums\ProjectStatusType::AwaitingReview, Enums\ProjectStatusType::Reviewed], Project::class)[0] ?? throw new Exceptions\ProjectNotFoundException();
	}

	/**
	 * @return array<Project>
	 */
	public static function GetAllByStatus(Enums\ProjectStatusType $status): array{
		return Db::MultiTableSelect('SELECT * from Projects inner join Ebooks on Projects.EbookId = Ebooks.EbookId where Projects.Status = ? order by regexp_replace(Title, \'^(A|An|The)\\\s\', \'\') asc', [$status], Project::class);
	}

	/**
	 * @param array<Enums\ProjectStatusType> $statuses
	 *
	 * @return array<Project>
	 */
	public static function GetAllByStatuses(array $statuses): array{
		return Db::MultiTableSelect('SELECT * from Projects inner join Ebooks on Projects.EbookId = Ebooks.EbookId where Projects.Status in ' . Db::CreateSetSql($statuses) . ' order by regexp_replace(Title, \'^(A|An|The)\\\s\', \'\') asc', $statuses, Project::class);
	}

	/**
	 * @return array<Project>
	 */
	public static function GetAllByManagerUserId(int $userId): array{
		return Db::MultiTableSelect('SELECT * from Projects inner join Ebooks on Projects.EbookId = Ebooks.EbookId where ManagerUserId = ? and Status in (?, ?, ?, ?) order by regexp_replace(Title, \'^(A|An|The)\\\s\', \'\') asc', [$userId, Enums\ProjectStatusType::InProgress, Enums\ProjectStatusType::Stalled, Enums\ProjectStatusType::AwaitingReview, ProjectStatusType::Reviewed], Project::class);
	}

	/**
	 * @return array<Project>
	 */
	public static function GetAllByReviewerUserId(int $userId): array{
		return Db::MultiTableSelect('SELECT * from Projects inner join Ebooks on Projects.EbookId = Ebooks.EbookId where ReviewerUserId = ? and Status in (?, ?, ?, ?) order by regexp_replace(Title, \'^(A|An|The)\\\s\', \'\') asc', [$userId, Enums\ProjectStatusType::InProgress, Enums\ProjectStatusType::Stalled, Enums\ProjectStatusType::AwaitingReview, ProjectStatusType::Reviewed], Project::class);
	}

	/**
	 * @return array<Project>
	 */
	public static function GetAllByProducerUserId(int $userId): array{
		return Db::MultiTableSelect('SELECT * from Projects inner join Ebooks on Projects.EbookId = Ebooks.EbookId where ProducerUserId = ? order by Projects.Created desc', [$userId], Project::class);
	}

	/**
	 * Creates a `Project` from a multi table array containing a `Project` and an `Ebook`.
	 *
	 * @param array<string, stdClass> $row
	 */
	public static function FromMultiTableRow(array $row): Project{
		$object = Project::FromRow($row['Projects']);

		if($row['Projects']->EbookId !== null){
			$object->Ebook = Ebook::FromRow($row['Ebooks']);
		}

		return $object;
	}
}
