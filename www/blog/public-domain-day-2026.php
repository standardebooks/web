<?

// Condense getting all `Ebook`s into one DB query, and sort them at the PHP level, instead of doing so many separate queries to get each `Ebook`.
$identifiers = [
	'https://standardebooks.org/ebooks/franz-kafka/the-castle/willa-muir_edwin-muir',
	'https://standardebooks.org/ebooks/dashiell-hammett/the-maltese-falcon',
	'https://standardebooks.org/ebooks/agatha-christie/the-murder-at-the-vicarage',
	'https://standardebooks.org/ebooks/langston-hughes/not-without-laughter',
	'https://standardebooks.org/ebooks/evelyn-waugh/vile-bodies',
	'https://standardebooks.org/ebooks/william-faulkner/as-i-lay-dying',
	'https://standardebooks.org/ebooks/margaret-ayer-barnes/years-of-grace',
	'https://standardebooks.org/ebooks/e-h-young/miss-mole',
	'https://standardebooks.org/ebooks/stella-benson/the-far-away-bride',
	'https://standardebooks.org/ebooks/geoffrey-dennis/the-end-of-the-world',
	'https://standardebooks.org/ebooks/arthur-ransome/swallows-and-amazons',
	'https://standardebooks.org/ebooks/edna-ferber/cimarron',
	'https://standardebooks.org/ebooks/t-s-eliot/poetry',
	'https://standardebooks.org/ebooks/daphne-du-maurier/short-fiction'
];

// Get all `Ebook`s that are not placeholders. We may have some PD Day books in our placeholders list but we don't want to show them here!
$ebooks = Db::Query('SELECT e.* from Ebooks e left outer join EbookPlaceholders ep using (EbookId) where Identifier in ' . Db::CreateSetSql($identifiers) . ' and ep.EbookId is null', $identifiers, Ebook::class);

$ebooksWithDescriptions = [];

foreach($ebooks as $ebook){
	$description = '';

	switch($ebook->Identifier){
		case 'https://standardebooks.org/ebooks/franz-kafka/the-castle/willa-muir_edwin-muir':
			$description = '<p>A land surveyor known only as K. is summoned to a remote village to perform some work for authorities in a nearby castle. When the locals inform him that there has been a mistake, K. continues to try to make contact with the officials in the castle to complete his work, in the face of an increasingly-surreal bureaucratic nightmare.</p><p><i>The Castle</i> was incomplete at the time of <a href="https://standardebooks.org/ebooks/franz-kafka">Kafka’s</a> death, and was published posthumously. This 1930 translation was the book that kickstarted the English-speaking world’s interest in all things Kafka.</p>';
			break;

		case 'https://standardebooks.org/ebooks/dashiell-hammett/the-maltese-falcon':
			$description = '<p>Sam Spade is a hard-boiled detective who looks out only for himself. When a stunning femme fatale arrives at his office, Sam hesitantly takes up her case—only for his partner to be murdered during a stakeout that very night. With the police eyeing him as a suspect, Sam soon finds himeself dragged into a web of greed and lies as he hunts for a mysterious artifact rumored to be worth a fortune.</p><p><i>The Maltese Falcon</i> is one of the most famous noir detective novels ever written. Sam Spade, like the <a href="/collections/continental-op">Continental Op</a>, is the prototypical noir gumshoe—hard-drinking, cruel, but compelled to deliver justice. It was later adapted as the legendary 1941 movie starring Humphrey Bogart, which went on to become one of the most famous film noirs ever produced.</p>';
			break;

		case 'https://standardebooks.org/ebooks/agatha-christie/the-murder-at-the-vicarage':
			$description = '<p>When an unloved local is murdered in the small rural town of St. Mary Mead, the local vicar takes up the case. But he soon finds himself relying on the help of his gossipy neighbor—an old spinster named <a href="/collections/miss-marple">Miss Marple</a>.</p><p>While this novel technically isnt’t Miss Marple’s first appearance—she was featured in a short story some years earlier—it <em>is</em> the first full-length story featuring the homely sleuth who would go on to become one of <a href="/ebooks/agatha-christie">Christie’s</a> most beloved characters.</p>';
			break;

		case 'https://standardebooks.org/ebooks/langston-hughes/not-without-laughter':
			$description = '<p><i>Not Without Laughter</i> is <a href="https://standardebooks.org/ebooks/langston-hughes">Langston Hughes’</a> semi-autobiographical first novel. It follows a young African-American boy growing up in rural Kansas, and how race, class, and religion shape how his community develops. The novel was published at the height of the Harlem Renaissance, a movement Hughes was instrumental in leading.</p>';
			break;

		case 'https://standardebooks.org/ebooks/evelyn-waugh/vile-bodies':
			$description = '<p></p>';
			break;
	}

	$ebooksWithDescriptions[array_search($ebook->Identifier, $identifiers)] = ['ebook' => $ebook, 'description' => $description];
}

ksort($ebooksWithDescriptions);

