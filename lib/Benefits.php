<?
class Benefits{
	use Traits\PropertyFromRequest;

	public int $UserId;
	public bool $CanAccessFeeds = false;
	public bool $CanVote = false;
	public bool $CanBulkDownload = false;
	public bool $CanUploadArtwork = false;
	public bool $CanReviewArtwork = false;
	public bool $IsArtworkAdmin = false;
	public bool $CanCreateUsers = false;
	public bool $CanEditUsers = false;
	public bool $CanEditPolls = false;
	public bool $CanEditCollections = false;
	public bool $CanEditEbooks = false;
	public bool $CanEditEbookPlaceholders = false;
	public bool $CanEditProjects = false;
	public bool $CanManageProjects = false;
	public bool $CanReviewProjects = false;
	public bool $CanBeAutoAssignedToProjects = false;
	public bool $CanEditBlogPosts = false;
	public bool $CanEditSpreadsheets = false;
	public bool $CanCreateNewsletterMailings = false;
	public bool $CanEditNewsletterMailings = false;
	public bool $CanViewReports = false;

	/** Whether this user can manage or review projects. */
	public bool $IsEditor{
		get{
			if(
				$this->CanManageProjects
				||
				$this->CanReviewProjects
			){
				return true;
			}

			return false;
		}
	}

	/**
	 * Whether any benefit is enabled.
	*/
	public private(set) bool $HasAnyBenefits{
		get{
			if(!isset($this->HasAnyBenefits)){
				$this->HasAnyBenefits = false;

				/** @phpstan-ignore-next-line */
				foreach($this as $property => $value){
					$rp = new ReflectionProperty(self::class, $property);
					$type = $rp->getType();

					if($type !== null && ($type instanceof \ReflectionNamedType)){
						$typeName = $type->getName();
						if($typeName == 'bool' && $value == true){
							$this->HasAnyBenefits = true;
							break;
						}
					}
				}
			}

			return $this->HasAnyBenefits;
		}
	}

	/**
	 * Return whether any non-Patron benefit is enabled.
	 */
	public function HasNonPatronBenefits(): bool{
		return $this->CanUploadArtwork
			|| $this->CanReviewArtwork
			|| $this->IsArtworkAdmin
			|| $this->CanCreateUsers
			|| $this->CanEditUsers
			|| $this->CanEditPolls
			|| $this->CanEditCollections
			|| $this->CanEditEbooks
			|| $this->CanEditEbookPlaceholders
			|| $this->CanEditProjects
			|| $this->CanManageProjects
			|| $this->CanReviewProjects
			|| $this->CanBeAutoAssignedToProjects
			|| $this->CanEditBlogPosts
			|| $this->CanEditSpreadsheets
			|| $this->CanCreateNewsletterMailings
			|| $this->CanEditNewsletterMailings
			|| $this->CanViewReports;
	}

	private function Validate(): void{
		if(!$this->CanManageProjects && !$this->CanReviewProjects){
			$this->CanBeAutoAssignedToProjects = false;
		}
	}

	public function Create(): void{
		$this->Validate();

		Db::Query('
				insert into Benefits (UserId, CanAccessFeeds, CanVote, CanBulkDownload, CanUploadArtwork, CanReviewArtwork, IsArtworkAdmin, CanCreateUsers, CanEditUsers, CanEditPolls, CanEditCollections, CanEditEbooks, CanEditEbookPlaceholders, CanEditProjects, CanManageProjects, CanReviewProjects, CanBeAutoAssignedToProjects, CanEditBlogPosts, CanEditSpreadsheets, CanCreateNewsletterMailings, CanEditNewsletterMailings, CanViewReports)
				values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
		', [$this->UserId, $this->CanAccessFeeds, $this->CanVote, $this->CanBulkDownload, $this->CanUploadArtwork, $this->CanReviewArtwork, $this->IsArtworkAdmin, $this->CanCreateUsers, $this->CanEditUsers, $this->CanEditPolls, $this->CanEditCollections, $this->CanEditEbooks, $this->CanEditEbookPlaceholders, $this->CanEditProjects, $this->CanManageProjects, $this->CanReviewProjects, $this->CanBeAutoAssignedToProjects, $this->CanEditBlogPosts, $this->CanEditSpreadsheets, $this->CanCreateNewsletterMailings, $this->CanEditNewsletterMailings, $this->CanViewReports]);
	}

	public function Save(): void{
		$this->Validate();

		Db::Query('
				update Benefits
				set CanAccessFeeds = ?, CanVote = ?, CanBulkDownload = ?, CanUploadArtwork = ?, CanReviewArtwork = ?, IsArtworkAdmin = ?, CanCreateUsers = ?, CanEditUsers = ?, CanEditPolls = ?, CanEditCollections = ?, CanEditEbooks = ?, CanEditEbookPlaceholders = ?, CanEditProjects = ?, CanManageProjects = ?, CanReviewProjects = ?, CanBeAutoAssignedToProjects = ?, CanEditBlogPosts = ?, CanEditSpreadsheets = ?, CanCreateNewsletterMailings = ?, CanEditNewsletterMailings = ?, CanViewReports = ?
				where
				UserId = ?
		', [$this->CanAccessFeeds, $this->CanVote, $this->CanBulkDownload, $this->CanUploadArtwork, $this->CanReviewArtwork, $this->IsArtworkAdmin, $this->CanCreateUsers, $this->CanEditUsers, $this->CanEditPolls, $this->CanEditCollections, $this->CanEditEbooks, $this->CanEditEbookPlaceholders, $this->CanEditProjects, $this->CanManageProjects, $this->CanReviewProjects, $this->CanBeAutoAssignedToProjects, $this->CanEditBlogPosts, $this->CanEditSpreadsheets, $this->CanCreateNewsletterMailings, $this->CanEditNewsletterMailings, $this->CanViewReports, $this->UserId]);
	}

	public function FillFromRequestBody(): void{
		$this->PropertyFromRequest('CanAccessFeeds');
		$this->PropertyFromRequest('CanVote');
		$this->PropertyFromRequest('CanBulkDownload');
		$this->PropertyFromRequest('CanUploadArtwork');
		$this->PropertyFromRequest('CanReviewArtwork');
		$this->PropertyFromRequest('IsArtworkAdmin');
		$this->PropertyFromRequest('CanCreateUsers');
		$this->PropertyFromRequest('CanEditUsers');
		$this->PropertyFromRequest('CanEditPolls');
		$this->PropertyFromRequest('CanEditCollections');
		$this->PropertyFromRequest('CanEditEbooks');
		$this->PropertyFromRequest('CanEditEbookPlaceholders');
		$this->PropertyFromRequest('CanEditProjects');
		$this->PropertyFromRequest('CanManageProjects');
		$this->PropertyFromRequest('CanReviewProjects');
		$this->PropertyFromRequest('CanBeAutoAssignedToProjects');
		$this->PropertyFromRequest('CanEditBlogPosts');
		$this->PropertyFromRequest('CanEditSpreadsheets');
		$this->PropertyFromRequest('CanCreateNewsletterMailings');
		$this->PropertyFromRequest('CanEditNewsletterMailings');
		$this->PropertyFromRequest('CanViewReports');
	}
}
