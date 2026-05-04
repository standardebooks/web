<?
/**
 * GET		/ebooks/:url-path/projects/active/reviewer
 * GET		/projects/:project-id/reviewer
 *
 * Get an XML representation of the reviewer for the `Ebook`'s current `Project`, useful for inserting into `content.opf`.
 */

try{
	/** @var Project $project The `Project` for this request, passed in from the router. */
	$project = $resource ?? throw new Exceptions\ProjectNotFoundException();

	$indent = HttpInput::Bool(GET, 'indent') ?? false;

	$output = new stdClass();
	$output->Name = $project->Reviewer->Name;
	$output->SortName = $project->Reviewer->SortName;

	switch($output->Name){
		case 'Alex Cabal':
			$output->Url = 'https://alexcabal.com/';
			break;

		case 'Emma Sweeney':
			$output->Url = 'https://www.linkedin.com/in/emma-sweeney-554927190/';
			break;

		case 'Weijia Cheng':
			$output->Url = 'https://weijiarhymeswith.asia/';
			break;

		case 'Robin Whittleton':
			$output->Url = 'https://www.robinwhittleton.com/';
			break;

		case 'Vince Rice':
			$output->Url = 'https://www.brokenandsaved.com/';
			break;

		case 'David Reimer':
			$output->Url = 'https://github.com/dajare';
			$output->NacoafUrl = 'http://id.loc.gov/authorities/names/n92075987';
			break;
	}

	header('content-type: text/plain');
}
catch(Exceptions\ProjectNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
?>
<? if($indent){ ?>		<? } ?><dc:contributor id="producer-2"><?= Formatter::EscapeXml($output->Name) ?></dc:contributor>
<? if($indent){ ?>		<? } ?><meta property="file-as" refines="#producer-2"><?= Formatter::EscapeXml($output->SortName) ?></meta>
<? if(isset($output->Url)){ ?>
<? if($indent){ ?>		<? } ?><meta property="se:url.homepage" refines="#producer-2"><?= Formatter::EscapeXml($output->Url) ?></meta>
<? } ?>
<? if(isset($output->NacoafUrl)){ ?>
<? if($indent){ ?>		<? } ?><meta property="se:url.authority.nacoaf" refines="#producer-2"><?= Formatter::EscapeXml($output->NacoafUrl) ?></meta>
<? } ?>
<? if($indent){ ?>		<? } ?><meta property="role" refines="#producer-2" scheme="marc:relators">pfr</meta>
