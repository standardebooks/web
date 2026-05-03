<?
use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	/** @var PollVote $pollVote The `PollVote` for this request, passed in from the router. */
	$pollVote = $resource ?? throw new Exceptions\PollVoteNotFoundException();

	$isCreated = false;

	if(isset($_SESSION['is-vote-created']) && $_SESSION['is-vote-created'] == $pollVote->UserId){
		$isCreated = true;
		http_response_code(Enums\HttpCode::Created->value);
		session_unset();
	}
}
catch(Exceptions\PollVoteNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
?><?= Template::Header(title: 'Your Vote Has Been Recorded!', description: 'Thank you for voting in a Standard Ebooks poll!') ?>
<main>
	<section class="narrow">
		<h1>Your Vote Has Been Recorded!</h1>
		<? if($isCreated){ ?>
			<p class="center-notice">Thank you for voting in the <a href="<?= $pollVote->PollItem->Poll->Url ?>"><?= Formatter::EscapeHtml($pollVote->PollItem->Poll->Name) ?> poll</a>.</p>
		<? }else{ ?>
			<p class="center-notice">Your vote in the <a href="<?= $pollVote->PollItem->Poll->Url ?>"><?= Formatter::EscapeHtml($pollVote->PollItem->Poll->Name) ?> poll</a> was submitted on <?= $pollVote->Created->format(Enums\DateTimeFormat::FullDateTime->value) ?>.</p>
		<? } ?>
		<p class="button-row narrow"><a class="button" href="<?= $pollVote->PollItem->Poll->Url ?>/votes"> view results</a></p>
	</section>
</main>
<?= Template::Footer() ?>
