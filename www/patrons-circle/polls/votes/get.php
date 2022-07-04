<?
require_once('Core.php');

session_start();

$vote = new Vote();

try{
	$vote = Vote::Get(HttpInput::Str(GET, 'pollurlname'), HttpInput::Int(GET, 'userid'));

	if(isset($_SESSION['vote-created']) && $_SESSION['vote-created'] == $vote->VoteId){
		http_response_code(201);
		session_unset();
	}
}
catch(Exceptions\SeException $ex){
	Template::Emit404();
}

?><?= Template::Header(['title' => 'Thank you for voting!', 'highlight' => '', 'description' => 'Thank you for voting in a Standard Ebooks poll!']) ?>
<main>
	<section>
		<h1>Thank you for voting!</h1>
		<p class="center-notice">Your vote in the <a href="<?= $vote->PollItem->Poll->Url ?>"><?= Formatter::ToPlainText($vote->PollItem->Poll->Name) ?> poll</a> has been recorded.</p>
		<p class="button-row narrow"><a class="button" href="<?= $vote->PollItem->Poll->Url ?>/votes"> view results</a></p>
	</section>
</main>
<?= Template::Footer() ?>
