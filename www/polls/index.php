<?
require_once('Core.php');

$pastPolls = Db::Query('select * from Polls where utc_timestamp() >= End order by Created desc', [], 'Poll');

$openPolls = Db::Query('select * from Polls where (End is null or utc_timestamp() <= End) and (Start is null or Start <= utc_timestamp()) order by Created desc', [], 'Poll');

?><?= Template::Header(['title' => 'Polls', 'highlight' => '', 'description' => 'The various polls active at Standard Ebooks.']) ?>
<main>
	<section class="narrow">
		<h1>Polls</h1>
		<p>Periodically members of the <a href="/about#patrons-circle">Standard Ebooks Patrons Circle</a> vote on the next ebook from the <a href="/contribute/wanted-ebooks">Wanted Ebook List</a> to enter immediate production.</p>
		<p>Anyone can <a href="/donate#patrons-circle">join the Patrons Circle</a> by making a small donation in support of our mission of producing beautiful digital literature, for free distribution.</p>
		<? if(sizeof($openPolls) > 0){ ?>
			<section id="open-polls">
				<h2>Open polls</h2>
				<ul>
				<? foreach($openPolls as $poll){ ?>
					<li>
						<p><a href="<?= $poll->Url ?>"><?= Formatter::ToPlainText($poll->Name) ?></a></p>
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
						<p><a href="<?= $poll->Url ?>"><?= Formatter::ToPlainText($poll->Name) ?></a></p>
					</li>
				<? } ?>
				</ul>
			</section>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
