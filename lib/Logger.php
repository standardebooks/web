<?
use function Safe\fopen;
use function Safe\fwrite;
use function Safe\fclose;
use function Safe\error_log;

class Logger{
	public static function WriteGithubWebhookLogEntry(string $requestId, string $text){
		try{
			$fp = fopen(GITHUB_WEBHOOK_LOG_FILE_PATH, 'a+');
		}
		catch(\Exception $ex){
			self::WriteErrorLogEntry('Couldn\'t open log file: ' . GITHUB_WEBHOOK_LOG_FILE_PATH . '. Exception: ' . vds($ex));
			return;
		}

		fwrite($fp, gmdate('Y-m-d H:i:s') . "\t" . $requestId . "\t" . $text . "\n");
		fclose($fp);
	}

	public static function WriteErrorLogEntry(string $text){
		error_log($text);
	}
}
?>
