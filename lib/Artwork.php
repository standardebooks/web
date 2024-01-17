<?

use Exceptions\InvalidUrlException;
use Safe\DateTime;
use function Safe\copy;
use function Safe\date;
use function Safe\exec;
use function Safe\getimagesize;
use function Safe\ini_get;
use function Safe\parse_url;
use function Safe\preg_match;
use function Safe\preg_replace;

/**
 * @property string $UrlName
 * @property string $Url
 * @property string $EditUrl
 * @property array<ArtworkTag> $Tags
 * @property Artist $Artist
 * @property string $ImageUrl
 * @property string $ThumbUrl
 * @property string $Thumb2xUrl
 * @property string $ImageFsPath
 * @property string $ThumbFsPath
 * @property string $Thumb2xFsPath
 * @property string $Dimensions
 * @property ArtworkStatus|string|null $Status
 * @property Ebook $Ebook
 * @property Museum $Museum
 * @property User $Submitter
 * @property User $Reviewer
 * @property ?ImageMimeType $MimeType
 * @property ?array<ArtworkTag> $_Tags
 */
class Artwork extends PropertiesBase{
	public ?string $Name = null;
	public ?int $ArtworkId = null;
	public ?int $ArtistId = null;
	public ?int $CompletedYear = null;
	public bool $CompletedYearIsCirca = false;
	public ?DateTime $Created = null;
	public ?DateTime $Updated = null;
	public ?string $EbookWwwFilesystemPath = null;
	public ?int $SubmitterUserId = null;
	public ?int $ReviewerUserId = null;
	public ?string $MuseumUrl = null;
	public ?int $PublicationYear = null;
	public ?string $PublicationYearPageUrl = null;
	public ?string $CopyrightPageUrl = null;
	public ?string $ArtworkPageUrl = null;
	public ?bool $IsPublishedInUs = null;
	public ?string $Exception = null;
	public ?string $Notes = null;
	protected ?string $_UrlName = null;
	protected ?string $_Url = null;
	protected $_Tags = null;
	protected ?Artist $_Artist = null;
	protected ?string $_ImageUrl = null;
	protected ?string $_ThumbUrl = null;
	protected ?string $_Thumb2xUrl = null;
	protected ?string $_ImageFsPath = null;
	protected ?string $_ThumbFsPath = null;
	protected ?string $_Thumb2xFsPath = null;
	protected ?string $_Dimensions = null;
	protected ?Ebook $_Ebook = null;
	protected ?Museum $_Museum = null;
	protected ?User $_Submitter = null;
	protected ?User $_Reviewer = null;
	protected ?ImageMimeType $_MimeType = null;
	protected ?ArtworkStatus $_Status = null;

	// *******
	// SETTERS
	// *******

	/**
	 * @param string|null|array<ArtworkTag> $tags
	 */
	protected function SetTags(null|string|array $tags): void{
		if($tags === null || is_array($tags)){
			$this->_Tags = $tags;
		}
		elseif(is_string($tags)){
			$tags = array_map('trim', explode(',', $tags));
			$tags = array_values(array_filter($tags));
			$tags = array_unique($tags);

			$this->_Tags = array_map(function ($str){
				$tag = new ArtworkTag();
				$tag->Name = $str;
				return $tag;
			}, $tags);
		}
	}

	protected function SetStatus(null|string|ArtworkStatus $status): void{
		if($status instanceof ArtworkStatus){
			$this->_Status = $status;
		}
		elseif($status === null){
			$this->_Status = null;
		}
		else{
			$this->_Status = ArtworkStatus::from($status);
		}
	}

	protected function SetMimeType(null|string|ImageMimeType $mimeType): void{
		if($mimeType instanceof ImageMimeType){
			$this->_MimeType = $mimeType;
		}
		elseif($mimeType === null){
			$this->_MimeType = null;
		}
		else{
			$this->_MimeType = ImageMimeType::tryFrom($mimeType);
		}
	}

	// *******
	// GETTERS
	// *******

