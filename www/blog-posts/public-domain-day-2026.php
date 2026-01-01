<?
use Safe\DateTimeImmutable;

$pdYear = 2026;
$pdPublicationYear = $pdYear - 96;

// Condense getting all `Ebook`s into one DB query, and sort them at the PHP level, instead of doing so many separate queries to get each `Ebook`.
$identifiers = [
	'https://standardebooks.org/ebooks/franz-kafka/the-castle/willa-muir_edwin-muir',
	'https://standardebooks.org/ebooks/dashiell-hammett/the-maltese-falcon',
	'https://standardebooks.org/ebooks/william-faulkner/as-i-lay-dying',
	'https://standardebooks.org/ebooks/langston-hughes/not-without-laughter',
	'https://standardebooks.org/ebooks/agatha-christie/the-murder-at-the-vicarage',
	'https://standardebooks.org/ebooks/dorothy-l-sayers/strong-poison',
	'https://standardebooks.org/ebooks/evelyn-waugh/vile-bodies',
	'https://standardebooks.org/ebooks/margaret-ayer-barnes/years-of-grace',
	'https://standardebooks.org/ebooks/e-h-young/miss-mole',
	'https://standardebooks.org/ebooks/stella-benson/the-faraway-bride',
	'https://standardebooks.org/ebooks/geoffrey-dennis/the-end-of-the-world',
	'https://standardebooks.org/ebooks/arthur-ransome/swallows-and-amazons',
	'https://standardebooks.org/ebooks/t-s-eliot/poetry',
	'https://standardebooks.org/ebooks/daphne-du-maurier/short-fiction',
	'https://standardebooks.org/ebooks/edna-ferber/cimarron',
	'https://standardebooks.org/ebooks/agatha-christie/giants-bread',
	'https://standardebooks.org/ebooks/carolyn-keene/the-secret-of-the-old-clock',
	'https://standardebooks.org/ebooks/carolyn-keene/the-hidden-staircase',
	'https://standardebooks.org/ebooks/carolyn-keene/the-bungalow-mystery',
	'https://standardebooks.org/ebooks/carolyn-keene/the-mystery-at-lilac-inn',
];

$ebooks = Db::Query('SELECT e.* from Ebooks e left outer join EbookPlaceholders ep using (EbookId) where Identifier in ' . Db::CreateSetSql($identifiers), $identifiers, Ebook::class);

$ebooksWithDescriptions = [];

