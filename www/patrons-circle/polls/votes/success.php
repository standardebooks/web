<?
require_once('Core.php');

$poll = new Poll();

try{
	$poll = Poll::GetByUrlName(HttpInput::Str(GET, 'pollurlname', false));
}
catch(Exceptions\SeException $ex){
	Template::Emit404();
}

?><?= Template::Header(['title' => 'Thank you for voting!', 'highlight' => 'newsletter', 'description' => 'Thank you for voting in a Standard Ebooks poll!']) ?>
<main>
	<article>
		<h1>Thank you for voting!</h1>
		<p class="center-notice">Your vote in the <?= Formatter::ToPlainText($poll->Name) ?> poll has been recorded.</p>
		<p class="button-row narrow"><a class="button" href="<?= $poll->Url ?>/votes"> view results</a></p>
	</article>
</main>
<?= Template::Footer() ?>
