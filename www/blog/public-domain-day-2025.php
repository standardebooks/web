<?

// Condense getting all `Ebook`s into one DB query, and sort them at the PHP level, instead of doing so many separate queries to get each `Ebook`.
$identifiers = [
	'url:https://standardebooks.org/ebooks/william-faulkner/the-sound-and-the-fury',
	'url:https://standardebooks.org/ebooks/erich-maria-remarque/all-quiet-on-the-western-front/a-w-wheen',
	'url:https://standardebooks.org/ebooks/ernest-hemingway/a-farewell-to-arms',
	'url:https://standardebooks.org/ebooks/john-steinbeck/cup-of-gold',
	'url:https://standardebooks.org/ebooks/dashiell-hammett/red-harvest',
	'url:https://standardebooks.org/ebooks/sinclair-lewis/dodsworth',
	'url:https://standardebooks.org/ebooks/oliver-la-farge/laughing-boy',
	'url:https://standardebooks.org/ebooks/graham-greene/the-man-within',
	'url:https://standardebooks.org/ebooks/calvin-coolidge/the-autobiography-of-calvin-coolidge',
	'url:https://standardebooks.org/ebooks/lloyd-c-douglas/magnificent-obsession',
	'url:https://standardebooks.org/ebooks/josephine-tey/the-man-in-the-queue',
	'url:https://standardebooks.org/ebooks/john-buchan/the-courts-of-the-morning',
	'url:https://standardebooks.org/ebooks/j-b-priestley/the-good-companions',
	'url:https://standardebooks.org/ebooks/dashiell-hammett/the-dain-curse',
	'url:https://standardebooks.org/ebooks/c-s-forester/brown-on-resolution',
	'url:https://standardebooks.org/ebooks/arthur-conan-doyle/the-maracot-deep'
];

$ebooks = Db::Query('SELECT * from Ebooks where Identifier in ' . Db::CreateSetSql($identifiers), $identifiers, Ebook::class);

$ebooksWithDescriptions = [];