foreach($ebooks as $ebook){
	$description = '';

	switch($ebook->Identifier){
		case 'https://standardebooks.org/ebooks/franz-kafka/the-castle/willa-muir_edwin-muir':
			$description = '<p>A land surveyor known only as K. is summoned to a remote village to perform some work for authorities in a nearby castle. When the locals inform him that there has been a mistake, K. continues to try to make contact with the officials in the castle to complete his work, in the face of an increasingly-surreal bureaucratic nightmare.</p><p><i>The Castle</i> was incomplete at the time of <a href="/ebooks/franz-kafka">Kafka’s</a> death, and was published posthumously by his literary executor Max Brod. This 1930 translation was the book that kickstarted the English-speaking world’s interest Kafka’s uniquely oppressive and modernist style.</p>';
			break;

		case 'https://standardebooks.org/ebooks/dashiell-hammett/the-maltese-falcon':
			$description = '<p>Sam Spade is a hard-boiled detective who looks out only for himself. When a stunning femme fatale arrives at his office, Sam hesitantly takes up her case—only for his partner to be murdered during a stakeout that very night. With the police eyeing him as a suspect, Sam soon finds himeself dragged into a web of greed and lies as he hunts for a mysterious artifact rumored to be worth a fortune.</p><p><i>The Maltese Falcon</i> is one of the most famous noir detective novels ever written. Sam Spade, like the <a href="/collections/continental-op">Continental Op</a>, is the prototypical noir gumshoe—hard-drinking, cruel, but compelled to deliver justice. It was later adapted as the legendary 1941 movie starring Humphrey Bogart, which went on to become one of the most famous film noirs ever produced.</p>';
			break;

		case 'https://standardebooks.org/ebooks/agatha-christie/the-murder-at-the-vicarage':
			$description = '<p>When an unloved local is murdered in the small rural town of St. Mary Mead, the local vicar takes up the case. But he soon finds himself relying on the help of his gossipy neighbor—an old spinster named <a href="/collections/miss-marple">Miss Marple</a>.</p><p>While this novel technically isn’t Miss Marple’s first appearance—she was featured in a short story some years earlier—it <em>is</em> the first full-length story featuring the homely sleuth who would go on to become one of <a href="/ebooks/agatha-christie">Christie’s</a> most beloved characters.</p>';
			break;

		case 'https://standardebooks.org/ebooks/langston-hughes/not-without-laughter':
			$description = '<p><i>Not Without Laughter</i> is <a href="/ebooks/langston-hughes">Langston Hughes’</a> semi-autobiographical first novel. It follows a young African-American boy growing up in rural Kansas, and how race, class, and religion shape how his community develops. The novel was published at the height of the Harlem Renaissance, a movement Hughes was instrumental in leading.</p>';
			break;

		case 'https://standardebooks.org/ebooks/evelyn-waugh/vile-bodies':
			$description = '<p><a href="/ebooks/evelyn-waugh">Evelyn Waugh</a> returns in this, his second novel, to deliver another scathing, comic satire of high society. This time his targets are the “bright young things” of post-World-War-I England. Waugh deftly skewers their raunchy, raucus, Jazz-age lifestyle, as well as the middle class public, who can’t seem to get enough of their gossip.</p><p>As a testament to the novel’s staying power, David Bowie used <i>Vile Bodies</i> as the primary inspiration for his song “Aladdin Sane.”</p>';
			break;

		case 'https://standardebooks.org/ebooks/william-faulkner/as-i-lay-dying':
			$description = '<p>Right on the heels of his acclaimed masterpiece, <i><a href="/ebooks/william-faulkner/the-sound-and-the-fury">The Sound and the Fury</a></i>, <a href="/ebooks/william-faulkner">William Faulkner</a> unleashes <i>As I Lay Dying</i>, a novel widely considered to be one of the best of the 20th century. Like his previous novel, this one uses the stream-of-consciousness technique that he and his literary peers <a href="/ebooks/james-joyce">James Joyce</a> and <a href="/ebooks/virginia-woolf">Virginia Woolf</a> were perfecting. In it we follow fifteen different characters during a family’s journey across the American South to bury one of their own.</p>';
			break;

		case 'https://standardebooks.org/ebooks/margaret-ayer-barnes/years-of-grace':
			$description = '<p><i>Years of Grace</i> follows the life of Jane Ward, a rather unsophisticated young girl who comes of age in 1890s Chicago. Her family is upper middle class, and Jane finds her traditional, homebody nature being pulled by the various forces of ambition, culture, and progress that swirl around her during the effervescent decades at the turn of the century. We see her into late middle age, where the world has been ripped apart by war, and with change accelerating faster and faster as another war looms on the horizon.</p>';
			break;

		case 'https://standardebooks.org/ebooks/e-h-young/miss-mole':
			$description = '<p>Miss Mole is a middle-age housekeeper who has recently left the employ of a wealthy matron to work for the young family of a minister. The mother has recently passed, leaving the family struggling to manage the household—but luckily for them, Miss Mole is exactly the kind of capable, witty, and assertive leader they need.</p><p>As they work on ordering the household, a shadowy figure from Miss Mole’s past weaves in and out of the narrative. But whatever happened in Miss Mole’s history can’t seem to put a damper on her bright, clever, and funny outlook.</p>';
			break;

		case 'https://standardebooks.org/ebooks/stella-benson/the-faraway-bride':
			$description = '<p><i>The Faraway Bride</i> concerns the Malinin family, shopowners in 1920s Manchuria who are struck by misfortune when their store is raided by Red Army thugs. Their father recalls that a friend owes him a debt of money that could save the family’s finances—but the friend lives in Seoul, which lies at a grueling three week’s walk across the Korean mountains. The difficulty of the journey doesn’t faze the two Malinin children, who excitedly embark on a quest to save the family.</p><p>The novel is based on the story of Tobit from the Apocrypha, but its setting, and the resulting mish-mash of languages and cultures, make for a decidedly unique read.</p>';
			break;

		case 'https://standardebooks.org/ebooks/geoffrey-dennis/the-end-of-the-world':
			$description = '<p>How will humankind, and the world, end? <a href="/ebooks/geoffrey-dennis">Geoffrey Dennis</a> aims to answer that question in this singular work of semi-fiction. He explores how prophecies and predictions can twist the fate of humankind, and how the progress of science and technology can simultaneously lift humans up, while making them susceptible to control by societal and political interests. Dennis’s conclusions blend religion, science, and history to create a unique book that straddles the line between fact and fiction.</p>';
			break;

		case 'https://standardebooks.org/ebooks/arthur-ransome/swallows-and-amazons':
			$description = '<p><a href="/ebooks/arthur-ransome">Arthur Ransome</a>, a journalist in Manchester, was inspired to write this children’s story of gentle adventure while spending a summer teaching a friend’s children to sail in the Lake District.</p><p>In it, the children of two families on rural vacations meet in the wilderness; one side sails the dinghy named the <i>Swallow</i>, and the other the dinghy <i>Amazon</i>. They soon join forces against the dastardly “Captain Flint,” who in reality is just a cranky old man trying to quietly write his memoirs in his houseboat. Their adventures are a charming tale that, by staying firmly grounded in reality, completely eschews the tropes typical of today’s children’s books.</p>';
			break;

		case 'https://standardebooks.org/ebooks/t-s-eliot/poetry':
			$ebook->Title = 'Ash Wednesday';
			$description = '<p><a href="/ebooks/t-s-eliot">T. S. Eliot</a> wrote “Ash Wednesday” during his conversion to Anglicanism. It explores themes of searching for the divine. His previous poetry, like “<a href="/ebooks/t-s-eliot/poetry/text/poetry#the-waste-land">The Waste Land</a>,” suggests that meaning could only be found in high art; in this poem, the speaker, weary of the search for meaning in the secular world, turns to God. Like most of Eliot’s poetry, “Ash Wednesday” is in a modernist style dense with allusion.</p>';
			break;

		case 'https://standardebooks.org/ebooks/daphne-du-maurier/short-fiction':
			$description = '<p><a href="/ebooks/daphne-du-maurier">Daphne du Maurier</a> had a long and illustrious career writing short fiction. This year, four of her short stories enter the U.S. public domain: “The Lover,” “The Supreme Artist,” “Frustration,” and “Indiscretion.” In her time, Du Maurier was often categorized as a romantic novelist, a label that frustrated her to no end, because her stories have more in common with the works of writers like <a href="/ebooks/wilkie-collins">Wilkie Collins</a> in that they explore dark and sinister themes, often tinged with a paranormal flavor. Many of her works have since been adapted to film.</p>';
			break;

		case 'https://standardebooks.org/ebooks/agatha-christie/giants-bread':
			$description = '<p><i>Giant’s Bread</i> is the first novel <a href="/ebooks/agatha-christie">Agatha Christie</a> wrote under a pen name, because it differed so much from her usual mystery fare that she wanted it to stand up to public scrutiny under its own merits and not on her reputation. And stand up it did, as reviewers received it with glowing praise.</p><p>The book follows Vernon Deyre, a young Englishman and brilliant musician, from infancy to adulthood before and during the Great War. It explores themes of love, sacrifice, art, and ultimately, redemption.</p>';
			break;

		case 'https://standardebooks.org/ebooks/edna-ferber/cimarron':
			$description = '<p><i>Cimarron</i> was the best-selling novel of 1930. Set in the lands of Oklahoma during the land rushes of 1889 and 1893, it follows a young family trying to make a life for themselves in a land beset by scrabbling settlers and outraged natives. The family’s trajectory rises as the charismatic patriarch founds a newspaper and settles local disputes, while the matriarch transforms from a faint-hearted Southern belle into a hard-eyed frontierswoman and politician.</p><p>Though later seen as a paean to feminism, <a href="/ebooks/edna-ferber">Ferber</a> originally intended the story to be a satire of American womanhood. Its popularity led it to being published as an Armed Services Edition and sent to soldiers on the front—the same fortuitous fate that graced <i><a href="/ebooks/f-scott-fitzgerald/the-great-gatsby">The Great Gatsby</a></i>—cementing its fame in the minds of a generation.</p>';
			break;

		case 'https://standardebooks.org/ebooks/dorothy-l-sayers/strong-poison':
			$description = '<p>Mystery writer Harriet Vane is on trial for the murder of her lover, a man with unconventional opinions on anarchy and free love. The result is a hung jury, so the judge orders a retrial—the perfect opportunity for <a href="/collections/lord-peter-wimsey">Lord Peter Wimsey</a> to unravel the case.</p><p>This mystery is the first appearance of the recurring character of Harriet Vane, who is said to be modeled after <a href="/ebooks/dorothy-l-sayers">Sayers</a> herself. Indeed, the novel incorporates many aspects of own life—including her famously rocky affair with a proponent of free love, John Cournos.</p>';
			break;

		case 'https://standardebooks.org/ebooks/carolyn-keene/the-secret-of-the-old-clock':
			$description = '<p><i>The Secret of the Old Clock</i> is the first <a href="/collections/nancy-drew-mystery-stories">Nancy Drew</a> novel—the bestseller that started a series spanning nearly a century. In it, Nancy is enlisted by the Turners, a family struggling to locate the missing will of a wealthy, recently-deceased relative. Though this book was published in 1930, it was rewritten in 1959 by Harriet Adams. This Standard Ebooks edition is the original 1930 text.</p>';
			break;

		case 'https://standardebooks.org/ebooks/carolyn-keene/the-hidden-staircase':
			$description = '<p>In <i>The Hidden Staircase</i>, <a href="/collections/nancy-drew-mystery-stories">Nancy Drew</a> is called upon to solve another mystery. This time, two elderly sisters are experiencing hauntings in their mansion, and they need Nancy’s help to figure out what’s going on.</p><p>Like the other Nancy Drew novels of the era, this one was heavily revised in 1959. The Standard Ebooks edition follows the original 1930 text.</p>';
			break;

		case 'https://standardebooks.org/ebooks/carolyn-keene/the-bungalow-mystery':
			$description = '<p><a href="/collections/nancy-drew-mystery-stories">Nancy Drew</a> is rowing with a friend when their boat capsizes in a storm. Their rescuer is Laura, a young woman about to enter into a large inheritance—and her new guardian is dead set on stealing it.</p><p>This is the last of the first three so-called “breeder” Nancy Drew stories, published to pilot the commercial viability of the series. It was heavily revised in 1960, but this Standard Ebooks edition follows the 1930 text.</p>';
			break;

		case 'https://standardebooks.org/ebooks/carolyn-keene/the-mystery-at-lilac-inn':
			$description = '<p><a href="/collections/nancy-drew-mystery-stories">Nancy Drew</a>, young detective, is on the case again when her friend’s inheritance of diamonds, worth forty thousand dollars, is stolen during a lunch. Her friend’s guardian is the prime suspect. Can Nancy uncover the true criminal before an innocent person is locked away?</p>
				<p>Like many other Nancy Drew novels, this one was rewritten in 1961 by a different author. This Standard Ebooks edition follows the 1930 text, written by Mildred Wirt Benson under the Carolyn Keene pen name.</p>';
			break;
	}

	$ebooksWithDescriptions[array_search($ebook->Identifier, $identifiers)] = ['ebook' => $ebook, 'description' => $description];
}

