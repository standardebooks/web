<?
use Safe\DateTimeImmutable;

class GitCommit{
	public DateTimeImmutable $Created;
	public string $Message;
	public string $Hash;

	public static function FromLog(string $unixTimestamp, string $hash, string $message): GitCommit{
		$instance = new GitCommit();
		$instance->Created = new DateTimeImmutable('@' . $unixTimestamp);
		$instance->Message = $message;
		$instance->Hash = $hash;
		return $instance;
	}
}
