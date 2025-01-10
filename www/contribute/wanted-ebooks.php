<?
$beginnerEbooks = Ebook::GetByIsWantedAndDifficulty(Enums\EbookPlaceholderDifficulty::Beginner);
$intermediateEbooks = Ebook::GetByIsWantedAndDifficulty(Enums\EbookPlaceholderDifficulty::Intermediate);
$advancedEbooks = Ebook::GetByIsWantedAndDifficulty(Enums\EbookPlaceholderDifficulty::Advanced);
?>
<?= Template::Header(['title' => 'Wanted Ebooks', 'highlight' => 'contribute', 'description' => 'A list of ebooks the Standard Ebooks editor would like to see produced, including suggestions for first-time producers.']) ?>
<main>
	<article>
		<h1>Wanted Ebooks</h1>
		<p>If you’re interested in producing an ebook for Standard Ebooks, why not work on one of these books?</p>
		<p>If something in this list interests you, please <a href="https://groups.google.com/g/standardebooks">contact us at our mailing list</a> for help before you start work.</p>
		<p>If you want to suggest a different book to produce, please carefully review <a href="/contribute/collections-policy">the kinds of work we do and don’t accept</a>.</p>

		<h2>Add a book to this list</h2>
		<p><a href="/donate#patrons-circle">Patrons Circle members</a> may submit ebooks for inclusion on this list.</p>
		<p>Patrons Circle members <a href="/polls">periodically vote on a selection from this list</a> to pick one ebook for immediate production. You can <a href="/donate#patrons-circle">join the Patrons Circle</a> to have a voice in the future of the Standard Ebooks catalog.</p>

		<h2>For your first production</h2>
		<p>If nothing on the list below interests you, you can pitch us something else you’d like to work on.</p>
		<p>First productions should be on the shorter side (less than 100,000 words maximum) and without too many complex formatting issues like illustrations, significant endnotes, letters, poems, etc. Most short plain fiction novels fall in this category.</p>
		<?= Template::WantedEbooksList(['ebooks' => $beginnerEbooks, 'showPlaceholderMetadata' => Session::$User?->Benefits->CanEditEbookPlaceholders]) ?>

		<h2>Moderate-difficulty productions</h2>
		<?= Template::WantedEbooksList(['ebooks' => $intermediateEbooks, 'showPlaceholderMetadata' => Session::$User?->Benefits->CanEditEbookPlaceholders]) ?>

		<h2>Advanced productions</h2>
		<?= Template::WantedEbooksList(['ebooks' => $advancedEbooks, 'showPlaceholderMetadata' => Session::$User?->Benefits->CanEditEbookPlaceholders]) ?>

		<h2 id="verne">Jules Verne</h2>
		<p>Verne has a complex publication and translation history. Please review these notes before starting any Verne books.</p>
		<ul class="wanted-list">
			<li>
				<p>As of 2025, <i>20,000 Leagues Under the Seas</i> does not have an acceptable public domain translation, therefore we will not host that ebook.</p>
			</li>
			<li>
				<p>Master of the World has two PD translations, one from 1911 and one from 1914. The 1911 version is bad, and the 1914 by Cranstoun Metcalfe version is preferred; but, as of 2023, there are no transcriptions or page scans for the 1914 version.</p>
			</li>
		</ul>

		<h2>Uncategorized lists</h2>
		<ul>
			<li>
				<p><a href="https://en.wikipedia.org/wiki/James_Tait_Black_Memorial_Prize">Entries in the James Tait Black Memorial Prize list</a></p>
			</li>
			<li>
				<p><a href="https://en.wikipedia.org/wiki/Prix_Goncourt">English translations from the Prix Goncourt list</a></p>
			</li>
			<li>
				<p><a href="https://en.wikipedia.org/wiki/Hawthornden_Prize">Entries in the Hawthornden Prize list</a></p>
			</li>
			<li>
				<p><a href="https://en.wikipedia.org/wiki/Newcastle_Forgotten_Fantasy_Library">Public domain entries in the Newcastle Forgotten Fantasy Library</a></p>
			</li>
			<li>
				<p><a href="https://www.theguardian.com/books/2015/aug/17/the-100-best-novels-written-in-english-the-full-list">Public domain entries in the Guardian’s top 100 novels of all time list</a></p>
			</li>
			<li>
				<p><a href="https://en.wikipedia.org/wiki/Le_Monde's_100_Books_of_the_Century">Public domain entries in Le Mondes’s 100 Books of the Century</a></p>
			</li>
			<li>
				<p><a href="https://en.wikipedia.org/wiki/Pulitzer_Prize_for_Drama">Public domain entries in Pulitzer Prize for Drama</a></p>
			</li>
			<li>
				<p><a href="https://en.wikipedia.org/wiki/Pulitzer_Prize_for_Fiction">Public domain entries in Pulitzer Prize for Fiction</a></p>
			</li>
			<li>
				<p>Public domain entries in the <a href="https://en.wikipedia.org/wiki/Ballantine_Adult_Fantasy_series">Ballantine Adult Fantasy series</a> (Note that not all of these are in the U.S. public domain)</p>
			</li>
			<li>
				<p>Public domain entries in this <a href="https://docs.google.com/spreadsheets/d/1thH8qI_JgKc96Jzyvv6-N3H-oFBon5dwXqqI8DiTS9o/edit?usp=sharing">Spreadsheet of plays that have appeared on lists of the “Best Plays of All Time.”</a></p>
			</li>
			<li>
				<p>Entries in the <a href="https://en.wikipedia.org/wiki/Harvard_Classics#The_Harvard_Classics_Shelf_of_Fiction">Harvard Classics Shelf of Fiction</a></p>
			</li>
		</ul>
	</article>
</main>
<?= Template::Footer() ?>
