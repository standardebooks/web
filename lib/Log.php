<?
use function Safe\fopen;
use function Safe\fwrite;
use function Safe\fclose;
use function Safe\error_log;

class Log{
	private string $RequestId;
	private ?string $LogFilePath = null;
	/** @var array<string> $_Messages */
	private array $_Messages = [];

	/**
	 * @param ?string $logFilePath The path of the log file to write to, or `null` to write to PHP's default error log.
	 */
	public function __construct(?string $logFilePath = null){
		// Get a semi-random ID to identify this request within the log.
		$this->RequestId = substr(sha1(time() . rand()), 0, 8);
		$this->LogFilePath = $logFilePath;
	}


	// *******
	// METHODS
	// *******

	/**
	 * Write a message to disk, prepended with a timestamp and semi-random request ID.
	 */
	public function Write(string $text): void{
		$this->WriteToFile(NOW->format('Y-m-d H:i:s') . "\t" . $this->RequestId . "\t" . $text);
	}

	/**
	 * Write a message to disk.
	 */
	private function WriteToFile(string $text): void{
		$text = trim($text) . "\n";

		if($this->LogFilePath === null){
			error_log($text);
		}
		else{
			try{
				$fp = fopen($this->LogFilePath, 'a+');
			}
			catch(Exception $ex){
				error_log('Couldn\'t open log file: ' . $this->LogFilePath . '. Exception: ' . vds($ex));
				return;
			}

			fwrite($fp, $text);
			fclose($fp);
		}
	}

	/**
	 * Add a message to the message queue, without writing to disk. To write all queued messages to disk, call `Log::WriteQueue()`.
	 */
	public function Queue(string $text): void{
		$this->_Messages[] = NOW->format('Y-m-d H:i:s') . "\t" . $this->RequestId . "\t" . trim($text);
	}

	/**
	 * Write all queued messages to disk and clear the queue.
	 */
	public function WriteQueue(): void{
		$this->WriteToFile(implode("\n", $this->_Messages));
		$this->_Messages = [];
	}
}
