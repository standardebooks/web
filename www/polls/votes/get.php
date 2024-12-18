<?
use function Safe\session_unset;

session_start();

$vote = new PollVote();
$created = false;

try{
	$vote = PollVote::Get(HttpInput::Str(GET, 'pollurlname'), HttpInput::Int(GET, 'userid'));

	if(isset($_SESSION['is-vote-created']) && $_SESSION['is-vote-created'] == $vote->UserId){
		$created = true;
		http_response_code(Enums\HttpCode::Created->value);
		session_unset();
	}
}
catch(Exceptions\AppException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}

?><?= Template::Header(['title' => 'Your Vote Has Been Recorded!', 'highlight' => '', 'description' => 'Thank you for voting in a Standard Ebooks poll!']) ?>
<main>
	<section class="narrow">
		<h1>Your Vote Has Been Recorded!</h1>
		<? if($created){ ?>
			<p class="center-notice">Thank you for voting in the <a href="<?= $vote->PollItem->Poll->Url ?>"><?= Formatter::EscapeHtml($vote->PollItem->Poll->Name) ?> poll</a>.</p>
		<? }else{ ?>
			<p class="center-notice">Your vote in the <a href="<?= $vote->PollItem->Poll->Url ?>"><?= Formatter::EscapeHtml($vote->PollItem->Poll->Name) ?> poll</a> was submitted on <?= $vote->Created->format(Enums\DateTimeFormat::FullDateTime->value) ?>.</p>
		<? } ?>
		<p class="button-row narrow"><a class="button" href="<?= $vote->PollItem->Poll->Url ?>/votes"> view results</a></p>
	</section>
</main>
<?= Template::Footer() ?>
