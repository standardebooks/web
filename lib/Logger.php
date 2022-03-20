<?
use function Safe\fopen;
use function Safe\fwrite;
use function Safe\fclose;
use function Safe\error_log;
use function Safe\gmdate;

class Logger{
	public static function WritePostmarkWebhookLogEntry(string $requestId, string $text): void{
		self::WriteLogEntry(POSTMARK_WEBHOOK_LOG_FILE_PATH, $requestId . "\t" . $text);
	}

	public static function WriteGithubWebhookLogEntry(string $requestId, string $text): void{
		self::WriteLogEntry(GITHUB_WEBHOOK_LOG_FILE_PATH, $requestId . "\t" . $text);
	}

	public static function WriteLogEntry(string $file, string $text): void{
		try{
			$fp = fopen($file, 'a+');
		}
		catch(\Exception $ex){
			self::WriteErrorLogEntry('Couldn\'t open log file: ' . $file . '. Exception: ' . vds($ex));
			return;
		}

		fwrite($fp, gmdate('Y-m-d H:i:s') . "\t" . $text . "\n");
		fclose($fp);
	}

	public static function WriteErrorLogEntry(string $text): void{
		error_log($text);
	}
}
