<?
$pastPolls = Db::Query('
			SELECT *
			from Polls
			where utc_timestamp() >= end
			order by Start desc
		', [], 'Poll');

$openPolls = Db::Query('
			SELECT *
			from Polls
			where (End is null
			       or utc_timestamp() <= End)
			    and
			        (Start is null
			         or Start <= utc_timestamp())
			order by Start desc
		', [], 'Poll');

?><?= Template::Header(['title' => 'Polls', 'highlight' => '', 'description' => 'The various polls active at Standard Ebooks.']) ?>
<main>
	<section class="narrow has-hero">
		<hgroup>
			<h1>Vote in Our Polls</h1>
			<h2>and decide the direction of the Standard Ebooks catalog</h2>
		</hgroup>
		<picture data-caption="County Election. John Sartain after George Caleb Bingham, 1854">
			<source srcset="/images/county-election@2x.avif 2x, /images/county-election.avif 1x" type="image/avif"/>
			<source srcset="/images/county-election@2x.jpg 2x, /images/county-election.jpg 1x" type="image/jpg"/>
			<img src="/images/county-election@2x.jpg" alt="Voters step up to cast votes in a county poll."/>
		</picture>
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
