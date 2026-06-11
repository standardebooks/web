<?
use Safe\DateTimeImmutable;

use function Safe\preg_match;
use function Safe\preg_match_all;
use function Safe\preg_replace;

/**
 * @property User $User
 * @property-read string $Url
 * @property-read string $EditUrl
 * @property array<Ebook> $Ebooks
 * @property-read string $EbookIdentifiers A newline-separated list of `Ebook` identifiers related to this `BlogPost`.
 * @property-read HtmlFragment $Title
 * @property-write HtmlFragment|string $Title
 * @property-read ?HtmlFragment $Subtitle
 * @property-read ?string $Excerpt
 * @property-write HtmlFragment|string|null $Subtitle
 * @property-read ?HtmlFragment $Body May be `null` if the `BlogPost` is meant to redirect to a file, like the Public Domain Day posts.
 * @property-write HtmlFragment|string|null $Body
 */
class BlogPost{
	use Traits\Accessor;
	use Traits\PropertyFromRequest;

	public int $BlogPostId;
	public ?string $Description;
	public string $UrlTitle;
	public int $UserId;
	public DateTimeImmutable $Created;
	public DateTimeImmutable $Updated;
	public DateTimeImmutable $Published = NOW;

	protected string $_Url;
	protected string $_EditUrl;
	protected ?string $_Excerpt = null;
	protected User $_User;
	/** @var array<Ebook> */
	protected array $_Ebooks;
	protected string $_EbookIdentifiers;
	protected HtmlFragment $_Title; // TODO: Convert to property hook in PHP 8.4.
	protected ?HtmlFragment $_Subtitle; // TODO: Convert to property hook in PHP 8.4.
	protected ?HtmlFragment $_Body; // TODO: Convert to property hook in PHP 8.4.

	// *******
	// GETTERS
	// *******

	protected function GetExcerpt(): ?string{
		if(!isset($this->_Excerpt)){
			if($this->Body !== null){
				$this->_Excerpt = substr(strip_tags($this->Body), 0, 200) . '…';
			}
			elseif(isset($this->Subtitle)){
				$this->_Excerpt = strip_tags($this->Subtitle);
			}
		}

		return $this->_Excerpt;
	}

	protected function GetUrl(): string{
		return $this->_Url ??= '/blog/' . $this->UrlTitle;
	}

	protected function GetEditUrl(): string{
		return $this->_EditUrl ??= $this->Url . '/edit';
	}

	protected function GetEbookIdentifiers(): string{
		if(!isset($this->_EbookIdentifiers)){
			$this->_EbookIdentifiers = '';
			foreach($this->Ebooks as $ebook){
				$this->_EbookIdentifiers .= $ebook->Identifier . "\n";
			}

			$this->_EbookIdentifiers = trim($this->_EbookIdentifiers);
		}

		return $this->_EbookIdentifiers;
	}

	/**
	 * @return array<Ebook>
	 */
	protected function GetEbooks(): array{
		if(isset($this->BlogPostId)){
			return $this->_Ebooks ??= Db::Query('SELECT Ebooks.* from Ebooks inner join BlogPostEbooks using (EbookId) where BlogPostId = ? order by BlogPostEbooks.SortOrder asc', [$this->BlogPostId], Ebook::class);
		}
		else{
			return $this->_Ebooks ??= [];
		}
	}


	// *******
	// SETTERS
	// *******

	protected function SetTitle(string|HtmlFragment $string): void{
		$this->_Title = new HtmlFragment($string);
	}

	protected function SetSubtitle(string|HtmlFragment|null $string): void{
		if(isset($string)){
			$this->_Subtitle = new HtmlFragment($string);
		}
		else{
			$this->_Subtitle = $string;
		}
	}

	protected function SetBody(string|HtmlFragment|null $string): void{
		if(isset($string)){
			$this->_Body = new HtmlFragment($string);
		}
		else{
			$this->_Body = $string;
		}
	}


	// *******
	// METHODS
	// *******

