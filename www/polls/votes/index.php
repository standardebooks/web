<?
$poll = new Poll();

try{
	$poll = Poll::GetByUrlName(HttpInput::Str(HttpVariableSource::Get, 'pollurlname'));
}
catch(Exceptions\AppException){
	Template::Emit404();
}

?><?= Template::Header(['title' => 'Results for the ' . $poll->Name . ' Poll', 'highlight' => '', 'description' => 'The voting results for the ' . $poll->Name . ' poll.']) ?>
<main>
	<section class="narrow">
		<h1>Results for the <?= Formatter::EscapeHtml($poll->Name) ?> Poll</h1>
		<p class="center-notice">Total votes: <?= number_format($poll->VoteCount) ?></p>
		<? if($poll->IsActive()){ ?>
			<? if($poll->End !== null){ ?>
				<p class="center-notice">This poll closes on <?= $poll->End->format('F j, Y g:i a') ?>.</p>
			<? } ?>
		<? }elseif($poll->End !== null){ ?>
			<p class="center-notice">This poll closed on <?= $poll->End->format('F j, Y g:i a') ?>.</p>
		<? } ?>
		<table class="votes">
			<tbody>
			<? foreach($poll->PollItemsByWinner as $pollItem){ ?>
				<tr>
					<td><?= $pollItem->Name ?></td>
					<td>
						<div class="meter">
							<div aria-hidden="true">
								<p><?= number_format($pollItem->VoteCount) ?></p>
							</div>
							<? /* @max must be at least 1, otherwise 0/0 will appear as 100% */ ?>
							<meter min="0" max="<?= $poll->VoteCount ?: 1 ?>" value="<?= $pollItem->VoteCount ?>"></meter>
						</div>
					</td>
				</tr>
			<? } ?>
			</tbody>
		</table>
	</section>
</main>
<?= Template::Footer() ?>
