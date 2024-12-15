<?
/**
 * @property bool $HasBenefits Are any of the benefits in this object **`TRUE`**?
 * @property bool $RequiresPassword Do any of the benefits in this object require the `User` to have a password set?
 */
class Benefits{
	use Traits\Accessor;
	use Traits\PropertyFromHttp;

	public int $UserId;
	public bool $CanAccessFeeds = false;
	public bool $CanVote = false;
	public bool $CanBulkDownload = false;
	public bool $CanUploadArtwork = false;
	public bool $CanReviewArtwork = false;
	public bool $CanReviewOwnArtwork = false;
	public bool $CanEditUsers = false;
	public bool $CanEditCollections = false;
	public bool $CanEditEbooks = false;
	public bool $CanCreateEbookPlaceholders = false;
	public bool $CanManageProjects = false;
	public bool $CanReviewProjects = false;
	public bool $CanEditProjects = false;

	protected bool $_HasBenefits;

	protected function GetRequiresPassword(): bool{
		if(
			$this->CanUploadArtwork
			||
			$this->CanReviewArtwork
			||
			$this->CanReviewOwnArtwork
			||
			$this->CanEditUsers
			||
			$this->CanEditCollections
			||
			$this->CanEditEbooks
			||
			$this->CanCreateEbookPlaceholders
			||
			$this->CanManageProjects
			||
			$this->CanReviewProjects
			||
			$this->CanEditProjects
		){
			return true;
		}

		return false;
	}

	protected function GetHasBenefits(): bool{
		if(!isset($this->_HasBenefits)){
			$this->_HasBenefits = false;

			/** @phpstan-ignore-next-line */
			foreach($this as $property => $value){
				$rp = new ReflectionProperty(self::class, $property);
				$type = $rp->getType();

				if($type !== null && ($type instanceof \ReflectionNamedType)){
					$typeName = $type->getName();
					if($typeName == 'bool' && $value == true){
						$this->_HasBenefits = true;
						break;
					}
				}
			}
		}

		return $this->_HasBenefits;
	}

	public function Create(): void{
		Db::Query('
				INSERT into Benefits (UserId, CanAccessFeeds, CanVote, CanBulkDownload, CanUploadArtwork, CanReviewArtwork, CanReviewOwnArtwork, CanEditUsers, CanCreateEbookPlaceholders)
				values (?, ?, ?, ?, ?, ?, ?, ?, ?)
		', [$this->UserId, $this->CanAccessFeeds, $this->CanVote, $this->CanBulkDownload, $this->CanUploadArtwork, $this->CanReviewArtwork, $this->CanReviewOwnArtwork, $this->CanEditUsers, $this->CanCreateEbookPlaceholders]);
	}

	public function Save(): void{
		Db::Query('
				UPDATE Benefits
				set CanAccessFeeds = ?, CanVote = ?, CanBulkDownload = ?, CanUploadArtwork = ?, CanReviewArtwork = ?, CanReviewOwnArtwork = ?, CanEditUsers = ?, CanCreateEbookPlaceholders = ?
				where
				UserId = ?
		', [$this->CanAccessFeeds, $this->CanVote, $this->CanBulkDownload, $this->CanUploadArtwork, $this->CanReviewArtwork, $this->CanReviewOwnArtwork, $this->CanEditUsers, $this->CanCreateEbookPlaceholders, $this->UserId]);
	}

	public function FillFromHttpPost(): void{
		$this->PropertyFromHttp('CanAccessFeeds');
		$this->PropertyFromHttp('CanVote');
		$this->PropertyFromHttp('CanBulkDownload');
		$this->PropertyFromHttp('CanUploadArtwork');
		$this->PropertyFromHttp('CanReviewArtwork');
		$this->PropertyFromHttp('CanReviewOwnArtwork');
		$this->PropertyFromHttp('CanEditUsers');
		$this->PropertyFromHttp('CanCreateEbookPlaceholders');
	}
}
