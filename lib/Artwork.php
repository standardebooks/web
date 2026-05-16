<?
use Safe\DateTimeImmutable;

use function Safe\copy;
use function Safe\exec;
use function Safe\getimagesize;
use function Safe\parse_url;
use function Safe\preg_match;
use function Safe\preg_replace;
use function Safe\preg_split;
use function Safe\unlink;

/**
 * @property string $UrlName
 * @property-read string $Url
 * @property-read string $EditUrl
 * @property-read array<ArtworkTag> $Tags
 * @property-write array<ArtworkTag>|string $Tags
 * @property Artist $Artist
 * @property string $ImageUrl
 * @property string $ThumbUrl
 * @property string $Thumb2xUrl
 * @property string $ImageFsPath
 * @property string $ThumbFsPath
 * @property string $Thumb2xFsPath
 * @property string $Dimensions
 * @property ?Ebook $Ebook
 * @property ?Museum $Museum
 * @property ?User $Submitter
 * @property ?User $Reviewer
 * @property-read ?Markdown $Exception
 * @property-write Markdown|string|null $Exception
 * @property-read ?Markdown $Notes
 * @property-write Markdown|string|null $Notes
 */
final class Artwork{
	use Traits\Accessor;
	use Traits\PropertyFromHttp;

	public int $ArtworkId;
	public string $Name = '';
	public int $ArtistId;
	public ?int $CompletedYear = null;
	public bool $CompletedYearIsCirca = false;
	public DateTimeImmutable $Created;
	public DateTimeImmutable $Updated;
	public ?int $EbookId = null;
	public ?int $SubmitterUserId = null;
	public ?int $ReviewerUserId = null;
	public ?string $MuseumUrl = null;
	public ?int $PublicationYear = null;
	public ?string $PublicationYearPageUrl = null;
	public ?string $CopyrightPageUrl = null;
	public ?string $ArtworkPageUrl = null;
	public bool $IsPublishedInUs = false;
	public Enums\ImageMimeType $MimeType;
	public Enums\ArtworkStatusType $Status = Enums\ArtworkStatusType::Unverified;
	public bool $IsAutoReviewed = false;

	protected string $_UrlName;
	protected string $_Url;
	protected string $_EditUrl;
	/** @var array<ArtworkTag> $_Tags */
	protected array $_Tags;
	protected Artist $_Artist;
	protected string $_ImageUrl;
	protected string $_ThumbUrl;
	protected string $_Thumb2xUrl;
	protected string $_Dimensions ;
	protected ?Ebook $_Ebook = null;
	protected ?Museum $_Museum;
	protected ?User $_Submitter;
	protected ?User $_Reviewer;
	protected ?Markdown $_Exception = null; // TODO: Convert to property hook in PHP 8.4.
	protected ?Markdown $_Notes = null; // TODO: Convert to property hook in PHP 8.4.


	// *******
	// SETTERS
	// *******

	/**
	 * @param string|null|array<ArtworkTag> $tags
	 */
	protected function SetTags(null|string|array $tags): void{
		if(is_array($tags)){
			$this->_Tags = $tags;
		}
		else{
			$tags = trim($tags ?? '');

			if($tags === ''){
				$this->_Tags = [];
			}
			else{
				$tags = array_map('trim', explode(',', $tags));
				$tags = array_values(array_filter($tags));
				$tags = array_unique($tags);

				$this->_Tags = array_map(function ($str): ArtworkTag{
					$tag = new ArtworkTag();
					$tag->Name = $str;
					return $tag;
				}, $tags);
			}
		}
	}

	protected function SetException(string|Markdown|null $string): void{
		if(isset($string)){
			$this->_Exception = new Markdown($string);
		}
		else{
			$this->_Exception = $string;
		}
	}

	protected function SetNotes(string|Markdown|null $string): void{
		if(isset($string)){
			$this->_Notes = new Markdown($string);
		}
		else{
			$this->_Notes = $string;
		}
	}


	// *******
	// GETTERS
	// *******

	protected function GetUrlName(): string{
		if(!isset($this->_UrlName)){
			if($this->Name == ''){
				$this->_UrlName = '';
			}
			else{
				$this->_UrlName = Formatter::MakeUrlSafe($this->Name);
			}
		}

		return $this->_UrlName;
	}

	protected function GetSubmitter(): ?User{
		if(!isset($this->_Submitter)){
			try{
				$this->_Submitter = User::Get($this->SubmitterUserId);
			}
			catch(Exceptions\UserNotFoundException){
				$this->Submitter = null;
			}
		}

		return $this->_Submitter;
	}

	protected function GetReviewer(): ?User{
		if(!isset($this->_Reviewer)){
			try{
				$this->_Reviewer = User::Get($this->ReviewerUserId);
			}
			catch(Exceptions\UserNotFoundException){
				$this->_Reviewer = null;
			}
		}

		return $this->_Reviewer;
	}

	protected function GetUrl(): string{
		return $this->_Url ??= '/artworks/' . $this->Artist->UrlName . '/' . $this->UrlName;
	}

	protected function GetEditUrl(): string{
		return $this->_EditUrl ??= $this->Url . '/edit';
	}