foreach($ebooks as $ebook){
	$description = '';

	switch($ebook->Identifier){
		case 'url:https://standardebooks.org/ebooks/william-faulkner/the-sound-and-the-fury':
			$description = '<p>Faulkner’s widely-acclaimed masterpiece is well-known in America, as it’s taught in high schools across the country. In it we follow the Compson family, an aristocratic family in Mississippi, and their slow decline into poverty and ruin. What makes the novel so special—and what lends it its reputation as a challenging read—is its stream-of-consciousness style, in which Faulkner attempts to narrate the characters’ thoughts directly to the reader.</p><p><i>The Sound and the Fury</i> was an essential step in developing that modernist prose style, and is still considered to be one of the greatest works of American literature.</p>';
			break;
		case 'url:https://standardebooks.org/ebooks/erich-maria-remarque/all-quiet-on-the-western-front/a-w-wheen':
			$description = '<p>One of the greatest war novels to come from any conflict, Erich Maria Remarque’s grisly tale of the brutality and horror of the German trenches during the Great War was so powerful that it earned him nominations for the Nobel Prizes in <em>both</em> Literature and Peace, and was soon widely banned in a Europe that was preparing for a second cataclysmic conflict.</p>';
			break;
		case 'url:https://standardebooks.org/ebooks/ernest-hemingway/a-farewell-to-arms':
			$description = '<p>Called “the premier American war novel from World War I,” Hemingway’s semi-autobiographical story of an American ambulance driver serving in the Italian front, and a bright and cynical British nurse, cemented his reputation as one of the generation’s foremost literary figures. Unlike Remarque, whose <i>All Quite on the Western Front</i> paints the war’s destruction in full color, Hemingway’s story is one of the mundanity of war—of the quotidian smallness that underpins even the most horrific of events.</p>';
			break;
		case 'url:https://standardebooks.org/ebooks/john-steinbeck/cup-of-gold':
			$description = '<p><i>Cup of Gold</i> is John Steinbeck’s first novel, the swashbuckling story of the real-life Captain Morgan, legendary pirate. Little of Morgan’s actual life is known, but Steinbeck fills in the blanks of this rich historical fiction with rich portraits of Caribbean ports and flourishes of magical realism.</p><p>While one might expect a rollicking tale of adventure, the novel is actually a deep meditation on the pursuit of—and inability to find—true happiness, and its skillful craft deftly foreshadows Steinbeck’s later ascension to literary titanhood.</p>';
			break;
		case 'url:https://standardebooks.org/ebooks/dashiell-hammett/red-harvest':
			$description = '<p><i>Red Harvest</i> is Dashiell Hammet’s first full-length novel to feature the <a href="/collections/continental-op">Continental Op</a>, the nameless, hard-drinking, cynical private eye that single-handedly created the archetype of the hard-boiled detective. The novel is a fast-paced, tightly-written murder thriller that simultaneously touches all the bases of, and <em>defines</em>, the classic noir style that was much-imitated, and later much-spoofed, in the following century.</p>';
			break;
		case 'url:https://standardebooks.org/ebooks/sinclair-lewis/dodsworth':
			$description = '<p>Samuel Dodsworth is a successful automobile executive who decides to retire early. His younger wife Fran wants to tour Europe, so the two embark on a trip. But soon after they arrive, Fran becomes enchanted by the whirlwind of culture and old-world society, while Samuel, a mild-mannered, down-to-earth Midwesterner, yearns to escape the pretentiousness and return to the quiet stability of home. The novel explores the slow breakdown of their marriage while deftly satirizing American middle-class mores and the wide gulf between them and European culture.</p>';
			break;
		case 'url:https://standardebooks.org/ebooks/oliver-la-farge/laughing-boy':
			$description = '<p><i>Laughing Boy</i>, the winner of the 1930 <a href="/collections/pulitzer-prize-for-fiction-winners">Pulitzer Prize for Fiction</a>, is the story of the titular main character, a young Navajo man living in the American Southwest around the turn of the 20th century. He meets the fiery young Slim Girl at a tribal meet, but her reputation precedes her, and the tribe disapproves of their union. Ignoring the advice of the tribe, the two start a life together as they try to keep ancient traditions alive in the face of the rapidly-encroaching modernization of the American Southwest.</p>';
			break;
		case 'url:https://standardebooks.org/ebooks/graham-greene/the-man-within':
			$description = '<p><i>The Man Within</i> is acclaimed novelist Graham Greene’s first novel. Set against the backdrop of the English countryside,, the novel explores themes of guilt, redemption, the nature of courage and cowardice, and the complex relationship between one’s inner beliefs and outward actions.</p>';
			break;
		case 'url:https://standardebooks.org/ebooks/calvin-coolidge/the-autobiography-of-calvin-coolidge':
			$description = '<p>Calvin Coolidge was the 30th president of the United States, entering the office as vice president when president Warren G. Harding suddenly passed, and winning a reelection term. Even though he was hugely popular, he declined running for a second full term, opting to retire instead. In this autobiography—which is as brief as “Silent Cal’s” legend suggests it might be—we follow the former president from his idyllic boyhood in Vermont, to a career in the law, to the governorship of Massachusetts, to the presidency and beyond.</p>';
			break;
		case 'url:https://standardebooks.org/ebooks/lloyd-c-douglas/magnificent-obsession':
			$description = '<p>Robert Merrick, a young man from a wealthy family, accidentally causes the death of an esteemed neurosurgeon. Wracked by guilt, Robert decides to devote his own life to improving the life of others.</p><p><i>Magnificent Obsession</i> was a hugely popular work in its time, inspiring a blockbuster 1935 film of the same name.</p>';
			break;
		case 'url:https://standardebooks.org/ebooks/josephine-tey/the-man-in-the-queue':
			$description = '<p>Standing in line in a long queue for a show at a theater, a young man is stabbed in the back. <a href="/collections/inspector-grant">Inspector Alan Grant</a> of the Metropolitan Police is soon on the case, though he finds it deeply puzzling—not least because the identity of the victim is itself a mystery.</p><p><i>The Man in the Queue</i> was the first in a series of hugely successful detective novels by Josephine Tey.</p>';
			break;
		case 'url:https://standardebooks.org/ebooks/john-buchan/the-courts-of-the-morning':
			$description = '<p><i>The Courts of the Morning</i> opens with <a href="/collections/richard-hannay">Major-General Richard Hannay</a> being approached by American diplomats regarding the disappearance of a wealthy industrialist. He in turn seeks the help of his friend Sandy Arbuthnot—but Arbuthnot himself quickly goes missing. We soon head to the South American country of Olifa, where a powerful head of a mining company is gradually enslaving the populace. It seems that only guerrilla warfare will save the country from rule under a ruthless tyrant.</p>';
			break;
		case 'url:https://standardebooks.org/ebooks/j-b-priestley/the-good-companions':
			$description = '<p>The recipient of the <a href="/collections/james-tait-black-memorial-fiction-prize-winners">James Tait Black Memorial Fiction Prize</a>, <i>The Good Companions</i> was a blockbuster novel that made J. B. Priestley’s reputation. In it we follow three protagonists from different walks of life who, looking for a change of pace, strike out from home. They eventually cross paths with each other and with a group of “concert players,” a type of traveling vaudeville troupe common in the day. They decide to join forces and form the “Good Companions,” a musical act that takes them on a series of cozy adventures.</p>';
			break;
		case 'url:https://standardebooks.org/ebooks/dashiell-hammett/the-dain-curse':
			$description = '<p>In <i>The Dain Curse</i>, the second <a href="/collections/continental-op">Continental Op</a> novel, the legendary but nameless hard-drinking and quick-shooting detective is sent to investigate the theft of diamonds from a San Francisco family. The fast-paced noir thriller quickly veers from car chases to cultists to the supernatural, but the unflappable Continental Op is relentless in his pursuit of truth.</p>';
			break;
		case 'url:https://standardebooks.org/ebooks/c-s-forester/brown-on-resolution':
			$description = '<p>While on operations in the Pacific during the first World War, the sailor Albert Brown’s ship is sunk—but he survives, and is taken on board the German cruiser that sank them. It too has suffered damage, and heads to some nearby islands for repairs. In this unlikely and hostile setting, Brown, alone, pits himself against the German ship and its crew, seeking to delay its progress while British naval reinforcements rush to his rescue.</p><p>Forester’s careful historical research adds an unimpeachable air of verisimilitude to the novel, and indeed, the plot is loosely based on real events.</p>';
			break;
		case 'url:https://standardebooks.org/ebooks/arthur-conan-doyle/the-maracot-deep':
			$description = '<p>While investigating the deepest part of the Atlantic Ocean, a team led by Dr. Maracot is cut off from their ship and hurled to the bottom of the ocean. There, they find themselves in the remnants of the ancient civilization of Atlantis.</p><p>Though Doyle is most famous for his <a href="/collections/sherlock-holmes">Sherlock Holmes</a> stories, in which a brilliant logician uses reason and deduction to solve crime, in later years he became deeply spiritual. This novel, written just a year before his death, combines his interest in science and reason with his new spiritual outlook.</p>';
			break;

	}

	$ebooksWithDescriptions[array_search($ebook->Identifier, $identifiers)] = ['ebook' => $ebook, 'description' => $description];
}

