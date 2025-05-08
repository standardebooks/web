<?
use function Safe\session_start;
use function Safe\session_unset;

session_start();

$isSaved = HttpInput::Bool(SESSION, 'is-user-saved') ?? false;

try{
	$user = User::GetByIdentifier(HttpInput::Str(GET, 'user-identifier'));

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditUsers){
		throw new Exceptions\InvalidPermissionsException();
	}

	// We got here because a `User` was successfully saved.
	if($isSaved){
		session_unset();
	}
}
catch(Exceptions\UserNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
?><?= Template::Header(
	title: $user->DisplayName,
	canonicalUrl: $user->Url,
	css: ['/css/user.css']
) ?>
<main>
	<section class="narrow">
		<h1><?= Formatter::EscapeHtml($user->DisplayName) ?></h1>

		<? if($isSaved){ ?>
			<p class="message success">User saved!</p>
		<? } ?>

		<p>
			<a href="<?= $user->EditUrl ?>">Edit user</a>
		</p>

		<? if($user->Benefits->CanManageProjects || $user->Benefits->CanReviewProjects){ ?>
			<p>
				<a href="<?= $user->Url ?>/projects">Projects</a>
			</p>
		<? } ?>

		<h2>Basics</h2>
		<table class="admin-table">
			<tbody>
				<tr>
					<td>User ID:</td>
					<td><?= $user->UserId ?></td>
				</tr>
				<tr>
					<td>Email:</td>
					<td><?= Formatter::EscapeHtml($user->Email) ?></td>
				</tr>
				<tr>
					<td>Name:</td>
					<td><?= Formatter::EscapeHtml($user->Name) ?></td>
				</tr>
				<tr>
					<td>UUID:</td>
					<td><?= Formatter::EscapeHtml($user->Uuid) ?></td>
				</tr>
				<tr>
					<td>Created:</td>
					<td><?= $user->Created->format(Enums\DateTimeFormat::FullDateTime->value) ?></td>
				</tr>
			</tbody>
		</table>

		<h2>Patron info</h2>
		<table class="admin-table">
			<tbody>
				<tr>
					<td>Is Patron:</td>
					<td><? if($user->Patron !== null && $user->Patron->Ended === null){ ?>☑<? }else{ ?>☐<? } ?></td>
				</tr>
				<? if($user->Patron !== null && $user->Patron->Ended === null){ ?>
					<tr>
						<td>Created:</td>
						<td><?= $user->Patron->Created->format(Enums\DateTimeFormat::FullDateTime->value) ?></td>
					</tr>
					<tr>
						<td>Cycle type:</td>
						<td>
							<? if($user->Patron->CycleType !== null){ ?>
								<?= ucfirst($user->Patron->CycleType->value) ?>
							<? }else{ ?>
								<i>Not set</i>
							<? } ?>
						</td>
					</tr>
					<tr>
						<td>Base cost:</td>
						<td>
							<? if($user->Patron->BaseCost !== null){ ?>
								<?= Formatter::FormatCurrency($user->Patron->BaseCost) ?>
							<? }else{ ?>
								<i>Not set</i>
							<? } ?>
						</td>
					</tr>
					<tr>
						<td>Is anonymous:</td>
						<td><? if($user->Patron->IsAnonymous){ ?>☑<? }else{ ?>☐<? } ?></td>
					</tr>
					<? if($user->Patron->AlternateName !== null){ ?>
						<tr>
							<td>Alternate credit:</td>
							<td><?= Formatter::EscapeHtml($user->Patron->AlternateName) ?></td>
						</tr>
					<? } ?>
				<? } ?>
			</tbody>
		</table>

		<h2>Newsletter subscriptions</h2>
		<? if($user->NewsletterSubscription === null || (!$user->NewsletterSubscription->IsSubscribedToNewsletter && !$user->NewsletterSubscription->IsSubscribedToSummary)){ ?>
			<p class="empty-notice">None.</p>
		<? }else{ ?>
			<ul>
				<? if($user->NewsletterSubscription->IsSubscribedToNewsletter){ ?>
					<li>General newsletter</li>
				<? } ?>
				<? if($user->NewsletterSubscription->IsSubscribedToSummary){ ?>
					<li>Monthly summary newsletter</li>
				<? } ?>
			</ul>
		<? } ?>

		<h2>Registration info</h2>
		<table class="admin-table">
			<tbody>
				<tr>
					<td>Is registered:</td>
					<td><? if($user->IsRegistered){ ?>☑<? }else{ ?>☐<? } ?></td>
				</tr>
				<? if($user->IsRegistered){ ?>
					<tr>
						<td>Can access feeds:</td>
						<td><? if($user->Benefits->CanAccessFeeds){ ?>☑<? }else{ ?>☐<? } ?></td>
					</tr>
					<tr>
						<td>Can vote:</td>
						<td><? if($user->Benefits->CanVote){ ?>☑<? }else{ ?>☐<? } ?></td>
					</tr>
					<tr>
						<td>Can bulk download:</td>
						<td><? if($user->Benefits->CanBulkDownload){ ?>☑<? }else{ ?>☐<? } ?></td>
					</tr>
					<tr>
						<td>Can upload artwork:</td>
						<td><? if($user->Benefits->CanUploadArtwork){ ?>☑<? }else{ ?>☐<? } ?></td>
					</tr>
					<tr>
						<td>Can review artwork:</td>
						<td><? if($user->Benefits->CanReviewArtwork){ ?>☑<? }else{ ?>☐<? } ?></td>
					</tr>
					<tr>
						<td>Can review own artwork:</td>
						<td><? if($user->Benefits->CanReviewOwnArtwork){ ?>☑<? }else{ ?>☐<? } ?></td>
					</tr>
					<tr>
						<td>Can edit users:</td>
						<td><? if($user->Benefits->CanEditUsers){ ?>☑<? }else{ ?>☐<? } ?></td>
					</tr>
					<tr>
						<td>Can edit ebook placeholders:</td>
						<td><? if($user->Benefits->CanEditEbookPlaceholders){ ?>☑<? }else{ ?>☐<? } ?></td>
					</tr>
					<tr>
						<td>Can edit projects:</td>
						<td><? if($user->Benefits->CanEditProjects){ ?>☑<? }else{ ?>☐<? } ?></td>
					</tr>
					<tr>
						<td>Can manage projects:</td>
						<td><? if($user->Benefits->CanManageProjects){ ?>☑<? }else{ ?>☐<? } ?></td>
					</tr>
					<tr>
						<td>Can review projects:</td>
						<td><? if($user->Benefits->CanReviewProjects){ ?>☑<? }else{ ?>☐<? } ?></td>
					</tr>
				<? } ?>
			</tbody>
		</table>

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
	</section>
</main>
<?= Template::Footer() ?>
