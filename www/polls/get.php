<?
$poll = new Poll();
$canVote = true; // Allow non-logged-in users to see the 'vote' button.

try{
	$poll = Poll::GetByUrlName(HttpInput::Str(GET, 'pollurlname'));

	if(!$poll->IsActive() && $poll->End !== null && $poll->End < NOW){
		// If the poll ended, redirect to the results.
		header('Location: ' . $poll->Url . '/votes');
		exit();
	}

	if(Session::$User !== null){
		$canVote = false; // User is logged in, hide the vote button unless they haven't voted yet.
		try{
			PollVote::Get($poll->UrlName, Session::$User->UserId);
		}
		catch(Exceptions\AppException){
			// User has already voted
			$canVote = true;
		}
	}
}
catch(Exceptions\AppException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}

?><?= Template::Header(['title' => $poll->Name, 'highlight' => '', 'description' => $poll->Description]) ?>
<main>
	<section class="narrow">
		<h1><?= Formatter::EscapeHtml($poll->Name) ?></h1>
		<p><?= $poll->Description ?></p>
		<? if($poll->IsActive()){ ?>
			<? if($poll->End !== null){ ?>
				<p class="center-notice">This poll closes on <?= $poll->End->format(Enums\DateTimeFormat::FullDateTime->value) ?>.</p>
			<? } ?>
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
			<? if($poll->Start !== null && $poll->Start > NOW){ ?>
				<p class="center-notice">This poll opens on <?= $poll->Start->format(Enums\DateTimeFormat::FullDateTime->value) ?>.</p>
			<? }else{ ?>
				<p class="center-notice">This poll closed on <?= $poll->End->format(Enums\DateTimeFormat::FullDateTime->value) ?>.</p>
				<p class="button-row narrow"><a href="<?= $poll->Url ?>/votes" class="button">View results</a></p>
			<? } ?>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
