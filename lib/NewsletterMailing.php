<?
use Safe\DateTimeImmutable;

use function Safe\preg_match;
use function Safe\preg_match_all;
use function Safe\preg_replace;
use function Safe\simplexml_load_string;


/**
 * @property Newsletter $Newsletter
 * @property string $Url
 * @property string $EditUrl
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
	public ?string $Preheader = null;
	public string $BodyText = '';
	public Enums\QueueStatus $Status;
	public ?string $FromName = null;
	public DateTimeImmutable $SendOn;
	public ?int $RecipientCount = null;
	/** @var array<string> */
	public array $Emails = [];
	public ?string $InternalName = null;
	public ?int $OpenCount = null;
	public bool $ExcludePatrons = false;
	public DateTimeImmutable $Created;
	public DateTimeImmutable $Updated;

	protected Newsletter $_Newsletter;
	protected string $_Url;
	protected string $_EditUrl;
	/** @var array<NewsletterSubscription> $_Recipients */
	protected array $_Recipients;
	protected HtmlDocument $_BodyHtml; // Should be converted to property hooks when PHP 8.4 is available; also see `FillFromHttpPost()`.
	protected EmailAddress $_FromEmail; // Should be converted to property hooks when PHP 8.4 is available; also see `FillFromHttpPost()`.

	public function __construct(){
		$this->_FromEmail = new EmailAddress(NEWSLETTER_DEFAULT_FROM_EMAIL_ADDRESS);
	}

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
			$this->_Url = '/newsletter-mailings/' . $this->NewsletterMailingId;
		}

		return $this->_Url;
	}

	protected function GetEditUrl(): string{
		if(!isset($this->_EditUrl)){
			$this->_EditUrl = $this->Url . '/edit';
		}

		return $this->_EditUrl;
	}

	/**
	 * @return array<NewsletterSubscription>
	 */
	protected function GetRecipients(): array{
		if(!isset($this->Recipients)){
			if($this->ExcludePatrons){
				$this->_Recipients = Db::MultiTableSelect('
					SELECT *
					from NewsletterSubscriptions
					inner join Users
					on NewsletterSubscriptions.UserId = Users.UserId
					left outer join Patrons
					on Users.UserId = Patrons.UserId
					where
						NewsletterId = ?
						and
						IsConfirmed = true
						and
						NewsletterSubscriptions.IsVisible = true
						and CanReceiveEmail = true
						and Patrons.Ended is null
					', [$this->NewsletterId], NewsletterSubscription::class);
			}
			else{
				$this->_Recipients = Db::MultiTableSelect('
					SELECT *
					from NewsletterSubscriptions
					inner join Users
					on NewsletterSubscriptions.UserId = Users.UserId
					where
						NewsletterId = ?
						and
						IsConfirmed = true
						and
						NewsletterSubscriptions.IsVisible = true
						and CanReceiveEmail = true
					', [$this->NewsletterId], NewsletterSubscription::class);
			}
		}

		return $this->_Recipients;
	}


	// *******
	// SETTERS
	// *******

	protected function SetBodyHtml(string|HtmlDocument $string): void{
		$this->_BodyHtml = new HtmlDocument($string);
	}

	protected function SetFromEmail(string|EmailAddress $string): void{
		$this->_FromEmail = new EmailAddress($string);
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
			$this->Validate();

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
					$em->BodyHtml = str_replace(NEWSLETTER_FIRST_NAME_VARIABLE, Formatter::EscapeHtml($newsletterSubscription->User->FirstName), $em->BodyHtml);
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

	protected function AddFooterToBody(): void{
		$footerHtml = Template::EmailMarketingFooterElement(newsletter: $this->Newsletter);

		$footerText = "\n" . Template::EmailMarketingFooterText(newsletter: $this->Newsletter);

		// Remove any existing footers.
		$this->BodyHtml = preg_replace('/(<div class="footer">.+?<\/div>|<footer>.+?<\/footer>)/ius', '', (string)$this->BodyHtml);

		$this->BodyHtml = str_ireplace('</body>', $footerHtml . '</body>', $this->BodyHtml);

		// Remove any existing footers.
		$this->BodyText = preg_replace('/\* \* \*.+/ius', '', (string)$this->BodyText);
		$this->BodyText .= "\n\n" . $footerText;
	}

	/**
	 * @throws Exceptions\FieldMissingException If a footer can't be found in `BodyHtml`.
	 * @throws Exceptions\EbookNotFoundException If an ebook identifier in the `BodyHtml` doesn't resolve to a real `Ebook`.
	 */
	protected function AddEbooksToBody(): void{
		if(!preg_match('/<div class="footer|<footer\b/ius', $this->BodyHtml)){
			throw new Exceptions\FieldMissingException('No footer found, but a footer is required to add ebooks.');
		}
		else{
			$identifiers = [];
			preg_match_all('/="((?:https:\/\/standardebooks.org)?\/ebooks\/[^\/"]+?\/[^"]+?)"/iu', $this->BodyHtml, $matches);

			foreach($matches[1] as $identifier){
				// Remove anchors or `/text/...` links
				$identifier = preg_replace('/(#.+|\/text|\/text\/.*)$/u', '', $identifier);

				// Add the full domain to URL if not present.
				$identifier = preg_replace('/^\//u', 'https://standardebooks.org/', $identifier);

				$identifiers[] = $identifier;
			}

			$identifiers = array_unique($identifiers);
			$ebooks = [];
			$missingEbooks = '';

			foreach($identifiers as $identifier){
				if($identifier == ''){
					continue;
				}

				try{
					$ebooks[] = Ebook::GetByIdentifier($identifier);
				}
				catch(Exceptions\EbookNotFoundException){
					$missingEbooks .= 'Ebook not found: ' . $identifier . "\n";
				}
			}

			if($missingEbooks != ''){
				throw new Exceptions\EbookNotFoundException($missingEbooks);
			}

			$carouselHtml = '';
			$carouselText = '';
			if(sizeof($ebooks) > 0){
				$carouselHtml = '<h2 id="ebooks-in-this-newsletter">Free ebooks in this newsletter</h2>' . "\n" . '<ul class="featured-ebooks">' . "\n";
				$carouselText = '## Free ebooks in this newsletter' . "\n\n";
				foreach($ebooks as $ebook){
					$carouselHtml .= '<li>
								<a href="' . SITE_URL . $ebook->Url . '">
									<img src="' . SITE_URL . $ebook->CoverImage2xUrl . '" alt="'
									 . Formatter::EscapeHtml(strip_tags($ebook->TitleWithCreditsHtml)) . '" />
								</a>
							</li>';

					$carouselText .= '- [' . Formatter::EscapeMarkdown(strip_tags($ebook->TitleWithCreditsHtml)) . '](' . SITE_URL . $ebook->Url . ')' . "\n\n";
				}
				$carouselHtml .= "\n" . '</ul>';
			}

			// Remove any existing ebook carousel and add the new one in.
			$this->BodyHtml = preg_replace('/<h2 id="ebooks-in-this-newsletter">Free ebooks in this newsletter<\/h2>/ius', '', (string)$this->BodyHtml);
			$this->BodyHtml = preg_replace('/<ul class="featured-ebooks">.+?<\/ul>/ius', '', (string)$this->BodyHtml);
			$this->BodyHtml = preg_replace('/(<div class="footer">|<footer>)/ius', $carouselHtml . "\n" . '\1', (string)$this->BodyHtml, 1);

			$this->BodyText = preg_replace('/\#\# Free ebooks in this newsletter.+\* \* \*/ius', '* * *', $this->BodyText);
			$this->BodyText = preg_replace('/\* \* \*/ius', $carouselText . '* * *', $this->BodyText);
		}
	}

	/**
	 * @throws Exceptions\FieldMissingException If a footer can't be found in `BodyHtml`.
	 * @throws Exceptions\EbookNotFoundException If an ebook identifier in the `BodyHtml` doesn't resolve to a real `Ebook`.
	 */
	protected function NormalizeBody(bool $addFooter, bool $addEbooks): void{
		// Insert or remove preheader.
		$this->BodyHtml = preg_replace('/<p class="preheader">[^<]+?<\/p>/ius', '', (string)$this->BodyHtml);

		// If we received only text, convert to HTML.
		if($this->BodyText != '' && $this->BodyHtml == ''){
			$this->BodyHtml = Template::NewsletterMailingHtml(bodyHtml: Formatter::MarkdownToHtml($this->BodyText), subject: $this->Subject);
		}

		// If we received only HTML, convert to text.
		if($this->BodyHtml != '' && $this->BodyText == ''){
			if(mb_stripos($this->BodyHtml, '<body') === false){
				$this->BodyHtml = Template::NewsletterMailingHtml(bodyHtml: $this->BodyHtml, subject: $this->Subject);
			}

			$this->BodyText = Formatter::HtmlToMarkdown($this->BodyHtml);
		}

		if(!preg_match('/^<!doctype html>/ius', $this->BodyHtml)){
			$this->BodyHtml = Template::NewsletterMailingHtml(bodyHtml: $this->BodyHtml, subject: $this->Subject);
		}

		if($this->Preheader !== null){
			$this->BodyHtml = preg_replace('/<body([^>]*?)>\s*/ius', '<body\1>' . "\n\t" . '<p class="preheader">' . Formatter::EscapeHtml($this->Preheader) . '</p>' . "\n\t", (string)$this->BodyHtml);

			// Add preheader CSS so that we don't remove it later.
			$this->BodyHtml = preg_replace('/\s*\.preheader\s*{[^}]+?}\s*/ius', '', (string)$this->BodyHtml);
			$this->BodyHtml = preg_replace('/body\s*{/ius', "\n\n\t\t" . '.preheader{
			display: none !important;
			visibility: hidden;
			mso-hide: all;
			font-size: 1px;
			color: #ffffff;
			line-height: 1px;
			height: 0;
			width: 0;
			opacity: 0;
			overflow: hidden;
			position: absolute;
			top: -9999px;
		}

		body{', (string)$this->BodyHtml);
		}

		if($addFooter){
			$this->AddFooterToBody();
		}

		if($addEbooks){
			$this->AddEbooksToBody();
		}
	}

	/**
	 * @throws Exceptions\InvalidNewsletterMailingException If the `NewsletterMailing` is invalid.
	 */
	public function Save(bool $addFooter, bool $addEbooks): void{
		$error = new Exceptions\InvalidNewsletterMailingException();

		try{
			$this->NormalizeBody($addFooter, $addEbooks);
		}
		catch(Exceptions\AppException $ex){
			$error->Add($ex);
		}

		try{
			$this->Validate();
		}
		catch(Exceptions\InvalidNewsletterMailingException $ex){
			$error->Add($ex);
		}

		if($error->HasExceptions){
			throw $error;
		}

		Db::Query('UPDATE NewsletterMailings set NewsletterId = ?, ExcludePatrons = ?, Subject = ?, Preheader = ?, BodyHtml = ?, BodyText = ?, Status = ?, FromName = ?, FromEmail = ?, SendOn = ?, InternalName = ? where NewsletterMailingId = ?', [$this->NewsletterId, $this->ExcludePatrons, $this->Subject, $this->Preheader, $this->BodyHtml, $this->BodyText, $this->Status, $this->FromName, $this->FromEmail, $this->SendOn, $this->InternalName, $this->NewsletterMailingId]);
	}

	/**
	 * @throws Exceptions\InvalidNewsletterMailingException If the `NewsletterMailing` is invalid.
	 */
	public function Create(bool $addFooter, bool $addEbooks): void{
		$error = new Exceptions\InvalidNewsletterMailingException();

		try{
			$this->NormalizeBody($addFooter, $addEbooks);
		}
		catch(Exceptions\AppException $ex){
			$error->Add($ex);
		}

		try{
			$this->Validate();
		}
		catch(Exceptions\InvalidNewsletterMailingException $ex){
			$error->Add($ex);
		}

		if($error->HasExceptions){
			throw $error;
		}

		// Only check this when creating.
		if($this->SendOn < NOW){
			$error->Add(new Exceptions\InvalidNewsletterSendOnException());
		}

		if($error->HasExceptions){
			throw $error;
		}

		$this->NewsletterMailingId = Db::QueryInt('INSERT into NewsletterMailings (NewsletterId, ExcludePatrons, Subject, Preheader, BodyHtml, BodyText, Status, FromName, FromEmail, SendOn, InternalName) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) returning NewsletterMailingId', [$this->NewsletterId, $this->ExcludePatrons, $this->Subject, $this->Preheader, $this->BodyHtml, $this->BodyText, Enums\QueueStatus::Queued, $this->FromName, $this->FromEmail, $this->SendOn, $this->InternalName]);
	}

	/**
	 * @throws Exceptions\InvalidNewsletterMailingException If the `NewsletterMailing` is invalid.
	 */
	public function Validate(): void{
		$error = new Exceptions\InvalidNewsletterMailingException();

		$this->BodyHtml = str_replace('\'', '’', $this->BodyHtml);
		$this->BodyText = trim(str_replace('\'', '’', $this->BodyText));
		$this->FromName = $this->FromName !== null ? trim($this->FromName) : null;
		$this->FromEmail ??= '';

		if(!preg_match("/^<!DOCTYPE html>/ius", (string)$this->BodyHtml)){
			$this->BodyHtml = Template::NewsletterMailingHtml(bodyHtml: $this->BodyHtml, subject: $this->Subject);
		}

		if(mb_stripos($this->BodyText, '.test') !== false){
			$error->Add(new Exceptions\FieldInvalidException('Newsletter text contains .test TLD.'));
		}

		if(mb_stripos($this->BodyHtml, '.test') !== false){
			$error->Add(new Exceptions\FieldInvalidException('Newsletter HTML contains .test TLD.'));
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
			$matches = [];
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

		$this->Preheader = str_replace('\'', '’', trim($this->Preheader ?? ''));
		if($this->Preheader == ''){
			$this->Preheader = null;
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
				// Sometimes emits warnings, quiet them.
				$t = @new Gt\CssXPath\Translator($selector);

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

	/**
	 * @return array<NewsletterMailing>
	 */
	public static function GetAll(): array{
		return Db::Query('SELECT * from NewsletterMailings order by SendOn desc', [], NewsletterMailing::class);
	}

	public function FillFromHttpPost(): void{
		$this->PropertyFromHttp('NewsletterId');
		$this->PropertyFromHttp('FromName');
		$this->PropertyFromHttp('Subject');
		$this->PropertyFromHttp('InternalName');
		$this->PropertyFromHttp('BodyText');
		$this->PropertyFromHttp('Status');
		$this->PropertyFromHttp('Preheader');
		$this->PropertyFromHttp('ExcludePatrons');

		if(isset($_POST['newsletter-mailing-body-html'])){
			$this->BodyHtml = HttpInput::Str(POST, 'newsletter-mailing-body-html') ?? '';
		}

		if(isset($_POST['newsletter-mailing-from-email'])){
			$this->FromEmail = HttpInput::Str(POST, 'newsletter-mailing-from-email') ?? '';
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
