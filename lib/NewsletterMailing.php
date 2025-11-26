<?
use Safe\DateTimeImmutable;

use function Safe\preg_match;
use function Safe\preg_match_all;
use function Safe\preg_replace;
use function Safe\simplexml_load_string;


/**
 * @property Newsletter $Newsletter
 * @property string $Url
 */
class NewsletterMailing{
	use Traits\Accessor;
	use Traits\PropertyFromHttp;

	public int $NewsletterMailingId;
	public int $NewsletterId;
	public string $Subject = '';
	public string $BodyHtml = '';
	public string $BodyText = '';
	public Enums\QueueStatus $Status;
	public ?string $FromName = null;
	public string $FromEmail = '';
	public DateTimeImmutable $SendOn;
	public ?int $RecipientCount = null;
	/** @var array<string> */
	public array $Emails = [];
	public ?string $InternalName = null;
	public ?int $OpenCount = null;
	public DateTimeImmutable $Created;
	public DateTimeImmutable $Updated;

	protected Newsletter $_Newsletter;
	protected string $_Url;

	/** @var array<stdClass> $_Recipients */
	private array $_Recipients;

	/**
	 * @throws Exceptions\NewsletterNotFoundException If the `Newsletter` can't be found.
	 */
	protected function GetNewsletter(): Newsletter{
		return Db::Query('select * from Newsletters where NewsletterId = ?', [$this->NewsletterId], Newsletter::class)[0] ?? throw new Exceptions\NewsletterNotFoundException();
	}

	/**
	 * @throws Exceptions\NewsletterMailingNotFoundException If the `NewsletterMailing` can't be found.
	 */
	public static function Get(?int $newsletterMailingId): NewsletterMailing{
		if($newsletterMailingId === null){
			throw new Exceptions\NewsletterMailingNotFoundException();
		}

		return Db::Query('select * from NewsletterMailings where NewsletterMailingId = ?', [$newsletterMailingId], NewsletterMailing::class)[0] ?? throw new Exceptions\NewsletterMailingNotFoundException();
	}

	protected function GetUrl(): string{
		if(!isset($this->_Url)){
			$this->_Url = '/admin/newsletter-mailings/' . $this->NewsletterMailingId;
		}

		return $this->_Url;
	}

	/**
	 * @throws Exceptions\InvalidNewsletterMailingException If the `NewsletterMailing` is invalid.
	 * @throws Exceptions\InvalidEmailException If any of the recipient email addresses is invalid.
	 * @throws \Exception If an error occurs during mailing.
	 */
	public function Send(): void{
		try{
			// Validate this again to make double sure the mailing is valid.
			$this->Validate(false);

			if(!isset($this->_Recipients)){
				$this->GetRecipients();
			}

			$emailMessages = [];
			foreach($this->_Recipients as $recipient){
				$em = new QueuedEmailMessage();
				$em->From = $this->FromEmail;
				$em->FromName = $this->FromName;
				$em->To = $recipient->Email;
				$em->ToName = $recipient->FullName;
				$em->Subject = $this->Subject;
				$em->Priority = Enums\Priority::Low;
				$em->UnsubscribeUrl = SITE_URL . $this->Newsletter->Url . '/subscriptions/' . $recipient->EmailKey . '/delete';
				$em->BodyHtml = str_replace('SCRIBOPHILE_UNSUBSCRIBE_URL', rawurlencode($em->UnsubscribeUrl), $this->BodyHtml);
				$em->BodyText = str_replace('SCRIBOPHILE_UNSUBSCRIBE_URL', $em->UnsubscribeUrl, $this->BodyText);
				$em->Metadata['NewsletterMailingId'] = (string)$this->NewsletterMailingId;
				$emailMessages[] = $em;
			}

			QueuedEmailMessage::CreateBatch($emailMessages);

			Db::Query('update NewsletterMailings set RecipientCount = ?, Status = ?, OpenCount = ifnull(OpenCount, 0) where NewsletterMailingId = ?', [$this->RecipientCount, Enums\QueueStatus::Completed, $this->NewsletterMailingId]);
		}
		catch(\Exception $ex){
			Db::Query('update NewsletterMailings set Status = ? where NewsletterMailingId = ?', [Enums\QueueStatus::Failed, $this->NewsletterMailingId]);

			throw $ex;
		}
	}