	/**
	 * @param ?string $userIdentifier
	 * @param ?string $ebookIdentifiers A newline-separated list of ebook identifiers to merge with any ebook identifiers found in the body.
	 *
	 * @throws Exceptions\BlogPostInvalidException
	 */
	public function Validate(?string $userIdentifier = null, ?string $ebookIdentifiers = null): void{
		$error = new Exceptions\BlogPostInvalidException();

		$this->Title ??= '';

		if($this->Title == ''){
			$error->Add(new Exceptions\BlogPostTitleRequiredException());
		}
		elseif(strlen($this->Title) > BLOG_POST_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Title'));
		}
		else{
			try{
				$this->Title->Validate();
			}
			catch(Exceptions\HtmlInvalidException $ex){
				$error->Add(new Exceptions\BlogPostTitleHtmlInvalidException($ex->RawMessage));
			}
		}

		$this->Subtitle ??= '';
		if($this->Subtitle == ''){
			$this->Subtitle = null;
		}
		else{
			try{
				$this->Subtitle->Validate();
			}
			catch(Exceptions\HtmlInvalidException $ex){
				$error->Add(new Exceptions\BlogPostSubtitleHtmlInvalidException($ex->RawMessage));
			}
		}

		$this->UrlTitle = Formatter::MakeUrlSafe(strip_tags($this->Title));

		$identifiers = explode("\n", $ebookIdentifiers ?? '');
		$this->Body ??= '';
		if($this->Body == ''){
			$this->Body = null;

			if(!file_exists(WEB_ROOT . '/blog-posts/' . $this->UrlTitle . '.php')){
				$error->Add(new Exceptions\BlogPostFileInvalidException());
			}
		}
		else{
			try{
				$this->Body->Validate();

				// Test the case where the fragment doesn't start with an element.
				if(!preg_match('/^</ius', $this->Body)){
					$error->Add(new Exceptions\HtmlInvalidException('Body must begin with an HTML element.'));
				}
			}
			catch(Exceptions\HtmlInvalidException $ex){
				$error->Add(new Exceptions\BlogPostBodyHtmlInvalidException($ex->RawMessage));
			}

			preg_match_all('/="((?:https:\/\/standardebooks.org)?\/ebooks\/[^\/"]+?\/[^"]+?)"/iu', $this->Body, $matches);

			foreach($matches[1] as $identifier){
				// Remove anchors or `/text/...` links
				$identifier = preg_replace('/(#.+|\/text|\/text\/.*)$/u', '', $identifier);

				// Add the full domain to URL if not present.
				$identifier = preg_replace('/^\//u', 'https://standardebooks.org/', $identifier);

				$identifiers[] = $identifier;
			}
		}

		foreach($this->Ebooks as $ebook){
			$identifiers[] = $ebook->Identifier;
		}

		$identifiers = array_unique($identifiers);

		$this->_Ebooks = [];
		foreach($identifiers as $identifier){
			if($identifier == ''){
				continue;
			}

			try{
				$this->_Ebooks[] = Ebook::GetByIdentifier($identifier);
			}
			catch(Exceptions\EbookNotFoundException){
				$error->Add(new Exceptions\EbookNotFoundException('Ebook not found: ' . $identifier));
			}
		}

		$this->Description = trim($this->Description ?? '');
		if($this->Description == ''){
			$this->Description = null;
		}

		if($userIdentifier !== null){
			try{
				$this->User = User::GetByIdentifier($userIdentifier);
				$this->UserId = $this->User->UserId;
			}
			catch(Exceptions\AmbiguousUserException | Exceptions\UserNotFoundException $ex){
				$error->Add($ex);
			}
		}
		else{
			if(!isset($this->UserId)){
				$error->Add(new Exceptions\BlogPostUserRequiredException());
			}
			else{
				try{
					User::Get($this->UserId);
				}
				catch(Exceptions\UserNotFoundException $ex){
					$error->Add($ex);
				}
			}
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * @throws Exceptions\BlogPostInvalidException
	 * @throws Exceptions\BlogPostExistsException
	 */
	public function Create(?string $userIdentifier = null, ?string $ebookIdentifiers = null): void{
		$this->Validate($userIdentifier, $ebookIdentifiers);
		$this->Created = NOW;

		try{
			$this->BlogPostId = Db::QueryInt('
				INSERT into BlogPosts (UserId, Title, Subtitle, Description, UrlTitle, Body, Published, Created)
				values (?,
				        ?,
				        ?,
				        ?,
				        ?,
				        ?,
				        ?,
				        ?)
				returning BlogPostId
			', [$this->UserId, $this->Title, $this->Subtitle, $this->Description, $this->UrlTitle, $this->Body, $this->Published, $this->Created]);
		}
		catch(Exceptions\DuplicateDatabaseKeyException){
			throw new Exceptions\BlogPostExistsException();
		}

		$i = 0;
		foreach($this->Ebooks as $ebook){
			Db::Query('INSERT into BlogPostEbooks (BlogPostId, EbookId, SortOrder) values (?, ?, ?)', [$this->BlogPostId, $ebook->EbookId, $i++]);
		}
	}

	/**
	 * @throws Exceptions\BlogPostInvalidException
	 * @throws Exceptions\BlogPostExistsException
	 */
	public function Save(?string $userIdentifier = null, ?string $ebookIdentifiers = null): void{
		$this->Validate($userIdentifier, $ebookIdentifiers);

		try{
			Db::Query('
				UPDATE BlogPosts
				set UserId = ?, Title = ?, Subtitle = ?, Description = ?, UrlTitle = ?, Body = ?, Published = ? where BlogPostId = ?
			', [$this->UserId, $this->Title, $this->Subtitle, $this->Description, $this->UrlTitle, $this->Body, $this->Published, $this->BlogPostId]);
		}
		catch(Exceptions\DuplicateDatabaseKeyException){
			throw new Exceptions\BlogPostExistsException();
		}

		Db::Query('DELETE from BlogPostEbooks where BlogPostId = ?', [$this->BlogPostId]);

		$i = 0;
		foreach($this->Ebooks as $ebook){
			Db::Query('INSERT into BlogPostEbooks (BlogPostId, EbookId, SortOrder) values (?, ?, ?)', [$this->BlogPostId, $ebook->EbookId, $i++]);
		}
	}

	public function FillFromRequestBody(): void{
		$this->PropertyFromRequest('Description');

		if(isset(Http::$Request->Body->Variables['blog-post-title'])){
			$this->Title = Http::$Request->Body->Get('blog-post-title') ?? '';
		}

		if(isset(Http::$Request->Body->Variables['blog-post-subtitle'])){
			$this->Subtitle = Http::$Request->Body->Get('blog-post-subtitle');
		}

		if(isset(Http::$Request->Body->Variables['blog-post-body'])){
			$this->Body = Http::$Request->Body->Get('blog-post-body');
		}

		// `Published` is always interpreted as being sent in the `America/Chicago` timezone.
		// Therefore we have to do some gymnastics to store it as UTC in our object.
		$published = Http::$Request->Body->Get('blog-post-published');
		if($published !== null){
			/** @throws void */
			$this->Published = (new DateTimeImmutable($published, SITE_TZ))->setTimezone(new DateTimeZone('UTC'));
		}
	}


	// ***********
	// ORM METHODS
	// ***********

	/**
	 * @throws Exceptions\BlogPostNotFoundException
	 */
	public static function GetByUrlTitle(?string $urlTitle): BlogPost{
		if($urlTitle === null){
			throw new Exceptions\BlogPostNotFoundException();
		}

		return Db::Query('
				SELECT *
				from BlogPosts
				where UrlTitle = ?
			', [$urlTitle], BlogPost::class)[0] ?? throw new Exceptions\BlogPostNotFoundException();
	}

	/**
	 * @return array<BlogPost>
	 */
	public static function GetAllByIsPublished(): array{
		return Db::Query('
			SELECT *
			from BlogPosts
			where Published < utc_timestamp()
			order by Published desc', [], BlogPost::class);
	}

	/**
	 * @return array<BlogPost>
	 */
	public static function GetAllByCreated(): array{
		return Db::Query('
			SELECT *
			from BlogPosts
			order by Created desc', [], BlogPost::class);
	}
}
