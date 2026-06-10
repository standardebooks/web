<?
use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	/** @var PollVote $pollVote The `PollVote` for this request, passed in from the router. */
	$pollVote = $resource ?? throw new Exceptions\PollVoteNotFoundException();

	if(Session::$User?->UserId != $pollVote->UserId){
		throw new Exceptions\PermissionsInvalidException();
	}

	$isCreated = Http::$Request->Session->Get('is-poll-vote-created', 'bool');

	if($isCreated){
		http_response_code(Enums\HttpCode::Created->value);
		session_unset();
	}
}
catch(Exceptions\PollVoteNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
?><?= Template::Header(title: 'Your Vote Has Been Recorded!', description: 'Thank you for voting in a Standard Ebooks poll!') ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
			<a href="/polls">Polls</a> →
			<a href="<?= $pollVote->PollItem->Poll->Url ?>"><?= Formatter::EscapeHtml($pollVote->PollItem->Poll->Name) ?></a> →
			<a href="<?= $pollVote->PollItem->Poll->Url ?>/votes">Votes</a> →
		</nav>
		<h1>Your Vote Has Been Recorded!</h1>
		<? if($isCreated){ ?>
			<p class="center-notice">Thank you for voting in the <a href="<?= $pollVote->PollItem->Poll->Url ?>"><?= Formatter::EscapeHtml($pollVote->PollItem->Poll->Name) ?> poll</a>.</p>
		<? }else{ ?>
			<p class="center-notice">Your vote in the <a href="<?= $pollVote->PollItem->Poll->Url ?>"><?= Formatter::EscapeHtml($pollVote->PollItem->Poll->Name) ?> poll</a> was submitted on <?= $pollVote->Created->format(Enums\DateTimeFormat::FullDateTime->value) ?>.</p>
		<? } ?>
		<p class="center-notice">You voted for <?= $pollVote->PollItem->Name->ToHtmlFragment(true) ?>.</p>
		<p class="button-row narrow"><a class="button" href="<?= $pollVote->PollItem->Poll->Url ?>/votes"> view results</a></p>
	</section>
</main>
<?= Template::Footer() ?>
