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
 * @property Ebook $Ebook
 * @property ?Museum $Museum
 * @property ?User $Submitter
 * @property ?User $Reviewer
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
	public ?string $Exception = null;
	public ?string $Notes = null;
	public Enums\ImageMimeType $MimeType;
	public Enums\ArtworkStatusType $Status = Enums\ArtworkStatusType::Unverified;

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


	// *******
	// GETTERS
	// *******

	protected function GetUrlName(): string{
		if(!isset($this->_UrlName)){
			if(!isset($this->Name) || $this->Name == ''){
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
	 * @throws Exceptions\InvalidUrlException
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
	 * @throws Exceptions\InvalidArtworkException
	 */
	protected function GetImageUrl(): string{
		if(!isset($this->_ImageUrl)){
			if(!isset($this->ArtworkId) || !isset($this->MimeType)){
				throw new Exceptions\InvalidArtworkException();
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
	 * @throws Exceptions\InvalidArtworkException
	 */
	protected function Validate(?string $imagePath = null, bool $isImageRequired = true): void{
		$thisYear = intval(NOW->format('Y'));
		$error = new Exceptions\InvalidArtworkException();

		if(!isset($this->Artist)){
			$error->Add(new Exceptions\InvalidArtistException());
		}
		else{
			try{
				$this->Artist->Validate();
			}
			catch(Exceptions\ValidationException $ex){
				$error->Add($ex);
			}
		}

		$this->Exception = trim($this->Exception ?? '');

		if($this->Exception == ''){
			$this->Exception = null;
		}

		$this->Notes = trim($this->Notes ?? '');

		if($this->Notes == ''){
			$this->Notes = null;
		}

		$this->Name = trim($this->Name ?? '');

		if($this->Name == ''){
			$error->Add(new Exceptions\ArtworkNameRequiredException());
		}

		if(strlen($this->Name) > ARTWORK_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Artwork Name'));
		}

		if(isset($this->CompletedYear) && ($this->CompletedYear <= 0 || $this->CompletedYear > $thisYear)){
			$error->Add(new Exceptions\InvalidCompletedYearException());
		}

		if($this->CompletedYear === null && $this->CompletedYearIsCirca){
			$this->CompletedYearIsCirca = false;
		}

		if(isset($this->PublicationYear) && ($this->PublicationYear <= 0 || $this->PublicationYear > $thisYear)){
			$error->Add(new Exceptions\InvalidPublicationYearException());
		}

		if(!isset($this->Status)){
			$error->Add(new Exceptions\InvalidArtworkStatusException());
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
			catch(Exceptions\MuseumNotFoundException | Exceptions\InvalidUrlException $ex){
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
				$error->Add(new Exceptions\InvalidPublicationYearPageUrlException());
			}
			else{
				try{
					$this->PublicationYearPageUrl = Artwork::NormalizePageScanUrl($this->PublicationYearPageUrl);
				}
				catch(Exceptions\InvalidUrlException $ex){
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
				$error->Add(new Exceptions\InvalidCopyrightPageUrlException());
			}
			else{
				try{
					$this->CopyrightPageUrl = Artwork::NormalizePageScanUrl($this->CopyrightPageUrl);
				}
				catch(Exceptions\InvalidUrlException $ex){
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
				$error->Add(new Exceptions\InvalidArtworkPageUrlException());
			}
			else{
				try{
					$this->ArtworkPageUrl = Artwork::NormalizePageScanUrl($this->ArtworkPageUrl);
				}
				catch(Exceptions\InvalidUrlException $ex){
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
			}
			catch(Exceptions\EbookNotFoundException){
				// Ebook not found, error!
				$error->Add(new Exceptions\EbookNotFoundException('Couldn’t find an ebook with EbookId: ' . $this->EbookId));
			}
		}

		// Check for existing `Artwork` objects with the same URL but different `ArtworkID`s.
		try{
			$existingArtwork = Artwork::GetByUrl($this->Artist->UrlName, $this->UrlName);
			if(!isset($this->ArtworkId) || $existingArtwork->ArtworkId != $this->ArtworkId){
				$error->Add(new Exceptions\ArtworkAlreadyExistsException());
			}
		}
		catch(Exceptions\ArtworkNotFoundException){
			// No duplicates found, continue.
		}

		if($isImageRequired || $imagePath !== null){
			if($imagePath === null){
				$error->Add(new Exceptions\InvalidImageUploadException('An image is required.'));
			}
			else{
				try{
					$this->MimeType = Enums\ImageMimeType::FromFile($imagePath) ?? throw new Exceptions\InvalidMimeTypeException();
				}
				catch(Exceptions\InvalidMimeTypeException $ex){
					$error->Add($ex);
				}

				if(!is_writable(WEB_ROOT . COVER_ART_UPLOAD_PATH)){
					$error->Add(new Exceptions\InvalidImageUploadException('Upload path not writable.'));
				}

				// Check for minimum dimensions.
				try{
					list($imageWidth, $imageHeight) = (@getimagesize($imagePath) ?? throw new \Exception());
					if(!$imageWidth || !$imageHeight || $imageWidth < ARTWORK_IMAGE_MINIMUM_WIDTH || $imageHeight < ARTWORK_IMAGE_MINIMUM_HEIGHT){
						$error->Add(new Exceptions\ArtworkImageDimensionsTooSmallException());
					}
				}
				catch(\Exception){
					$error->Add(new Exceptions\InvalidImageUploadException());
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

		$curl = new CurlRequest();
		try{
			$response = $curl->Execute(Enums\HttpMethod::Get, $this->MuseumUrl);
		}
		catch(Exceptions\CurlException $e){
			return;
		}

		if($response->HttpCode != Enums\HttpCode::Ok->value){
			return;
		}

		// TODO: When PHP 8.4 is available, use the new `Dom\HTMLDocument` class.
		$dom = new DOMDocument();
		@$dom->loadHTML($response->Data);
		$xpath = new DOMXPath($dom);

		if($xpath->evaluate($this->Museum->LicenseXPath)){
			$this->Status = Enums\ArtworkStatusType::Approved;
		}
	}

	/**
	 * @throws Exceptions\InvalidUrlException
	 * @throws Exceptions\InvalidPageScanUrlException
	 */
	public static function NormalizePageScanUrl(string $url): string{
		$outputUrl = $url;

		// Before we start, replace global Google TLDs like `google.ca` with `google.com`.
		$url = preg_replace('|^(https://[^/]+?\.google)\.[^/]+/|ius', '\1.com/', $url);

		try{
			$parsedUrl = parse_url($url);
		}
		catch(Exception){
			throw new Exceptions\InvalidUrlException($url);
		}

		if(!is_array($parsedUrl)){
			throw new Exceptions\InvalidUrlException($url);
		}

		if(stripos($parsedUrl['host'], 'hathitrust.org') !== false){
			$exampleUrl = 'https://babel.hathitrust.org/cgi/pt?id=hvd.32044034383265&seq=13';

			if($parsedUrl['host'] != 'babel.hathitrust.org'){
				throw new Exceptions\InvalidPageScanUrlException($url, $exampleUrl);
			}

			if($parsedUrl['path'] != '/cgi/pt'){
				throw new Exceptions\InvalidPageScanUrlException($url, $exampleUrl);
			}

			parse_str($parsedUrl['query'] ?? '', $vars);

			if(!isset($vars['id']) || !isset($vars['seq']) || is_array($vars['id']) || is_array($vars['seq'])){
				throw new Exceptions\InvalidPageScanUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'] . '?id=' . $vars['id'] . '&view=1up&seq=' . $vars['seq'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'archive.org') !== false){
			$exampleUrl = 'https://archive.org/details/royalacademypict1902roya/page/n9/mode/1up';

			if($parsedUrl['host'] != 'archive.org'){
				throw new Exceptions\InvalidPageScanUrlException($url, $exampleUrl);
			}

			// If we're missing the view mode, append it.
			if(preg_match('|^/details/[^/]+?/page/[^/]+$|ius', $parsedUrl['path'])){
				$parsedUrl['path'] = $parsedUrl['path'] . '/mode/1up';
			}

			// Internet Archive URLs may have both a book ID and collection ID, like <https://archive.org/details/TheStrandMagazineAnIllustratedMonthly/TheStrandMagazine1914bVol.XlviiiJul-dec/page/n254/mode/1up>.
			// The `/page/<number>` portion of the URL may also be missing if we're on page 1 (like the cover).
			if(!preg_match('|^/details/[^/]+?(/[^/]+?)?(/page/[^/]+)?/mode/1up$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidPageScanUrlException($url, $exampleUrl);
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
					throw new Exceptions\InvalidPageScanUrlException($url, $exampleUrl);
				}

				parse_str($parsedUrl['query'] ?? '', $vars);

				if(!isset($vars['id']) || !isset($vars['pg']) || is_array($vars['id']) || is_array($vars['pg'])){
					throw new Exceptions\InvalidPageScanUrlException($url, $exampleUrl);
				}

				$outputUrl = 'https://www.google.com/books/edition/_/' . $vars['id'] . '?gbpv=1&pg=' . $vars['pg'];
			}
			elseif($parsedUrl['host'] == 'www.google.com'){
				// New style.

				if(!preg_match('|^/books/edition/[^/]+/[^/]+$|ius', $parsedUrl['path'])){
					throw new Exceptions\InvalidPageScanUrlException($url, $exampleUrl);
				}

				preg_match('|^/books/edition/[^/]+/([^/]+)$|ius', $parsedUrl['path'], $matches);
				$id = $matches[1] ?? '';

				parse_str($parsedUrl['query'] ?? '', $vars);

				if(!isset($vars['gbpv']) || $vars['gbpv'] !== '1' || !isset($vars['pg']) || is_array($vars['pg'])){
					throw new Exceptions\InvalidPageScanUrlException($url, $exampleUrl);
				}

				$outputUrl = 'https://' . $parsedUrl['host'] . '/books/edition/_/' . $id . '?gbpv=' . $vars['gbpv'] . '&pg=' . $vars['pg'];
			}
			else{
				throw new Exceptions\InvalidPageScanUrlException($url, $exampleUrl);
			}

			return $outputUrl;
		}

		return $outputUrl;
	}

	/**
	 * @throws Exceptions\InvalidImageUploadException
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
			throw new Exceptions\InvalidImageUploadException('Failed to generate thumbnail.');
		}
	}

	/**
	 * @throws Exceptions\InvalidArtworkException
	 * @throws Exceptions\InvalidArtworkTagException
	 * @throws Exceptions\InvalidArtistException
	 * @throws Exceptions\InvalidImageUploadException
	 */
	public function Create(?string $imagePath = null): void{
		$this->Validate($imagePath, true);

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
			Artworks (ArtistId, Name, UrlName, CompletedYear, CompletedYearIsCirca, Created, Updated, Status, SubmitterUserId, ReviewerUserId, MuseumUrl,
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
			        ?)
			returning ArtworkId
		', [$this->Artist->ArtistId, $this->Name, $this->UrlName, $this->CompletedYear, $this->CompletedYearIsCirca,
				$this->Created, $this->Updated, $this->Status, $this->SubmitterUserId, $this->ReviewerUserId, $this->MuseumUrl, $this->PublicationYear, $this->PublicationYearPageUrl,
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
	}

	/**
	 * @throws Exceptions\InvalidArtworkException
	 * @throws Exceptions\InvalidArtistException
	 * @throws Exceptions\InvalidArtworkTagException
	 * @throws Exceptions\InvalidImageUploadException
	 */
	public function Save(?string $imagePath = null): void{
		unset($this->_UrlName);

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
				$this->Updated, $this->Status, $this->SubmitterUserId, $this->ReviewerUserId, $this->MuseumUrl, $this->PublicationYear, $this->PublicationYearPageUrl,
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
	* @return array{artworks: array<Artwork>, artworksCount: int}
	*/
	public static function GetAllByFilter(?string $query = null, ?Enums\ArtworkFilterType $artworkFilterType = null, ?Enums\ArtworkSortType $sort = null, ?int $submitterUserId = null, int $page = 1, int $perPage = ARTWORK_PER_PAGE): array{
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
		elseif($artworkFilterType == Enums\ArtworkFilterType::UnverifiedSubmitter && $submitterUserId !== null){
			$statusCondition = 'Status = ? and SubmitterUserId = ?';
			$params[] = Enums\ArtworkStatusType::Unverified->value;
			$params[] = $submitterUserId;
		}
		elseif($artworkFilterType == Enums\ArtworkFilterType::ApprovedInUse){
			$statusCondition = 'Status = ? and EbookId is not null';
			$params[] = Enums\ArtworkStatusType::Approved->value;
		}
		elseif($artworkFilterType == Enums\ArtworkFilterType::ApprovedNotInUse){
			$statusCondition = 'Status = ? and EbookId is null';
			$params[] = Enums\ArtworkStatusType::Approved->value;
		}
		elseif($artworkFilterType == Enums\ArtworkFilterType::Declined){
			$statusCondition = 'Status = ?';
			$params[] = Enums\ArtworkStatusType::Declined->value;
		}
		elseif($artworkFilterType == Enums\ArtworkFilterType::Unverified){
			$statusCondition = 'Status = ?';
			$params[] = Enums\ArtworkStatusType::Unverified->value;
		}
		else{
			// Default to the `Enums\ArtworkFilterType::Approved` view.
			$statusCondition = 'Status = ?';
			$params[] = Enums\ArtworkStatusType::Approved->value;
		}

		$orderBy = 'art.Created desc';
		if($sort == Enums\ArtworkSortType::ArtistAlpha){
			$orderBy = 'a.Name';
		}
		elseif($sort == Enums\ArtworkSortType::CompletedNewest){
			$orderBy = 'art.CompletedYear desc';
		}

		// Remove diacritics and non-alphanumeric characters.
		if($query !== null && $query != ''){
			$query = Formatter::RemoveDiacritics($query);

			// Remove apostrophes outright, don't replace with a space.
			$query = preg_replace('/[\'’]/u', '', $query);

			// Replace all other non-alphanumeric characters with a space.
			$query = trim(preg_replace('|[^a-zA-Z0-9 ]|ius', ' ', $query));
		}
		else{
			$query = '';
		}

		$limit = $perPage;
		$offset = (($page - 1) * $perPage);

		if($query == ''){
			$artworksCount = Db::QueryInt('
				SELECT count(*)
				from Artworks art
				where ' . $statusCondition, $params);

			$params[] = $limit;
			$params[] = $offset;

			$artworks = Db::Query('
				SELECT art.*
				from Artworks art
				inner join Artists a USING (ArtistId)
				where ' . $statusCondition . '
				order by ' . $orderBy . '
				limit ?
				offset ?', $params, Artwork::class);
		}
		else{
			// Split the query on word boundaries followed by spaces. This keeps words with apostrophes intact.
			$tokenArray = preg_split('/\b\s+/', $query, -1, PREG_SPLIT_NO_EMPTY);

			// Join the tokens with `|` to search on any token, but add word boundaries to force the full token to match.
			$tokenizedQuery = '\b(' . implode('|', $tokenArray) . ')\b';

			$params[] = $tokenizedQuery; // art.Name
			$params[] = $tokenizedQuery; // art.UrlName
			$params[] = $tokenizedQuery; // a.Name
			$params[] = $tokenizedQuery; // a.UrlName
			$params[] = $tokenizedQuery; // aan.Name
			$params[] = $tokenizedQuery; // e.Title
			$params[] = $tokenizedQuery; // e.IndexableAuthors
			$params[] = $tokenizedQuery; // t.Name

			$artworksCount = Db::QueryInt('
				SELECT
				    count(*)
				from
				    (SELECT distinct
				        ArtworkId
				    from
				        Artworks art
				    inner join Artists a using (ArtistId)
				    left join ArtistAlternateNames aan using (ArtistId)
				    left join ArtworkTags at using (ArtworkId)
				    left join Ebooks e using (EbookId)
				    left join Tags t using (TagId)
				    where
				        ' . $statusCondition . '
				            and (art.Name regexp ?
				            or art.UrlName regexp ?
				            or a.Name regexp ?
				            or a.UrlName regexp ?
				            or aan.Name regexp ?
				            or e.Title regexp ?
				            or e.IndexableAuthors regexp ?
				            or t.Name regexp ?)
				    group by art.ArtworkId) x', $params);

			$params[] = $limit;
			$params[] = $offset;

			$artworks = Db::Query('
				SELECT art.*
				from Artworks art
				  inner join Artists a using (ArtistId)
				  left join ArtistAlternateNames aan using (ArtistId)
				  left join ArtworkTags at using (ArtworkId)
				  left join Ebooks e using (EbookId)
				  left join Tags t using (TagId)
				where ' . $statusCondition . '
				  and (art.Name regexp ?
				  or art.UrlName regexp ?
				  or a.Name regexp ?
				  or a.UrlName regexp ?
				  or aan.Name regexp ?
				  or e.Title regexp ?
				  or e.IndexableAuthors regexp ?
				  or t.Name regexp ?)
				group by art.ArtworkId
				order by ' . $orderBy . '
				limit ?
				offset ?', $params, Artwork::class);
		}

		return ['artworks' => $artworks, 'artworksCount' => $artworksCount];
	}

	/**
	 * @throws Exceptions\InvalidUrlException
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
		$this->PropertyFromHttp('Exception');
		$this->PropertyFromHttp('Notes');

		$this->Tags = HttpInput::Str(POST, 'artwork-tags') ?? ''; // Converted from a string to an array via a setter.

		$ebookUrl = HttpInput::Str(POST, 'artwork-ebook-url');
		if(isset($ebookUrl)){
			try{
				$ebook = Ebook::GetByIdentifier($ebookUrl);
				$this->EbookId = $ebook->EbookId;
			}
			catch(Exceptions\EbookNotFoundException){
				throw new Exceptions\InvalidUrlException($ebookUrl);
			}

		}
	}
}
