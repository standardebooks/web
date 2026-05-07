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

	public function __construct(?string $logFilePath = null){
		// Get a semi-random ID to identify this request within the log.
		$this->RequestId = substr(sha1(time() . rand()), 0, 8);
		$this->LogFilePath = $logFilePath;
	}


	// *******
	// METHODS
	// *******

	/**
	 * Write a message to disk.
	 */
	public function Write(string $text): void{
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

			fwrite($fp, NOW->format('Y-m-d H:i:s') . "\t" . $this->RequestId . "\t" . $text . "\n");
			fclose($fp);
		}
	}

	/**
	 * Add a message to the message queue, without writing to disk. To write all queued messages to disk, call `Log::WriteQueue()`.
	 */
	public function Queue(string $text): void{
		$this->_Messages[] = NOW->format('Y-m-d H:i:s') . "\t" . $this->RequestId . "\t" . $text . "\n";
	}

	/**
	 * Write all queued messages to disk and clear the queue.
	 */
	public function WriteQueue(): void{
		$this->Write(implode("\n", $this->_Messages));
		$this->_Messages = [];
	}
}
