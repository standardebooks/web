<?
class EmailMessage{
	public ?string $ToName = null;
	public ?string $FromName = null;
	public string $Subject;
	public ?string $UnsubscribeUrl = null;
	/** @var array<array{contents: string, filename: string, mimeType?: string}> $Attachments */
	public array $Attachments = [];
	/** @var array<string, string> $Metadata */
	public array $Metadata = [];

	public EmailAddress $To{
		set(string|EmailAddress $value){
			$this->To = new EmailAddress($value);
		}
	}

	public EmailAddress $From{
		set(string|EmailAddress $value){
			$this->From = new EmailAddress($value);
		}
	}

	public ?EmailAddress $ReplyTo = null{
		set(string|EmailAddress|null $value){
			$this->ReplyTo = $value === null ? null : new EmailAddress($value);
		}
	}

	public HtmlDocument $BodyHtml{
		set(string|HtmlDocument $value){
			$this->BodyHtml = new HtmlDocument($value);
		}
	}

	public ?Markdown $BodyText = null{
		set(string|Markdown|null $value){
			$this->BodyText = $value === null ? null : new Markdown($value);
		}
	}

	public function __construct(bool $isNoReplyEmail = false){
		if($isNoReplyEmail){
			$this->From = SUPPORT_EMAIL_ADDRESS;
			$this->FromName = SUPPORT_FROM_NAME;
			$this->ReplyTo = SUPPORT_EMAIL_ADDRESS;
		}
	}

	/**
	 * @throws Exceptions\EmailMessageInvalidException If the `EmailMessage` is invalid.
	 */
	public function Validate(): void{
		$error = new Exceptions\EmailMessageInvalidException();

		$this->ReplyTo ??= '';
		if($this->ReplyTo == ''){
			$this->ReplyTo = null;
		}

		$this->To ??= '';
		if($this->To == ''){
			$error->Add(new Exceptions\FieldMissingException('Missing To address.'));
		}

		try{
			$this->To->Validate();
		}
		catch(Exceptions\EmailAddressInvalidException){
			$error->Add(new Exceptions\EmailAddressInvalidException('Invalid To email address: ' . $this->To));
		}

		try{
			$this->From->Validate();
		}
		catch(Exceptions\EmailAddressInvalidException){
			$error->Add(new Exceptions\EmailAddressInvalidException('Invalid From email address: ' . $this->From));
		}

		if(isset($this->ReplyTo)){
			try{
				$this->ReplyTo->Validate();
			}
			catch(Exceptions\EmailAddressInvalidException){
				$error->Add(new Exceptions\EmailAddressInvalidException('Invalid email address: ' . $this->ReplyTo));
			}
		}

		$this->ToName = trim($this->ToName ?? '');
		if($this->ToName == ''){
			$this->ToName = null;
		}

		$this->BodyHtml = trim($this->BodyHtml ?? '');
		if($this->BodyHtml == ''){
			$error->Add(new Exceptions\FieldMissingException('Missing body HTML.'));
		}

		$this->BodyText = trim($this->BodyText ?? '');
		if($this->BodyText == ''){
			$this->BodyText = null;
		}

		$this->Subject = trim($this->Subject ?? '');
		if($this->Subject == ''){
			$error->Add(new Exceptions\FieldMissingException('Missing subject.'));
		}

		foreach($this->Attachments as $attachment){
			// Multiply by 1.4 because AWS calculates the size of the attachment in base64 encoded form, which adds about 33% to the file size.
			if(strlen($attachment['contents']) * 1.4 > AWS_MAX_ATTACHMENT_BYTES){
				$error->Add(new Exceptions\FieldInvalidException('Attachment larger than 2MB.'));
			}
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	public function Send(): void{
		try{
			$this->Validate();

			if(SITE_STATUS == SITE_STATUS_DEV){
				$log = new Log(EMAIL_LOG_FILE_PATH);
				$log->Write('Sending mail to ' . $this->To . ' from ' . $this->From);
				$log->Write('Subject: ' . $this->Subject);
				$log->Write($this->BodyHtml);
				$log->Write($this->BodyText ?? '');
			}
			else{
				AwsSesApi::Send([$this]);
			}
		}
		catch(Exceptions\EmailMessageInvalidException $ex){
			$log = new Log();
			$log->Write('Failed validating email. Exception: ' . $ex . "\n" . 'Email: ' . vds($this));
		}
		catch(\Exception $ex){
			$log = new Log(EMAIL_LOG_FILE_PATH);
			$log->Write('Failed sending email to ' . $this->To . ' Exception: ' . $ex . "\n" . ' Subject: ' . $this->Subject . "\nBody:\n" . $this->BodyHtml);
		}
	}
}
