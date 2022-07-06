<?
require_once('Core.php');

use function Safe\session_unset;

session_start();

$vote = $_SESSION['vote'] ?? new PollVote();
$exception = $_SESSION['exception'] ?? null;

$poll = new Poll();

try{
	$poll = Poll::GetByUrlName(HttpInput::Str(GET, 'pollurlname', false));
}
catch(Exceptions\SeException $ex){
	Template::Emit404();
}

if($exception){
	http_response_code(400);
	session_unset();
}

?><?= Template::Header(['title' => $poll->Name . ' - Vote Now', 'highlight' => '', 'description' => 'Vote in the ' . $poll->Name . ' poll']) ?>
<main>
	<section class="narrow">
		<h1>Vote in the <?= Formatter::ToPlainText($poll->Name) ?> Poll</h1>
		<?= Template::Error(['exception' => $exception]) ?>
		<form method="post" action="<?= Formatter::ToPlainText($poll->Url) ?>/votes">
			<label class="email">Your email address
				<input type="email" name="email" value="<? if($vote->User !== null){ ?><?= Formatter::ToPlainText($vote->User->Email) ?><? } ?>" maxlength="80" required="required" />
			</label>
			<fieldset>
				<p>Select one of these options</p>
				<ul>
			<? foreach($poll->PollItems as $pollItem){ ?>
				<li>
					<label class="checkbox">
						<input type="radio" value="<?= $pollItem->PollItemId ?>" name="pollitemid" required="required"<? if($vote->PollItemId == $pollItem->PollItemId){ ?> checked="checked"<? } ?>/>
						<span>
							<b><?= Formatter::ToPlainText($pollItem->Name) ?></b>
						<? if($pollItem->Description !== null){ ?>
							<span><?= Formatter::ToPlainText($pollItem->Description) ?></span>
						<? } ?>
						</span>
					</label>
				</li>
			<? } ?>
				</ul>
			</fieldset>
			<button>Vote</button>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