ksort($ebooksWithDescriptions);

?><?= Template::Header(title: 'Public Domain Day ' . $pdYear . ' in Literature - Blog', description: 'Read about the new ebooks Standard Ebooks is releasing for Public Domain Day ' . $pdYear . '!', css: ['/css/public-domain-day.css']) ?>
<main>
	<section class="narrow blog has-hero">
		<nav class="breadcrumbs"><a href="/blog">Blog</a> →</nav>
		<hgroup>
			<h1>Public Domain Day <?= $pdYear ?> in Literature</h1>
			<p>Read <?= number_format(sizeof($identifiers)) ?> of the best books entering the public domain in <?= $pdYear ?></p>
		</hgroup>
		<picture data-caption="Birmingham Reference Library—The Reading Room. Edward Richard Taylor, 1881">
			<source srcset="/images/birmingham-reference-library@2x.avif 2x, /images/birmingham-reference-library.avif 1x" type="image/avif"/>
			<source srcset="/images/birmingham-reference-library@2x.jpg 2x, /images/birmingham-reference-library.jpg 1x" type="image/jpg"/>
			<img src="/images/birmingham-reference-library@2x.jpg" alt="The reading room of a large neoclassical reference library."/>
		</picture>
		<p>Happy Public Domain Day!</p>
		<p>Around the world, people celebrate Public Domain Day on January 1, the day in which copyright expires on some older works and they enter the public domain in many different countries.</p>
		<p>In the U.S. Constitution, copyright terms were meant to be very limited in order to “promote the Progress of Science and useful Arts.” The first copyright act, written in 1790 by the founding fathers themselves, set the term to be up to twenty-eight years.</p>
		<p>But since then, powerful corporations have repeatedly extended the length of copyright to promote not the progress of society, but their profit. The result is that today in the U.S., work only enters the public domain ninety-five years after publication—locking our culture away for <em>nearly a century</em>.</p>
		<p>2019 was the year in which new works were finally scheduled to enter the public domain, ending this long, corporate-dictated cultural winter. And as that year drew closer, it became clear that these corporations <em>wouldn’t</em> try to extend copyright yet again—making it the first year in almost a hundred years in which a significant amount of art and literature once again entered the U.S. public domain, free for anyone in the U.S. to read, use, share, remix, build upon, and enjoy.</p>
		<p>Ever since then, January 1 has been celebrated as Public Domain Day, the day in which the next year’s crop of books, movies, music, and artwork graduates into the public domain. At Standard Ebooks, we’ve prepared some of the year’s biggest literary hits for you to read this January 1.</p>
		<hr class="fleuron"/>
		<p><strong>On January 1, <?= $pdYear ?>, books published in <?= $pdPublicationYear ?> enter the U.S. public domain.</strong></p>
		<p>This includes legendary books by <a href="/ebooks/william-faulkner">William Faulkner</a>, <a href="/ebooks/franz-kafka">Franz Kafka</a>, <a href="/ebooks/agatha-christie">Agatha Christie</a>, and <a href="/ebooks/langston-hughes">Langston Hughes</a>. In addition, <i><a href="/ebooks/dashiell-hammett/the-maltese-falcon">The Maltese Falcon</a></i>, perhaps the best-known noir book—and film—of all time, and books by <a href="/ebooks/evelyn-waugh">Evelyn Waugh</a>, <a href="/ebooks/dorothy-l-sayers">Dorothy L. Sayers</a>, and more, enter the U.S. public domain, becoming free for anyone in the U.S. to read, use, and re-use.</p>
		<p>Our friends at the Public Domain Review have written about some <a href="https://publicdomainreview.org/blog/2026/01/public-domain-day-2026/">other things that enter the public domain this year, too</a>.</p>
		<p>These past few months at Standard Ebooks, our volunteers have been working hard to prepare a selection of the books published in <?= $pdPublicationYear ?> in advance of Public Domain Day. We’re excited to finally be able to share these <strong><?= number_format(sizeof($identifiers)) ?> new free ebooks</strong> with you!</p>
		<? if(sizeof($ebooksWithDescriptions) == 0){ ?>
			<p class="empty">We’re still preparing these free ebooks for Public Domain Day. Check back on January 1!</p>
		<? }else{ ?>
			<? if(PD_NOW < new DateTimeImmutable('January 1, ' . $pdYear . ' 8:00 AM', SITE_TZ)){ ?>
				<aside class="alert">
					<p>It’s not Public Doman Day yet — these books will be revealed and available to download for free on January 1, <?= $pdYear ?>.</p>
				</aside>
			<? } ?>
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
							<? if(sizeof($ebookGroup['ebook']->CollectionMemberships) > 0){ ?>
								<div class="collections">
									<? foreach($ebookGroup['ebook']->CollectionMemberships as $collectionMembership){ ?>
										<p>
											<?= Template::CollectionDescriptor(collectionMembership: $collectionMembership) ?>.
										</p>
									<? } ?>
								</div>
							<? } ?>
							<?= $ebookGroup['description'] ?>
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