	protected function GetUrlName(): string{
		if($this->Name === null || $this->Name == ''){
			return '';
		}

		if($this->_UrlName === null){
			$this->_UrlName = Formatter::MakeUrlSafe($this->Name);
		}

		return $this->_UrlName;
	}

	protected function GetSubmitter(): ?User{
		if($this->_Submitter === null){
			try{
				$this->_Submitter = User::Get($this->SubmitterUserId);
			}
			catch(Exceptions\InvalidUserException){
				// Return null
			}
		}

		return $this->_Submitter;
	}

	protected function GetReviewer(): ?User{
		if($this->_Reviewer === null){
			try{
				$this->_Reviewer = User::Get($this->ReviewerUserId);
			}
			catch(Exceptions\InvalidUserException){
				// Return null
			}
		}

		return $this->_Reviewer;
	}

	protected function GetUrl(): string{
		if($this->_Url === null){
			$this->_Url = '/artworks/' . $this->Artist->UrlName . '/' . $this->UrlName;
		}

		return $this->_Url;
	}

	protected function GetEditUrl(): string{
		return $this->Url . '/edit';
	}

	/**
	 * @return array<ArtworkTag>
	 */
	protected function GetTags(): array{
		if($this->_Tags === null){
			$this->_Tags = Db::Query('
							SELECT t.*
							from Tags t
							inner join ArtworkTags at using (TagId)
							where ArtworkId = ?
						', [$this->ArtworkId], 'ArtworkTag');
		}

		return $this->_Tags;
	}

	public function GetMuseum(): ?Museum{
		if($this->_Museum === null){
			try{
				$this->_Museum = Museum::GetByUrl($this->MuseumUrl);
			}
			catch(Exceptions\MuseumNotFoundException){
				// Pass
			}
		}

		return $this->_Museum;
	}

	public function ImplodeTags(): string{
		$tags = $this->Tags ?? [];
		$tags = array_column($tags, 'Name');
		return trim(implode(', ', $tags));
	}

	/**
	 * @throws \Exceptions\InvalidArtworkException
	 */
	protected function GetImageUrl(): string{
		if($this->_ImageUrl === null){
			if($this->ArtworkId === null || $this->MimeType === null){
				throw new Exceptions\InvalidArtworkException();
			}

			$this->_ImageUrl = COVER_ART_UPLOAD_PATH . $this->ArtworkId . $this->MimeType->GetFileExtension() . '?ts=' . $this->Updated?->getTimestamp();
		}

		return $this->_ImageUrl;
	}

	/**
	 * @throws \Exceptions\ArtworkNotFoundException
	 */
	protected function GetThumbUrl(): string{
		if($this->_ThumbUrl === null){
			if($this->ArtworkId === null){
				throw new Exceptions\ArtworkNotFoundException();
			}

			$this->_ThumbUrl = COVER_ART_UPLOAD_PATH . $this->ArtworkId . '-thumb.jpg' . '?ts=' . $this->Updated?->getTimestamp();
		}

		return $this->_ThumbUrl;
	}

	/**
	 * @throws \Exceptions\ArtworkNotFoundException
	 */
	protected function GetThumb2xUrl(): string{
		if($this->_Thumb2xUrl === null){
			if($this->ArtworkId === null){
				throw new Exceptions\ArtworkNotFoundException();
			}

			$this->_Thumb2xUrl = COVER_ART_UPLOAD_PATH . $this->ArtworkId . '-thumb@2x.jpg' . '?ts=' . $this->Updated?->getTimestamp();
		}

		return $this->_Thumb2xUrl;
	}

	protected function GetImageFsPath(): string{
		return WEB_ROOT . rtrim($this->ImageUrl, '?ts=0123456789');
	}

	protected function GetThumbFsPath(): string{
		return WEB_ROOT . rtrim($this->ThumbUrl, '?ts=0123456789');
	}

	protected function GetThumb2xFsPath(): string{
		return WEB_ROOT . rtrim($this->Thumb2xUrl, '?ts=0123456789');
	}

	protected function GetDimensions(): string{
		$this->_Dimensions = '';
		try{
			list($imageWidth, $imageHeight) = getimagesize($this->ImageFsPath);
			if($imageWidth && $imageHeight){
				$this->_Dimensions = $imageWidth . ' × ' . $imageHeight;
			}
		}
		catch(Exception){
			// Image doesn't exist, return blank string
		}

		return $this->_Dimensions;
	}

	protected function GetEbook(): ?Ebook{
		if($this->_Ebook === null){
			$this->_Ebook = Library::GetEbook(EBOOKS_DIST_PATH . str_replace('_', '/', $this->EbookWwwFilesystemPath ?? ''));
		}

		return $this->_Ebook;
	}

	// *******
	// METHODS
	// *******
	/**
	 * @param array<mixed> $uploadedFile
	 * @throws \Exceptions\ValidationException
	 */
	protected function Validate(array &$uploadedFile = []): void{
		$now = new DateTime('now', new DateTimeZone('UTC'));
		$thisYear = intval($now->format('Y'));
		$error = new Exceptions\ValidationException();

		if($this->Artist === null){
			$error->Add(new Exceptions\InvalidArtworkException());
		}

		try{
			$this->Artist->Validate();
		}
		catch(Exceptions\ValidationException $ex){
			$error->Add($ex);
		}

		if($this->Exception !== null && trim($this->Exception) == ''){
			$this->Exception = null;
		}

		if($this->Notes !== null && trim($this->Notes) == ''){
			$this->Notes = null;
		}

		if($this->Name === null || $this->Name == ''){
			$error->Add(new Exceptions\ArtworkNameRequiredException());
		}

		if($this->Name !== null && strlen($this->Name) > ARTWORK_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Artwork Name'));
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

		if($this->Status === null){
			$error->Add(new Exceptions\InvalidArtworkException('Invalid status.'));
		}

		if($this->Status == ArtworkStatus::InUse && $this->EbookWwwFilesystemPath === null){
			$error->Add(new Exceptions\MissingEbookException());
		}

		if(count($this->Tags) == 0){
			// In-use artwork doesn't have user-provided tags.
			if($this->Status != ArtworkStatus::InUse){
				$error->Add(new Exceptions\TagsRequiredException());
			}
		}

		if(count($this->Tags) > ARTWORK_MAX_TAGS){
			$error->Add(new Exceptions\TooManyTagsException());
		}

		foreach($this->Tags as $tag){
			if(strlen($tag->Name) > ARTWORK_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Artwork Tag: '. $tag->Name));
			}
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
					$this->PublicationYearPageUrl = $this->NormalizePageScanUrl($this->PublicationYearPageUrl);
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
					$this->CopyrightPageUrl = $this->NormalizePageScanUrl($this->CopyrightPageUrl);
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
					$this->ArtworkPageUrl = $this->NormalizePageScanUrl($this->ArtworkPageUrl);
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

		if($this->MimeType === null){
			$error->Add(new Exceptions\InvalidMimeTypeException());
		}

		// Check the ebook www filesystem path.
		// We don't check if it exists, because the book might not be published yet.
		// But we do a basic check that the string includes one _. It might not include a dash, for example anonymous_poetry
		if($this->EbookWwwFilesystemPath !== null){
			if(mb_stripos($this->EbookWwwFilesystemPath, '_') === false){
				$error->Add(new Exceptions\InvalidEbookException('Invalid ebook. Expected file system slug like “c-s-lewis_poetry”.'));
			}
		}

		// Check for existing Artwork objects with the same URL but different Artwork IDs.
		try{
			$existingArtwork = Artwork::GetByUrl($this->Artist->UrlName, $this->UrlName);
			if($existingArtwork->ArtworkId != $this->ArtworkId){
				// Duplicate found, alert the user
				$error->Add(new Exceptions\ArtworkAlreadyExistsException());
			}
		}
		catch(Exceptions\ArtworkNotFoundException){
			// No duplicates found, continue
		}

		if(!is_writable(WEB_ROOT . COVER_ART_UPLOAD_PATH)){
			$error->Add(new Exceptions\InvalidImageUploadException('Upload path not writable.'));
		}

		if(!empty($uploadedFile) && $this->MimeType !== null){
			$uploadError = $uploadedFile['error'];
			if($uploadError > UPLOAD_ERR_OK){
				// see https://www.php.net/manual/en/features.file-upload.errors.php
				$message = match($uploadError){
					UPLOAD_ERR_INI_SIZE => 'Image upload too large (maximum ' . ini_get('upload_max_filesize') . ').',
					default => 'Image failed to upload (error code ' . $uploadError . ').',
				};
				$error->Add(new Exceptions\InvalidImageUploadException($message));
			}

			// Check for minimum dimensions
			list($imageWidth, $imageHeight) = getimagesize($uploadedFile['tmp_name']);
			if(!$imageWidth || !$imageHeight || $imageWidth < ARTWORK_IMAGE_MINIMUM_WIDTH || $imageHeight < ARTWORK_IMAGE_MINIMUM_HEIGHT){
				$error->Add(new Exceptions\ArtworkImageDimensionsTooSmallException());
			}
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	private function NormalizePageScanUrl(string $url): string{
		$outputUrl = $url;

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

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'] . '?id=' . $vars['id'] . '&seq=' . $vars['seq'];

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

	private function WriteImageAndThumbnails(string $imageUploadPath): void{
		exec('exiftool -quiet -overwrite_original -all= ' . escapeshellarg($imageUploadPath));
		copy($imageUploadPath, $this->ImageFsPath);

		// Generate the thumbnails
		try{
			$image = new Image($imageUploadPath);
			$image->Resize($this->ThumbFsPath, ARTWORK_THUMBNAIL_WIDTH, ARTWORK_THUMBNAIL_HEIGHT);
			$image->Resize($this->Thumb2xFsPath, ARTWORK_THUMBNAIL_WIDTH * 2, ARTWORK_THUMBNAIL_HEIGHT * 2);
		}
		catch(\Safe\Exceptions\FilesystemException | \Safe\Exceptions\ImageException){
			throw new Exceptions\InvalidImageUploadException('Failed to generate thumbnail.');
		}
	}

	/**
	 * @param array<mixed> $uploadedFile
	 * @throws \Exceptions\ValidationException
	 * @throws \Exceptions\InvalidImageUploadException
	 */
	public function Create(array $uploadedFile): void{
		$this->MimeType = ImageMimeType::FromFile($uploadedFile['tmp_name'] ?? null);

		$this->Validate($uploadedFile);

		$this->Created = new DateTime();

		$tags = [];
		foreach($this->Tags as $artworkTag){
			$tags[] = ArtworkTag::GetOrCreate($artworkTag);
		}
		$this->Tags = $tags;

		$this->Artist = Artist::GetOrCreate($this->Artist);

		Db::Query('
			INSERT into
			Artworks (ArtistId, Name, UrlName, CompletedYear, CompletedYearIsCirca, Created, Status, SubmitterUserId, ReviewerUserId, MuseumUrl,
			                      PublicationYear, PublicationYearPageUrl, CopyrightPageUrl, ArtworkPageUrl, IsPublishedInUs,
			                      EbookWwwFilesystemPath, MimeType, Exception, Notes)
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
			        ?)
		', [$this->Artist->ArtistId, $this->Name, $this->UrlName, $this->CompletedYear, $this->CompletedYearIsCirca,
				$this->Created, $this->Status, $this->SubmitterUserId, $this->ReviewerUserId, $this->MuseumUrl, $this->PublicationYear, $this->PublicationYearPageUrl,
				$this->CopyrightPageUrl, $this->ArtworkPageUrl, $this->IsPublishedInUs, $this->EbookWwwFilesystemPath, $this->MimeType, $this->Exception, $this->Notes]
		);

		$this->ArtworkId = Db::GetLastInsertedId();

		foreach($this->Tags as $tag){
			Db::Query('
				INSERT into ArtworkTags (ArtworkId, TagId)
				values (?,
				        ?)
			', [$this->ArtworkId, $tag->TagId]);
		}

		$this->WriteImageAndThumbnails($uploadedFile['tmp_name']);
	}

	/**
	 * @param array<mixed> $uploadedFile
	 * @throws \Exceptions\ValidationException
	 */
	public function Save(array $uploadedFile = []): void{
		$this->_UrlName = null;

		if(!empty($uploadedFile) && $uploadedFile['error'] == UPLOAD_ERR_OK){
			$this->MimeType = ImageMimeType::FromFile($uploadedFile['tmp_name'] ?? null);
		}

		$this->Validate($uploadedFile);

		$this->Updated = new DateTime();

		$tags = [];
		foreach($this->Tags as $artworkTag){
			$tags[] = ArtworkTag::GetOrCreate($artworkTag);
		}
		$this->Tags = $tags;

		$this->Artist = Artist::GetOrCreate($this->Artist);

		Db::Query('
			UPDATE Artworks
			set
			ArtistId = ?,
			Name = ?,
			UrlName = ?,
			CompletedYear = ?,
			CompletedYearIsCirca = ?,
			Created = ?,
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
			EbookWwwFilesystemPath = ?,
			MimeType = ?,
			Exception = ?,
			Notes = ?
			where
			ArtworkId = ?
		', [$this->Artist->ArtistId, $this->Name, $this->UrlName, $this->CompletedYear, $this->CompletedYearIsCirca,
				$this->Created, $this->Updated, $this->Status, $this->SubmitterUserId, $this->ReviewerUserId, $this->MuseumUrl, $this->PublicationYear, $this->PublicationYearPageUrl,
				$this->CopyrightPageUrl, $this->ArtworkPageUrl, $this->IsPublishedInUs, $this->EbookWwwFilesystemPath, $this->MimeType, $this->Exception, $this->Notes,
				$this->ArtworkId]
		);

		Artist::DeleteUnreferencedArtists();

		Db::Query('
			DELETE FROM ArtworkTags
			WHERE
			ArtworkId = ?
		', [$this->ArtworkId]
		);

		foreach($this->Tags as $tag){
			Db::Query('
				INSERT INTO ArtworkTags (ArtworkId, TagId)
				VALUES (?,
				        ?)
			', [$this->ArtworkId, $tag->TagId]);
		}

		if(!empty($uploadedFile) && $uploadedFile['error'] == UPLOAD_ERR_OK){
			$this->WriteImageAndThumbnails($uploadedFile['tmp_name']);
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
	}

	public function Contains(string $query): bool{
		$searchString = $this->Name;

		$searchString .= ' ' . $this->Artist->Name;
		$searchString .= ' ' . implode(' ', $this->Artist->AlternateSpellings);

		foreach($this->Tags as $tag){
			$searchString .= ' ' . $tag->Name;
		}

		// Remove diacritics and non-alphanumeric characters
		$searchString = trim(preg_replace('|[^a-zA-Z0-9 ]|ius', ' ', Formatter::RemoveDiacritics($searchString)));
		$query = trim(preg_replace('|[^a-zA-Z0-9 ]|ius', ' ', Formatter::RemoveDiacritics($query)));

		if($query == ''){
			return false;
		}

		if(mb_stripos($searchString, $query) !== false){
			return true;
		}

		return false;
	}

	// ***********
	// ORM METHODS
	// ***********

	/**
	 * @throws \Exceptions\ArtworkNotFoundException
	 */
	public static function Get(?int $artworkId): Artwork{
		if($artworkId === null){
			throw new Exceptions\ArtworkNotFoundException();
		}

		$result = Db::Query('
				SELECT *
				from Artworks
				where ArtworkId = ?
			', [$artworkId], 'Artwork');

		if(sizeof($result) == 0){
			throw new Exceptions\ArtworkNotFoundException();
		}

		return $result[0];
	}

	/**
         * Looks up an existing artwork regardless of status (unlike GetByUrl()) in order to
         * enforce that the Artist UrlName + Artwork UrlName combo is globally unique.
         */
	/**
	 * @throws \Exceptions\InvalidArtworkException
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
			', [$artistUrlName, $artworkUrlName], 'Artwork');

		if(sizeof($result) == 0){
			throw new Exceptions\ArtworkNotFoundException();
		}

		return $result[0];
	}
}
