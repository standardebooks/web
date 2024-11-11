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
 * @property string $Url
 * @property string $EditUrl
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
 * @property Museum $Museum
 * @property ?User $Submitter
 * @property ?User $Reviewer
 */
class Artwork{
	use Traits\Accessor;
	use Traits\PropertyFromHttp;

	public int $ArtworkId;
	public string $Name = '';
	public int $ArtistId;
	public ?int $CompletedYear = null;
	public bool $CompletedYearIsCirca = false;
	public DateTimeImmutable $Created;
	public DateTimeImmutable $Updated;
	public ?string $EbookUrl = null;
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
	protected ?Ebook $_Ebook;
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
		if(!isset($this->_Url)){
			$this->_Url = '/artworks/' . $this->Artist->UrlName . '/' . $this->UrlName;
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
	 * @return array<ArtworkTag>
	 */
	protected function GetTags(): array{
		if(!isset($this->_Tags)){
			$this->_Tags = Db::Query('
							SELECT t.*
							from Tags t
							inner join ArtworkTags at using (TagId)
							where ArtworkId = ?
						', [$this->ArtworkId], ArtworkTag::class);
		}

		return $this->_Tags;
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
				// Pass.
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
				list($imageWidth, $imageHeight) = getimagesize($this->ImageFsPath);
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

	protected function GetEbook(): ?Ebook{
		if(!isset($this->_Ebook)){
			if($this->EbookUrl === null){
				return null;
			}

			$identifier = 'url:' . $this->EbookUrl;
			try{
				$this->_Ebook = Ebook::GetByIdentifier($identifier);
			}
			catch(Exceptions\EbookNotFoundException){
				// The ebook is probably unreleased.
				return null;
			}
		}

		return $this->_Ebook;
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

		if($this->Exception !== null && trim($this->Exception) == ''){
			$this->Exception = null;
		}

		if($this->Notes !== null && trim($this->Notes) == ''){
			$this->Notes = null;
		}

		if(isset($this->Name)){
			if($this->Name == ''){
				$error->Add(new Exceptions\ArtworkNameRequiredException());
			}

			if(strlen($this->Name) > ARTWORK_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Artwork Name'));
			}
		}
		else{
			$error->Add(new Exceptions\ArtworkNameRequiredException());
		}

		if($this->CompletedYear !== null && ($this->CompletedYear <= 0 || $this->CompletedYear > $thisYear)){
			$error->Add(new Exceptions\InvalidCompletedYearException());
		}

		if($this->CompletedYear === null && $this->CompletedYearIsCirca){
			$this->CompletedYearIsCirca = false;
		}

		if($this->PublicationYear !== null && ($this->PublicationYear <= 0 || $this->PublicationYear > $thisYear)){
			$error->Add(new Exceptions\InvalidPublicationYearException());
		}

		if(!isset($this->Status)){
			$error->Add(new Exceptions\InvalidArtworkException('Invalid status.'));
		}

		if(isset($this->Tags)){
			if(count($this->Tags) == 0){
				$error->Add(new Exceptions\TagsRequiredException());
			}

			if(count($this->Tags) > ARTWORK_MAX_TAGS){
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
		}
		else{
			$error->Add(new Exceptions\TagsRequiredException());
		}

		if($this->MuseumUrl !== null){
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

		if($this->PublicationYearPageUrl !== null){
			if(strlen($this->PublicationYearPageUrl) > ARTWORK_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Link to page with year of publication'));
			}

			if($this->PublicationYearPageUrl == '' || filter_var($this->PublicationYearPageUrl, FILTER_VALIDATE_URL) === false){
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

		if($this->CopyrightPageUrl !== null){
			if(strlen($this->CopyrightPageUrl) > ARTWORK_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Link to page with copyright details'));
			}

			if($this->CopyrightPageUrl == '' || filter_var($this->CopyrightPageUrl, FILTER_VALIDATE_URL) === false){
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

		if($this->ArtworkPageUrl !== null){
			if(strlen($this->ArtworkPageUrl) > ARTWORK_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Link to page with artwork'));
			}

			if($this->ArtworkPageUrl == '' || filter_var($this->ArtworkPageUrl, FILTER_VALIDATE_URL) === false){
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

		$hasMuseumProof = $this->MuseumUrl !== null && $this->MuseumUrl != '';
		$hasBookProof = $this->PublicationYear !== null
			&& ($this->PublicationYearPageUrl !== null && $this->PublicationYearPageUrl != '')
			&& ($this->ArtworkPageUrl !== null && $this->ArtworkPageUrl != '');

		if(!$hasMuseumProof && !$hasBookProof && $this->Exception === null){
			$error->Add(new Exceptions\MissingPdProofException());
		}

		// Check the ebook URL.
		// We don't check if it exists, because the book might not be published yet.
		// But we do a basic check that URL has the correct prefix and that it contains a slash between the author(s) and title.
		if($this->EbookUrl !== null){
			if(!preg_match('|^https://standardebooks.org/ebooks/[^/]+?/[^/]+?|ius', $this->EbookUrl)){
				$error->Add(new Exceptions\EbookNotFoundException('Invalid ebook URL. Check that it matches the URL in dc:identifier.'));
			}
		}

		// Check for existing `Artwork` objects with the same URL but different `ArtworkID`s.
		if(isset($this->ArtworkId)){
			try{
				$existingArtwork = Artwork::GetByUrl($this->Artist->UrlName, $this->UrlName);
				if($existingArtwork->ArtworkId != $this->ArtworkId){
					// Duplicate found, alert the user.
					$error->Add(new Exceptions\ArtworkAlreadyExistsException());
				}
			}
			catch(Exceptions\ArtworkNotFoundException){
				// No duplicates found, continue.
			}
		}

		if($isImageRequired){
			if($imagePath === null){
				$error->Add(new Exceptions\InvalidImageUploadException('An image is required.'));
			}
			else{
				if(!is_writable(WEB_ROOT . COVER_ART_UPLOAD_PATH)){
					$error->Add(new Exceptions\InvalidImageUploadException('Upload path not writable.'));
				}

				// Check for minimum dimensions.
				list($imageWidth, $imageHeight) = getimagesize($imagePath);
				if(!$imageWidth || !$imageHeight || $imageWidth < ARTWORK_IMAGE_MINIMUM_WIDTH || $imageHeight < ARTWORK_IMAGE_MINIMUM_HEIGHT){
					$error->Add(new Exceptions\ArtworkImageDimensionsTooSmallException());
				}
			}
		}

		if(!isset($this->MimeType)){
			$error->Add(new Exceptions\InvalidMimeTypeException());
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
	 * @throws Exceptions\InvalidUrlException
	 * @throws Exceptions\InvalidPageScanUrlException
	 */
	public static function NormalizePageScanUrl(string $url): string{
		$outputUrl = $url;

		// Before we start, replace Google TLDs like google.ca with .com
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

			// If we're missing the view mode, append it
			if(preg_match('|^/details/[^/]+?/page/[^/]+$|ius', $parsedUrl['path'])){
				$parsedUrl['path'] = $parsedUrl['path'] . '/mode/1up';
			}

			// archive.org URLs may have both a book ID and collection ID, like
			// https://archive.org/details/TheStrandMagazineAnIllustratedMonthly/TheStrandMagazine1914bVol.XlviiiJul-dec/page/n254/mode/1up
			// The `/page/<number>` portion of the URL may also be missing if we're on page 1 (like the cover)
			if(!preg_match('|^/details/[^/]+?(/[^/]+?)?(/page/[^/]+)?/mode/1up$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidPageScanUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'google.com') !== false){
			// Old style: https://books.google.com/books?id=mZpAAAAAYAAJ&pg=PA70-IA2
			// New style: https://www.google.com/books/edition/_/mZpAAAAAYAAJ?gbpv=1&pg=PA70-IA2

			$exampleUrl = 'https://www.google.com/books/edition/_/mZpAAAAAYAAJ?gbpv=1&pg=PA70-IA2';

			if($parsedUrl['host'] == 'books.google.com'){
				// Old style, convert to new style

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
				// New style

				if(!preg_match('|^/books/edition/[^/]+/[^/]+$|ius', $parsedUrl['path'])){
					throw new Exceptions\InvalidPageScanUrlException($url, $exampleUrl);
				}

				preg_match('|^/books/edition/[^/]+/([^/]+)$|ius', $parsedUrl['path'], $matches);
				$id = $matches[1];

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
		exec('exiftool -quiet -overwrite_original -all= ' . escapeshellarg($imagePath));
		copy($imagePath, $this->ImageFsPath);

		// Generate the thumbnails
		try{
			$image = new Image($imagePath);
			$image->Resize($this->ThumbFsPath, ARTWORK_THUMBNAIL_WIDTH, ARTWORK_THUMBNAIL_HEIGHT);
			$image->Resize($this->Thumb2xFsPath, ARTWORK_THUMBNAIL_WIDTH * 2, ARTWORK_THUMBNAIL_HEIGHT * 2);
		}
		catch(\Safe\Exceptions\FilesystemException | \Safe\Exceptions\ImageException){
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
		$this->MimeType = Enums\ImageMimeType::FromFile($imagePath) ?? throw new Exceptions\InvalidImageUploadException();

		$this->Validate($imagePath, true);

		$this->Created = NOW;
		$this->Updated = NOW;

		$tags = [];
		foreach($this->Tags as $artworkTag){
			$tags[] = ArtworkTag::GetOrCreate($artworkTag);
		}
		$this->Tags = $tags;

		$this->Artist = Artist::GetOrCreate($this->Artist);

		Db::Query('
			INSERT into
			Artworks (ArtistId, Name, UrlName, CompletedYear, CompletedYearIsCirca, Created, Updated, Status, SubmitterUserId, ReviewerUserId, MuseumUrl,
			                      PublicationYear, PublicationYearPageUrl, CopyrightPageUrl, ArtworkPageUrl, IsPublishedInUs,
			                      EbookUrl, MimeType, Exception, Notes)
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
		', [$this->Artist->ArtistId, $this->Name, $this->UrlName, $this->CompletedYear, $this->CompletedYearIsCirca,
				$this->Created, $this->Updated, $this->Status, $this->SubmitterUserId, $this->ReviewerUserId, $this->MuseumUrl, $this->PublicationYear, $this->PublicationYearPageUrl,
				$this->CopyrightPageUrl, $this->ArtworkPageUrl, $this->IsPublishedInUs, $this->EbookUrl, $this->MimeType, $this->Exception, $this->Notes]
		);

		$this->ArtworkId = Db::GetLastInsertedId();

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
			$this->MimeType = Enums\ImageMimeType::FromFile($imagePath) ?? throw new Exceptions\InvalidImageUploadException();

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
			EbookUrl = ?,
			MimeType = ?,
			Exception = ?,
			Notes = ?
			where
			ArtworkId = ?
		', [$this->Artist->ArtistId, $this->Name, $this->UrlName, $this->CompletedYear, $this->CompletedYearIsCirca,
				$this->Updated, $this->Status, $this->SubmitterUserId, $this->ReviewerUserId, $this->MuseumUrl, $this->PublicationYear, $this->PublicationYearPageUrl,
				$this->CopyrightPageUrl, $this->ArtworkPageUrl, $this->IsPublishedInUs, $this->EbookUrl, $this->MimeType, $this->Exception, $this->Notes,
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
			unlink($this->ImageFsPath);
		}
		catch(\Safe\Exceptions\FilesystemException){
			// Pass.
		}

		try{
			unlink($this->ThumbFsPath);
		}
		catch(\Safe\Exceptions\FilesystemException){
			// Pass.
		}

		try{
			unlink($this->Thumb2xFsPath);
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
	public static function GetAllByArtist(?string $artistUrlName, ?string $status, ?int $submitterUserId): array{
		if($artistUrlName === null){
			throw new Exceptions\ArtistNotFoundException();
		}

		// $status is only one of three special statuses, which are a subset of FilterArtwork() above:
		// null: same as "all"
		// "all": Show all approved and in use artwork
		// "all-admin": Show all artwork regardless of status
		// "all-submitter": Show all approved and in use artwork, plus unverified artwork from the submitter
		$statusCondition = '';
		$params = [];

		if($status == 'all-admin'){
			$statusCondition = 'true';
		}
		elseif($status == 'all-submitter' && $submitterUserId !== null){
			$statusCondition = '(Status = ? or (Status = ? and SubmitterUserId = ?))';
			$params[] = Enums\ArtworkStatusType::Approved->value;
			$params[] = Enums\ArtworkStatusType::Unverified->value;
			$params[] = $submitterUserId;
		}
		else{
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
	public static function GetAllByFilter(?string $query = null, ?string $status = null, ?Enums\ArtworkSortType $sort = null, ?int $submitterUserId = null, int $page = 1, int $perPage = ARTWORK_PER_PAGE): array{
		// $status is either the string value of an ArtworkStatus enum, or one of these special statuses:
		// null: same as "all"
		// "all": Show all approved and in use artwork
		// "all-admin": Show all artwork regardless of status
		// "all-submitter": Show all approved and in use artwork, plus unverified artwork from the submitter
		// "unverified-submitter": Show unverified artwork from the submitter
		// "in-use": Show only in-use artwork

		$statusCondition = '';
		$params = [];

		if($status === null || $status == 'all'){
			$statusCondition = 'Status = ?';
			$params[] = Enums\ArtworkStatusType::Approved->value;
		}
		elseif($status == 'all-admin'){
			$statusCondition = 'true';
		}
		elseif($status == 'all-submitter' && $submitterUserId !== null){
			$statusCondition = '(Status = ? or (Status = ? and SubmitterUserId = ?))';
			$params[] = Enums\ArtworkStatusType::Approved->value;
			$params[] = Enums\ArtworkStatusType::Unverified->value;
			$params[] = $submitterUserId;
		}
		elseif($status == 'unverified-submitter' && $submitterUserId !== null){
			$statusCondition = 'Status = ? and SubmitterUserId = ?';
			$params[] = Enums\ArtworkStatusType::Unverified->value;
			$params[] = $submitterUserId;
		}
		elseif($status == 'in-use'){
			$statusCondition = 'Status = ? and EbookUrl is not null';
			$params[] = Enums\ArtworkStatusType::Approved->value;
		}
		elseif($status == Enums\ArtworkStatusType::Approved->value){
			$statusCondition = 'Status = ? and EbookUrl is null';
			$params[] = Enums\ArtworkStatusType::Approved->value;
		}
		else{
			$statusCondition = 'Status = ?';
			$params[] = $status;
		}

		$orderBy = 'art.Created desc';
		if($sort == Enums\ArtworkSortType::ArtistAlpha){
			$orderBy = 'a.Name';
		}
		elseif($sort == Enums\ArtworkSortType::CompletedNewest){
			$orderBy = 'art.CompletedYear desc';
		}

		// Remove diacritics and non-alphanumeric characters, but preserve apostrophes
		if($query !== null && $query != ''){
			$query = trim(preg_replace('|[^a-zA-Z0-9\'’ ]|ius', ' ', Formatter::RemoveDiacritics($query)));
		}
		else{
			$query = '';
		}

		// We use replace() below because if there's multiple contributors separated by an underscore,
		// the underscore won't count as word boundary and we won't get a match.
		// See https://github.com/standardebooks/web/pull/325
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

			// Join the tokens with '|' to search on any token, but add word boundaries to force the full token to match
			$tokenizedQuery = '\b(' . implode('|', $tokenArray) . ')\b';

			$params[] = $tokenizedQuery; // art.Name
			$params[] = $tokenizedQuery; // art.EbookUrl
			$params[] = $tokenizedQuery; // a.Name
			$params[] = $tokenizedQuery; // aan.Name
			$params[] = $tokenizedQuery; // t.Name

			$artworksCount = Db::QueryInt('
				SELECT
				    count(*)
				from
				    (SELECT distinct
				        ArtworkId
				    from
				        Artworks art
				    inner join Artists a USING (ArtistId)
				    left join ArtistAlternateNames aan USING (ArtistId)
				    left join ArtworkTags at USING (ArtworkId)
				    left join Tags t USING (TagId)
				    where
				        ' . $statusCondition . '
				            and (art.Name regexp ?
				            or replace(art.EbookUrl, "_", " ") regexp ?
				            or a.Name regexp ?
				            or aan.Name regexp ?
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
				  left join Tags t using (TagId)
				where ' . $statusCondition . '
				  and (art.Name regexp ?
				  or replace(art.EbookUrl, "_", " ") regexp ?
				  or a.Name regexp ?
				  or aan.Name regexp ?
				  or t.Name regexp ?)
				group by art.ArtworkId
				order by ' . $orderBy . '
				limit ?
				offset ?', $params, Artwork::class);
		}

		return ['artworks' => $artworks, 'artworksCount' => $artworksCount];
	}

	public function FillFromHttpPost(): void{
		if(!isset($this->Artist)){
			$this->Artist = new Artist();
		}

		$this->Artist->FillFromHttpPost();

		$this->PropertyFromHttp('Name');
		$this->PropertyFromHttp('CompletedYear');
		$this->PropertyFromHttp('CompletedYearIsCirca');
		$this->PropertyFromHttp('Status');
		$this->PropertyFromHttp('EbookUrl');
		$this->PropertyFromHttp('IsPublishedInUs');
		$this->PropertyFromHttp('PublicationYear');
		$this->PropertyFromHttp('PublicationYearPageUrl');
		$this->PropertyFromHttp('CopyrightPageUrl');
		$this->PropertyFromHttp('ArtworkPageUrl');
		$this->PropertyFromHttp('MuseumUrl');
		$this->PropertyFromHttp('Exception');
		$this->PropertyFromHttp('Notes');

		$this->Tags = HttpInput::Str(POST, 'artwork-tags') ?? ''; // Converted from a string to an array via a setter.
	}
}
