<?
require_once('Core.php');
?><?= Template::Header(['title' => 'Collections Policy', 'highlight' => 'contribute', 'description' => 'Standard Ebooks only accepts certain kinds of ebooks for production and hosting. This is the full list.']) ?>
<main>
	<article>
		<hgroup>
			<h1>Collections Policy</h1>
			<h2 class="miniheader">Or, ebooks we do and don’t accept.</h2>
		</hgroup>
		<p>Standard Ebooks only works on books that have entered the U.S. public domain due to copyright expiration. Generally this means a book must have been published before <?= PD_STRING ?>, though there are exceptions for works from later periods that didn’t follow copyright formalities. For more information on determining the copyright status of a work in the U.S., see <a href="https://www.gutenberg.org/help/copyright.html">Project Gutenberg’s Copyright How-To</a>.</p>
		<p>Note that a book that is in the U.S. public domain may not be in the public domain of other countries, and vice versa.</p>
		<h2>Types of ebooks we do accept</h2>
		<ul>
			<li>
				<p>Most long fiction, including plays.</p>
			</li>
			<li>
				<p>Most short fiction and poetry, when presented in collections or omnibuses. We consider short fiction to be works less than 40,000 words in length, a length that typically includes novellas. Some independent short works may be produced individually on a case-by-case basis.</p>
			</li>
			<li>
				<p>Some “timeless” non-fiction, including philosophy, travelogue, classics from antiquity, and notable autobiography. In general, this means books about <em>ideas and experiences</em>, not <em>facts</em>.</p>
			</li>
		</ul>
		<h2>Types of ebooks we don’t accept</h2>
		<ul>
			<li>
				<p>Ebooks that are not clearly in the U.S. public domain. If it’s not hosted on <a href="https://www.gutenberg.org">Project Gutenberg</a>, we’ll probably decline it.</p>
			</li>
			<li>
				<p>Individual short works that could instead be collected in a larger omnibus, like individual pamphlets, essays, or short stories.</p>
				<p>For example, we wouldn’t accept individual ebook productions of Jonathan Swift’s “<a href="https://www.gutenberg.org/ebooks/1080">A Modest Proposal</a>” or Philip K. Dick’s “<a href="https://www.gutenberg.org/ebooks/32522">Mr. Spaceship</a>.” However we <em>do</em> want <em>complete compilations</em> of those kinds of short works, for example our <a href="/ebooks/philip-k-dick/short-fiction">ebook of all of Philip K. Dick’s public domain short stories</a>.</p>
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
						<p>Craft and science books including cookbooks, practical how-tos, instructional books, books of medicine, textbooks, books about science, books of research, or technical references;</p>
					</li>
					<li>
						<p>Periodical or journalistic non-fiction like magazine articles, serials, newspapers, and other journalism;</p>
					</li>
					<li>
						<p>Legal documents, including codes of law and constitutions.</p>
					</li>
				</ul>
				<p>In general, this means books about <em>facts</em> and not <em>ideas</em>. Note that we may occasionally make exceptions, for example for <i>The History of the Decline and Fall of the Roman Empire</i> or <i><a href="/ebooks/julius-caesar/commentaries-on-the-gallic-war/w-a-mcdevitte_w-s-bohn">Commentaries on the Gallic War</a></i> or <i>The Life of Samuel Johnson</i>.</p>
			</li>
			<li>
				<p>Books that are illustration-heavy, like picture or art books, or whose content greatly depends on the arrangement of illustrations or graphics.</p>
				<p>For example, we wouldn’t produce anything by <a href="https://en.wikipedia.org/wiki/William_Blake">William Blake</a>, as his intricate arrangement of words and illustration would result in an ebook that is merely a series of images, without parseable text.</p>
			</li>
			<li>
				<p>Modern books that have released to the public domain, including self-published books.</p>
			</li>
			<li>
				<p>Major religious texts from modern world religions, like the Bible or the Koran, won’t be accepted. Texts <em>about</em> religion will usually be accepted. Texts from historical religious movements that were culturally influential but are now defunct, or are otherwise not significant in modern times, <em>might</em> be accepted; ask first.</p>
			</li>
			<li>
				<p>Different editions of books, including editions published in different places or years, and different translations. We aim to host a single “best” edition of an book. For books that differ significantly across editions, this typically means the latest possible edition, presuming that it’s the most correct and most aligned with the author’s intent. (Of course, case-by-case circumstances often warrant exceptions to this rule of thumb.) For books in translation, we want the one public domain translation that reviewers or scholars consider to be the “best.”</p>
				<p>For example, we currently host the 1831 edition of <i><a href="/ebooks/mary-shelley/frankenstein">Frankenstein</a></i>, which is the author’s heavy revision of the original 1818 edition; therefore we wouldn’t accept the 1818 edition, or any other editions. In a similar vein, we currently host a respected translation of <i><a href="/ebooks/dante-alighieri/the-divine-comedy/henry-wadsworth-longfellow">The Divine Comedy</a></i>, therefore we wouldn’t accept a separate ebook of a different translation.</p>
			</li>
			<li>
				<p>“The complete works”-type omnibuses, in which an author who wrote in various styles (like poetry, short stories, and novels) has their entire literary corpus compiled into one ebook. However we <em>are</em> interested in omnibuses of <em>single types of writing</em>.</p>
				<p>For example, Anton Chekhov wrote many plays, short stories, novels, and novellas. We <em>wouldn’t</em> accept “The Complete Works of Anton Chekhov,” but we <em>would</em> accept <i><a href="/ebooks/anton-chekhov/short-fiction/constance-garnett">Anton Chekhov’s Complete Short Fiction</a></i>, and his many novels and plays as individual ebooks.</p>
			</li>
			<li>
				<p>Non-English-language books. Translations to English are, of course, OK.</p>
			</li>
			<li>
				<p>Translations into English which are severely abridged, Bowdlerized, wildly archaic or near-unreadable to modern readers, or which scholars agree are poor translations.</p>
				<p>For example, the only public domain translations of <i>Twenty Thousand Leagues Under the Seas</i> are ones widely considered to be slapdash—thus we wouldn’t accept any of them, even if that means not hosting the ebook at all.</p>
			</li>
		</ul>
	</article>
</main>
<?= Template::Footer() ?>
