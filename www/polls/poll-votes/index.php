<?
/**
 * GET		/polls/:poll-url-name/votes
 */

try{
	$poll = Poll::GetByUrlName(Http::$Request->QueryString->Get('poll-url-name'));
}
catch(Exceptions\PollNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
?><?= Template::Header(title: 'Results for the ' . $poll->Name . ' Poll', description: 'The voting results for the ' . $poll->Name . ' poll.') ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
			<a href="/polls">Polls</a> →
			<a href="<?= $poll->Url ?>"><?= Formatter::EscapeHtml($poll->Name) ?></a> →
		</nav>
		<h1>Results</h1>
		<p class="center-notice">Total votes: <?= number_format($poll->VoteCount) ?></p>
		<? if($poll->IsActive()){ ?>
			<p class="center-notice">This poll closes on <?= $poll->End->setTimezone(SITE_TZ)->format(Enums\DateTimeFormat::FullDateTime->value) ?> <?= SITE_TZ_STRING ?>.</p>
		<? }elseif($poll->End !== null){ ?>
			<p class="center-notice">This poll closed on <?= $poll->End->setTimezone(SITE_TZ)->format(Enums\DateTimeFormat::FullDateTime->value) ?> <?= SITE_TZ_STRING ?>.</p>
		<? } ?>
		<table class="votes">
			<tbody>
				<? foreach($poll->PollItemsByWinner as $pollItem){ ?>
					<tr>
						<td><?= $pollItem->Name->ToHtmlFragment(true) ?></td>
						<td>
						<div class="meter">
							<div aria-hidden="true">
								<p><?= number_format($pollItem->VoteCount) ?></p>
							</div>
							<? /* `@max` must be at least 1, otherwise 0/0 will appear as 100%. */ ?>
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
