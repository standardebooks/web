<?
require_once('Core.php');

use Safe\DateTime;

$poll = new Poll();

try{
	$poll = Poll::GetByUrlName(HttpInput::Str(GET, 'pollurlname', false));
}
catch(Exceptions\SeException $ex){
	Template::Emit404();
}

?><?= Template::Header(['title' => $poll->Name, 'highlight' => '', 'description' => $poll->Description]) ?>
<main>
	<section>
		<h1><?= Formatter::ToPlainText($poll->Name) ?></h1>
		<p><?= $poll->Description ?></p>
		<? if($poll->IsActive()){ ?>
			<? if($poll->End !== null){ ?>
				<p class="center-notice">This poll closes on <?= $poll->End->format('F j, Y g:i A') ?>.</p>
			<? } ?>
		<p class="button-row narrow">
			<a href="<?= $poll->Url ?>/votes/new" class="button">Vote now</a>
			<a href="<?= $poll->Url ?>/votes" class="button">View results</a>
		</p>
		<? }else{ ?>
			<? if($poll->Start !== null && $poll->Start > new DateTime()){ ?>
				<p class="center-notice">This poll opens on <?= $poll->Start->format('F j, Y g:i A') ?>.</p>
			<? }else{ ?>
			<p class="center-notice">This poll closed on <?= $poll->End->format('F j, Y g:i A') ?>.</p>
			<p class="button-row narrow"><a href="<?= $poll->Url ?>/votes" class="button">View results</a></p>
			<? } ?>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
