<?
/**
 * @property-read EmailAddress $To
 * @property-write EmailAddress|string $To
 * @property-read EmailAddress $From
 * @property-write EmailAddress|string $From
 * @property-read ?EmailAddress $ReplyTo
 * @property-write EmailAddress|string|null $ReplyTo
 */
class EmailMessage{
	use Traits\Accessor;

	public ?string $ToName = null;
	public ?string $FromName = null;
	public string $Subject;
	public string $BodyHtml;
	public ?string $BodyText = null;
	public ?string $UnsubscribeUrl = null;
	/** @var array<array{contents: string, filename: string, mimeType?: string}> $Attachments */
	public array $Attachments = [];
	/** @var array<string, string> $Metadata */
	public array $Metadata = [];

	protected EmailAddress $_To; // Should be converted to property hooks when PHP 8.4 is available.
	protected EmailAddress $_From; // Should be converted to property hooks when PHP 8.4 is available.
	protected ?EmailAddress $_ReplyTo = null; // Should be converted to property hooks when PHP 8.4 is available.

	public function __construct(bool $isNoReplyEmail = false){
		if($isNoReplyEmail){
			$this->From = SUPPORT_EMAIL_ADDRESS;
			$this->FromName = SUPPORT_FROM_NAME;
			$this->ReplyTo = SUPPORT_EMAIL_ADDRESS;
		}
	}


	// *******
	// SETTERS
	// *******

	protected function SetTo(string|EmailAddress $string): void{
		$this->_To = new EmailAddress($string);
	}

	protected function SetFrom(string|EmailAddress $string): void{
		$this->_From = new EmailAddress($string);
	}

	protected function SetReplyTo(string|EmailAddress $string): void{
		$this->_ReplyTo = new EmailAddress($string);
	}

	/**
	 * @throws Exceptions\InvalidEmailMessageException If the `EmailMessage` is invalid.
	 */
	public function Validate(): void{
		$error = new Exceptions\InvalidEmailMessageException();

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
		catch(Exceptions\InvalidEmailAddressException){
			$error->Add(new Exceptions\InvalidEmailAddressException('Invalid To email address: ' . $this->To));
		}

		try{
			$this->From->Validate();
		}
		catch(Exceptions\InvalidEmailAddressException){
			$error->Add(new Exceptions\InvalidEmailAddressException('Invalid From email address: ' . $this->From));
		}

		if(isset($this->ReplyTo)){
			try{
				$this->ReplyTo->Validate();
			}
			catch(Exceptions\InvalidEmailAddressException){
				$error->Add(new Exceptions\InvalidEmailAddressException('Invalid email address: ' . $this->ReplyTo));
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
				Log::WriteMailLogEntry('Sending mail to ' . $this->To . ' from ' . $this->From);
				Log::WriteMailLogEntry('Subject: ' . $this->Subject);
				Log::WriteMailLogEntry($this->BodyHtml);
				Log::WriteMailLogEntry($this->BodyText ?? '');
			}
			else{
				AwsSesApi::Send([$this]);
			}
		}
		catch(Exceptions\InvalidEmailMessageException $ex){
			Log::WriteErrorLogEntry('Failed validating email. Exception: ' . $ex->getMessage() . "\n" . 'Email: ' . vds($this));
		}
		catch(\Exception $ex){
			Log::WriteMailLogEntry('Failed sending email to ' . $this->To . ' Exception: ' . $ex->getMessage() . "\n" . ' Subject: ' . $this->Subject . "\nBody:\n" . $this->BodyHtml);
		}
	}
}
