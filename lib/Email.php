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
			$phpMailer->SetFrom($this->From, $this->FromName);
			$phpMailer->AddReplyTo($this->ReplyTo);
			$phpMailer->AddAddress($this->To, $this->ToName);
			$phpMailer->Subject = $this->Subject;
			$phpMailer->CharSet = 'UTF-8';
			if($this->TextBody !== null && $this->TextBody != ''){
				$phpMailer->IsHTML(true);
				$phpMailer->Body = $this->Body;
				$phpMailer->AltBody = $this->TextBody;
			}
			else{
				$phpMailer->MsgHTML($this->Body);
			}

			foreach($this->Attachments as $attachment){
				if(is_array($attachment)){
					$phpMailer->addStringAttachment($attachment['contents'], $attachment['filename']);
				}
			}

			$phpMailer->IsSMTP();
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
				Log::WriteErrorLogEntry('Sending mail to ' . $this->To . ' from ' . $this->From);
				Log::WriteErrorLogEntry('Subject: ' . $this->Subject);
				Log::WriteErrorLogEntry($this->Body);
				Log::WriteErrorLogEntry($this->TextBody);
			}
			else{
				$phpMailer->Send();
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