?><?= Template::Header(title: 'Public Domain Day 2025 in Literature - Blog', description: 'Read about the new ebooks Standard Ebooks is releasing for Public Domain Day 2025!', css: ['/css/public-domain-day.css']) ?>
<main>
	<section class="narrow blog has-hero">
		<nav class="breadcrumbs"><a href="/blog">Blog</a> →</nav>
		<hgroup>
			<h1>Public Domain Day in Literature</h1>
			<p>Read <?= number_format(sizeof($identifiers)) ?> of the best books entering the public domain in 2025</p>
		</hgroup>
		<picture data-caption="The Reader. Harold Knight, circa 1910">
			<source srcset="/images/the-reader@2x.avif 2x, /images/the-reader.avif 1x" type="image/avif"/>
			<source srcset="/images/the-reader@2x.jpg 2x, /images/the-reader.jpg 1x" type="image/jpg"/>
			<img src="/images/the-reader@2x.jpg" alt="An oil painting of a woman reading a book in front of a bookcase."/>
		</picture>
		<p>Happy Public Domain Day!</p>
		<p>Around the world, people celebrate Public Domain Day on January 1, the day in which copyright expires on some older works and they enter the public domain in many different countries.</p>
		<p>In the U.S. Constitution, copyright terms were meant to be very limited in order to “promote the Progress of Science and useful Arts.” The first copyright act, written in 1790 by the founding fathers themselves, set the term to be up to twenty-eight years.</p>
		<p>But since then, powerful corporations have repeatedly extended the length of copyright to promote not the progress of society, but their profit. The result is that today in the U.S., work only enters the public domain ninety-five years after publication—locking our culture away for <em>nearly a century</em>.</p>
		<p>2019 was the year in which new works were finally scheduled to enter the public domain, ending this long, corporate-dictated cultural winter. And as that year drew closer, it became clear that these corporations <em>wouldn’t</em> try to extend copyright yet again—making it the first year in almost a century in which a significant amount of art and literature once again entered the U.S. public domain, free for anyone in the U.S. to read, use, share, remix, build upon, and enjoy.</p>
		<p>Ever since then, we’ve been celebrating Public Domain Day by preparing some of the year’s biggest literary hits for you to read on January 1.</p>
		<hr class="fleuron"/>
		<p><strong>On January 1, 2026, books published in 1930 enter the U.S. public domain.</strong></p>
		<p>Books by <a href="/ebooks/william-faulkner">William Faulkner</a>, <a href="/ebooks/ernest-hemingway">Ernest Hemingway</a>, <a href="/ebooks/mahatma-gandhi">Mahatma Gandhi</a>, and <a href="/ebooks/john-steinbeck">John Steinbeck</a> enter the U.S. public domain. Joining these esteemed names is the English translation of <i><a href="/ebooks/erich-maria-remarque/all-quiet-on-the-western-front/a-w-wheen">All Quiet on the Western Front</a></i>, the war novel so grisly that it was banned in parts of Europe; <i><a href="/ebooks/dashiell-hammett/red-harvest">Red Harvest</a></i>, the first novel starring the <a href="/collections/continental-op">Continental Op</a>, the hard-boiled noir detective who formed the archetype for every hard-drinking, fedora-wearing private eye to grace page and screen since; and much more.</p>
		<p>Our friends at the Public Domain Review have written about some <a href="https://publicdomainreview.org/blog/2026/01/public-domain-day-2026/">other things that enter the public domain this year, too</a>.</p>
		<p>These past few months at Standard Ebooks, our volunteers have been working hard to prepare a selection of the books published in 1930 in advance of Public Domain Day. We’re excited to finally be able to share these <strong><?= number_format(sizeof($identifiers)) ?> new free ebooks</strong> with you!</p>
		<? if(sizeof($ebooksWithDescriptions) == 0){ ?>
			<p class="empty">We’re still preparing these free ebooks for Public Domain Day. Check back on January 1!</p>
		<? }else{ ?>
			<ul class="public-domain-day">
				<? foreach($ebooksWithDescriptions as $ebookGroup){ ?>
					<li>
						<div>
							<a href="<?= $ebookGroup['ebook']->Url ?>">
								<?= Template::RealisticEbook(ebook: $ebookGroup['ebook']) ?>
							</a>
						</div>
						<div>
							<h2>
								<a href="<?= $ebookGroup['ebook']->Url ?>"><?= Formatter::EscapeHtml($ebookGroup['ebook']->Title) ?></a>
							</h2>
							<p class="byline">by <a href="<?= $ebookGroup['ebook']->AuthorsUrl ?>"><?= Formatter::EscapeHtml($ebookGroup['ebook']->Authors[0]->Name) ?></a></p>
							<p>
								<?= $ebookGroup['description'] ?>
							</p>
							<p>
								<a href="<?= $ebookGroup['ebook']->Url ?>">Download and read for free →</a>
							</p>
						</div>
					</li>
				<? } ?>
			</ul>
		<? } ?>
		<?= Template::DonationCounter() ?>
		<?= Template::DonationProgress() ?>
	</section>
</main>
<?= Template::Footer() ?>
