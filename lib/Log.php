<?
use function Safe\fopen;
use function Safe\fwrite;
use function Safe\fclose;
use function Safe\error_log;

class Log{
	private string $RequestId;
	private ?string $LogFilePath = null;

	public function __construct(?string $logFilePath){
		// Get a semi-random ID to identify this request within the log.
		$this->RequestId = substr(sha1(time() . rand()), 0, 8);
		$this->LogFilePath = $logFilePath;
	}


	// *******
	// METHODS
	// *******

	public function Write(string $text): void{
		if($this->LogFilePath === null){
			self::WriteErrorLogEntry($text);
		}
		else{
			try{
				$fp = fopen($this->LogFilePath, 'a+');
			}
			catch(Exception $ex){
				self::WriteErrorLogEntry('Couldn\'t open log file: ' . $this->LogFilePath . '. Exception: ' . vds($ex));
				return;
			}

			fwrite($fp, NOW->format('Y-m-d H:i:s') . "\t" . $this->RequestId . "\t" . $text . "\n");
			fclose($fp);
		}
	}

	public static function WriteErrorLogEntry(string $text): void{
		error_log($text);
	}
}
