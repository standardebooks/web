<?
use Safe\DateTimeImmutable;

class GitCommit{
	public $Created;
	public $Message;
	public $Hash;

	public function __construct(string $unixTimestamp, string $hash, string $message){
		$this->Created = new DateTimeImmutable('@' . $unixTimestamp);
		$this->Message = $message;
		$this->Hash = $hash;
	}
}
