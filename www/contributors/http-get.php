<?
/**
 * GET /contributors?query=IDENTIFIER
 * GET /contributors/:url-name
 *
 * Return the shared contributor metadata as an OPF fragment.
 */

try{
	$urlName = Http::$Request->QueryString->Get('contributor-url-name');
	if($urlName !== null){
		$identifier = $urlName;
		$contributor = Contributor::GetByUrlName($urlName);
	}
	else{
		// Use a query parameter because Apache merges consecutive path slashes and rejects encoded path slashes by default.
		// This preserves URL identifiers without changing slash handling across the entire website.
		$identifier = Http::$Request->QueryString->Get('query');
		$contributor = Contributor::GetByIdentifier($identifier);
	}

	// Return the OPF metadata.
	header('content-type: application/xml; charset=utf-8');
	print($contributor->MetadataXmlFragment ?? '');
	exit();
}
catch(Exceptions\ContributorNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\AmbiguousContributorException $ex){
	$contributors = $ex->Contributors;
	http_response_code(Enums\HttpCode::MultipleChoices->value);
}
?><?= Template::Header(title: 'Disambiguation - ' . $identifier) ?>
<main>
	<section class="narrow">
		<h1><?= Formatter::EscapeHtml($contributors[0]->Name ?? '') ?></h1>
		<p>There are <?= number_format(sizeof($contributors)) ?> contributors matching that identifier:</p>
		<ul>
			<? foreach($contributors as $match){ ?>
				<li>
					<a href="/contributors/<?= Formatter::EscapeHtml($match->UrlName) ?>"><?= Formatter::EscapeHtml($match->DisplayName ?? $match->Name) ?> (#<?= $match->ContributorId ?>)</a><? if($match->NacoafUrl !== null || $match->WikipediaUrl !== null){ ?> — <? } ?>
					<? if($match->NacoafUrl !== null){ ?><a href="<?= Formatter::EscapeHtml($match->NacoafUrl) ?>">NACOAF</a><? if($match->WikipediaUrl !== null){ ?> • <? } ?><? } ?>
					<? if($match->WikipediaUrl !== null){ ?><a href="<?= Formatter::EscapeHtml($match->WikipediaUrl) ?>">Wikipedia</a><? } ?>
				</li>
			<? } ?>
		</ul>
	</section>
</main>
<?= Template::Footer() ?>
