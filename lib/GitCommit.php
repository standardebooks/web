<?
use Safe\DateTimeImmutable;

class GitCommit{
	public int $EbookId;
	public DateTimeImmutable $Created;
	public string $Message;
	public string $Hash;


	// ***********
	// ORM METHODS
	// ***********

	/**
	 * @throws Exceptions\InvalidGitCommitException
	 */
	public static function FromLogLine(string $logLine): GitCommit{
		$instance = new GitCommit();
		list($unixTimestamp, $hash, $message) = explode(' ', $logLine, 3);

		try{
			$instance->Created = new DateTimeImmutable('@' . $unixTimestamp);
		}
		catch(\Exception){
			throw new Exceptions\InvalidGitCommitException('Invalid timestamp for Git commit.');
		}

		$instance->Message = $message;
		$instance->Hash = $hash;
		return $instance;
	}


	// *******
	// METHODS
	// *******

	/**
	 * @throws Exceptions\InvalidGitCommitException
	 */
	public function Validate(): void{
		$error = new Exceptions\InvalidGitCommitException();

		if(!isset($this->EbookId)){
			$error->Add(new Exceptions\GitCommitEbookIdRequiredException());
		}

		if(isset($this->Created)){
			if($this->Created > NOW){
				$error->Add(new Exceptions\InvalidGitCommitCreatedDatetimeException($this->Created));
			}
		}
		else{
			$error->Add(new Exceptions\GitCommitCreatedDatetimeRequiredException());
		}

		if(isset($this->Message)){
			$this->Message = trim($this->Message);

			if($this->Message == ''){
				$error->Add(new Exceptions\GitCommitMessageRequiredException());
			}
		}
		else{
			$error->Add(new Exceptions\GitCommitMessageRequiredException());
		}

		if(isset($this->Hash)){
			$this->Hash = trim($this->Hash);

			if($this->Hash == ''){
				$error->Add(new Exceptions\GitCommitHashRequiredException());
			}
		}
		else{
			$error->Add(new Exceptions\GitCommitHashRequiredException());
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * @throws Exceptions\InvalidGitCommitException
	 */
	public function Create(): void{
		$this->Validate();
		Db::Query('
			INSERT into GitCommits (EbookId, Created, Message, Hash)
			values (?,
				?,
				?,
				?)
		', [$this->EbookId, $this->Created, $this->Message, $this->Hash]);
	}
}
