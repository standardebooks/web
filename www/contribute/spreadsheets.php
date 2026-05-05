<?
use function Safe\session_start;
use function Safe\session_unset;

session_start();

$spreadsheets = Spreadsheet::GetAllGroupedByCategory();
$isCreated = HttpInput::Bool(SESSION, 'is-spreadsheet-created') ?? false;
$isSaved = HttpInput::Bool(SESSION, 'is-spreadsheet-saved') ?? false;
$isDeleted = HttpInput::Bool(SESSION, 'is-spreadsheet-deleted') ?? false;

if($isCreated || $isSaved || $isDeleted){
	session_unset();
}

$spreadsheetSections = [
	Enums\SpreadsheetCategory::Available->value => [
		'id' => 'available',
		'title' => 'Ebooks that can be worked on immediately',
		'description' => '<p>These spreadsheets are for ebooks that can be worked on today. Rows in <strong>yellow</strong> are items that can be added to an omnibus immediately.</p>',
	],
	Enums\SpreadsheetCategory::HelpWanted->value => [
		'id' => 'help-wanted',
		'title' => 'Help wanted',
		'description' => '<p>These spreadsheets are known to be incomplete, and we need help to complete them.</p>',
	],
	Enums\SpreadsheetCategory::Incomplete->value => [
		'id' => 'incomplete',
		'title' => 'Incomplete because not fully P.D. yet',
		'description' => '<p>These spreadsheets are for ebooks that we host that we consider to be <em>currently</em> complete, but whose author still has more work that is not yet in the public domain. Therefore, we’ll have to continue adding to these ebooks in the future as more work enters the public domain.</p><p>Rows in <strong>yellow</strong> or <strong>white</strong> are items which may possibly be added to existing omnibuses immediately. Some may be missing information, usually page scan sources to verify their text and publication date; we need help to fill in that missing information so we can complete these omnibuses.</p><p>Rows in <b>red</b> are items which are not yet in the public domain.</p>',
	],
	Enums\SpreadsheetCategory::Complete->value => [
		'id' => 'complete',
		'title' => 'Complete',
		'description' => '<p>These spreadsheets are for ebooks that are complete, and in which the author’s entire corpus is in the public domain. We don’t expect to add any more items to these ebooks.</p>',
	],
	Enums\SpreadsheetCategory::Legacy->value => [
		'id' => 'legacy',
		'title' => 'Legacy',
		'description' => '<p>These spreadsheets haven’t been converted to the new spreadsheet template yet. They’re for ebooks which we have already produced, but may still need more work.</p>',
	],
];
?>
<?= Template::Header(title: 'Research Spreadsheets', highlight: 'contribute', description: 'A list of spreadsheets created and used by Standard Ebooks producers.') ?>
<main>
	<article>
		<h1>Research Spreadsheets</h1>

		<? if(Session::$User?->Benefits->CanEditSpreadsheets){ ?>
			<ul role="menu">
				<li><a href="/spreadsheets/new">Create a spreadsheet</a></li>
			</ul>
		<? } ?>

		<? if($isCreated){ ?>
			<p class="message success">Spreadsheet created!</p>
		<? } ?>

		<? if($isSaved){ ?>
			<p class="message success">Spreadsheet saved!</p>
		<? } ?>

		<? if($isDeleted){ ?>
			<p class="message success">Spreadsheet deleted!</p>
		<? } ?>

		<p>From time to time it’s useful to compile spreadsheets of an author’s oeuvre. This page lists some of the spreadsheets our volunteers have created.</p>

		<section id="help-wanted">
			<h2>Help wanted</h2>
			<p>We’re looking for volunteers to:</p>
			<ul>
				<li>
					<p><a href="#creating-a-new-spreadsheet">Create new research spreadsheets.</a></p>
				</li>
				<li>
					<p>Convert our <a href="#legacy">legacy spreadsheets</a> to the <a href="#creating-a-new-spreadsheet">new spreadsheet format</a>.</p>
				</li>
			</ul>
		</section>
		<section id="spreadsheets">
			<h2>Spreadsheets</h2>
			<? foreach($spreadsheetSections as $category => $spreadsheetSection){ ?>
				<section id="<?= $spreadsheetSection['id'] ?>">
					<h3><?= $spreadsheetSection['title'] ?></h3>
					<?= $spreadsheetSection['description'] ?>
					<ul>
						<? foreach($spreadsheets[$category] as $spreadsheet){ ?>
							<li>
								<p>
									<a href="<?= Formatter::EscapeHtml($spreadsheet->ExternalUrl) ?>"><?= Formatter::EscapeHtml($spreadsheet->Title) ?></a><? if($spreadsheet->Notes !== null){ ?> (<?= $spreadsheet->Notes->ToHtmlFragment(true) ?>)<? } ?><? if(Session::$User?->Benefits->CanEditSpreadsheets){ ?> — <a href="<?= $spreadsheet->EditUrl ?>">Edit</a> • <a href="<?= $spreadsheet->DeleteUrl ?>">Delete</a><? } ?>
								</p>
							</li>
						<? } ?>
					</ul>
				</section>
			<? } ?>
		</section>
		<section id="creating-a-new-spreadsheet">
			<h2>Creating a new spreadsheet</h2>
			<p>If you’d like to create a spreadsheet to catalog possible items to include in one or more omnibuses, please start by copying our <a href="https://docs.google.com/spreadsheets/d/1CE2AsQ7E5WS9NORubHuiVAmWHYDrmU3bXWUghmnU4kU/copy">omnibus research template</a>.</p>
			<p>You can see an <a href="https://docs.google.com/spreadsheets/d/1JbH6O1LSqYgSo7ODmbcbNcPbw7oDoAbBBCmJOp1d-kg">example of how this spreadsheet might be filled out</a>.</p>
			<ul>
				<li>
					<p>Change the title of the spreadsheet to something relevant to your research, like “Oscar Wilde Poetry.”</p>
				</li>
				<li>
					<p>When copy and pasting or moving rows or columns, make sure to <strong>paste values only</strong> (with ctrl + shift + v) to prevent damaging the automatic formatting in the spreadsheet.</p>
				</li>
				<li>
					<p>Include the author’s entire corpus, even if some items aren’t in the U.S. public domain yet, or if transcriptions or scans aren’t available, or if you feel items don’t fit the S.E. collections policy. This will help us to better understand the author’s entire corpus, and to continue adding to omnibuses over time as items gradually enter the public domain. If an item isn’t in the public domain yet, it’ll be automatically colored red.</p>
				</li>
				<li>
					<p>You may add additional columns after “Notes,” but leave the basic columns intact.</p>
				</li>
				<li>
					<p>Once you’ve actually added an item to an omnibus, check the “In omnibus?” field to indicate that it’s been included in a real S.E. omnibus and future producers no longer have to consider it.</p>
				</li>
				<li>
					<p><strong>Remember that you’re creating a spreadsheet for future producers,</strong> not just for yourself. Don’t include notes to yourself, obscure abbreviations, or information that would clutter the overall vision; don’t significantly stray from the template, or overwhelm future producers. Oftentimes, <strong>less is more</strong>.</p>
				</li>
			</ul>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
