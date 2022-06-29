<?
require_once('Core.php');

use function Safe\session_unset;

session_start();

$vote = $_SESSION['vote'] ?? new Vote();
$exception = $_SESSION['exception'] ?? null;

$poll = null;

try{
	$poll = Poll::GetByUrlName(HttpInput::Str(GET, 'pollurlname', false));
}
catch(Exceptions\SeException $ex){
	http_response_code(404);
	include(WEB_ROOT . '/404.php');
	exit();
}

if($exception){
	http_response_code(400);
	session_unset();
}

?><?= Template::Header(['title' => $poll->Name . ' - Vote Now', 'highlight' => '', 'description' => 'Vote in the ' . $poll->Name . ' poll']) ?>
<main>
	<article>
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
	</article>
</main>
<?= Template::Footer() ?>
