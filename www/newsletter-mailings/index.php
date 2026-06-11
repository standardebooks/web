<?
use function Safe\session_start;
use function Safe\session_unset;

session_start();

try{
	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanCreateNewsletterMailings && !Session::$User->Benefits->CanEditNewsletterMailings){
		throw new Exceptions\PermissionsInvalidException();
	}

	$isCreated = Http::$Request->Session->Get('newsletter-mailing/create/is-created', 'bool') ?? false;
	$isSaved = Http::$Request->Session->Get('newsletter-mailing/edit/is-saved', 'bool') ?? false;
	$page = Http::$Request->QueryString->Get('page', 'int') ?? 1;
	$perPage = 5;

	$result = NewsletterMailing::GetAllByPage($page, $perPage);

	$newsletterMailings = $result['newsletterMailings'];
	$pages = $result['totalPages'];

	if($isCreated){
		http_response_code(Enums\HttpCode::Created->value);
	}

	if($isCreated || $isSaved){
		session_unset();
	}
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\PageOutOfBoundsException $ex){
	header('location: /newsletter-mailings?page=' . $ex->TotalPages);
	exit();
}
?>
<?= Template::Header(
		title: 'Newsletter Mailings',
		css: ['/css/newsletter-mailings.css'],
		description: 'Manage newsletter mailings in the Standard Ebooks system.'
) ?>
<main>
	<section class="narrow has-hero">
		<h1>Newsletter Mailings</h1>

		<picture data-caption="Rack Pictures for Dr. Nones. William A. Mitchell, 1879">
			<source srcset="/images/rack-picture-for-dr-nones@2x.avif 2x, /images/rack-picture-for-dr-nones.avif 1x" type="image/avif"/>
			<source srcset="/images/rack-picture-for-dr-nones@2x.jpg 2x, /images/rack-picture-for-dr-nones.jpg 1x" type="image/jpg"/>
			<img src="/images/rack-picture-for-dr-nones@2x.jpg" alt="Postal mail attached to a billboard."/>
		</picture>

		<? if(Session::$User->Benefits->CanCreateNewsletterMailings){ ?>
			<ul role="menu">
				<li><a href="/newsletter-mailings/new">Create a newsletter mailing</a></li>
			</ul>
		<? } ?>

		<? if($isSaved){ ?>
			<p class="message success">Newsletter mailing saved!</p>
		<? } ?>

		<? if($isCreated){ ?>
			<p class="message success">Newsletter mailing created!</p>
		<? } ?>

		<? if(sizeof($newsletterMailings) > 0){ ?>
			<ol class="newsletter-mailings">
				<? foreach($newsletterMailings as $newsletterMailing){ ?>
					<li>
						<p><?= Formatter::EscapeHtml($newsletterMailing->Subject) ?> (#<?= $newsletterMailing->NewsletterMailingId ?>)</p>
						<p><?= ucfirst($newsletterMailing->Status->value) ?><? if($newsletterMailing->Status == Enums\QueueStatus::Completed && $newsletterMailing->RecipientCount !== null){ ?> • <?= number_format($newsletterMailing->RecipientCount) ?> <?= Formatter::Pluralize($newsletterMailing->RecipientCount, 'recipient') ?><? } ?><? if($newsletterMailing->ExcludePatrons){ ?> (Patrons excluded)<? } ?></p>
						<p><?= $newsletterMailing->SendOn->setTimezone(SITE_TZ)->format(Enums\DateTimeFormat::FullDateTime->value) ?> <?= SITE_TZ_STRING ?> • <?= Formatter::EscapeHtml($newsletterMailing->Newsletter->Name) ?></p>
						<? if(Session::$User->Benefits->CanEditNewsletterMailings){ ?>
							<p>
								<a href="<?= $newsletterMailing->EditUrl ?>">Edit</a>
							</p>
						<? } ?>
					</li>
				<? } ?>
			</ol>
			<nav class="pagination" aria-label="Pagination">
				<a<? if($page > 1){ ?> href="/newsletter-mailings?page=<?= $page - 1 ?>" rel="prev"<? }else{ ?> aria-disabled="true"<? } ?>>Back</a>
				<ol>
					<? for($i = 1; $i < $pages + 1; $i++){ ?>
						<li>
							<a <? if($page == $i){ ?>aria-current="page" href="#"<? }else{ ?>href="/newsletter-mailings?page=<?= $i ?>"<? } ?>><?= $i ?></a>
						</li>
					<? } ?>
				</ol>
				<a<? if($page < $pages){ ?> href="/newsletter-mailings?page=<?= $page + 1 ?>" rel="next"<? }else{ ?> aria-disabled="true"<? } ?>>Next</a>
			</nav>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
