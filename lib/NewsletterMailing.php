<?
use Safe\DateTimeImmutable;

use function Safe\preg_match;
use function Safe\preg_match_all;
use function Safe\preg_replace;
use function Safe\simplexml_load_string;


/**
 * @property Newsletter $Newsletter
 * @property string $Url
 * @property array<NewsletterSubscription> $Recipients
 * @property-read HtmlDocument $BodyHtml
 * @property-write HtmlDocument|string $BodyHtml
 * @property-read EmailAddress $FromEmail
 * @property-write EmailAddress|string $FromEmail
 */
class NewsletterMailing{
	use Traits\Accessor;
	use Traits\PropertyFromHttp;

	public int $NewsletterMailingId;
	public int $NewsletterId;
	public string $Subject = '';
	public string $BodyText = '';
	public Enums\QueueStatus $Status;
	public ?string $FromName = null;
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
	/** @var array<NewsletterSubscription> $_Recipients */
	protected array $_Recipients;
	protected HtmlDocument $_BodyHtml; // Should be converted to property hooks when PHP 8.4 is available; also see `FillFromHttpPost()`.
	protected EmailAddress $_FromEmail; // Should be converted to property hooks when PHP 8.4 is available; also see `FillFromHttpPost()`.


	// *******
	// GETTERS
	// *******

	/**
	 * @throws Exceptions\NewsletterNotFoundException If the `Newsletter` can't be found.
	 */
	protected function GetNewsletter(): Newsletter{
		return Db::Query('SELECT * from Newsletters where NewsletterId = ?', [$this->NewsletterId], Newsletter::class)[0] ?? throw new Exceptions\NewsletterNotFoundException();
	}

	protected function GetUrl(): string{
		if(!isset($this->_Url)){
			$this->_Url = '/admin/newsletter-mailings/' . $this->NewsletterMailingId;
		}

		return $this->_Url;
	}

	/**
	 * @return array<NewsletterSubscription>
	 */
	protected function GetRecipients(): array{
		if(!isset($this->Recipients)){
			$this->_Recipients = Db::MultiTableSelect('SELECT * from NewsletterSubscriptions inner join Users on NewsletterSubscriptions.UserId = Users.UserId where NewsletterId = ? and IsConfirmed = true and NewsletterSubscriptions.IsVisible = true and CanReceiveEmail = true', [$this->NewsletterId], NewsletterSubscription::class);
		}

		return $this->_Recipients;
	}


	// *******
	// SETTERS
	// *******

	protected function SetBodyHtml(string|HtmlDocument $string): void{
		$this->_BodyHtml = new HtmlDocument($string);
	}


	// *******
	// METHODS
	// *******

