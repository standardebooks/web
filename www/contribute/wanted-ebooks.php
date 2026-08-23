<?
$beginnerEbooks = Ebook::GetByIsWantedAndDifficulty(Enums\EbookPlaceholderDifficulty::Beginner);
$intermediateEbooks = Ebook::GetByIsWantedAndDifficulty(Enums\EbookPlaceholderDifficulty::Intermediate);
$advancedEbooks = Ebook::GetByIsWantedAndDifficulty(Enums\EbookPlaceholderDifficulty::Advanced);

$collections = Collection::GetAllByMissingEntries();
?>
<?= Template::Header(title: 'Wanted Ebooks', highlight: 'contribute', description: 'A list of ebooks the Standard Ebooks editor would like to see produced, including suggestions for first-time producers.') ?>
<main>
	<article>
		<h1>Wanted Ebooks</h1>
		<? if(Session::$User?->Benefits->CanEditEbookPlaceholders){ ?>
			<ul role="menu">
				<li><a href="/ebook-placeholders/new?ebook-placeholder-is-wanted=true">Submit a new ebook placeholder</a></li>
			</ul>
		<? } ?>
		<p>If you’re interested in producing an ebook for Standard Ebooks, why not work on one of these books?</p>
		<p>If something in this list interests you, please <a href="https://groups.google.com/g/standardebooks">contact us at our mailing list</a> for help before you start work.</p>
		<p>If you want to suggest a different book to produce, please carefully review <a href="/contribute/collections-policy">the kinds of work we do and don’t accept</a>.</p>
		<p>You can also see <a href="/projects">a list of all of our currently active ebook projects</a>.</p>

		<section id="add-a-book-to-this-list">
			<h2>Add a book to this list<a class="heading-permalink" href="#add-a-book-to-this-list" aria-label="Permalink"></a></h2>
		<p><a href="/donate#patrons-circle">Patrons Circle members</a> may submit ebooks for inclusion on this list.</p>
		<p>Patrons Circle members <a href="/polls">periodically vote on a selection from this list</a> to pick one ebook for immediate production. You can <a href="/donate#patrons-circle">join the Patrons Circle</a> to have a voice in the future of the Standard Ebooks catalog.</p>

		</section>
		<section id="omnibuses">
			<h2>Omnibuses<a class="heading-permalink" href="#omnibuses" aria-label="Permalink"></a></h2>
		<p>In addition to the books on this page, our volunteers have also compiled <a href="/contribute/spreadsheets">various spreadsheets</a> to plan future omnibuses. Creating or contributing to an omnibus is a task for someone who has already produced at least one ebook for S.E., though volunteers of any skill level can help us <a href="/contribute/spreadsheets#help-wanted">complete missing research</a> in these spreadsheets.</p>

		</section>
		<section id="for-your-first-production">
			<h2>For your first production<a class="heading-permalink" href="#for-your-first-production" aria-label="Permalink"></a></h2>
		<p>If nothing on the list below interests you, you can pitch us something else you’d like to work on.</p>
		<p>First productions should be on the shorter side (less than 100,000 words maximum) and without too many complex formatting issues like illustrations, significant endnotes, letters, poems, etc. Most short plain fiction novels fall in this category.</p>
		<?= Template::WantedEbooksList(ebooks: $beginnerEbooks, showPlaceholderMetadata: Session::$User->Benefits->CanEditEbookPlaceholders ?? false) ?>

		</section>
		<section id="moderate-difficulty-productions">
			<h2>Moderate-difficulty productions<a class="heading-permalink" href="#moderate-difficulty-productions" aria-label="Permalink"></a></h2>
		<?= Template::WantedEbooksList(ebooks: $intermediateEbooks, showPlaceholderMetadata: Session::$User->Benefits->CanEditEbookPlaceholders ?? false) ?>

		</section>
		<section id="advanced-productions">
			<h2>Advanced productions<a class="heading-permalink" href="#advanced-productions" aria-label="Permalink"></a></h2>
		<?= Template::WantedEbooksList(ebooks: $advancedEbooks, showPlaceholderMetadata: Session::$User->Benefits->CanEditEbookPlaceholders ?? false) ?>
		</section>

		<? if(sizeof($collections) > 0){ ?>
			<section id="collections">
				<h2>Collections<a class="heading-permalink" href="#collections" aria-label="Permalink"></a></h2>
			<p>These collections are partially complete, and contain ebooks that can be worked on immediately.</p>
			<ul class="collections-list">
				<? foreach($collections as $collection){ ?>
					<li>
						<p><a href="<?= $collection->Url ?>"><?= Formatter::EscapeHtml($collection->Name) ?></a></p>
					</li>
				<? } ?>
			</ul>
			</section>
		<? } ?>

		<section id="verne">
			<h2>Jules Verne<a class="heading-permalink" href="#verne" aria-label="Permalink"></a></h2>
		<p>Verne has a complex publication and translation history. Please review these notes before starting any Verne books.</p>
		<ul class="wanted-list">
			<li>
				<p>The first acceptable translation of <i><a href="/ebooks/jules-verne/twenty-thousand-leagues-under-the-seas/walter-james-miller">20,000 Leagues Under the Seas</a></i> was published in 1966 by Walter James Miller. All known earlier translations are widely considered to be bad, therefore we won’t host them and must wait until the Miller translation enters the public domain.</p>
			</li>
			<li>
				<p>Master of the World has two PD translations, one from 1911 and one from 1914. The 1911 version is bad, and the 1914 by Cranstoun Metcalfe version is preferred; but as of 2023, there are no transcriptions or page scans of the 1914 version.</p>
			</li>
		</ul>

		</section>
		<section id="uncategorized-lists">
			<h2>Uncategorized lists<a class="heading-permalink" href="#uncategorized-lists" aria-label="Permalink"></a></h2>
		<ul>
			<li>
				<p><a href="https://en.wikipedia.org/wiki/Prix_Goncourt">English translations from the Prix Goncourt list</a></p>
			</li>
			<li>
				<p><a href="https://en.wikipedia.org/wiki/Newcastle_Forgotten_Fantasy_Library">Public domain entries in the Newcastle Forgotten Fantasy Library</a></p>
			</li>
			<li>
				<p>Public domain entries in the <a href="https://en.wikipedia.org/wiki/Ballantine_Adult_Fantasy_series">Ballantine Adult Fantasy series</a> (Note that not all of these are in the U.S. public domain)</p>
			</li>
			<li>
				<p>Public domain entries in this <a href="https://docs.google.com/spreadsheets/d/1thH8qI_JgKc96Jzyvv6-N3H-oFBon5dwXqqI8DiTS9o/edit?usp=sharing">Spreadsheet of plays that have appeared on lists of the “Best Plays of All Time.”</a></p>
			</li>
		</ul>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
