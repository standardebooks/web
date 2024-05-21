<?
use Safe\DateTimeImmutable;

class GitCommit{
	public DateTimeImmutable $Created;
	public string $Message;
	public string $Hash;

	/**
	 * @throws Exceptions\InvalidGitCommitException
	 */
	public static function FromLog(string $unixTimestamp, string $hash, string $message): GitCommit{
		$instance = new GitCommit();
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
}
