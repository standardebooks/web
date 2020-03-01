<?
use Safe\DateTimeImmutable;

class GitCommit{
	public $Timestamp;
	public $Message;

	public function __construct(string $unixTimestamp, string $message){
		$this->Timestamp = new DateTimeImmutable('@' . $unixTimestamp);
		$this->Message = $message;
	}
}
