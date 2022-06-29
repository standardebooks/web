<?
require_once('Core.php');

$poll = null;

try{
	$poll = Poll::GetByUrlName(HttpInput::Str(GET, 'pollurlname', false));
}
catch(Exceptions\SeException $ex){
	http_response_code(404);
	include(WEB_ROOT . '/404.php');
	exit();
}

?><?= Template::Header(['title' => 'Results for the ' . $poll->Name . ' poll', 'highlight' => '', 'description' => 'The voting results for the ' . $poll->Name . ' poll.']) ?>
<main>
	<article>
		<h1>Results for the <?= Formatter::ToPlainText($poll->Name) ?> Poll</h1>
		<p class="center-notice">Total votes: <?= number_format($poll->VoteCount) ?></p>
		<? if($poll->IsActive()){ ?>
			<? if($poll->End !== null){ ?>
				<p class="center-notice">This poll closes on <?= $poll->End->format('F j, Y g:i A') ?>.</p>
			<? } ?>
		<? }elseif($poll->End !== null){ ?>
			<p class="center-notice">This poll closed on <?= $poll->End->format('F j, Y g:i A') ?>.</p>
		<? } ?>
		<table class="votes">
			<tbody>
		<? foreach($poll->PollItemsByWinner as $pollItem){ ?>
			<tr>
				<td><?= Formatter::ToPlainText($pollItem->Name) ?></td>
				<td>
					<div class="meter">
						<div aria-hidden="true">
							<p><?= number_format($pollItem->VoteCount) ?></p>
						</div>
						<meter min="0" max="<?= $poll->VoteCount ?>" value="<?= $pollItem->VoteCount ?>"></meter>
					</div>
				</td>
			</tr>
		<? } ?>
			</tbody>
		</table>
	</article>
</main>
<?= Template::Footer() ?>
