<?
try{
	$urlPath = HttpInput::Str(GET, 'url-path');

	if($urlPath !== null){
		$identifier = EBOOKS_IDENTIFIER_PREFIX .  trim(str_replace('.', '', $urlPath), '/'); // Contains the portion of the URL (without query string) that comes after `https://standardebooks.org/ebooks/`.

		$project = Project::GetByIdentifierAndIsInProgress($identifier);
	}
	else{
		$project = Project::Get(HttpInput::Int(GET, 'project-id'));
	}

	$indent = HttpInput::Bool(GET, 'indent') ?? false;

	$output = new stdClass();
	$output->Name = $project->Reviewer->Name;
	$output->SortName = $project->Reviewer->SortName;

	switch($output->Name){
		case 'Alex Cabal':
			$output->Url = 'https://alexcabal.com';
			break;

		case 'Emma Sweeney':
			$output->Url = 'https://www.linkedin.com/in/emma-sweeney-554927190/';
			break;

		case 'Weijia Cheng':
			$output->Url = 'https://weijiarhymeswith.asia';
			break;

		case 'Robin Whittleton':
			$output->Url = 'https://www.robinwhittleton.com';
			break;

		case 'Vince Rice':
			$output->Url = 'https://www.brokenandsa';
			break;

		case 'David Reimer':
			$output->Url = 'https://github.com/dajare';
			$output->NacoafUrl = 'http://id.loc.gov/authorities/names/n92075987';
			break;
	}

	header('Content-type: text/plain');
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
