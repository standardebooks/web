<?
use Safe\DateTimeImmutable;

class GitCommit{
	public DateTimeImmutable $Created;
	public string $Message;
	public string $Hash;

	public function __construct(string $unixTimestamp, string $hash, string $message){
		$this->Created = new DateTimeImmutable('@' . $unixTimestamp);
		$this->Message = $message;
		$this->Hash = $hash;
	}
}
