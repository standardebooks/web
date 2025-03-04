<?
use Safe\DateTimeImmutable;

/**
 * @method static string ArtworkForm(Artwork $artwork, $isEditForm = false)
 * @method static string ArtworkList(array<Artwork> $artworks)
 * @method static string AtomFeed(string $id, string $url, string $title, ?string $subtitle = null, DateTimeImmutable $updated, array<Ebook> $entries)
 * @method static string AtomFeedEntry(Ebook $entry)
 * @method static string BulkDownloadTable(string $label, array<stdClass> $collections)
 * @method static string CollectionDescriptor(?CollectionMembership $collectionMembership)
 * @method static string ContributeAlert()
 * @method static string DonationAlert()
 * @method static string DonationCounter(bool $autoHide = true, bool $showDonateButton = true)
 * @method static string DonationProgress(bool $autoHide = true, bool $showDonateButton = true)
 * @method static string EbookCarousel(array<Ebook> $carousel, bool $isMultiSize = false)
 * @method static string EbookGrid(array<Ebook> $ebooks, ?Collection $collection = null, Enums\ViewType $view = Enums\ViewType::Grid)
 * @method static string EbookMetadata(Ebook $ebook, bool $showPlaceholderMetadata = false)
 * @method static string EbookPlaceholderForm(Ebook $ebook, bool $isEditForm = false, bool $showProjectForm = true)
 * @method static string EmailAdminNewPatron(Patron $patron, Payment $payment)
 * @method static string EmailAdminNewPatronText(Patron $patron, Payment $payment)
 * @method static string EmailAdminUnprocessedDonations()
 * @method static string EmailAdminUnprocessedDonationsText()
 * @method static string EmailDonationProcessingFailed(string $exception)
 * @method static string EmailDonationProcessingFailedText(string $exception)
 * @method static string EmailDonationThankYou()
 * @method static string EmailDonationThankYouText()
 * @method static string EmailFooter(bool $includeLinks = true)
 * @method static string EmailFooterText()
 * @method static string EmailHeader(?string $preheader = null, bool $hasLetterhead = false, bool $hasAdminTable = false)
 * @method static string EmailManagerNewProject(Project $project, string $role, User $user)
 * @method static string EmailManagerNewProjectText(Project $project, string $role, User $user)
 * @method static string EmailNewsletterConfirmation(bool $isSubscribedToNewsletter, bool $isSubscribedToSummary, NewsletterSubscription $subscription)
 * @method static string EmailNewsletterConfirmationText(bool $isSubscribedToNewsletter, bool $isSubscribedToSummary, NewsletterSubscription $subscription)
 * @method static string EmailPatronsCircleCompleted(int $ebooksThisYear)
 * @method static string EmailPatronsCircleCompletedText(int $ebooksThisYear)
 * @method static string EmailPatronsCircleRecurringCompleted()
 * @method static string EmailPatronsCircleRecurringCompletedText()
 * @method static string EmailPatronsCircleWelcome(bool $isAnonymous, bool $isReturning)
 * @method static string EmailPatronsCircleWelcomeText(bool $isAnonymous, bool $isReturning)
 * @method static string EmailProjectAbandoned()
 * @method static string EmailProjectAbandonedText()
 * @method static string EmailProjectStalled()
 * @method static string EmailProjectStalledText()
 * @method static string Error(?Exception $exception)
 * @method static string FeedHowTo()
 * @method static string Footer()
 * @method static string Header(?string $title = null, ?string $highlight = null, ?string $description = null, bool $isManual = false, bool $isXslt = false, ?string $feedUrl = null, ?string $feedTitle = null, bool $isErrorPage = false, ?string $downloadUrl = null, ?string $canonicalUrl = null, ?string $coverUrl = null, string $ogType = 'website', array<string> $css = [])
 * @method static string ImageCopyrightNotice()
 * @method static string OpdsAcquisitionEntry(Ebook $entry)
 * @method static string OpdsAcquisitionFeed(string $id, string $url, string $parentUrl, string $title, ?string $subtitle, DateTimeImmutable $updated, array<Ebook> $entries, bool $isCrawlable = false)
 * @method static string OpdsNavigationFeed(string $id, string $url, ?string $parentUrl, string $title, ?string $subtitle, DateTimeImmutable $updated, array<OpdsNavigationEntry> $entries)
 * @method static string ProjectDetailsTable(Project $project, bool $useFullyQualifiedUrls = false, bool $showTitle = true, bool $showArtworkStatus = true)
 * @method static string ProjectForm(Project $project, $areFieldsRequired = true, $isEditForm = false)
 * @method static string ProjectsTable(array<Project> $projects, bool $includeTitle = true, bool $includeStatus = true, bool $showEditButton = false)
 * @method static string RealisticEbook(Ebook $ebook)
 * @method static string RssEntry(Ebook $entry)
 * @method static string RssFeed(string $title, string $description, DateTimeImmutable $updated, string $url, array<Ebook> $entries)
 * @method static string SearchForm(string $query, array<string> $tags, Enums\EbookSortType $sort, Enums\ViewType $view, int $perPage)
 * @method static string UserForm(User $user, Enums\PasswordActionType $passwordAction, bool $generateNewUuid, bool $isEditForm = false)
 * @method static string WantedEbooksList(array<Ebook> $ebooks, bool $showPlaceholderMetadata)
 */
class Template extends TemplateBase{
	/**
	 * Redirect the user to the login page.
	 *
	 * @param bool $redirectToDestination After login, redirect the user to the page they came from.
	 * @param ?string $destinationUrl If `$redirectToDestination` is **`TRUE`**, redirect to this URL instead of hte page they came from.
	 *
	 * @return never
	 */
	public static function RedirectToLogin(bool $redirectToDestination = true, ?string $destinationUrl = null): void{
		if($redirectToDestination){
			if($destinationUrl === null){
				/** @var string $destinationUrl */
				$destinationUrl = $_SERVER['SCRIPT_URL'];
			}

			header('Location: /sessions/new?redirect=' . urlencode($destinationUrl));
		}
		else{
			header('Location: /sessions/new');
		}

		exit();
	}

	public static function IsEreaderBrowser(): bool{
		/** @var string $httpUserAgent */
		$httpUserAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
		return $httpUserAgent != '' && (strpos($httpUserAgent, "Kobo") !== false || strpos($httpUserAgent, "Kindle") !== false);
	}
}
