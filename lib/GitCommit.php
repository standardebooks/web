<?
use Safe\DateTimeImmutable;

class GitCommit{
	public $Timestamp;
	public $Message;
	public $Hash;

	public function __construct(string $unixTimestamp, string $hash, string $message){
		$this->Timestamp = new DateTimeImmutable('@' . $unixTimestamp);
		$this->Message = $message;
		$this->Hash = $hash;
	}
}
