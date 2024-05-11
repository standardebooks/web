<?
use Safe\DateTimeImmutable;

class GitCommit{
	public DateTimeImmutable $Created;
	public string $Message;
	public string $Hash;

	/**
	 * @throws Exceptions\InvalidGitCommitException
	 */
	public function __construct(string $unixTimestamp, string $hash, string $message){
		try{
			$this->Created = new DateTimeImmutable('@' . $unixTimestamp);
		}
		catch(\Exception){
			throw new Exceptions\InvalidGitCommitException('Invalid timestamp for Git commit.');
		}
		$this->Message = $message;
		$this->Hash = $hash;
	}
}
