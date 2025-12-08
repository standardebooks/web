<?
/**
 * @property-read bool $HasBenefits Are any of the benefits in this object **`TRUE`**?
 * @property-read bool $RequiresPassword Do any of the benefits in this object require the `User` to have a password set?
 * @property-read bool $IsEditor Can this `User` manage or review projects?
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
	public bool $CanEditEbookPlaceholders = false;
	public bool $CanManageProjects = false;
	public bool $CanReviewProjects = false;
	public bool $CanEditProjects = false;
	public bool $CanBeAutoAssignedToProjects = false;
	public bool $CanCreateUsers = false;
	public bool $CanEditBlogPosts = false;

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
			$this->CanEditEbookPlaceholders
			||
			$this->CanManageProjects
			||
			$this->CanReviewProjects
			||
			$this->CanEditProjects
			||
			$this->CanBeAutoAssignedToProjects
			||
			$this->CanCreateUsers
			||
			$this->CanEditBlogPosts
		){
			return true;
		}

		return false;
	}

	protected function GetIsEditor(): bool{
		if(
			$this->CanManageProjects
			||
			$this->CanReviewProjects
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
				INSERT into Benefits (UserId, CanAccessFeeds, CanVote, CanBulkDownload, CanUploadArtwork, CanReviewArtwork, CanReviewOwnArtwork, CanEditUsers, CanEditCollections, CanEditEbooks, CanEditEbookPlaceholders, CanManageProjects, CanReviewProjects, CanBeAutoAssignedToProjects, CanCreateUsers, CanEditBlogPosts)
				values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
		', [$this->UserId, $this->CanAccessFeeds, $this->CanVote, $this->CanBulkDownload, $this->CanUploadArtwork, $this->CanReviewArtwork, $this->CanReviewOwnArtwork, $this->CanEditUsers, $this->CanEditCollections, $this->CanEditEbooks, $this->CanEditEbookPlaceholders, $this->CanManageProjects, $this->CanReviewProjects, $this->CanBeAutoAssignedToProjects, $this->CanCreateUsers, $this->CanEditBlogPosts]);
	}

	public function Save(): void{
		Db::Query('
				UPDATE Benefits
				set CanAccessFeeds = ?, CanVote = ?, CanBulkDownload = ?, CanUploadArtwork = ?, CanReviewArtwork = ?, CanReviewOwnArtwork = ?, CanEditUsers = ?, CanEditEbookPlaceholders = ?, CanCreateUsers = ?, CanEditBlogPosts = ?
				where
				UserId = ?
		', [$this->CanAccessFeeds, $this->CanVote, $this->CanBulkDownload, $this->CanUploadArtwork, $this->CanReviewArtwork, $this->CanReviewOwnArtwork, $this->CanEditUsers, $this->CanEditEbookPlaceholders, $this->CanCreateUsers, $this->CanEditBlogPosts, $this->UserId]);
	}

	public function FillFromHttpPost(): void{
		$this->PropertyFromHttp('CanAccessFeeds');
		$this->PropertyFromHttp('CanVote');
		$this->PropertyFromHttp('CanBulkDownload');
		$this->PropertyFromHttp('CanUploadArtwork');
		$this->PropertyFromHttp('CanReviewArtwork');
		$this->PropertyFromHttp('CanReviewOwnArtwork');
		$this->PropertyFromHttp('CanEditUsers');
		$this->PropertyFromHttp('CanEditEbookPlaceholders');
		$this->PropertyFromHttp('CanEditProjects');
		$this->PropertyFromHttp('CanReviewProjects');
		$this->PropertyFromHttp('CanManageProjects');
		$this->PropertyFromHttp('CanCreateUsers');
		$this->PropertyFromHttp('CanEditBlogPosts');
	}
}
