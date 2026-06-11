<?
/**
 * GET		/polls/:poll-url-name
 */

use function Safe\session_start;
use function Safe\session_unset;

session_start();

try{
	/** @var Poll $poll The `Poll` for this request, passed in from the router. */
	$poll = $resource ?? throw new Exceptions\PollNotFoundException();

	$canVote = true; // Allow non-logged-in users to see the 'vote' button.

	if(!$poll->IsActive() && $poll->End < NOW){
		// If the poll ended, redirect to the results.
		header('location: ' . $poll->Url . '/votes');
		exit();
	}

	$isSaved = Http::$Request->Session->Get('is-poll-saved', 'bool') ?? false;
	$isCreated = Http::$Request->Session->Get('is-poll-created', 'bool') ?? false;

	if(Session::$User !== null){
		$canVote = false; // User is logged in, hide the vote button unless they haven't voted yet.
		try{
			PollVote::Get($poll->UrlName, Session::$User->UserId);
			// If we get here, the `User` has already voted.
		}
		catch(Exceptions\AppException){
			$canVote = true;
		}
	}

	if($isCreated){
		http_response_code(Enums\HttpCode::Created->value);
	}

	if($isCreated || $isSaved){
		session_unset();
	}

	$canEditPolls = Session::$User?->Benefits->CanEditPolls ?? false;
}
catch(Exceptions\PollNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
?><?= Template::Header(title: $poll->Name, description: $poll->Description ?? '') ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
			<a href="/polls">Polls</a> →
		</nav>
		<h1><?= Formatter::EscapeHtml($poll->Name) ?></h1>
		<? if($canEditPolls){ ?>
			<ul role="menu">
				<li><a href="<?= $poll->EditUrl ?>">Edit poll</a></li>
			</ul>
		<? } ?>
		<? if($isSaved){ ?>
			<p class="message success">Poll saved!</p>
		<? } ?>

		<? if($isCreated){ ?>
			<p class="message success">Poll created!</p>
		<? } ?>

		<? if($poll->Description !== null){ ?>
			<p><?= $poll->Description->ToHtmlFragment(true) ?></p>
		<? } ?>
		<? if($poll->IsActive()){ ?>
			<p class="center-notice">This poll closes on <?= $poll->End->setTimezone(SITE_TZ)->format(Enums\DateTimeFormat::FullDateTime->value) ?> <?= SITE_TZ_STRING ?>.</p>
			<? if(!$canVote){ ?>
				<p class="center-notice">You’ve already voted in this poll.</p>
			<? } ?>
			<p class="button-row narrow">
				<? if($canVote){ ?>
					<a href="<?= $poll->Url ?>/votes/new" class="button">Vote now</a>
				<? } ?>
				<a href="<?= $poll->Url ?>/votes" class="button">View results</a>
			</p>
		<? }else{ ?>
			<? if($poll->Start > NOW){ ?>
				<p class="center-notice">This poll opens on <?= $poll->Start->setTimezone(SITE_TZ)->format(Enums\DateTimeFormat::FullDateTime->value) ?> <?= SITE_TZ_STRING ?>.</p>
			<? }else{ ?>
				<p class="center-notice">This poll closed on <?= $poll->End->setTimezone(SITE_TZ)->format(Enums\DateTimeFormat::FullDateTime->value) ?> <?= SITE_TZ_STRING ?>.</p>
				<p class="button-row narrow"><a href="<?= $poll->Url ?>/votes" class="button">View results</a></p>
			<? } ?>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
