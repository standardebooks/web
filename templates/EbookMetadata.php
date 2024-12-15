<?
/**
 * @var Ebook $ebook
 */
?>
<h2>Metadata</h2>
<table class="admin-table">
	<tbody>
		<tr>
			<td>Ebook ID:</td>
			<td><?= $ebook->EbookId ?></td>
		</tr>
		<tr>
			<td>Identifier:</td>
			<td><?= Formatter::EscapeHtml($ebook->Identifier) ?></td>
		</tr>
		<? if(sizeof($ebook->Projects) > 0){ ?>
			<tr>
				<td>Projects:</td>
				<td>
					<ul>
						<? foreach($ebook->Projects as $project){ ?>
							<li>
								<p>
									<?= $project->Started->format(Enums\DateTimeFormat::FullDateTime->value) ?> — <?= $project->Status->GetDisplayName() ?> — <? if($project->ProducerEmail !== null){ ?><a href="mailto:<?= Formatter::EscapeHtml($project->ProducerEmail) ?>"><?= Formatter::EscapeHtml($project->ProducerName) ?></a><? }else{ ?><?= Formatter::EscapeHtml($project->ProducerName) ?><? } ?> — <a href="<?= $project->Url ?>">Link</a>
								</p>
							</li>
						<? } ?>
					</ul>
				</td>
			</tr>
		<? } ?>
	</tbody>
</table>
