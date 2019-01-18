<?
class Logger{
	public static function WriteGithubWebhookLogEntry(string $requestId, string $text){
		$fp = fopen(GITHUB_WEBHOOK_LOG_FILE_PATH, 'a+');

		if($fp === false){
			self::WriteErrorLogEntry('Could not open log file: ' . GITHUB_WEBHOOK_LOG_FILE_PATH);
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
