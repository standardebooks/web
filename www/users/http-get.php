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

	if(preg_match('/^\/users\/get\b/iu', Http::$Request->RelativeUri->getPath())){
		// If we got here by "jumping" to a `User` from the `User`s index page, redirect to their actual URL instead of the "jump" URL.
		header('location: ' . $user->Url);
		exit();
	}

	$identifier = Http::$Request->QueryString->Get('user-identifier');
	$isCreated = Http::$Request->Session->Get('user/create/is-created', 'bool') ?? false;
	$isSaved = Http::$Request->Session->Get('user/edit/is-saved', 'bool') ?? false;
	$isPatronCreated = Http::$Request->Session->Get('patron/create/is-created', 'bool') ?? false;
	$isSubscriptionCanceled = Http::$Request->Session->Get('subscription/delete/is-deleted', 'bool') ?? false;
	$isSubscriptionEdited = Http::$Request->Session->Get('subscription/edit/is-edited', 'bool') ?? false;

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	$isLocalUser = Session::$User->UserId === $user->UserId && !Session::$User->Benefits->CanEditUsers;

	if(!$isLocalUser && !Session::$User->Benefits->CanCreateUsers && !Session::$User->Benefits->CanEditUsers){
		throw new Exceptions\PermissionsInvalidException();
	}

	if(!ctype_digit($identifier)){
		http_response_code(Enums\HttpCode::Found->value);
		header('location: ' . $user->Url);
		exit();
	}

	if(Http::$Request->QueryString->Get('is-subscription-edited', 'bool') == true && Session::$User->UserId === $user->UserId){
		$_SESSION['subscription/edit/is-edited'] = true;
		http_response_code(Enums\HttpCode::SeeOther->value);
		header('location: ' . $user->Url);
		exit();
	}

	// Clear one-time status messages after reading them.
	if($isCreated || $isSaved || $isPatronCreated || $isSubscriptionCanceled || $isSubscriptionEdited){
		session_unset();
	}

	/** @var array<Subscription> $subscriptions */
	$subscriptions = [];
	if($isLocalUser){
		$subscriptions = Db::Query('select * from Subscriptions where UserId = ? and EndedAt is null order by CreatedAt desc', [$user->UserId], Subscription::class);
	}
	elseif(Session::$User->Benefits->CanEditUsers){
		$subscriptions = Db::Query('select * from Subscriptions where UserId = ? order by CreatedAt desc', [$user->UserId], Subscription::class);
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
		<? if($isLocalUser){ ?>
			<h1><?= Formatter::EscapeHtml($user->DisplayName) ?></h1>

			<? if($isSubscriptionEdited){ ?>
				<p class="message success">Your billing information has been updated!</p>
			<? } ?>

			<? if($isSubscriptionCanceled){ ?>
				<p class="message success">Your subscription has been canceled!</p>
			<? } ?>
			<h2>Account details</h2>
			<dl>
				<dt>Name:</dt>
				<dd><?= Formatter::EscapeHtml($user->Name) ?></dd>
				<dt>Email:</dt>
				<dd><?= Formatter::EscapeHtml($user->Email) ?></dd>
				<dt>Patrons Circle membership:</dt>
				<dd><?= $user->Patron !== null && $user->Patron->EndedAt === null ? 'Active' : 'Inactive' ?></dd>
			</dl>
			<h2>Newsletter subscriptions</h2>
			<? if(sizeof($user->NewsletterSubscriptions) === 0){ ?>
				<p class="empty-notice">None.</p>
			<? }else{ ?>
				<ul>
					<? foreach($user->NewsletterSubscriptions as $newsletterSubscription){ ?>
						<li><?= Formatter::EscapeHtml($newsletterSubscription->Newsletter->Name) ?> — <a href="<?= $newsletterSubscription->DeleteUrl ?>">Unsubscribe</a></li>
					<? } ?>
				</ul>
			<? } ?>

			<h2>Recurring donations</h2>
			<? if(sizeof($subscriptions) === 0){ ?>
				<p class="empty-notice">None.</p>
			<? }else{ ?>
				<ul class="recurring-donations">
					<? foreach($subscriptions as $subscription){ ?>
						<li>
							<p><?= Formatter::FormatCurrency($subscription->Amount) ?>/<?= $subscription->CycleType === Enums\CycleType::Monthly ? 'month' : 'year' ?> • <? if($subscription->NextPaymentAt !== null){ ?>Next donation on <time datetime="<?= $subscription->NextPaymentAt->format(Enums\DateTimeFormat::Html->value) ?>"><?= date_format($subscription->NextPaymentAt, Enums\DateTimeFormat::ShortDateWithoutYear->value) ?></time><? }else{ ?>Last donation on <time datetime="<?= $subscription->LastPayment->CreatedAt->format(Enums\DateTimeFormat::Html->value) ?>"><?= $subscription->LastPayment->CreatedAt->format(Enums\DateTimeFormat::ShortDate->value) ?></time><? } ?> — <a href="<?= $subscription->EditUrl ?>">Update billing info</a> • <a href="<?= $subscription->DeleteUrl ?>">Cancel</a></p>
						</li>
					<? } ?>
				</ul>
			<? } ?>

			<h2>Past donations</h2>
			<? if(sizeof($user->Payments) === 0){ ?>
				<p class="empty-notice">None.</p>
			<? }else{ ?>
				<table class="data-table payments local-payments">
					<thead>
						<tr>
							<th>Date</th>
							<th>Amount</th>
							<th>Recurring?</th>
						</tr>
					</thead>
					<tbody>
						<? foreach($user->Payments as $payment){ ?>
							<tr>
								<td>
									<? if($payment->RefundedAt !== null){ ?>(<i>Refunded.</i>) <? } ?>
									<time datetime="<?= $payment->CreatedAt->format(Enums\DateTimeFormat::Html->value) ?>"><?= $payment->CreatedAt->format(Enums\DateTimeFormat::ShortDate->value) ?></time>
								</td>
								<td class="currency"><?= Formatter::FormatCurrency($payment->Amount) ?></td>
								<td><?= $payment->IsRecurring ? '☑' : '☐' ?></td>
							</tr>
						<? } ?>
					</tbody>
				</table>
			<? } ?>
		<? }else{ ?>
			<nav class="breadcrumbs" aria-label="Breadcrumbs">
				<a href="/users">Users</a> →
			</nav>
			<h1><?= Formatter::EscapeHtml($user->DisplayName) ?></h1>

			<? if($isSubscriptionEdited){ ?>
				<p class="message success">Billing information updated!</p>
			<? } ?>

			<ul role="menu">
				<li><a href="<?= $user->EditUrl ?>">Edit user</a></li>
				<? if($user->Patron === null || $user->Patron->EndedAt !== null){ ?>
					<li><a href="<?= $user->Url ?>/patrons/new">Make Patron</a></li>
				<? } ?>
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

			<? if($isPatronCreated){ ?>
				<p class="message success">Patron created!</p>
			<? } ?>

			<? if($isSubscriptionCanceled){ ?>
				<p class="message success">Recurring donation canceled!</p>
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
				<dd><?= $user->CreatedAt->format(Enums\DateTimeFormat::FullDateTime->value) ?></dd>
			</dl>

			<h2>Patron info</h2>
			<dl>
				<dt>Is Patron:</dt>
				<dd><? if($user->Patron !== null && $user->Patron->EndedAt === null){ ?>☑<? }else{ ?>☐<? } ?></dd>
				<? if($user->Patron !== null && $user->Patron->EndedAt === null){ ?>
					<dt>Created:</dt>
					<dd><?= $user->Patron->CreatedAt->format(Enums\DateTimeFormat::FullDateTime->value) ?></dd>
					<dt>Cycle type:</dt>
					<dd><?= ucfirst($user->Patron->CycleType->value) ?></dd>
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
							<p><?= Formatter::EscapeHtml($newsletterSubscription->Newsletter->Name) ?> — <a href="<?= $newsletterSubscription->DeleteUrl ?>">Unsubscribe</a></p>
						</li>
					<? } ?>
				</ul>
			<? } ?>

			<h2>Benefits</h2>
			<? if(!$user->Benefits->HasAnyBenefits){ ?>
				<p class="empty-notice">None.</p>
			<? }else{ ?>
				<p>Patron benefits are in bold.</p>
				<ul class="benefits-list">
					<? if($user->Benefits->CanAccessFeeds){ ?><li><strong>Can access feeds</strong></li><? } ?>
					<? if($user->Benefits->CanVote){ ?><li><strong>Can vote</strong></li><? } ?>
					<? if($user->Benefits->CanBulkDownload){ ?><li><strong>Can bulk download</strong></li><? } ?>
					<? if($user->Benefits->CanUploadArtwork){ ?><li>Can upload artwork</li><? } ?>
					<? if($user->Benefits->CanReviewArtwork){ ?><li>Can review artwork</li><? } ?>
					<? if($user->Benefits->IsArtworkAdmin){ ?><li>Is artwork admin</li><? } ?>
					<? if($user->Benefits->CanCreateUsers){ ?><li>Can create users</li><? } ?>
					<? if($user->Benefits->CanEditUsers){ ?><li>Can edit users</li><? } ?>
					<? if($user->Benefits->CanEditPolls){ ?><li>Can edit polls</li><? } ?>
					<? if($user->Benefits->CanEditCollections){ ?><li>Can edit collections</li><? } ?>
					<? if($user->Benefits->CanEditEbooks){ ?><li>Can edit ebooks</li><? } ?>
					<? if($user->Benefits->CanEditEbookPlaceholders){ ?><li>Can edit ebook placeholders</li><? } ?>
					<? if($user->Benefits->CanEditProjects){ ?><li>Can edit projects</li><? } ?>
					<? if($user->Benefits->CanManageProjects){ ?><li>Can manage projects</li><? } ?>
					<? if($user->Benefits->CanReviewProjects){ ?><li>Can review projects</li><? } ?>
					<? if($user->Benefits->CanBeAutoAssignedToProjects){ ?><li>Can be auto-assigned to projects</li><? } ?>
					<? if($user->Benefits->CanEditBlogPosts){ ?><li>Can edit blog posts</li><? } ?>
					<? if($user->Benefits->CanEditSpreadsheets){ ?><li>Can edit spreadsheets</li><? } ?>
					<? if($user->Benefits->CanCreateNewsletterMailings){ ?><li>Can create newsletter mailings</li><? } ?>
					<? if($user->Benefits->CanEditNewsletterMailings){ ?><li>Can edit newsletter mailings</li><? } ?>
					<? if($user->Benefits->CanViewReports){ ?><li>Can view reports</li><? } ?>
				</ul>
			<? } ?>

			<h2>Recurring donations</h2>
			<? if(sizeof($subscriptions) == 0){ ?>
				<p class="empty-notice">None.</p>
			<? }else{ ?>
				<table class="data-table subscriptions">
					<thead>
						<tr>
							<th>Created</th>
							<th>Ended</th>
							<th>Amount</th>
							<th>Cycle</th>
							<th>Processor</th>
							<th>Subscription ID</th>
							<th>Processor subscription ID</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<? foreach($subscriptions as $subscription){ ?>
							<tr>
								<td><time datetime="<?= $subscription->CreatedAt->format(Enums\DateTimeFormat::Html->value) ?>"><?= $subscription->CreatedAt->format(Enums\DateTimeFormat::ShortDate->value) ?></time></td>
								<td><? if($subscription->EndedAt !== null){ ?><time datetime="<?= $subscription->EndedAt->format(Enums\DateTimeFormat::Html->value) ?>"><?= $subscription->EndedAt->format(Enums\DateTimeFormat::ShortDate->value) ?></time><? } ?></td>
								<td class="currency"><?= Formatter::FormatCurrency($subscription->Amount) ?></td>
								<td><?= ucfirst($subscription->CycleType->value) ?></td>
								<td><?= $subscription->Processor == Enums\PaymentProcessorType::Stripe ? 'Stripe' : 'Fractured Atlas' ?></td>

								<td><?= $subscription->SubscriptionId ?></td>
								<td><? if($subscription->Processor == Enums\PaymentProcessorType::Stripe && $subscription->ProcessorSubscriptionId !== null){ ?><a href="https://dashboard.stripe.com/<?= SITE_STATUS == SITE_STATUS_DEV ? 'test/' : '' ?>subscriptions/<?= Formatter::EscapeHtml($subscription->ProcessorSubscriptionId) ?>"><?= Formatter::EscapeHtml($subscription->ProcessorSubscriptionId) ?></a><? } ?></td>
								<td>
									<? if($subscription->Processor == Enums\PaymentProcessorType::Stripe && $subscription->EndedAt === null && $subscription->ProcessorSubscriptionId !== null){ ?>
										<p><a href="<?= $user->Url ?>/subscriptions/<?= $subscription->SubscriptionId ?>/delete">Cancel</a></p>
									<? } ?>
								</td>
							</tr>
						<? } ?>
					</tbody>
				</table>
			<? } ?>

			<h2>Payments</h2>
			<? if(sizeof($user->Payments) == 0){ ?>
				<p class="empty-notice">None.</p>
			<? }else{ ?>
				<? if($user->HasFracturedAtlasPayments){ ?>
					<p>
						<a href="https://fundraising.fracturedatlas.org/admin/general_support/donations?query=<?= urlencode($user->Email ?? '') ?>">View all payments at Fractured Atlas</a>
					</p>
				<? } ?>
				<table class="data-table payments">
					<thead>
						<tr>
							<th>Created</th>
							<th>Processor</th>
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
									<? if($payment->RefundedAt !== null){ ?>
										(<i>Refunded.</i>)
									<? } ?>
									<time datetime="<?= $payment->CreatedAt->format(Enums\DateTimeFormat::Html->value) ?>"><?= $payment->CreatedAt->format(Enums\DateTimeFormat::ShortDate->value) ?></time>
								</td>
								<td><?= Formatter::EscapeHtml($payment->Processor->GetDisplayName()) ?></td>
								<td>
									<? if($payment->IsRecurring){ ?>
										☑
									<? }else{ ?>
										☐
									<? } ?>
								</td>
								<td class="currency">
									<?= Formatter::FormatCurrency($payment->Amount) ?>
								</td>
								<td class="currency">
									<?= $payment->Fee === null ? 'Pending' : Formatter::FormatCurrency($payment->Fee) ?>
								</td>
								<td class="currency">
									<?= $payment->Fee === null ? 'Pending' : Formatter::FormatCurrency($payment->Amount - $payment->Fee) ?>
								</td>
								<td>
									<a href="<?= Formatter::EscapeHtml($payment->ProcessorUrl) ?>"><?= Formatter::EscapeHtml($payment->TransactionId) ?></a>
								</td>
							</tr>
						<? } ?>
					</tbody>
				</table>
			<? } ?>

			<? if(sizeof($user->ProjectsProducing) > 0){ ?>
				<h2>Projects</h2>
				<?= Template::ProjectsTable(projects: $user->ProjectsProducing, showContactInformation: true, isAdminView: Session::$User->Benefits->CanEditCollections) ?>
			<? } ?>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
