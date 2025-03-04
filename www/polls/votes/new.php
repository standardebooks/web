<?
use function Safe\session_unset;

session_start();

$poll = new Poll();
$vote = HttpInput::SessionObject('vote', PollVote::class) ?? new PollVote();
$exception = HttpInput::SessionObject('exception', Exceptions\AppException::class);

try{
	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!isset($vote->UserId)){
		$vote->UserId = Session::$User->UserId;
		$vote->User = Session::$User;
	}

	$poll = Poll::GetByUrlName(HttpInput::Str(GET, 'pollurlname'));

	try{
		$vote = PollVote::Get($poll->UrlName, Session::$User->UserId);

		// Vote was found, don't allow another vote.
		throw new Exceptions\PollVoteExistsException($vote);
	}
	catch(Exceptions\PollVoteNotFoundException){
		// Vote was not found, user is OK to vote.
	}

	if($exception){
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
	}
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PollNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\PollVoteExistsException $ex){
	$redirect = $poll->Url;
	if($ex->Vote !== null){
		$redirect = $ex->Vote->Url;
	}

	header('Location: ' . $redirect);
	exit();
}
?><?= Template::Header(title: 'Vote in the ' . $poll->Name . ' Poll', description: 'Vote in the ' . $poll->Name . ' poll') ?>
<main>
	<section class="narrow">
		<h1>Vote in the <?= Formatter::EscapeHtml($poll->Name) ?> Poll</h1>
		<?= Template::Error(exception: $exception) ?>
		<form method="<?= Enums\HttpMethod::Post->value ?>" action="<?= Formatter::EscapeHtml($poll->Url) ?>/votes">
			<input type="hidden" name="email" value="<?= Formatter::EscapeHtml($vote->User->Email) ?>" maxlength="80" required="required" />
			<fieldset>
				<p>Select one of these options.</p>
				<ul>
					<? foreach($poll->PollItems as $pollItem){ ?>
						<li>
							<label class="checkbox">
								<input type="radio" value="<?= $pollItem->PollItemId ?>" name="poll-vote-poll-item-id" required="required"<? if(isset($vote->PollItemId) && $vote->PollItemId == $pollItem->PollItemId){ ?> checked="checked"<? } ?>/>
								<span>
									<b><?= $pollItem->Name ?></b>
									<? if($pollItem->Description !== null){ ?>
										<span><?= Formatter::EscapeHtml($pollItem->Description) ?></span>
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