	/**
	 * @return array<ArtworkTag>
	 */
	protected function GetTags(): array{
		return $this->_Tags ??= Db::Query('
							SELECT t.*
							from Tags t
							inner join ArtworkTags at using (TagId)
							where ArtworkId = ?
						', [$this->ArtworkId], ArtworkTag::class);
	}

	/**
	 * @throws Exceptions\UrlInvalidException
	 */
	public function GetMuseum(): ?Museum{
		if(!isset($this->_Museum)){
			try{
				$this->_Museum = Museum::GetByUrl($this->MuseumUrl);
			}
			catch(Exceptions\MuseumNotFoundException){
				$this->_Museum = null;
			}
		}

		return $this->_Museum;
	}

	/**
	 * @throws Exceptions\ArtworkInvalidException
	 */
	protected function GetImageUrl(): string{
		if(!isset($this->_ImageUrl)){
			if(!isset($this->ArtworkId) || !isset($this->MimeType)){
				throw new Exceptions\ArtworkInvalidException();
			}

			$this->_ImageUrl = COVER_ART_UPLOAD_PATH . $this->ArtworkId . $this->MimeType->GetFileExtension() . '?ts=' . $this->Updated->getTimestamp();
		}

		return $this->_ImageUrl;
	}

	/**
	 * @throws Exceptions\ArtworkNotFoundException
	 */
	protected function GetThumbUrl(): string{
		if(!isset($this->_ThumbUrl)){
			if(!isset($this->ArtworkId)){
				throw new Exceptions\ArtworkNotFoundException();
			}

			$this->_ThumbUrl = COVER_ART_UPLOAD_PATH . $this->ArtworkId . '-thumb.jpg' . '?ts=' . $this->Updated->getTimestamp();
		}

		return $this->_ThumbUrl;
	}

	/**
	 * @throws Exceptions\ArtworkNotFoundException
	 */
	protected function GetThumb2xUrl(): string{
		if(!isset($this->_Thumb2xUrl)){
			if(!isset($this->ArtworkId)){
				throw new Exceptions\ArtworkNotFoundException();
			}

			$this->_Thumb2xUrl = COVER_ART_UPLOAD_PATH . $this->ArtworkId . '-thumb@2x.jpg' . '?ts=' . $this->Updated->getTimestamp();
		}

		return $this->_Thumb2xUrl;
	}

	protected function GetImageFsPath(): string{
		return WEB_ROOT . preg_replace('/\?[^\?]*$/ius', '', $this->ImageUrl);
	}

	protected function GetThumbFsPath(): string{
		return WEB_ROOT . preg_replace('/\?[^\?]*$/ius', '', $this->ThumbUrl);
	}

	protected function GetThumb2xFsPath(): string{
		return WEB_ROOT . preg_replace('/\?[^\?]*$/ius', '', $this->Thumb2xUrl);
	}

	protected function GetDimensions(): string{
		if(!isset($this->Dimensions)){
			$this->_Dimensions = '';
			try{
				list($imageWidth, $imageHeight) = (@getimagesize($this->ImageFsPath) ?? throw new \Exception());
				if($imageWidth && $imageHeight){
					$this->_Dimensions = number_format($imageWidth) . ' × ' . number_format($imageHeight);
				}
			}
			catch(Exception){
				// Image doesn't exist, return a blank string.
			}
		}

		return $this->_Dimensions;
	}


	// *******
	// METHODS
	// *******

	public function CanBeEditedBy(?User $user): bool{
		if($user === null){
			return false;
		}

		if($user->Benefits->CanReviewOwnArtwork){
			// Admins can edit all artwork.
			return true;
		}

		if(($user->Benefits->CanReviewArtwork || $user->UserId == $this->SubmitterUserId) && ($this->Status == Enums\ArtworkStatusType::Unverified || $this->Status == Enums\ArtworkStatusType::Declined)){
			// Editors can edit an artwork, and submitters can edit their own artwork, if it's not yet approved.
			return true;
		}

		return false;
	}

	public function CanStatusBeChangedBy(?User $user): bool{
		if($user === null){
			return false;
		}

		if($user->Benefits->CanReviewOwnArtwork){
			// Admins can change the status of all artwork.
			return true;
		}

		if($user->Benefits->CanReviewArtwork && $user->UserId != $this->SubmitterUserId && ($this->Status == Enums\ArtworkStatusType::Unverified || $this->Status == Enums\ArtworkStatusType::Declined)){
			// Editors can change the status of artwork they did not submit themselves, and that is not yet approved.
			return true;
		}

		return false;
	}

	public function CanEbookUrlBeChangedBy(?User $user): bool{
		if($user === null){
			return false;
		}

		if($user->Benefits->CanReviewArtwork || $user->Benefits->CanReviewOwnArtwork){
			// Admins and editors can change the file system path of all artwork.
			return true;
		}

		return false;
	}

	/**
	 * @throws Exceptions\ArtworkInvalidException
	 */
	protected function Validate(?string $imagePath = null, bool $isImageRequired = true): void{
		$thisYear = intval(NOW->format('Y'));
		$error = new Exceptions\ArtworkInvalidException();

		if(!isset($this->Artist)){
			$error->Add(new Exceptions\ArtistInvalidException());
		}
		else{
			try{
				$this->Artist->Validate();
			}
			catch(Exceptions\ValidationException $ex){
				$error->Add($ex);
			}
		}

		if($this->Exception == ''){
			$this->Exception = null;
		}

		if($this->Notes == ''){
			$this->Notes = null;
		}

		$this->Name = trim($this->Name);

		if($this->Name == ''){
			$error->Add(new Exceptions\ArtworkNameRequiredException());
		}

		if(strlen($this->Name) > ARTWORK_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Artwork Name'));
		}

		if(isset($this->CompletedYear) && ($this->CompletedYear <= 0 || $this->CompletedYear > $thisYear)){
			$error->Add(new Exceptions\CompletedYearInvalidException());
		}

		if($this->CompletedYear === null && $this->CompletedYearIsCirca){
			$this->CompletedYearIsCirca = false;
		}

		if(isset($this->PublicationYear) && ($this->PublicationYear <= 0 || $this->PublicationYear > $thisYear)){
			$error->Add(new Exceptions\PublicationYearInvalidException());
		}

		$this->Tags ??= [];

		if(sizeof($this->Tags) == 0){
			$error->Add(new Exceptions\TagsRequiredException());
		}

		if(sizeof($this->Tags) > ARTWORK_MAX_TAGS){
			$error->Add(new Exceptions\TooManyTagsException());
		}

		foreach($this->Tags as $tag){
			try{
				$tag->Validate();
			}
			catch(Exceptions\ValidationException $ex){
				$error->Add($ex);
			}
		}

		$this->MuseumUrl = trim($this->MuseumUrl ?? '');

		if($this->MuseumUrl != ''){
			if(strlen($this->MuseumUrl) > ARTWORK_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Link to an approved museum page'));
			}

			try{
				$this->Museum = Museum::GetByUrl($this->MuseumUrl);
				$this->MuseumUrl = Museum::NormalizeUrl($this->MuseumUrl);
			}
			catch(Exceptions\MuseumNotFoundException | Exceptions\UrlInvalidException $ex){
				$error->Add($ex);
			}
		}
		else{
			$this->MuseumUrl = null;
		}

		$this->PublicationYearPageUrl = trim($this->PublicationYearPageUrl ?? '');

		if($this->PublicationYearPageUrl != ''){
			if(strlen($this->PublicationYearPageUrl) > ARTWORK_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Link to page with year of publication'));
			}

			if(filter_var($this->PublicationYearPageUrl, FILTER_VALIDATE_URL) === false){
				$error->Add(new Exceptions\PublicationYearPageUrlInvalidException());
			}
			else{
				try{
					$this->PublicationYearPageUrl = Artwork::NormalizePageScanUrl($this->PublicationYearPageUrl);
				}
				catch(Exceptions\UrlInvalidException $ex){
					$error->Add($ex);
				}
			}
		}
		else{
			$this->PublicationYearPageUrl = null;
		}

		$this->CopyrightPageUrl = trim($this->CopyrightPageUrl ?? '');

		if($this->CopyrightPageUrl != ''){
			if(strlen($this->CopyrightPageUrl) > ARTWORK_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Link to page with copyright details'));
			}

			if(filter_var($this->CopyrightPageUrl, FILTER_VALIDATE_URL) === false){
				$error->Add(new Exceptions\CopyrightPageUrlInvalidException());
			}
			else{
				try{
					$this->CopyrightPageUrl = Artwork::NormalizePageScanUrl($this->CopyrightPageUrl);
				}
				catch(Exceptions\UrlInvalidException $ex){
					$error->Add($ex);
				}
			}
		}
		else{
			$this->CopyrightPageUrl = null;
		}

		$this->ArtworkPageUrl = trim($this->ArtworkPageUrl ?? '');

		if($this->ArtworkPageUrl != ''){
			if(strlen($this->ArtworkPageUrl) > ARTWORK_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Link to page with artwork'));
			}

			if(filter_var($this->ArtworkPageUrl, FILTER_VALIDATE_URL) === false){
				$error->Add(new Exceptions\ArtworkPageUrlInvalidException());
			}
			else{
				try{
					$this->ArtworkPageUrl = Artwork::NormalizePageScanUrl($this->ArtworkPageUrl);
				}
				catch(Exceptions\UrlInvalidException $ex){
					$error->Add($ex);
				}
			}
		}
		else{
			$this->ArtworkPageUrl = null;
		}

		$hasMuseumProof = $this->MuseumUrl !== null;
		$hasBookProof = $this->PublicationYear !== null && $this->PublicationYearPageUrl !== null && $this->ArtworkPageUrl !== null;

		if(!$hasMuseumProof && !$hasBookProof && $this->Exception === null){
			$error->Add(new Exceptions\MissingPdProofException());
		}

		if(isset($this->EbookId)){
			try{
				Ebook::Get($this->EbookId);

				// Ebook found, continue.

				// Is this `Ebook` URL already attached to a different `Artwork`?
				if(isset($this->ArtworkId)){
					$exists = Db::QueryBool('SELECT exists (select * from Artworks where EbookId = ? and ArtworkId != ?)', [$this->EbookId, $this->ArtworkId]);
				}
				else{
					$exists = Db::QueryBool('SELECT exists (select * from Artworks where EbookId = ?)', [$this->EbookId]);
				}

				if($exists){
					$error->Add(new Exceptions\ArtworkAssignedException());
				}
			}
			catch(Exceptions\EbookNotFoundException){
				// Ebook not found, error!
				$error->Add(new Exceptions\EbookNotFoundException('Couldn’t find ebook #' . $this->EbookId));
			}
		}

		// Check for existing `Artwork` objects with the same URL but different `ArtworkId`s.
		if(isset($this->ArtworkId)){
			try{
				$artwork = Artwork::GetByUrl($this->Artist->UrlName, $this->UrlName);
				if($artwork->ArtworkId != $this->ArtworkId){
					$error->Add(new Exceptions\ArtworkExistsException());
				}
			}
			catch(Exceptions\ArtworkNotFoundException){
				// Pass.
			}
		}

		if($isImageRequired || $imagePath !== null){
			if($imagePath === null){
				$error->Add(new Exceptions\ImageUploadInvalidException('An image is required.'));
			}
			else{
				try{
					$this->MimeType = Enums\ImageMimeType::FromFile($imagePath) ?? throw new Exceptions\MimeTypeInvalidException();
				}
				catch(Exceptions\MimeTypeInvalidException $ex){
					$error->Add($ex);
				}

				if(!is_writable(WEB_ROOT . COVER_ART_UPLOAD_PATH)){
					$error->Add(new Exceptions\ImageUploadInvalidException('Upload path not writable.'));
				}

				// Check for minimum dimensions.
				try{
					list($imageWidth, $imageHeight) = (@getimagesize($imagePath) ?? throw new \Exception());
					if(!$imageWidth || !$imageHeight || $imageWidth < ARTWORK_IMAGE_MINIMUM_WIDTH || $imageHeight < ARTWORK_IMAGE_MINIMUM_HEIGHT){
						$error->Add(new Exceptions\ArtworkImageDimensionsTooSmallException());
					}
				}
				catch(\Exception){
					$error->Add(new Exceptions\ImageUploadInvalidException());
				}
			}
		}


		if($error->HasExceptions){
			throw $error;
		}
	}

	public function ImplodeTags(): string{
		$tags = $this->Tags ?? [];
		$tags = array_column($tags, 'Name');
		return trim(implode(', ', $tags));
	}

	/**
	 * Updates an artwork from `Unverified` status to `Approved` if the artwork has a valid `MuseumUrl` and the page contents of that URL contain the museum’s `LicenseXPath`.
	 */
	public function ApproveByMuseumUrl(): void{
		if($this->Status !== Enums\ArtworkStatusType::Unverified){
			return;
		}

		if(!isset($this->MuseumUrl) || !isset($this->Museum) || !isset($this->Museum->LicenseXPath)){
			return;
		}

		$curl = new HttpRequest();
		try{
			$response = $curl->Execute(Enums\HttpMethod::Get, $this->MuseumUrl);
		}
		catch(Exceptions\HttpRequestException){
			return;
		}

		if($response->HttpCode != Enums\HttpCode::Ok){
			return;
		}

		// TODO: When PHP 8.4 is available, use the new `Dom\HTMLDocument` class.
		$dom = new DOMDocument();
		@$dom->loadHTML($response->Body);
		$xpath = new DOMXPath($dom);

		if($xpath->evaluate($this->Museum->LicenseXPath)){
			$this->Status = Enums\ArtworkStatusType::Approved;
			$this->ReviewerUserId = null;
			$this->Reviewer = null;
			$this->IsAutoReviewed = true;
		}
	}

	/**
	 * @throws Exceptions\UrlInvalidException
	 * @throws Exceptions\PageScanUrlInvalidException
	 */
	public static function NormalizePageScanUrl(string $url): string{
		$outputUrl = $url;

		// Before we start, replace global Google TLDs like `google.ca` with `google.com`.
		$url = preg_replace('|^(https://[^/]+?\.google)\.[^/]+/|ius', '\1.com/', $url);

		try{
			$parsedUrl = parse_url($url);
		}
		catch(Exception){
			throw new Exceptions\UrlInvalidException($url);
		}

		if(!is_array($parsedUrl)){
			throw new Exceptions\UrlInvalidException($url);
		}

		if(stripos($parsedUrl['host'], 'hathitrust.org') !== false){
			$exampleUrl = 'https://babel.hathitrust.org/cgi/pt?id=hvd.32044034383265&seq=13';

			if($parsedUrl['host'] != 'babel.hathitrust.org'){
				throw new Exceptions\PageScanUrlInvalidException($url, $exampleUrl);
			}

			if($parsedUrl['path'] != '/cgi/pt'){
				throw new Exceptions\PageScanUrlInvalidException($url, $exampleUrl);
			}

			parse_str($parsedUrl['query'] ?? '', $vars);

			if(!isset($vars['id']) || !isset($vars['seq']) || is_array($vars['id']) || is_array($vars['seq'])){
				throw new Exceptions\PageScanUrlInvalidException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'] . '?id=' . $vars['id'] . '&view=1up&seq=' . $vars['seq'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'archive.org') !== false){
			$exampleUrl = 'https://archive.org/details/royalacademypict1902roya/page/n9/mode/1up';

			if($parsedUrl['host'] != 'archive.org'){
				throw new Exceptions\PageScanUrlInvalidException($url, $exampleUrl);
			}

			// If we're missing the view mode, append it.
			if(preg_match('|^/details/[^/]+?/page/[^/]+$|ius', $parsedUrl['path'])){
				$parsedUrl['path'] = $parsedUrl['path'] . '/mode/1up';
			}

			// Internet Archive URLs may have both a book ID and collection ID, like <https://archive.org/details/TheStrandMagazineAnIllustratedMonthly/TheStrandMagazine1914bVol.XlviiiJul-dec/page/n254/mode/1up>.
			// The `/page/<number>` portion of the URL may also be missing if we're on page 1 (like the cover).
			if(!preg_match('|^/details/[^/]+?(/[^/]+?)?(/page/[^/]+)?/mode/1up$|ius', $parsedUrl['path'])){
				throw new Exceptions\PageScanUrlInvalidException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'google.com') !== false){
			// Old style: <https://books.google.com/books?id=mZpAAAAAYAAJ&pg=PA70-IA2>.
			// New style: <https://www.google.com/books/edition/_/mZpAAAAAYAAJ?gbpv=1&pg=PA70-IA2>.

			$exampleUrl = 'https://www.google.com/books/edition/_/mZpAAAAAYAAJ?gbpv=1&pg=PA70-IA2';

			if($parsedUrl['host'] == 'books.google.com'){
				// Old style, convert to new style.

				if($parsedUrl['path'] != '/books'){
					throw new Exceptions\PageScanUrlInvalidException($url, $exampleUrl);
				}

				parse_str($parsedUrl['query'] ?? '', $vars);

				if(!isset($vars['id']) || !isset($vars['pg']) || is_array($vars['id']) || is_array($vars['pg'])){
					throw new Exceptions\PageScanUrlInvalidException($url, $exampleUrl);
				}

				$outputUrl = 'https://www.google.com/books/edition/_/' . $vars['id'] . '?gbpv=1&pg=' . $vars['pg'];
			}
			elseif($parsedUrl['host'] == 'www.google.com'){
				// New style.

				if(!preg_match('|^/books/edition/[^/]+/[^/]+$|ius', $parsedUrl['path'])){
					throw new Exceptions\PageScanUrlInvalidException($url, $exampleUrl);
				}

				preg_match('|^/books/edition/[^/]+/([^/]+)$|ius', $parsedUrl['path'], $matches);
				$id = $matches[1] ?? '';

				parse_str($parsedUrl['query'] ?? '', $vars);

				if(!isset($vars['gbpv']) || $vars['gbpv'] !== '1' || !isset($vars['pg']) || is_array($vars['pg'])){
					throw new Exceptions\PageScanUrlInvalidException($url, $exampleUrl);
				}

				$outputUrl = 'https://' . $parsedUrl['host'] . '/books/edition/_/' . $id . '?gbpv=' . $vars['gbpv'] . '&pg=' . $vars['pg'];
			}
			else{
				throw new Exceptions\PageScanUrlInvalidException($url, $exampleUrl);
			}

			return $outputUrl;
		}

		return $outputUrl;
	}

	/**
	 * @throws Exceptions\ImageUploadInvalidException
	 */
	private function WriteImageAndThumbnails(string $imagePath): void{
		try{
			exec('exiftool -quiet -overwrite_original -all= ' . escapeshellarg($imagePath));
			copy($imagePath, $this->ImageFsPath);

			// Generate the thumbnails.
			$image = new Image($imagePath);
			$image->Resize($this->ThumbFsPath, ARTWORK_THUMBNAIL_WIDTH, ARTWORK_THUMBNAIL_HEIGHT);
			$image->Resize($this->Thumb2xFsPath, ARTWORK_THUMBNAIL_WIDTH * 2, ARTWORK_THUMBNAIL_HEIGHT * 2);
		}
		catch(\Safe\Exceptions\ExecException | \Safe\Exceptions\FilesystemException){
			throw new Exceptions\ImageUploadInvalidException('Failed to generate thumbnail.');
		}
	}

	/**
	 * @throws Exceptions\ArtworkInvalidException
	 * @throws Exceptions\ArtworkTagInvalidException
	 * @throws Exceptions\ArtistInvalidException
	 * @throws Exceptions\ImageUploadInvalidException
	 * @throws Exceptions\ArtworkExistsException
	 */
	public function Create(?string $imagePath = null): void{
		$this->Validate($imagePath, true);

		// Do we already have an `Artwork` with the same URL?
		$doesArtworkExist = Db::QueryBool('SELECT sum(result) = 2
							from
							(
							select exists(select * from Artworks where UrlName = ?) as result
							union all
							select exists(select * from Artists where UrlName = ?) as result
							) x', [$this->UrlName, $this->Artist->UrlName]);

		if($doesArtworkExist){
			throw new Exceptions\ArtworkExistsException();
		}

		$this->Created = NOW;
		$this->Updated = NOW;

		$tags = [];
		foreach($this->Tags as $artworkTag){
			$tags[] = ArtworkTag::GetOrCreate($artworkTag);
		}
		$this->Tags = $tags;

		$this->Artist = Artist::GetOrCreate($this->Artist);

		$this->ArtworkId = Db::QueryInt('
			INSERT into
			Artworks (ArtistId, Name, UrlName, CompletedYear, CompletedYearIsCirca, Created, Updated, Status, SubmitterUserId, ReviewerUserId, IsAutoReviewed, MuseumUrl,
			                      PublicationYear, PublicationYearPageUrl, CopyrightPageUrl, ArtworkPageUrl, IsPublishedInUs,
			                      EbookId, MimeType, Exception, Notes)
			values (?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?)
			returning ArtworkId
		', [$this->Artist->ArtistId, $this->Name, $this->UrlName, $this->CompletedYear, $this->CompletedYearIsCirca,
				$this->Created, $this->Updated, $this->Status, $this->SubmitterUserId, $this->ReviewerUserId, $this->IsAutoReviewed, $this->MuseumUrl, $this->PublicationYear, $this->PublicationYearPageUrl,
				$this->CopyrightPageUrl, $this->ArtworkPageUrl, $this->IsPublishedInUs, $this->EbookId, $this->MimeType, $this->Exception, $this->Notes]
		);

		foreach($this->Tags as $tag){
			Db::Query('
				INSERT into ArtworkTags (ArtworkId, TagId)
				values (?,
				        ?)
			', [$this->ArtworkId, $tag->TagId]);
		}

		if($imagePath !== null){
			$this->WriteImageAndThumbnails($imagePath);
		}

		$this->UpdateSearchRepresentation();
	}

	/**
	 * @throws Exceptions\ArtworkInvalidException
	 * @throws Exceptions\ArtistInvalidException
	 * @throws Exceptions\ArtworkTagInvalidException
	 * @throws Exceptions\ImageUploadInvalidException
	 */
	public function Save(?string $imagePath = null): void{
		unset($this->_UrlName);
		unset($this->_Url);
		unset($this->_EditUrl);

		if($imagePath !== null){
			// Manually set the updated timestamp, because if we only update the image and nothing else, the row's updated timestamp won't change automatically.
			$this->Updated = NOW;
			unset($this->_ImageUrl);
			unset($this->_ThumbUrl);
			unset($this->_Thumb2xUrl);
		}

		$this->Validate($imagePath, false);

		$tags = [];
		foreach($this->Tags as $artworkTag){
			$tags[] = ArtworkTag::GetOrCreate($artworkTag);
		}
		$this->Tags = $tags;

		$newDeathYear = $this->Artist->DeathYear;
		$this->Artist = Artist::GetOrCreate($this->Artist);

		// Save the artist death year in case we changed it.
		if($newDeathYear != $this->Artist->DeathYear){
			Db::Query('UPDATE Artists set DeathYear = ? where ArtistId = ?', [$newDeathYear , $this->Artist->ArtistId]);
		}

		$this->Updated = NOW;

		// Save the artwork.
		Db::Query('
			UPDATE Artworks
			set
			ArtistId = ?,
			Name = ?,
			UrlName = ?,
			CompletedYear = ?,
			CompletedYearIsCirca = ?,
			Updated = ?,
			Status = ?,
			SubmitterUserId = ?,
			ReviewerUserId = ?,
			IsAutoReviewed = ?,
			MuseumUrl = ?,
			PublicationYear = ?,
			PublicationYearPageUrl = ?,
			CopyrightPageUrl = ?,
			ArtworkPageUrl = ?,
			IsPublishedInUs = ?,
			EbookId = ?,
			MimeType = ?,
			Exception = ?,
			Notes = ?
			where
			ArtworkId = ?
		', [$this->Artist->ArtistId, $this->Name, $this->UrlName, $this->CompletedYear, $this->CompletedYearIsCirca,
				$this->Updated, $this->Status, $this->SubmitterUserId, $this->ReviewerUserId, $this->IsAutoReviewed, $this->MuseumUrl, $this->PublicationYear, $this->PublicationYearPageUrl,
				$this->CopyrightPageUrl, $this->ArtworkPageUrl, $this->IsPublishedInUs, $this->EbookId, $this->MimeType, $this->Exception, $this->Notes,
				$this->ArtworkId]
		);

		// Delete artists who are no longer to attached to an artwork.
		// Don't delete from the ArtistAlternateNames table to prevent accidentally deleting those manually-added entries.
		Db::Query('
			DELETE
			from Artists
			where ArtistId not in
				(select distinct ArtistId from Artworks)
		');

		// Update tags for this artwork.
		Db::Query('
			DELETE from ArtworkTags
			where
			ArtworkId = ?
		', [$this->ArtworkId]
		);

		foreach($this->Tags as $tag){
			Db::Query('
				INSERT into ArtworkTags (ArtworkId, TagId)
				values (?,
				        ?)
			', [$this->ArtworkId, $tag->TagId]);
		}

		// Handle the uploaded file if the user provided one during the save.
		if($imagePath !== null){
			$this->WriteImageAndThumbnails($imagePath);
		}

		$this->UpdateSearchRepresentation();
	}

	public function Delete(): void{
		Db::Query('
			DELETE
			from ArtworkTags
			where ArtworkId = ?
		', [$this->ArtworkId]);

		Db::Query('
			DELETE
			from Artworks
			where ArtworkId = ?
		', [$this->ArtworkId]);

		try{
			@unlink($this->ImageFsPath);
		}
		catch(\Safe\Exceptions\FilesystemException){
			// Pass.
		}

		try{
			@unlink($this->ThumbFsPath);
		}
		catch(\Safe\Exceptions\FilesystemException){
			// Pass.
		}

		try{
			@unlink($this->Thumb2xFsPath);
		}
		catch(\Safe\Exceptions\FilesystemException){
			// Pass.
		}

		$this->DeleteSearchRepresentation();
	}


	// ***********
	// ORM METHODS
	// ***********

	/**
	 * @throws Exceptions\ArtworkNotFoundException
	 */
	public static function Get(?int $artworkId): Artwork{
		if($artworkId === null){
			throw new Exceptions\ArtworkNotFoundException();
		}

		$result = Db::Query('
				SELECT *
				from Artworks
				where ArtworkId = ?
			', [$artworkId], Artwork::class);

		return $result[0] ?? throw new Exceptions\ArtworkNotFoundException();
	}

	/**
	 * @throws Exceptions\ArtworkNotFoundException
	 */
	public static function GetByUrl(?string $artistUrlName, ?string $artworkUrlName): Artwork{
		if($artistUrlName === null || $artworkUrlName === null){
			throw new Exceptions\ArtworkNotFoundException();
		}

		$result = Db::Query('
				SELECT Artworks.*
				from Artworks
				inner join Artists using (ArtistId)
				where Artists.UrlName = ? and Artworks.UrlName = ?
			', [$artistUrlName, $artworkUrlName], Artwork::class);

		return $result[0] ?? throw new Exceptions\ArtworkNotFoundException();
	}

	/**
	 * @return array<Artwork>
	 *
	 * @throws Exceptions\ArtistNotFoundException
	 */
	public static function GetAllByArtist(?string $artistUrlName, ?Enums\ArtworkFilterType $artworkFilterType, ?int $submitterUserId): array{
		if($artistUrlName === null){
			throw new Exceptions\ArtistNotFoundException();
		}

		if($artworkFilterType === null){
			$artworkFilterType = Enums\ArtworkFilterType::Approved;
		}

		$statusCondition = '';
		$params = [];

		if($artworkFilterType == Enums\ArtworkFilterType::Admin){
			$statusCondition = 'true';
		}
		elseif($artworkFilterType == Enums\ArtworkFilterType::ApprovedSubmitter && $submitterUserId !== null){
			$statusCondition = '(Status = ? or (Status = ? and SubmitterUserId = ?))';
			$params[] = Enums\ArtworkStatusType::Approved->value;
			$params[] = Enums\ArtworkStatusType::Unverified->value;
			$params[] = $submitterUserId;
		}
		else{
			// Default to the `Enums\ArtworkFilterType::Approved` view.
			$statusCondition = 'Status = ?';
			$params[] = Enums\ArtworkStatusType::Approved->value;
		}

		$params[] = $artistUrlName; // a.UrlName

		$artworks = Db::Query('
			SELECT art.*
			from Artworks art
			  inner join Artists a using (ArtistId)
			where ' . $statusCondition . '
			and a.UrlName = ?
			order by art.Created desc', $params, Artwork::class);

		return $artworks;
	}

	/**
	* @return array{'artworks': array<Artwork>, 'count': int}
	*/
	public static function GetAllByFilter(?string $query = null, ?int $startYear = null, ?int $endYear = null, ?Enums\ArtworkFilterType $artworkFilterType = null, ?Enums\ArtworkSortType $sort = null, ?int $submitterUserId = null, int $page = 1, int $perPage = ARTWORK_PER_PAGE): array{
		if($artworkFilterType === null){
			$artworkFilterType = Enums\ArtworkFilterType::Approved;
		}

		$query = trim($query ?? '');

		if(mb_strlen($query) > DATABASE_SEARCH_MAXIMUM_QUERY_LENGTH){
			$query = mb_substr($query, 0, DATABASE_SEARCH_MAXIMUM_QUERY_LENGTH);
		}

		if($query == ''){
			$query = null;
		}

		$whereCondition = '';
		$params = [];

		if($artworkFilterType == Enums\ArtworkFilterType::Admin){
			$whereCondition = '1=1';
		}
		elseif($artworkFilterType == Enums\ArtworkFilterType::ApprovedSubmitter && $submitterUserId !== null){
			$whereCondition = '(Status = ? or (Status = ? and SubmitterUserId = ?))';
			$params[] = Enums\ArtworkStatusType::Approved->value;
			$params[] = Enums\ArtworkStatusType::Unverified->value;
			$params[] = $submitterUserId;
		}
		elseif($artworkFilterType == Enums\ArtworkFilterType::UnverifiedSubmitter && $submitterUserId !== null){
			$whereCondition = 'Status = ? and SubmitterUserId = ?';
			$params[] = Enums\ArtworkStatusType::Unverified->value;
			$params[] = $submitterUserId;
		}
		elseif($artworkFilterType == Enums\ArtworkFilterType::ApprovedInUse){
			if($query === null){
				$whereCondition = 'Status = ? and EbookId is not null';
			}
			else{
				// Manticore doesn't have nullable columns, use `0` instead.
				$whereCondition = 'Status = ? and EbookId != 0';
			}
			$params[] = Enums\ArtworkStatusType::Approved->value;
		}
		elseif($artworkFilterType == Enums\ArtworkFilterType::ApprovedNotInUse){
			if($query === null){
				$whereCondition = 'Status = ? and EbookId is null';
			}
			else{
				// Manticore doesn't have nullable columns, use `0` instead.
				$whereCondition = 'Status = ? and EbookId = 0';
			}
			$params[] = Enums\ArtworkStatusType::Approved->value;
		}
		elseif($artworkFilterType == Enums\ArtworkFilterType::Declined){
			$whereCondition = 'Status = ?';
			$params[] = Enums\ArtworkStatusType::Declined->value;
		}
		elseif($artworkFilterType == Enums\ArtworkFilterType::Unverified){
			$whereCondition = 'Status = ?';
			$params[] = Enums\ArtworkStatusType::Unverified->value;
		}
		else{
			// Default to the `Enums\ArtworkFilterType::Approved` view.
			$whereCondition = 'Status = ?';
			$params[] = Enums\ArtworkStatusType::Approved->value;
		}

		if($startYear !== null){
			$whereCondition .= ' and CompletedYear >= ?';
			$params[] = $startYear;
		}

		if($endYear !== null){
			if($query === null){
				$whereCondition .= ' and CompletedYear <= ?';
			}
			else{
				// `null` years are stored as `0` in Manticore, so exclude them explicitly when filtering by an upper bound.
				$whereCondition .= ' and CompletedYear > 0 and CompletedYear <= ?';
			}
			$params[] = $endYear;
		}

		if($query === null){
			$orderBy = 'art.Created desc';
			if($sort == Enums\ArtworkSortType::ArtistAlpha){
				$orderBy = 'a.Name asc';
			}
			elseif($sort == Enums\ArtworkSortType::CompletedNewest){
				$orderBy = 'art.CompletedYear desc';
			}
		}
		else{
			$orderBy = 'Created desc';
			if($sort == Enums\ArtworkSortType::ArtistAlpha){
				$orderBy = 'ArtistNameSort asc';
			}
			elseif($sort == Enums\ArtworkSortType::CompletedNewest){
				$orderBy = 'CompletedYear desc';
			}
		}

		$limit = $perPage;
		$offset = (($page - 1) * $perPage);

		if($query === null){
			$params[] = $limit;
			$params[] = $offset;

			$artworks = Db::Query('
				SELECT SQL_CALC_FOUND_ROWS art.*
				from Artworks art
				inner join Artists a using(ArtistId)
				where ' . $whereCondition . '
				order by ' . $orderBy . '
				limit ?
				offset ?', $params, Artwork::class);

			$artworksCount = Db::QueryInt('SELECT found_rows()');
		}
		else{
			$whereCondition .= ' and match(?)';
			$params[] = $query;

			$params[] = $limit;
			$params[] = $offset;

			$maxMatches = $offset + $limit;

			$result = SearchDb::QueryMatch('SELECT id from artworks where ' . $whereCondition . ' order by ' . $orderBy . ' limit ? offset ? option max_matches=' . $maxMatches, $params, sizeof($params) - 3);

			// Try to get the total matches from built-in metadata instead of running a second resource-intensive query.
			$artworksCount = SearchDb::GetLastQueryTotalResultCount();

			if($artworksCount === null){
				// Exact number of total matches not found, calculate it using a separate query.
				array_pop($params);
				array_pop($params);
				$artworksCount = SearchDb::QueryMatch('SELECT count(*) as Count from artworks where ' . $whereCondition, $params, sizeof($params) - 1)[0]->count ?? 0;
			}

			if($artworksCount == 0){
				return ['artworks' => [], 'count' => 0];
			}

			if(sizeof($result) == 0){
				return ['artworks' => [], 'count' => $artworksCount];
			}

			$ids = '';

			foreach($result as $row){
				$ids .= $row->id . ',';
			}

			$ids = rtrim($ids, ',');

			// `find_in_set()` allows us to order the resultset from MariaDB in the same order that it came from Manticore.
			$artworks = Db::Query('
				SELECT art.*
				from Artworks art
				inner join Artists a using(ArtistId)
				where art.ArtworkId in (' . $ids . ')
				order by find_in_set(art.ArtworkId, "' . $ids . '")'
				, [], Artwork::class);
		}

		return ['artworks' => $artworks, 'count' => $artworksCount];
	}

	/**
	 * Create or update this `Artwork` in the search database.
	 */
	public function UpdateSearchRepresentation(): void{
		$tags = '';
		foreach($this->Tags as $tag){
			$tags .= $tag->Name . ' ';
		}

		$tags = trim($tags);
		SearchDb::Query('
			REPLACE into artworks (
				id,
				Name,
				UrlName,
				ArtistName,
				ArtistNameSort,
				ArtistUrlName,
				ArtistAlternateNames,
				EbookTitle,
				EbookAuthors,
				Tags,
				Status,
				SubmitterUserId,
				CompletedYear,
				EbookId,
				Created
			)
			values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
				$this->ArtworkId,
				$this->Name,
				$this->UrlName,
				$this->Artist->Name,
				$this->Artist->Name,
				$this->Artist->UrlName,
				$this->Artist->AlternateNamesString,
				$this->Ebook->Title ?? '',
				$this->Ebook->AuthorsString ?? '',
				$tags,
				$this->Status,
				$this->SubmitterUserId ?? 0,
				$this->CompletedYear ?? 0,
				$this->EbookId ?? 0,
				$this->Created
			]);
	}

	/**
	 * Delete this `Artwork` from the search database.
	 */
	private function DeleteSearchRepresentation(): void{
		SearchDb::Query('
			DELETE from artworks
			where id = ?', [$this->ArtworkId]);
	}

	/**
	 * @throws Exceptions\UrlInvalidException
	 */
	public function FillFromHttpPost(): void{
		if(!isset($this->Artist)){
			$this->Artist = new Artist();
		}

		$this->Artist->FillFromHttpPost();

		$this->PropertyFromHttp('Name');
		$this->PropertyFromHttp('CompletedYear');
		$this->PropertyFromHttp('CompletedYearIsCirca');
		$this->PropertyFromHttp('Status');
		$this->PropertyFromHttp('IsPublishedInUs');
		$this->PropertyFromHttp('PublicationYear');
		$this->PropertyFromHttp('PublicationYearPageUrl');
		$this->PropertyFromHttp('CopyrightPageUrl');
		$this->PropertyFromHttp('ArtworkPageUrl');
		$this->PropertyFromHttp('MuseumUrl');
		if(isset($_POST['artwork-exception'])){
			$this->Exception = Http::$Request->Body->Get('artwork-exception');
		}

		if(isset($_POST['artwork-notes'])){
			$this->Notes = Http::$Request->Body->Get('artwork-notes');
		}

		$this->PropertyFromHttp('ArtworkStatus');

		$tags = Http::$Request->Body->Get('artwork-tags', 'empty-string');
		if($tags !== null){
			$this->Tags = $tags; // Converted from a string to an array via a setter.
		}

		$ebookUrl = Http::$Request->Body->Get('artwork-ebook-url');
		if(isset($ebookUrl)){
			try{
				$ebook = Ebook::GetByIdentifier($ebookUrl);
				$this->EbookId = $ebook->EbookId;
			}
			catch(Exceptions\EbookNotFoundException){
				throw new Exceptions\UrlInvalidException($ebookUrl);
			}
		}
		else{
			$this->Ebook = null;
			$this->EbookId = null;
		}
	}
}