	/**
	 * @throws Exceptions\InvalidEmailException If any of the recipient email addresses is invalid.
	 */
	public function GetRecipients(): void{
		$contacts = [];
		$this->_Recipients = [];

		// If no recipients are specified, get them from the database.
		$result = Db::Query('select Email, EmailKey from NewsletterSubscriptions ns inner join NewsletterContacts nc using (NewsletterContactId) where ns.NewsletterId = ? and ns.IsConfirmed = true', [$this->NewsletterId]);

		foreach($result as $row){
			$contacts[] = $row;
		}

		// Validate email addresses before sending.
		foreach($contacts as $contact){
			$recipient = new stdClass();
			$recipient->EmailKey = $contact->EmailKey;
			$recipient->FullName = null;
			$recipient->FirstName = null;

			if(preg_match('/(^.+?) <(.+?)>$/ius', $contact->Email, $matches)){
				if(!Validator::IsValidEmail($matches[2])){
					throw new Exceptions\InvalidEmailException('Invalid email: ' . $contact->Email);
				}

				$recipient->Email = $matches[2];
				$recipient->FullName = $matches[1];

				// Try to figure out the first name.
				// Strip `the` from the username.
				$name = preg_replace('/^the /is', '', $recipient->FullName);

				// Favor strings of initials first, like `N. C. Wyeth`.
				preg_match('/^[A-Z\s\.]+\s/us', $name, $matches);
				if(sizeof($matches) > 0){
					$recipient->FirstName = trim($matches[0]);
				}
				else{
					// No initials found, try the full first name.
					$pos = strpos($name, ' ');
					if($pos === false){
						$recipient->FirstName = $name;
					}
					else{
						$recipient->FirstName = mb_substr($name, 0, $pos);
					}
				}
			}
			elseif(!Validator::IsValidEmail($contact->Email)){
				throw new Exceptions\InvalidEmailException('Invalid email: ' . $contact->Email);
			}
			else{
				$recipient->Email = $contact->Email;
			}

			$this->_Recipients[] = $recipient;
		}

		$this->RecipientCount = sizeof($this->_Recipients);
	}

	/**
	 * @throws Exceptions\InvalidNewsletterMailingException If the `NewsletterMailing` is invalid.
	 */
	public function Save(): void{
		$this->Validate(false);

		Db::Query('update NewsletterMailings set NewsletterId = ?, Subject = ?, BodyHtml = ?, BodyText = ?, Status = ?, FromName = ?, FromEmail = ?, SendOnTimestamp = ?, InternalName = ? where NewsletterMailingId = ?', [$this->NewsletterId, $this->Subject, $this->BodyHtml, $this->BodyText, $this->Status, $this->FromName, $this->FromEmail, $this->SendOnTimestamp, $this->InternalName, $this->NewsletterMailingId]);
	}

	/**
	 * @throws Exceptions\InvalidNewsletterMailingException If the `NewsletterMailing` is invalid.
	 */
	public function Create(bool $addFooter): void{
		$this->Validate($addFooter);

		// Only check this when creating.
		if($this->SendOnTimestamp < NOW){
			$error = new Exceptions\InvalidNewsletterMailingException();
			$error->Add(new Exceptions\InvalidNewsletterSendOnTimestampException());
			throw $error;
		}

		$this->NewsletterMailingId = Db::QueryInt('insert into NewsletterMailings (NewsletterId, Subject, BodyHtml, BodyText, Status, FromName, FromEmail, SendOnTimestamp, InternalName) values (?, ?, ?, ?, ?, ?, ?, ?, ?) returning NewsletterMailingId', [$this->NewsletterId, $this->Subject, $this->BodyHtml, $this->BodyText, Enums\QueueStatus::Queued, $this->FromName, $this->FromEmail, $this->SendOnTimestamp, $this->InternalName]);
	}

