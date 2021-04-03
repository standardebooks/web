<?
require_once('Core.php');
?><?= Template::Header(['title' => 'Ebooks We Do and Don’t Accept', 'highlight' => 'contribute', 'description' => 'Standard Ebooks only accepts certain kinds of ebooks for production and hosting. This is the full list.']) ?>
<main>
	<article>
		<h1>Ebooks We Do and Don’t Accept</h1>
		<p>Standard Ebooks only works on books that have entered the U.S. public domain due to copyright expiration. Generally this means a book must have been published in <?= PD_YEAR ?> or earlier, though there are exceptions for works from later periods that didn’t follow copyright formalities. For full details, see <a href="https://www.gutenberg.org/help/copyright.html">Project Gutenberg’s Copyright How-To</a>.</p>
		<h2>Types of ebooks we do accept</h2>
		<ul>
			<li>
				<p>Most long fiction, including plays.</p>
			</li>
			<li>
				<p>Most short fiction and poetry, when presented in individual collections or omnibus forms. We consider short fiction to be works less than 40,000 words in length, which typically includes novellas.</p>
			</li>
			<li>
				<p>Some “timeless” non-fiction, including philosophy and notable autobiography.</p>
			</li>
		</ul>
		<h2>Types of ebooks we don’t accept</h2>
		<ul>
			<li>
				<p>Ebooks that are not clearly in the U.S. public domain. If it’s not hosted on <a href="https://www.gutenberg.org">Project Gutenberg</a>, we’ll probably decline it.</p>
			</li>
			<li>
				<p>Individual short works that could instead be collected in a larger omnibus, like individual pamphlets, essays, or short stories. For example, we wouldn’t accept individual ebook productions of Jonathan Swift’s “<a href="https://www.gutenberg.org/ebooks/1080">A Modest Proposal</a>” or Philip K. Dick’s “<a href="https://www.gutenberg.org/ebooks/32522">Mr. Spaceship</a>.” However we <em>do</em> want <em>complete compilations</em> of those kinds of short works, for example our <a href="/ebooks/philip-k-dick/short-fiction">ebook of all of Philip K. Dick’s public domain short stories</a>.</p>
			</li>
			<li>
				<p>Most non-fiction, including:</p>
				<ul>
					<li>
						<p>Dated non-fiction that isn’t relevant to a modern reader;</p>
					</li>
					<li>
						<p>Histories that aren’t otherwise notable and that could be superseded by modern research (though we <em>might</em> accept book-length primary source historical accounts);</p>
					</li>
					<li>
						<p>Biographies of long-forgotten people that aren’t notable;</p>
					</li>
					<li>
						<p>Craft and science books including cookbooks, books of medicine, textbooks, books about science, books of research, or technical references;</p>
					</li>
					<li>
						<p>Periodical or journalistic non-fiction like magazine articles, serials, newspapers, and other journalism;</p>
					</li>
					<li>
						<p>Legal documents, including codes of law and constitutions.</p>
					</li>
				</ul>
				<p>Note that we may occasionally make exceptions, for example for <i>The History of the Decline and Fall of the Roman Empire</i> or <i><a href="/ebooks/julius-caesar/commentaries-on-the-gallic-war/w-a-mcdevitte_w-s-bohn">Commentaries on the Gallic War</a></i> or <i>The Life of Samuel Johnson</i>.</p>
			</li>
			<li>
				<p>Books that are illustration-heavy, like picture or art books, or whose content greatly depends on the arrangement of illustrations or graphics.</p>
			</li>
			<li>
				<p>Modern books that have released to the public domain, including self-published books.</p>
			</li>
			<li>
				<p>Major religious texts from modern world religions, like the Bible or the Koran, won’t be accepted. Texts <em>about</em> religion will usually be accepted. Texts from historical religious movements that were culturally influential but are now defunct, or are otherwise not significant in modern times, <em>might</em> be accepted; ask first.</p>
			</li>
			<li>
				<p>“The complete works”-type ebooks, in which an author who wrote in various styles (like poetry, short stories, and novels) has their entire corpus compiled into one ebook. However we <em>are</em> interested in complete collections of <em>single types of writing</em>. For example, Anton Chekhov wrote many plays, short stories, novels, and novellas. We <em>wouldn’t</em> accept “The Complete Works of Anton Chekhov,” but we <em>would</em> accept <i><a href="/ebooks/anton-chekhov/short-fiction/constance-garnett">Anton Chekhov’s Complete Short Fiction</a></i>, or individual novels or plays he wrote.</p>
			</li>
			<li>
				<p>Ebooks in translation for which we already have a translation. We aim to host one “best” version of books; for example, we already have a modern translation of <i><a href="/ebooks/jules-verne/twenty-thousand-leagues-under-the-seas/f-p-walter">Twenty Thousand Leagues Under the Seas</a></i>, so we won’t be accepting a different translation.</p>
			</li>
			<li>
				<p>Non-English-language books. Translations to English are, of course, OK.</p>
			</li>
			<li>
				<p>Translations into English which are severely abridged, Bowdlerized, wildly archaic or near-unreadable to modern readers, or which scholars agree are poor translations.</p>
			</li>
		</ul>
	</article>
</main>
<?= Template::Footer() ?>
