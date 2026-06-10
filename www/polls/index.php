<?
/**
 * GET		/polls
 */

use function Safe\session_start;
use function Safe\session_unset;

session_start();

$isCreated = Http::$Request->Session->Get('is-poll-created', 'bool') ?? false;
$isSaved = Http::$Request->Session->Get('is-poll-saved', 'bool') ?? false;
$canEditPolls = Session::$User?->Benefits->CanEditPolls ?? false;
$futurePolls = [];

if($isCreated){
	http_response_code(Enums\HttpCode::Created->value);
}

if($isCreated || $isSaved){
	session_unset();
}

$pastPolls = Db::Query('
				SELECT *
				from Polls
				where utc_timestamp() >= End
				order by Start desc
			', [], Poll::class);

$openPolls = Db::Query('
			SELECT *
			from Polls
			where
				utc_timestamp() < End
				and
				Start <= utc_timestamp()
			order by Start desc
		', [], Poll::class);

if($canEditPolls){
	$futurePolls = Db::Query('
			SELECT *
			from Polls
			where
				utc_timestamp() < Start
			order by Start desc
		', [], Poll::class);
}
?><?= Template::Header(title: 'Polls', description: 'The various polls active at Standard Ebooks.') ?>
<main>
	<section class="narrow has-hero">
		<? if($canEditPolls){ ?>
			<h1>Polls</h1>
			<ul role="menu">
				<li><a href="/polls/new">Create a poll</a></li>
			</ul>
		<? }else{ ?>
			<hgroup>
				<h1>Vote in Our Polls</h1>
				<p>and decide the direction of the Standard Ebooks catalog</p>
			</hgroup>
			<picture data-caption="County Election. John Sartain after George Caleb Bingham, 1854">
				<source srcset="/images/county-election@2x.avif 2x, /images/county-election.avif 1x" type="image/avif"/>
				<source srcset="/images/county-election@2x.jpg 2x, /images/county-election.jpg 1x" type="image/jpg"/>
				<img src="/images/county-election@2x.jpg" alt="Voters step up to cast votes in a county poll."/>
			</picture>
			<p>Periodically members of the <a href="/about#patrons-circle">Standard Ebooks Patrons Circle</a> vote on the next ebook from the <a href="/contribute/wanted-ebooks">Wanted Ebook List</a> to enter immediate production.</p>
			<p>Anyone can <a href="/donate#patrons-circle">join the Patrons Circle</a> by making a small donation in support of our mission of producing beautiful digital literature, for free distribution.</p>
		<? } ?>
		<? if($isSaved){ ?>
			<p class="message success">Poll saved!</p>
		<? } ?>
		<? if($isCreated){ ?>
			<p class="message success">Poll created!</p>
		<? } ?>
		<? if(sizeof($futurePolls) > 0){ ?>
			<section id="future-polls">
				<h2>Future polls</h2>
				<ul>
					<? foreach($futurePolls as $poll){ ?>
						<li>
							<p>
								<a href="<?= $poll->Url ?>"><?= Formatter::EscapeHtml($poll->Name) ?></a> — <a href="<?= $poll->EditUrl ?>">Edit</a>
							</p>
						</li>
					<? } ?>
				</ul>
			</section>
		<? } ?>
		<? if(sizeof($openPolls) > 0){ ?>
			<section id="open-polls">
				<h2>Open polls</h2>
				<ul>
					<? foreach($openPolls as $poll){ ?>
						<li>
							<p>
								<a href="<?= $poll->Url ?>"><?= Formatter::EscapeHtml($poll->Name) ?></a>
								<? if($canEditPolls){ ?>
									— <a href="<?= $poll->EditUrl ?>">Edit</a>
								<? } ?>
							</p>
						</li>
					<? } ?>
				</ul>
			</section>
		<? } ?>
		<? if(sizeof($pastPolls) > 0){ ?>
			<section id="ended-polls">
				<h2>Past polls</h2>
				<ul>
					<? foreach($pastPolls as $poll){ ?>
						<li>
							<p>
								<a href="<?= $poll->Url ?>"><?= Formatter::EscapeHtml($poll->Name) ?></a>
								<? if($canEditPolls){ ?>
									— <a href="<?= $poll->EditUrl ?>">Edit</a>
								<? } ?>
							</p>
						</li>
					<? } ?>
				</ul>
			</section>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
