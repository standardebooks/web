<?
/**
 * @var ResultsPage<*> $result
 */
?>
<? if(sizeof($result->Results) > 0){ ?>
	<nav class="pagination" aria-label="Pagination">
		<a<? if($result->PreviousPageUrl !== null){ ?> href="<?= Formatter::EscapeHtml($result->PreviousPageUrl) ?>" rel="prev"<? }else{ ?> aria-disabled="true"<? } ?>>Back</a>
		<? if($result instanceof PaginatedResultsPage){ ?>
			<ol>
				<? for($i = 0; $i < sizeof($result->PageUrls); $i++){ ?>
					<li>
						<a <? if($result->Page == $i + 1){ ?>aria-current="page" href="#"<? }else{ ?>href="<?= Formatter::EscapeHtml($result->PageUrls[$i]) ?>"<? } ?>><?= $i + 1 ?></a>
					</li>
				<? } ?>
			</ol>
		<? } ?>
		<a<? if($result->NextPageUrl !== null){ ?> href="<?= Formatter::EscapeHtml($result->NextPageUrl) ?>" rel="next"<? }else{ ?> aria-disabled="true"<? } ?>>Next</a>
	</nav>
<? } ?>
