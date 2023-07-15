<?
use function Safe\session_unset;

session_start();

$poll = new Poll();
$vote = new PollVote();
$exception = $_SESSION['exception'] ?? null;

try{
	if($GLOBALS['User'] === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(isset($_SESSION['vote'])){
		$vote = $_SESSION['vote'];
	}
	else{
		$vote->User = $GLOBALS['User'];
	}

	$poll = Poll::GetByUrlName(HttpInput::Str(GET, 'pollurlname', false));

	try{
		$vote = PollVote::Get($poll->UrlName, $GLOBALS['User']->UserId);

		// Vote was found, don't allow another vote
		throw new Exceptions\PollVoteExistsException($vote);
	}
	catch(Exceptions\InvalidPollVoteException){
		// Vote was not found, user is OK to vote
	}

	if($exception){
		http_response_code(422);
		session_unset();
	}
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPollException){
	Template::Emit404();
}
catch(Exceptions\PollVoteExistsException $ex){
	$redirect = $poll->Url;
	if($ex->Vote !== null){
		$redirect = $ex->Vote->Url;
	}

	header('Location: ' . $redirect);
	exit();
}
?><?= Template::Header(['title' => $poll->Name . ' - Vote Now', 'highlight' => '', 'description' => 'Vote in the ' . $poll->Name . ' poll']) ?>
<main>
	<section class="narrow">
		<h1>Vote in the <?= Formatter::ToPlainText($poll->Name) ?> Poll</h1>
		<?= Template::Error(['exception' => $exception]) ?>
		<form method="post" action="<?= Formatter::ToPlainText($poll->Url) ?>/votes">
			<input type="hidden" name="email" value="<? if($vote->User !== null){ ?><?= Formatter::ToPlainText($vote->User->Email) ?><? } ?>" maxlength="80" required="required" />
			<fieldset>
				<p>Select one of these options.</p>
				<ul>
				<? foreach($poll->PollItems as $pollItem){ ?>
					<li>
						<label class="checkbox">
							<input type="radio" value="<?= $pollItem->PollItemId ?>" name="pollitemid" required="required"<? if($vote->PollItemId == $pollItem->PollItemId){ ?> checked="checked"<? } ?>/>
							<span>
								<b><?= $pollItem->Name ?></b>
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
