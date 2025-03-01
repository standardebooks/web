<?
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email{
	public string $To = '';
	public string $ToName = '';
	public string $From = '';
	public string $FromName = '';
	public string $ReplyTo = '';
	public string $Subject = '';
	public string $Body = '';
	public string $TextBody = '';
	/** @var array<array<string>> $Attachments */
	public $Attachments = [];
	public ?string $PostmarkStream = null;

	public function __construct(bool $isNoReplyEmail = false){
		if($isNoReplyEmail){
			$this->From = NO_REPLY_EMAIL_ADDRESS;
			$this->FromName = 'Standard Ebooks';
			$this->ReplyTo = NO_REPLY_EMAIL_ADDRESS;
		}
	}


	// *******
	// METHODS
	// *******

	public function Send(): bool{
		if($this->ReplyTo == ''){
			$this->ReplyTo = $this->From;
		}

		if($this->To == ''){
			return false;
		}

		$phpMailer = new PHPMailer(true);

		try{
			$phpMailer->setFrom($this->From, $this->FromName);
			$phpMailer->addReplyTo($this->ReplyTo);
			$phpMailer->addAddress($this->To, $this->ToName);
			$phpMailer->Subject = $this->Subject;
			$phpMailer->CharSet = 'UTF-8';
			if($this->TextBody != ''){
				$phpMailer->isHTML(true);
				$phpMailer->Body = $this->Body;
				$phpMailer->AltBody = $this->TextBody;
			}
			else{
				$phpMailer->msgHTML($this->Body);
			}

			foreach($this->Attachments as $attachment){
				$phpMailer->addStringAttachment($attachment['contents'], $attachment['filename']);
			}

			$phpMailer->isSMTP();
			$phpMailer->SMTPAuth = true;
			$phpMailer->SMTPSecure = 'tls';
			$phpMailer->Port = 587;
			$phpMailer->Host = EMAIL_SMTP_HOST;
			$phpMailer->Username = EMAIL_SMTP_USERNAME;
			$phpMailer->Password = EMAIL_SMTP_USERNAME;

			if($this->PostmarkStream !== null){
				$phpMailer->addCustomHeader('X-PM-Message-Stream', $this->PostmarkStream);
			}

			if(SITE_STATUS == SITE_STATUS_DEV){
				$log = new Log(EMAIL_LOG_FILE_PATH);
				$log->Write('Sending mail to ' . $this->To . ' from ' . $this->From);
				$log->Write('Subject: ' . $this->Subject);
				$log->Write($this->Body);
				$log->Write($this->TextBody);
			}
			else{
				$phpMailer->send();
			}
		}
		catch(Exception $ex){
			if(SITE_STATUS != SITE_STATUS_DEV){
				Log::WriteErrorLogEntry('Failed sending email to ' . $this->To . ' Exception: ' . $ex->errorMessage() . "\n" . '  Subject: ' . $this->Subject . "\nBody:\n" . $this->Body);
			}

			return false;
		}

		return true;
	}
}