	/**
	 * On sending, in `$BodyHtml` the string `SE_UNSUBSCRIBE_URL` is replaced by `self::$UnsubscribeUrl`, and the string `SE_FIRST_NAME` is replaced by the recipient's first name, or removed if there is no first name.
	 *
	 * @throws Exceptions\InvalidNewsletterMailingException If the `NewsletterMailing` is invalid.
	 * @throws Exceptions\InvalidEmailAddressException If any of the recipient email addresses is invalid.
	 * @throws \Exception If an error occurs during mailing.
	 */
	public function Send(): void{
		try{
			// Validate this again to make double sure the mailing is valid.
			$this->Validate(false);

			$emailMessages = [];
			foreach($this->Recipients as $newsletterSubscription){
				if($newsletterSubscription->User->Email === null){
					continue;
				}

				$em = new QueuedEmailMessage();
				$em->From = $this->FromEmail;
				$em->FromName = $this->FromName;
				$em->To = $newsletterSubscription->User->Email;
				$em->ToName = $newsletterSubscription->User->Name;
				$em->Subject = $this->Subject;
				$em->Priority = Enums\Priority::Low;
				$em->UnsubscribeUrl = SITE_URL . $newsletterSubscription->DeleteUrl;
				$em->BodyHtml = str_replace(NEWSLETTER_UNSUBSCRIBE_URL_VARIABLE, Formatter::EscapeHtml($em->UnsubscribeUrl), $this->BodyHtml);
				if($newsletterSubscription->User->FirstName !== null){
					$em->BodyHtml = str_replace(NEWSLETTER_FIRST_NAME_VARIABLE, Formatter::EscapeHtml($newsletterSubscription->User->FirstName), $this->BodyHtml);
				}
				else{
					// No first name, remove the variable and any white space around it.
					$em->BodyHtml = preg_replace('/\s*' . preg_quote(NEWSLETTER_FIRST_NAME_VARIABLE, '/') . '\s*/u', '', $em->BodyHtml);
				}
				$em->BodyText = str_replace(NEWSLETTER_UNSUBSCRIBE_URL_VARIABLE, $em->UnsubscribeUrl, $this->BodyText);
				$em->Metadata['NewsletterMailingId'] = (string)$this->NewsletterMailingId;
				$emailMessages[] = $em;
			}

			QueuedEmailMessage::CreateBatch($emailMessages);

			$this->RecipientCount = sizeof($this->Recipients);

			Db::Query('UPDATE NewsletterMailings set RecipientCount = ?, Status = ?, OpenCount = ifnull(OpenCount, 0) where NewsletterMailingId = ?', [$this->RecipientCount, Enums\QueueStatus::Completed, $this->NewsletterMailingId]);
		}
		catch(\Exception $ex){
			Db::Query('UPDATE NewsletterMailings set Status = ? where NewsletterMailingId = ?', [Enums\QueueStatus::Failed, $this->NewsletterMailingId]);

			throw $ex;
		}
	}

	/**
	 * @throws Exceptions\InvalidNewsletterMailingException If the `NewsletterMailing` is invalid.
	 */
	public function Save(): void{
		$this->Validate(false);

		Db::Query('UPDATE NewsletterMailings set NewsletterId = ?, Subject = ?, BodyHtml = ?, BodyText = ?, Status = ?, FromName = ?, FromEmail = ?, SendOn = ?, InternalName = ? where NewsletterMailingId = ?', [$this->NewsletterId, $this->Subject, $this->BodyHtml, $this->BodyText, $this->Status, $this->FromName, $this->FromEmail, $this->SendOn, $this->InternalName, $this->NewsletterMailingId]);
	}

	/**
	 * @throws Exceptions\InvalidNewsletterMailingException If the `NewsletterMailing` is invalid.
	 */
	public function Create(bool $addFooter): void{
		$this->Validate($addFooter);

		// Only check this when creating.
		if($this->SendOn < NOW){
			$error = new Exceptions\InvalidNewsletterMailingException();
			$error->Add(new Exceptions\InvalidNewsletterSendOnException());
			throw $error;
		}

		$this->NewsletterMailingId = Db::QueryInt('INSERT into NewsletterMailings (NewsletterId, Subject, BodyHtml, BodyText, Status, FromName, FromEmail, SendOn, InternalName) values (?, ?, ?, ?, ?, ?, ?, ?, ?) returning NewsletterMailingId', [$this->NewsletterId, $this->Subject, $this->BodyHtml, $this->BodyText, Enums\QueueStatus::Queued, $this->FromName, $this->FromEmail, $this->SendOn, $this->InternalName]);
	}

