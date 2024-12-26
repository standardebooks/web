<?
$ebookIds = [565, 778, 561, 1059];
$carousel = Db::Query('SELECT * from Ebooks where EbookId in ' . Db::CreateSetSql($ebookIds), $ebookIds, Ebook::class);
?>
<?= Template::Header(['title' => 'Joyce’s Ulysses, the Rubáiyát, and “Yes”', 'css' => ['/css/blog.css'], 'highlight' => '', 'description' => '']) ?>
<main>
	<section class="narrow blog">
		<nav class="breadcrumbs"><a href="/blog">Blog</a> →</nav>
		<h1>Joyce’s <i>Ulysses</i>, the <i>Rubáiyát</i>, and “Yes”</h1>
		<p class="byline">By Erin Endrei</p>
		<?= Template::DonationCounter() ?>
		<?= Template::DonationProgress() ?>
		<div class="editors-note">
			<p>This article first appeared in the December 2024 edition of our <a href="/newsletter">email newsletter</a>.</p>
		</div>
		<p>Edward FitzGerald’s <a href="https://standardebooks.org/ebooks/omar-khayyam/the-rubaiyat-of-omar-khayyam/edward-fitzgerald">Rubáiyát of Omar Khayyám</a> shot to fame about a decade before <a href="https://standardebooks.org/ebooks/james-joyce">James Joyce’s</a> birth in 1882. As its popularity endured well into Joyce’s youth, it’s no surprise that he became familiar with it. But it’s probably the outlook the <i>Rubáiyát</i> expresses, not just its temporal proximity, that earned it a place in Joyce’s own most celebrated book, <a href="https://standardebooks.org/ebooks/james-joyce/ulysses"><i>Ulysses</i></a>.</p>
		<p>Scholar Carole Brown suggests the motto “wine, women, and song” in FitzGerald’s Omar was in the late nineteenth century a snub to Victorian morality, and in Ireland, to the professed ideals of the Catholic Church. This is of a piece with the studied blasphemy of <i>Ulysses</i> and its “unusual frankness,” as Judge Woolsey put it when <a href="https://law.justia.com/cases/federal/district-courts/FSupp/5/182/2250768/">exonerating the book of obscenity in 1933</a>.</p>
		<p>One of FitzGerald’s best-known quatrains is featured at the beginning of the longest episode of <i>Ulysses</i>. It’s midnight in Dublin, 16 June 1904 is over, and Stephen Dedalus, protagonist of Joyce’s <a href="https://standardebooks.org/ebooks/james-joyce/a-portrait-of-the-artist-as-a-young-man">first novel</a>, is on his way to a brothel with his acquaintance Lynch. Stephen gives Lynch his ashplant stick in order to make a movement that “illustrates the loaf and jug of bread and wine in Omar.” <a href="https://standardebooks.org/ebooks/omar-khayyam/the-rubaiyat-of-omar-khayyam/edward-fitzgerald/text/the-rubaiyat-of-omar-khayyam#quatrain-12">The relevant quatrain</a> is this:</p>
		<blockquote>
			<p>A Book of Verses underneath the Bough,<br/>
			A Jug of Wine, a Loaf of Bread — and Thou<br/>
			Beside me singing in the Wilderness— <br/>
			Oh, Wilderness were Paradise enow!</p>
		</blockquote>
		<p>Combining the jug of wine and loaf of bread in the quatrain’s second line, Lynch asks Stephen which of his hands represents the “jug of bread,” then immediately dismisses the question: it “skills” (matters) not. (This last archaism is likely a reference to its use in <a href="https://standardebooks.org/ebooks/william-shakespeare/the-taming-of-the-shrew"><i>The Taming of the Shrew</i></a>, Act 3, Scene 1, as Stephen has just mentioned “shrewridden Shakespeare.”)</p>
		<p>In an article from 1984, Carole Brown analyzes this underdiscussed passage. As she explains, Lynch is referring to the policy of lay communicants’ receiving the Eucharist <em>only</em> as bread, not as both bread and wine like the clerics; and the Church’s doctrine known as “<a href="https://en.wikipedia.org/wiki/Concomitance_(doctrine)">concomitance</a>,” which states that even just the bread contains the whole and undivided body and blood of Christ.</p>
		<p>In Catholicism the Mass is understood as a reenactment of Christ’s crucifixion; this is why it’s sometimes called “the sacrifice of the mass,” or an <a href="https://www.vatican.va/content/catechism/en/part_two/section_two/chapter_one/article_3/v_the_sacramental_sacrifice_thanksgiving,_memorial,_presence.html">unbloody sacrifice</a> — in Italian, <i>sacrifizia incruento</i>. Stephen applies that very Italian phrase to himself in the tenth episode of <i>Ulysses</i> as he discusses having abandoned a musical career. But it’s Golgotha, not the altar, that Brown finds in Stephen’s statement that he needs only one gesture to illustrate the jug and the loaf. Stephen’s “one gesture,” as opposed to the two elevations of the Mass, apparently represents the singular elevation of the cross.</p>
		<p>While Brown’s exegesis goes even further, it’s clear that Joyce’s reference to FitzGerald’s Omar isn’t just a nod to the indecorous nature of Stephen’s midnight destination, but a subversive way to make a joke about at least one point — if not two — of Catholic theology.</p>
		<p>Brown speculates that Stephen’s pronouncements on “gesture” may also indicate the character’s familiarity with an 1896 musical setting of some of FitzGerald’s quatrains, from various editions, by <a href="https://en.wikipedia.org/wiki/Liza_Lehmann">Liza Lehmann</a>. The words “jug and loaf” differed in earlier editions of the <i>Rubáiyát</i>, establishing that Joyce was familiar with either Lehmann’s setting or the fifth edition of the <i>Rubáiyát</i>.</p>
		<p>But Stephen’s strange “gesture” is not the only point of contact between FitzGerald’s Omar and <i>Ulysses</i>. In <i>Finnegans Wake,</i> Joyce refers to <a href="https://standardebooks.org/ebooks/omar-khayyam/the-rubaiyat-of-omar-khayyam/edward-fitzgerald/text/the-rubaiyat-of-omar-khayyam#quatrain-43">quatrain XLIII</a> when he mentions people who have “quaff’d Night’s firefill’d Cup.” Brown notes this, but not that the <a href="https://standardebooks.org/ebooks/omar-khayyam/the-rubaiyat-of-omar-khayyam/edward-fitzgerald/text/the-rubaiyat-of-omar-khayyam#quatrain-42">quatrain immediately before it</a> contains <i>Ulysses</i>’ most famous word, “Yes”:</p>
		<blockquote>
			<p>And if the Wine you drink, the Lip you press<br/>
			End in what All begins and ends in — Yes;<br/>
			Think then you are Today what Yesterday<br/>
			You were — Tomorrow you shall not be less.</p>
		</blockquote>
		<p>You can see <i>Ulysses</i> here without squinting too much: its final chapter “begins and ends in Yes”; its “time” in the <a href="https://en.wikipedia.org/wiki/Linati_schema_for_Ulysses">schemata Joyce provided to friends</a> was infinity, which, if not exactly identical with All, is presumably inclusive of it; and biographer Richard Ellmann reports Joyce’s stated desire to ensure his own immortality via <i>Ulysses</i> — to not be less tomorrow than today.</p>
		<p>But the connection between <i>Ulysses</i>’ final word and quatrain XLII is also affected by the fact that in FitzGerald’s <a href="https://archive.org/details/rubiytomarkhayy00khaygoog/page/n32/mode/1up?q=%22nothing%22">first edition</a>, the quatrain’s second line was: “End in the Nothing all things end in — Yes.”</p>
		<p>Why FitzGerald says that Yes <em>is</em> Nothing — or “says Yes <em>to</em> Nothing,” as scholar Daniel Karlin writes — is something for another day, and relates, Karlin says, to the ideas of <a href="https://en.wikipedia.org/wiki/Lucretius">Lucretius</a> and <a href="https://en.wikipedia.org/wiki/Epicureanism">Epicurus</a>.</p>
		<p>But for a reader of <i>Ulysses</i>’ final episode it invites the question: what does that terminal Yes say? And at who, or what, is it directed? Nothing?</p>
		<p>The book’s last word is popularly supposed to say, and to be, just the opposite of nothing: a resounding affirmation of life, as Leopold Bloom’s wife Molly, Penelope to his Odysseus, remembers accepting his marriage proposal (on, she says comically, “the day I got him to propose to me”).</p>
		<p>But <a href="https://www.joyceproject.com/notes/180005yesyes.htm">Joyce also claimed</a> that the final thoughts of the book occur as Molly falls asleep, and that her final “Yes” was supposed to be “the least forceful word” possible, representative of acquiescence and surrender, “the end of all resistance.”</p>
		<p>So while there’s no evidence that quatrain XLII was ever intentionally invoked by Joyce, its themes and those of <i>Ulysses</i> seem consonant.</p>
		<p>Speaking of consonance: Brown says, citing Ellmann, that the last record Joyce heard before he died was a performance of Lehmann’s setting of Fitzgerald’s Omar. As is often the case with Ellmann, this might not be true, but it’s not absurd to suppose that it could be.</p>
		<p>And as very often with <i>Ulysses</i>, what first seems like nothing, or like material for a joke, may also turn out to be something else too, even something that matters. If <i>Ulysses</i> doesn’t entirely affirm life, then it does, in this respect at least, reflect it.</p>
		<h2 id="ebooks-in-this-newsletter">Free ebooks in this post</h2>
		<?= Template::EbookCarousel(['carousel' => $carousel]) ?>
	</section>
</main>
<?= Template::Footer() ?>
