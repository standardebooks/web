<?
/**
 * @var array<Project> $projects
 */

$includeTitle = $includeTitle ?? true;
?>
<table class="data-table">
	<caption aria-hidden="hidden">Scroll right →</caption>
	<thead>
		<tr class="mid-header">
			<? if($includeTitle){ ?>
				<th scope="col">Title</th>
			<? } ?>
			<th scope="col">Started</th>
			<th scope="col">Producer</th>
			<th scope="col">Status</th>
			<th/>
		</tr>
	</thead>
	<tbody>
		<? foreach($projects as $project){ ?>
			<tr>
				<? if($includeTitle){ ?>
					<td class="row-header">
						<a href="<?= $project->Ebook->Url ?>"><?= Formatter::EscapeHtml($project->Ebook->Title) ?></a>
					</td>
				<? } ?>
				<td>
					<?= $project->Started->format(Enums\DateTimeFormat::ShortDate->value) ?>
				</td>
				<td class="producer">
					<? if($project->ProducerEmail !== null){ ?>
						<a href="mailto:<?= Formatter::EscapeHtml($project->ProducerEmail) ?>"><?= Formatter::EscapeHtml($project->ProducerName) ?></a>
					<? }else{ ?>
						<?= Formatter::EscapeHtml($project->ProducerName) ?>
					<? } ?>
				</td>
				<td class="status">
					<?= ucfirst($project->Status->GetDisplayName()) ?>
				</td>
				<td>
					<a href="<?= Formatter::EscapeHtml($project->VcsUrl) ?>">GitHub repo</a>
				</td>
			</tr>
		<? } ?>
	</tbody>
</table>
