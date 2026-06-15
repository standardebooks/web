<?
/**
 * GET		/ebooks/:url-path
 */

use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	/** @var Ebook $ebook The `Ebook` for this request, passed in from the router. */
	$ebook = $resource ?? throw new Exceptions\EbookNotFoundException();

	if($ebook->EbookPlaceholder === null){
		throw new Exceptions\EbookNotFoundException();
	}

	$isSaved = Http::$Request->Session->Get('ebook-placeholder/edit/is-saved', 'bool') ?? false;
	$isProjectSaved = Http::$Request->Session->Get('project/edit/is-saved', 'bool') ?? false;

	if($isSaved || $isProjectSaved){
		session_unset();
	}
}
catch(Exceptions\EbookNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
?><?= Template::Header(
	title: strip_tags($ebook->TitleWithCreditsHtml),
	css: ['/css/ebook-placeholder.css'],
	highlight: 'ebooks',
	canonicalUrl: SITE_URL . $ebook->Url
)
?>
<main>
	<article class="ebook ebook-placeholder" typeof="schema:Book" about="<?= $ebook->Url ?>">
		<header>
			<hgroup>
				<h1 property="schema:name"><?= Formatter::EscapeHtml($ebook->Title) ?></h1>
				<? foreach($ebook->Authors as $author){ ?>
					<?
						/* We include the `resource` attr here because we can have multiple authors, and in that case their href URLs will link to their combined corpus.

						For example, William Wordsworth & Samuel Coleridge will both link to `/ebooks/william-wordsworth_samuel-taylor-coleridge`.

						But, each author is an individual, so we have to differentiate them in RDFa with `resource`.
					*/ ?>
					<? if($author->Name != 'Anonymous'){ ?>
						<p>
							<a property="schema:author" typeof="schema:Person" href="<?= Formatter::EscapeHtml($ebook->AuthorsUrl) ?>" resource="<?= '/ebooks/' . $author->UrlName ?>">
								<span property="schema:name"><?= Formatter::EscapeHtml($author->Name) ?></span>
								<meta property="schema:url" content="<?= SITE_URL . Formatter::EscapeHtml($ebook->AuthorsUrl) ?>"/>
								<? if($author->NacoafUrl){ ?>
									<meta property="schema:sameAs" content="<?= Formatter::EscapeHtml($author->NacoafUrl) ?>"/>
								<? } ?>
								<? if($author->WikipediaUrl){ ?>
									<meta property="schema:sameAs" content="<?= Formatter::EscapeHtml($author->WikipediaUrl) ?>"/>
								<? } ?>
							</a>
						</p>
					<? } ?>
				<? } ?>
			</hgroup>
		</header>

		<? if($isSaved){ ?>
			<p class="message success">Ebook placeholder saved!</p>
		<? } ?>

		<? if($isProjectSaved){ ?>
			<p class="message success">Project saved!</p>
		<? } ?>

		<? if($ebook->ContributorsHtml != '' || sizeof($ebook->CollectionMemberships) > 0){ ?>
			<aside id="reading-ease">
				<? if($ebook->ContributorsHtml != ''){ ?>
					<p><?= $ebook->ContributorsHtml ?></p>
				<? } ?>
				<? if(sizeof($ebook->CollectionMemberships) > 0){ ?>
					<? foreach($ebook->GetCollectionsHtml() as $line){ ?>
						<p><?= $line ?></p>
					<? } ?>
				<? } ?>
			</aside>
		<? } ?>

		<section class="placeholder-details" id="placeholder-details">
			<? if($ebook->EbookPlaceholder->IsPublicDomain){ ?>
				<? if($ebook->EbookPlaceholder->IsInProgress){ ?>
					<p>We don’t have this ebook in our catalog yet, but someone is working on it now! We hope to have it available for you to read very soon.</p>
				<? }else{ ?>
					<p>We don’t have this ebook in our catalog yet, but it’s <? if($ebook->EbookPlaceholder->IsWanted){ ?>on our <a href="/contribute/wanted-ebooks">Wanted Ebooks list</a><? }else{ ?>in the U.S. public domain<? } ?>!</p>
					<h2>Get this ebook made</h2>
					<ul>
						<li>
							<p><a href="/donate#sponsor-an-ebook">Sponsor this ebook</a> and we’ll get working on it immediately, so that you and everyone can read it for free forever. You can also choose to have your name inscribed in the ebook’s colophon.</p>
						</li>
						<? if(!$ebook->EbookPlaceholder->IsWanted){ ?>
							<li>
								<p><a href="/donate#patrons-circle">Join the Patrons Circle</a> to request that we add this book to our <a href="/contribute/wanted-ebooks">Wanted Ebooks list</a>. Books on this list have a high priority of getting produced, as our volunteers often select from this list when deciding what to work on next. Our Patrons may add one book to the Wanted Ebooks list per quarter.</p>
							</li>
						<? } ?>
						<li>
							<? if($ebook->EbookPlaceholder->Difficulty == Enums\EbookPlaceholderDifficulty::Beginner){ ?>
								<p><a href="/contribute#technical-contributors">Produce this ebook yourself</a> and your work will allow others to read it for free forever. <em>This book is a good choice to start with if you’ve never created an ebook for us before</em>—we’ll help you through the process!</p>
							<? }else{ ?>
								<p>If you’ve created an ebook for us before, you can <a href="/contribute#technical-contributors">produce this ebook yourself</a> so that others can read it for free. Your name will be inscribed in the colophon as the ebook’s producer.</p>
							<? } ?>
						</li>
					</ul>
				<? } ?>
			<? }elseif($ebook->EbookPlaceholder->YearPublished !== null){ ?>
				<p>This book was published in <?= $ebook->EbookPlaceholder->YearPublished ?>, and will therefore enter the U.S. public domain <? if($ebook->EbookPlaceholder->YearPublished >= 1978){ ?><b><?= $ebook->EbookPlaceholder->TimeTillIsPublicDomain ?>.</b><? }else{ ?>in <?= $ebook->EbookPlaceholder->TimeTillIsPublicDomain ?> on <b>January 1, <?= $ebook->EbookPlaceholder->YearPublished + 96 ?>.</b><? } ?></p>
				<p><a href="/about/standard-ebooks-and-the-public-domain">Read more about Standard Ebooks and the U.S. Public Domain.</a></p>
			<? }else{ ?>
				<p>This book is not yet in the U.S. public domain. We can’t offer it until it is.</p>
			<? } ?>
		</section>

		<? if(Session::$User?->Benefits->CanEditEbooks || Session::$User?->Benefits->CanEditEbookPlaceholders){ ?>
			<?= Template::EbookMetadata(ebook: $ebook, showPlaceholderMetadata: Session::$User->Benefits->CanEditEbookPlaceholders) ?>
		<? } ?>

		<? if(Session::$User?->Benefits->CanEditProjects || Session::$User?->Benefits->CanManageProjects || Session::$User?->Benefits->CanReviewProjects){ ?>
			<? if($ebook->ProjectInProgress !== null){ ?>
				<section id="projects-in-progress" class="admin">
					<h2>Project in progress</h2>
					<? if(Session::$User->Benefits->CanEditProjects){ ?>
						<ul role="menu">
							<li><a href="<?= $ebook->ProjectInProgress->EditUrl ?>">Edit project</a></li>
						</ul>
					<? } ?>
					<dl>
						<dt>Project ID:</dt>
						<dd class="id"><?= $ebook->ProjectInProgress->ProjectId ?></dd>
						<dt>Producer:</dt>
						<dd>
							<? if(Session::$User->Benefits->CanEditProjects){ ?>
								<a href="<?= $ebook->ProjectInProgress->Producer->Url ?>"><?= Formatter::EscapeHtml($ebook->ProjectInProgress->Producer->DisplayName) ?></a>
							<? }elseif($ebook->ProjectInProgress->Producer->Email !== null){ ?>
								<a href="mailto:<?= Formatter::EscapeHtml($ebook->ProjectInProgress->Producer->Email) ?>"><?= Formatter::EscapeHtml($ebook->ProjectInProgress->Producer->DisplayName) ?></a>
							<? }elseif($ebook->ProjectInProgress->DiscussionUrl !== null){ ?>
								<a href="<?= Formatter::EscapeHtml($ebook->ProjectInProgress->DiscussionUrl) ?>"><?= Formatter::EscapeHtml($ebook->ProjectInProgress->Producer->DisplayName) ?></a>
							<? }else{ ?>
								<?= Formatter::EscapeHtml($ebook->ProjectInProgress->Producer->DisplayName) ?>
							<? } ?>
						</dd>
						<dt>Manager:</dt>
						<dd>
							<a href="<?= $ebook->ProjectInProgress->Manager->Url ?>/projects"><?= Formatter::EscapeHtml($ebook->ProjectInProgress->Manager->DisplayName) ?></a>
						</dd>
						<dt>Reviewer:</dt>
						<dd>
							<a href="<?= $ebook->ProjectInProgress->Reviewer->Url ?>/projects"><?= Formatter::EscapeHtml($ebook->ProjectInProgress->Reviewer->DisplayName) ?></a>
						</dd>
						<dt>Started on:</dt>
						<dd>
							<? if(intval($ebook->ProjectInProgress->Started->format('Y')) == intval(NOW->format('Y'))){ ?>
								<?= $ebook->ProjectInProgress->Started->format(Enums\DateTimeFormat::ShortDateWithoutYear->value) ?>
							<? }else{ ?>
								<?= $ebook->ProjectInProgress->Started->format(Enums\DateTimeFormat::ShortDate->value) ?>
							<? } ?>
						</dd>
						<dt>Last activity:</dt>
						<dd>
							<? if(intval($ebook->ProjectInProgress->LastActivityTimestamp->format('Y')) == intval(NOW->format('Y'))){ ?>
								<?= $ebook->ProjectInProgress->LastActivityTimestamp->format(Enums\DateTimeFormat::ShortDateWithoutYear->value) ?>
							<? }else{ ?>
								<?= $ebook->ProjectInProgress->LastActivityTimestamp->format(Enums\DateTimeFormat::ShortDate->value) ?>
							<? } ?>
						</dd>
						<? if($ebook->ProjectInProgress->VcsUrl !== null){ ?>
							<dt>Repository:</dt>
							<dd>
								<a href="<?= Formatter::EscapeHtml($ebook->ProjectInProgress->VcsUrl) ?>"><?= Formatter::EscapeHtml($ebook->ProjectInProgress->VcsUrlDomain) ?></a>
							</dd>
						<? } ?>
						<? if($ebook->ProjectInProgress->DiscussionUrl !== null){ ?>
							<dt>Discussion:</dt>
							<dd>
								<a href="<?= Formatter::EscapeHtml($ebook->ProjectInProgress->DiscussionUrl) ?>"><?= Formatter::EscapeHtml($ebook->ProjectInProgress->DiscussionUrlDomain) ?></a>
							</dd>
						<? } ?>
						<dt>Cover art:</dt>
						<dd>
							<? if($ebook->ProjectInProgress->Ebook->Artwork !== null){ ?>
								<i>
									<a href="<?= $ebook->ProjectInProgress->Ebook->Artwork->Url ?>"><?= Formatter::EscapeHtml($ebook->ProjectInProgress->Ebook->Artwork->Name) ?></a>
								</i> (<?= ucfirst($ebook->ProjectInProgress->Ebook->Artwork->Status->value) ?>)
							<? }else{ ?>
								<i>None.</i>
							<? } ?>
						</dd>
						<dt>Status:</dt>
						<dd>
							<? if(
								Session::$User->Benefits->CanEditProjects
								||
								$ebook->ProjectInProgress->ManagerUserId == Session::$User->UserId
								||
								$ebook->ProjectInProgress->ReviewerUserId == Session::$User->UserId
							){ ?>

								<form action="<?= $ebook->ProjectInProgress->Url ?>" method="<?= Enums\HttpMethod::Post->value ?>" class="single-line-form">
									<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Patch->value ?>" />
									<label class="icon meter">
										<select name="project-status">
											<option value="<?= Enums\ProjectStatusType::InProgress->value ?>"<? if($ebook->ProjectInProgress->Status == Enums\ProjectStatusType::InProgress){?> selected="selected"<? } ?>>In progress</option>
											<option value="<?= Enums\ProjectStatusType::AwaitingReview->value ?>"<? if($ebook->ProjectInProgress->Status == Enums\ProjectStatusType::AwaitingReview){?> selected="selected"<? } ?>>Awaiting review</option>
											<option value="<?= Enums\ProjectStatusType::Reviewed->value ?>"<? if($ebook->ProjectInProgress->Status == Enums\ProjectStatusType::Reviewed){?> selected="selected"<? } ?>>Reviewed</option>
											<option value="<?= Enums\ProjectStatusType::Stalled->value ?>"<? if($ebook->ProjectInProgress->Status == Enums\ProjectStatusType::Stalled){?> selected="selected"<? } ?>>Stalled</option>
											<option value="<?= Enums\ProjectStatusType::Completed->value ?>"<? if($ebook->ProjectInProgress->Status == Enums\ProjectStatusType::Completed){?> selected="selected"<? } ?>>Completed</option>
											<option value="<?= Enums\ProjectStatusType::Abandoned->value ?>"<? if($ebook->ProjectInProgress->Status == Enums\ProjectStatusType::Abandoned){?> selected="selected"<? } ?>>Abandoned</option>
										</select>
									</label>
									<button>Save changes</button>
								</form>
							<? }else{ ?>
								<?= ucfirst($ebook->ProjectInProgress->Status->GetDisplayName()) ?>
							<? } ?>
						</dd>
					</dl>
				</section>
			<? } ?>

			<section id="past-projects" class="admin">
				<h2>Past projects</h2>
				<? if(Session::$User->Benefits->CanEditProjects && $ebook->ProjectInProgress === null){ ?>
					<ul role="menu">
						<li><a href="<?= $ebook->Url ?>/projects/new">New project</a></li>
					</ul>
				<? } ?>
				<? if(sizeof($ebook->PastProjects) == 0){ ?>
					<p class="empty-notice">None.</p>
				<? }else{ ?>
					<?= Template::ProjectsTable(projects: $ebook->PastProjects, includeTitle: false, showEditButton: Session::$User->Benefits->CanEditProjects, showContactInformation: true, isAdminView: Session::$User->Benefits->CanEditProjects, includeStatus: false) ?>
				<? } ?>
			</section>
		<? } ?>
	</article>
</main>
<?= Template::Footer() ?>
