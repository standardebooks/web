<?
/**
 * GET		/users/:user-identifier
 * GET		/users/get?user-identifier=:user-identifier
 */

use function Safe\preg_match;
use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	/** @var User $user The `User` for this request, passed in from the router. */
	$user = $resource ?? throw new Exceptions\UserNotFoundException();

	if(preg_match('/^\/users\/get\b/iu', Http::$Request->RelativePath)){
		// If we got here by "jumping" to a `User` from the `User`s index page, redirect to their actual URL instead of the "jump" URL.
		header('location: ' . $user->Url);
		exit();
	}

	$identifier = Http::$Request->QueryString->Get('user-identifier');
	$isCreated = Http::$Request->Session->Get('user/create/is-created', 'bool') ?? false;
	$isSaved = Http::$Request->Session->Get('user/edit/is-saved', 'bool') ?? false;

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanCreateUsers && !Session::$User->Benefits->CanEditUsers){
		throw new Exceptions\PermissionsInvalidException();
	}

	if(!ctype_digit($identifier)){
		http_response_code(Enums\HttpCode::Found->value);
		header('location: ' . $user->Url);
		exit();
	}

	// We got here because a `User` was successfully created or saved.
	if($isCreated || $isSaved){
		session_unset();
	}
}
catch(Exceptions\UserNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
?><?= Template::Header(
	title: $user->DisplayName,
	canonicalUrl: $user->Url,
	css: ['/css/user.css', '/css/project.css']
) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
			<a href="/users">Users</a> →
		</nav>
		<h1><?= Formatter::EscapeHtml($user->DisplayName) ?></h1>

		<ul role="menu">
			<li><a href="<?= $user->EditUrl ?>">Edit user</a></li>
			<? if($user->Benefits->CanManageProjects || $user->Benefits->CanReviewProjects){ ?>
				<li><a href="<?= $user->Url ?>/projects">Projects overseeing</a></li>
			<? } ?>
		</ul>

		<? if($isSaved){ ?>
			<p class="message success">User saved!</p>
		<? } ?>

		<? if($isCreated){ ?>
			<p class="message success">User created!</p>
		<? } ?>

		<h2>Basics</h2>
		<dl>
			<dt>User ID:</dt>
			<dd class="id"><?= $user->UserId ?></dd>
			<dt>Email:</dt>
			<dd><?= Formatter::EscapeHtml($user->Email) ?></dd>
			<dt>Name:</dt>
			<dd><?= Formatter::EscapeHtml($user->Name) ?></dd>
			<dt>UUID:</dt>
			<dd class="id"><?= Formatter::EscapeHtml($user->Uuid) ?></dd>
			<dt>Created:</dt>
			<dd><?= $user->Created->format(Enums\DateTimeFormat::FullDateTime->value) ?></dd>
		</dl>

		<h2>Patron info</h2>
		<dl>
			<dt>Is Patron:</dt>
			<dd><? if($user->Patron !== null && $user->Patron->Ended === null){ ?>☑<? }else{ ?>☐<? } ?></dd>
			<? if($user->Patron !== null && $user->Patron->Ended === null){ ?>
				<dt>Created:</dt>
				<dd><?= $user->Patron->Created->format(Enums\DateTimeFormat::FullDateTime->value) ?></dd>
				<dt>Cycle type:</dt>
				<dd>
					<? if($user->Patron->CycleType !== null){ ?>
						<?= ucfirst($user->Patron->CycleType->value) ?>
					<? }else{ ?>
						<i>Not set</i>
					<? } ?>
				</dd>
				<dt>Base cost:</dt>
				<dd>
					<? if($user->Patron->BaseCost !== null){ ?>
						<?= Formatter::FormatCurrency($user->Patron->BaseCost) ?>
					<? }else{ ?>
						<i>Not set</i>
					<? } ?>
				</dd>
				<dt>Is anonymous:</dt>
				<dd><? if($user->Patron->IsAnonymous){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<? if($user->Patron->AlternateName !== null){ ?>
					<dt>Alternate credit:</dt>
					<dd><?= Formatter::EscapeHtml($user->Patron->AlternateName) ?></dd>
				<? } ?>
			<? } ?>
		</dl>

		<h2>Newsletter subscriptions</h2>
		<? if(sizeof($user->NewsletterSubscriptions) == 0){ ?>
			<p class="empty-notice">None.</p>
		<? }else{ ?>
			<ul>
				<? foreach($user->NewsletterSubscriptions as $newsletterSubscription){ ?>
					<li>
						<p><?= Formatter::EscapeHtml($newsletterSubscription->Newsletter->Name) ?> • <a href="<?= $newsletterSubscription->DeleteUrl ?>">Unsubscribe</a></p>
					</li>
				<? } ?>
			</ul>
		<? } ?>

		<h2>Registration info</h2>
		<dl>
			<dt class="break">Requires password to log in:</dt>
			<dd><? if($user->Benefits->RequiresPassword){ ?>☑<? }else{ ?>☐<? } ?></dd>
			<? if($user->RequiresPassword){ ?>
				<dt>Can access feeds:</dt>
				<dd><? if($user->Benefits->CanAccessFeeds){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt>Can vote:</dt>
				<dd><? if($user->Benefits->CanVote){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt class="break">Can bulk download:</dt>
				<dd><? if($user->Benefits->CanBulkDownload){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt>Can upload artwork:</dt>
				<dd><? if($user->Benefits->CanUploadArtwork){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt>Can review artwork:</dt>
				<dd><? if($user->Benefits->CanReviewArtwork){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt>Is artwork admin:</dt>
				<dd><? if($user->Benefits->IsArtworkAdmin){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt>Can create users:</dt>
				<dd><? if($user->Benefits->CanCreateUsers){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt>Can edit users:</dt>
				<dd><? if($user->Benefits->CanEditUsers){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt>Can edit polls:</dt>
				<dd><? if($user->Benefits->CanEditPolls){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt>Can edit collections:</dt>
				<dd><? if($user->Benefits->CanEditCollections){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt>Can edit ebooks:</dt>
				<dd><? if($user->Benefits->CanEditEbooks){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt>Can edit ebook placeholders:</dt>
				<dd><? if($user->Benefits->CanEditEbookPlaceholders){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt>Can edit projects:</dt>
				<dd><? if($user->Benefits->CanEditProjects){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt>Can manage projects:</dt>
				<dd><? if($user->Benefits->CanManageProjects){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt>Can review projects:</dt>
				<dd><? if($user->Benefits->CanReviewProjects){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt>Can be auto-assigned to projects:</dt>
				<dd><? if($user->Benefits->CanBeAutoAssignedToProjects){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt>Can edit blog posts:</dt>
				<dd><? if($user->Benefits->CanEditBlogPosts){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt>Can edit spreadsheets:</dt>
				<dd><? if($user->Benefits->CanEditSpreadsheets){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt>Can create newsletter mailings:</dt>
				<dd><? if($user->Benefits->CanCreateNewsletterMailings){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt>Can edit newsletter mailings:</dt>
				<dd><? if($user->Benefits->CanEditNewsletterMailings){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<dt>Can view reports:</dt>
				<dd><? if($user->Benefits->CanViewReports){ ?>☑<? }else{ ?>☐<? } ?></dd>
			<? } ?>
		</dl>

		<h2>Payments</h2>
		<? if(sizeof($user->Payments) == 0){ ?>
			<p class="empty-notice">None.</p>
		<? }else{ ?>
			<p>
				<a href="https://fundraising.fracturedatlas.org/admin/general_support/donations?query=<?= urlencode($user->Email ?? '') ?>">View all payments at Fractured Atlas</a>
			</p>
			<table class="data-table payments">
				<thead>
					<tr>
						<th>Created</th>
						<th>Recurring?</th>
						<th>Gross</th>
						<th>Fee</th>
						<th>Net</th>
						<th>Transaction ID</th>
					</tr>
				</thead>
				<tbody>
					<? foreach($user->Payments as $payment){ ?>
						<tr>
							<td>
								<? if($payment->Refunded !== null){ ?>
									(<i>Refunded.</i>)
								<? } ?>
								<time datetime="<?= $payment->Created->format(Enums\DateTimeFormat::Html->value) ?>"><?= $payment->Created->format(Enums\DateTimeFormat::FullDateTime->value) ?></time>
							</td>
							<td>
								<? if($payment->IsRecurring){ ?>
									☑
								<? }else{ ?>
									☐
								<? } ?>
							</td>
							<td>
								<?= Formatter::FormatCurrency($payment->Amount) ?>
							</td>
							<td>
								<?= Formatter::FormatCurrency($payment->Fee) ?>
							</td>
							<td>
								<?= Formatter::FormatCurrency($payment->Amount - $payment->Fee) ?>
							</td>
							<td>
								<a href="<?= $payment->ProcessorUrl ?>"><?= Formatter::EscapeHtml($payment->TransactionId) ?></a>
							</td>
						</tr>
					<? } ?>
				</tbody>
			</table>
		<? } ?>

		<h2>Projects</h2>
		<? if(sizeof($user->ProjectsProducing) == 0){ ?>
			<p class="empty-notice">None.</p>
		<? }else{ ?>
			<?= Template::ProjectsTable(projects: $user->ProjectsProducing, showContactInformation: true, isAdminView: Session::$User->Benefits->CanEditCollections) ?>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