ksort($ebooksWithDescriptions);

?><?= Template::Header(['title' => 'Happy Public Domain Day 2025! - Blog', 'highlight' => '', 'description' => 'Read about the new ebooks Standard Ebooks is releasing for Public Domain Day 2025!', 'css' => ['/css/public-domain-day.css']]) ?>
<main>
	<section class="narrow">
		<h1>Happy Public Domain Day 2025!</h1>
		<?= Template::DonationCounter() ?>
		<?= Template::DonationProgress() ?>
		<p>Happy Public Domain Day!</p>
		<p>Around the world, people celebrate Public Domain Day on January 1, the day in which copyright expires on some older works, putting them in the public domain in many different countries.</p>
		<p>Some countries have a copyright term of the life of the author plus seventy years. These countries have been celebrating public domain day for some time now.</p>
		<p>The U.S. Constitution restricts copyright terms to “limited Times,” in order to “promote the Progress of Science and useful Arts.” Early U.S. copyright laws defined this limit to be up to twenty-eight years after publication. But ever since, powerful corporations have continually extended the length of copyright so they could continue to profit. The result is that today in the U.S., work only enters the public domain ninety-five years after publication. This has locked <em>all</em> of our cultural output away from us for <em>nearly a century</em>.</p>
		<p>2019 was the year in which the first works from almost a hundred years ago were finally scheduled to enter the public domain, ending this long, corporate-dictated cultural winter. As that year approached, we had every reason to assume that these powerful corporations would once again lobby to extend copyright even further.</p>
		<p>But as 2019 grew closer, it became clear that these corporations <em>wouldn’t</em> work to extend copyright yet again—making 2019 the first year in almost a century in which a significant amount of art and literature once again entered the public domain in the U.S., free for anyone in the U.S. to read, use, and share.</p>
		<p>Ever since then, each year we’ve been joining our international friends in celebrating Public Domain Day by preparing some of the biggest literary hits of the year for you to read on January 1.</p>
		<hr/>
		<p><strong>In 2025, books published in 1929 enter the U.S. public domain.</strong></p>
		<p>And 1929 was a literary doozy!</p>
		<p>Books by William Faulkner, Ernest Hemingway, Mahatma Gandhi, and John Steinbeck enter the U.S. public domain. Joining these esteemed names is the first novel featuring the Continental Op, the nameless hard-boiled noir detective who created the archetype for every hard-drinking, fedora-wearing private eye to grace page and screen since; the English translation of <i>All Quiet on the Western Front</i>; and much more.</p>
		<p>Our friends at Public Domain Review have written about some <a href="">other things that enter the public domain this year, too</a>.</p>
		<p>At Standard Ebooks, our volunteers have been working hard for the past few months to prepare the following ebooks to be ready for Public Domain Day. Join us in celebrating the freeing of our cultural heritage by downloading these ebooks and reading them for free:</p>
		<? if(sizeof($ebooksWithDescriptions) == 0){ ?>
			<p class="empty">We’re still preparing these free ebooks for Public Domain Day. Check back after January 1!</p>
		<? }else{ ?>
			<ul class="public-domain-day">
				<? foreach($ebooksWithDescriptions as $ebookGroup){ ?>
					<li>
						<div>
							<a href="<?= $ebookGroup['ebook']->Url ?>">
								<?= Template::RealisticEbook(['ebook' => $ebookGroup['ebook']]) ?>
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
	</section>
</main>
<?= Template::Footer() ?>
