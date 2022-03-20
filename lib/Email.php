<?
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email{
	public $To = '';
	public $From = '';
	public $FromName = '';
	public $ReplyTo = '';
	public $Subject = '';
	public $Body = '';
	public $TextBody = '';
	public $Attachments = array();
	public $PostmarkStream = null;

	public function Send(): bool{
		if($this->ReplyTo == ''){
			$this->ReplyTo = $this->From;
		}

		if($this->To === null || $this->To == ''){
			return false;
		}

		$phpMailer = new PHPMailer(true);

		try{
			$phpMailer->SetFrom($this->From, $this->FromName);
			$phpMailer->AddReplyTo($this->ReplyTo);
			$phpMailer->AddAddress($this->To);
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
			$phpMailer->Password = EMAIL_SMTP_PASSWORD;

			if($this->PostmarkStream !== null){
				$phpMailer->addCustomHeader('X-PM-Message-Stream', $this->PostmarkStream);
			}

			if(SITE_STATUS == SITE_STATUS_DEV){
				Logger::WriteErrorLogEntry('Sending mail to ' . $this->To . ' from ' . $this->From);
				Logger::WriteErrorLogEntry('Subject: ' . $this->Subject);
				Logger::WriteErrorLogEntry($this->Body);
				Logger::WriteErrorLogEntry($this->TextBody);
			}
			else{
				$phpMailer->Send();
			}
		}
		catch(Exception $ex){
			if(SITE_STATUS != SITE_STATUS_DEV){
				Logger::WriteErrorLogEntry('Failed sending email to ' . $this->To . ' Exception: ' . $ex->errorMessage() . "\n" . '  Subject: ' . $this->Subject . "\nBody:\n" . $this->Body);
			}

			return false;
		}

		return true;
	}

	public function __construct(bool $isNoReplyEmail = false){
		if($isNoReplyEmail){
			$this->From = NO_REPLY_EMAIL_ADDRESS;
			$this->FromName = 'Standard Ebooks';
			$this->ReplyTo = NO_REPLY_EMAIL_ADDRESS;
		}
	}
}