	/**
	 * @throws Exceptions\InvalidNewsletterMailingException If the `NewsletterMailing` is invalid.
	 */
	public function Validate(bool $addFooter): void{
		$error = new Exceptions\InvalidNewsletterMailingException();

		$this->BodyHtml = str_replace('\'', '’', $this->BodyHtml);
		$this->BodyText = trim(str_replace('\'', '’', $this->BodyText));
		$this->FromName = $this->FromName !== null ? trim($this->FromName) : null;
		$this->FromEmail ??= '';

		// If we received only HTML or only text, convert to one from the other.
		if($this->BodyHtml != '' && $this->BodyText == ''){
			if(mb_stripos($this->BodyHtml, '<body') === false){
				$this->BodyHtml = Template::NewsletterMailingHtml(bodyHtml: $this->BodyHtml, subject: $this->Subject);
			}

			$this->BodyText = Formatter::HtmlToMarkdown($this->BodyHtml);
		}

		if($this->BodyText != '' && $this->BodyHtml == ''){
			$this->BodyHtml = Template::NewsletterMailingHtml(bodyHtml: Formatter::MarkdownToHtml($this->BodyText), subject: $this->Subject);
		}

		if($addFooter){
			$footerHtml = Template::EmailMarketingFooterElement(newsletter: $this->Newsletter);

			$footerText = "\n" . Template::EmailMarketingFooterText(newsletter: $this->Newsletter);

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
			$this->BodyHtml->Validate();
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
			$this->BodyHtml = preg_replace('/<title>.+?<\/title>/ius', '<title>' . Formatter::EscapeHtml($this->Subject) . '</title>', (string)$this->BodyHtml);
		}

		$this->InternalName = trim($this->InternalName ?? '');

		if($this->InternalName == ''){
			$this->InternalName = null;
		}

		$this->Subject = trim($this->Subject);

		if($this->Subject == ''){
			$error->Add(new Exceptions\FieldMissingException('No email subject specified.'));
		}

		if($this->BodyText == ''){
			$error->Add(new Exceptions\FieldMissingException('Newsletter text body is empty.'));
		}

		if($this->FromEmail == ''){
			$error->Add(new Exceptions\FieldMissingException('Newsletter from email is empty.'));
		}
		else{
			try{
				$this->FromEmail->Validate();
			}
			catch(Exceptions\InvalidEmailAddressException){
				$error->Add(new Exceptions\InvalidEmailAddressException('Invalid email: ' . $this->FromEmail));
			}
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

		if(mb_stripos($this->BodyHtml, NEWSLETTER_UNSUBSCRIBE_URL_VARIABLE) === false){
			$error->Add(new Exceptions\FieldInvalidException('Newsletter HTML missing unsubscribe URL variable:  ' . NEWSLETTER_UNSUBSCRIBE_URL_VARIABLE . '.'));
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


	// ***********
	// ORM METHODS
	// ***********

	/**
	 * @throws Exceptions\NewsletterMailingNotFoundException If the `NewsletterMailing` can't be found.
	 */
	public static function Get(?int $newsletterMailingId): NewsletterMailing{
		if($newsletterMailingId === null){
			throw new Exceptions\NewsletterMailingNotFoundException();
		}

		return Db::Query('SELECT * from NewsletterMailings where NewsletterMailingId = ?', [$newsletterMailingId], NewsletterMailing::class)[0] ?? throw new Exceptions\NewsletterMailingNotFoundException();
	}

	public function FillFromHttpPost(): void{
		$this->PropertyFromHttp('NewsletterId');
		$this->PropertyFromHttp('FromName');
		$this->PropertyFromHttp('FromEmail');
		$this->PropertyFromHttp('Subject');
		$this->PropertyFromHttp('InternalName');
		$this->PropertyFromHttp('BodyText');
		$this->PropertyFromHttp('Status');

		if(isset($_POST['newsletter-mailing-body-html'])){
			$this->BodyHtml = HttpInput::Str(POST, 'newsletter-mailing-body-html') ?? '';
		}

		// `SendOn` is always interpreted as being sent in the `America/Chicago` timezone.
		// Therefore we have to do some gynmastics to store it as UTC in our object.
		$sendOn = HttpInput::Str(POST, 'newsletter-mailing-send-on');
		if($sendOn !== null){
			/** @throws void */
			$this->SendOn = (new DateTimeImmutable($sendOn, SITE_TZ))->setTimezone(new DateTimeZone('UTC'));
		}
	}
}