	/**
	 * @throws Exceptions\InvalidNewsletterMailingException If the `NewsletterMailing` is invalid.
	 */
	public function Validate(bool $addFooter): void{
		$error = new Exceptions\InvalidNewsletterMailingException();

		$this->BodyHtml = trim(str_replace('\'', '’', $this->BodyHtml));
		$this->BodyText = trim(str_replace('\'', '’', $this->BodyText));
		$this->FromName = $this->FromName !== null ? trim($this->FromName) : null;
		$this->FromEmail = trim($this->FromEmail);

		// If we received only HTML or only text, convert to one from the other.
		if($this->BodyHtml != '' && $this->BodyText == ''){
			if(mb_stripos($this->BodyHtml, '<body') === false){
				$this->BodyHtml = Template::NewsletterMailingHtml(['bodyHtml' => $this->BodyHtml, 'subject' => $this->Subject]);
			}

			$this->BodyText = Formatter::HtmlToMarkdown($this->BodyHtml);
		}

		if($this->BodyText != '' && $this->BodyHtml == ''){
			$this->BodyHtml = Template::NewsletterMailingHtml(['bodyHtml' => Formatter::MarkdownToHtml($this->BodyText), 'subject' => $this->Subject]);
		}

		if($addFooter){
			$footerHtml = Template::EmailMarketingFooterElement(['newsletter' => $this->Newsletter]);

			$footerText = "\n" . Template::EmailMarketingFooterText(['newsletter' => $this->Newsletter]);

			if(mb_stripos($this->BodyHtml, 'Unsubscribe from this newsletter.') === false){
				$this->BodyHtml = str_ireplace('</body>', $footerHtml . '</body>', $this->BodyHtml);
			}

			if(mb_stripos($this->BodyText, 'Unsubscribe from this newsletter.') === false){
				$this->BodyText .= "\n" . $footerText;
			}
		}

		if($this->BodyHtml == ''){
			$error->Add(new Exceptions\FieldMissingException('Newsletter HTML body is empty.'));
		}

		try{
			Validator::ValidateHtmlFragment($this->BodyHtml);
		}
		catch(Exceptions\InvalidHtmlException $ex){
			$error->Add(new Exceptions\InvalidNewsletterMailingBodyHtmlException($ex->getMessage()));
		}

		if($this->Subject == ''){
			// Try to infer the subject from the HTML file.
			preg_match('/<title>(.+?)<\/title>/ius', $this->BodyHtml, $matches);

			$this->Subject = $matches[1] ?? '';
		}
		else{
			// If we do have the subject, replace the HTML `<title>` with it.
			$this->BodyHtml = preg_replace('/<title>.+?<\/title>/ius', '<title>' . Formatter::EscapeHtml($this->Subject) . '</title>', $this->BodyHtml);
		}

		$this->InternalName = trim($this->InternalName ?? '');

		if($this->InternalName == ''){
			$this->InternalName = null;
		}

		if(trim($this->Subject) == ''){
			$error->Add(new Exceptions\FieldMissingException('No email subject specified.'));
		}

		$this->Subject = trim($this->Subject);

		if($this->BodyText == ''){
			$error->Add(new Exceptions\FieldMissingException('Newsletter text body is empty.'));
		}

		if($this->FromEmail == ''){
			$error->Add(new Exceptions\FieldMissingException('Newsletter from email is empty.'));
		}
		elseif(!Validator::IsValidEmail($this->FromEmail)){
			$error->Add(new Exceptions\FieldInvalidException('Invalid email: ' . $this->FromEmail));
		}

		if(mb_stripos($this->BodyHtml, '\'') !== false){
			$error->Add(new Exceptions\FieldInvalidException('Newsletter HTML contains `\'`.'));
		}

		if(mb_stripos($this->BodyText, '\'') !== false){
			$error->Add(new Exceptions\FieldInvalidException('Newsletter text contains `\'`.'));
		}

		if(mb_stripos($this->BodyText, '.test') !== false){
			$error->Add(new Exceptions\FieldInvalidException('Newsletter text contains .test TLD.'));
		}

		if(mb_stripos($this->BodyHtml, '.test') !== false){
			$error->Add(new Exceptions\FieldInvalidException('Newsletter HTML contains .test TLD.'));
		}

		if(mb_stripos($this->BodyHtml, 'SCRIBOPHILE_UNSUBSCRIBE_URL') === false){
			$error->Add(new Exceptions\FieldInvalidException('Newsletter HTML missing unsubscribe URL variable: SCRIBOPHILE_UNSUBSCRIBE_URL.'));
		}

		if($error->HasExceptions){
			throw $error;
		}

		// Remove unused CSS.
		$dom = simplexml_load_string($this->BodyHtml);
		$unusedSelectors = [];

		preg_match('/<style[^>]*?>(.+?)<\/style>/ius', $this->BodyHtml, $css);
		if(sizeof($css) > 1){
			preg_match_all('/^\s*[^{}]+?(?={)/im', $css[1], $selectors);

			foreach($selectors[0] as $selectorMatch){
				$selector = trim($selectorMatch);
				$t = new Gt\CssXPath\Translator($selector);

				$elements = $dom->xpath((string)$t);

				if($elements === false || $elements === null || sizeof($elements) == 0){
					$unusedSelectors[] = $selector;
				}
			}

			$newStyleElement = $css[0];

			foreach($unusedSelectors as $selector){
				$newStyleElement = preg_replace('/\s*' . $selector . '\s*{[^}]*?}/ius', '', $newStyleElement);
			}

			$this->BodyHtml = str_replace($css[0], $newStyleElement, $this->BodyHtml);
		}
	}

	public function FillFromHttpPost(): void{
		$this->PropertyFromHttp('NewsletterId');
		$this->PropertyFromHttp('FromName');
		$this->PropertyFromHttp('FromEmail');
		$this->PropertyFromHttp('Subject');
		$this->PropertyFromHttp('InternalName');
		$this->PropertyFromHttp('BodyHtml');
		$this->PropertyFromHttp('BodyText');
		$this->PropertyFromHttp('Status');

		// `SendOnTimestamp` is always interpreted as being sent in the `America/Chicago` timezone.
		// Therefore we have to do some gynmastics to store it as UTC in our object.
		$sendOnTimestamp = HttpInput::Str(POST, 'newsletter-mailing-send-on-timestamp');
		if($sendOnTimestamp !== null){
			/** @throws void */
			$this->SendOnTimestamp = (new DateTimeImmutable($sendOnTimestamp, new DateTimeZone('America/Chicago')))->setTimezone(new DateTimeZone('UTC'));
		}
	}
}
