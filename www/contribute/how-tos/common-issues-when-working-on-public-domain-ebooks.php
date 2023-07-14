<?
require_once('Core.php');
?><?= Template::Header(['title' => 'Common Issues When Working on Public Domain Ebooks', 'manual' => true, 'highlight' => 'contribute', 'description' => 'A list of common issues encountered when converting from public domain transcriptions.']) ?>
<main>
	<article>
		<h1>Common Issues When Working on Public Domain Ebooks</h1>
		<section>
		<h2 id="punctuation">Punctuation</h2>
		<ol>
			<li>
				<p>Punctuation, other than periods, appearing immediately inside a closing parenthesis should be moved outside the parenthesis.</p>
				<p>This comma that is inside the closing parenthesis…</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>…my brothers, (though fain would I see you all,) before my death…<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				<p>…should be moved outside the parenthesis. Since this is changing content, it is an editorial commit.</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>…my brothers, (though fain would I see you all), before my death…<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
			</li>
			<li>
				<p>Place names, e.g. pubs, inns, etc., should have quotation marks removed.</p>
				<p>For example, the quotes around the name of the inn…</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Shall we get supper at the ‘Lame Cow’?”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				<p>…should be removed:</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Shall we get supper at the Lame Cow?”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
			</li>
		</ol>
		</section>
		<section>
		<h2 id="capitalization">Capitalization</h2>
		<ol>
			<li>
				<p>Lowercase words immediately following exclamations and question-marks was a common practice and should be left as-is.</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Surrender you two! and confound you for two wild beasts!”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
			</li>
			<li>
				<p>Older public domain works, especially eighteenth century and prior, often used uppercased words as a kind of emphasis. Unless they are for purposes of personification, they should be changed to lowercase.</p>
				<p>Here, “History” is a personification, but “Courtiers” is not.</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>To the eye of History many things, in that sick-room of Louis, are now visible, which to the Courtiers there present were invisible.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				<p>Therefore, “Courtiers” should be lowercased. This would be also be an editorial commit.</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>To the eye of History many things, in that sick-room of Louis, are now visible, which to the courtiers there present were invisible.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
			</li>
		</ol>
	</section>
	<section>
		<h2 id="elision">Elision</h2>
		<ol>
			<li>
				<p>Semicolons were occasionally used for elision in names; these should be replaced with the S.E. standard two-em dash for partial elision, three-em dash for full elision.</p>
				<p>The ellipsis in the Bishop's name is incorrect for an S.E. production.</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>When I turned myself over to a Letter from a Beneficed Clergyman in the Country to the Bishop of C…r, I was becoming languid…<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				<p>It should be changed to our standard two-em dash in an editorial commit.</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>When I turned myself over to a Letter from a Beneficed Clergyman in the Country to the Bishop of C⸺r, I was becoming languid…<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
			</li>
		</ol>
	</section>
	<section>
		<h2 id="diacritics">Diacritics</h2>
		<ol>
			<li>
				<p>Diacritics on words that appear in Merriam-Webster without them should generally be removed. <code class="bash"><b>se</b> modernize-spelling</code> corrects some of these, so it is best to wait until after that step to see if any others are left. <code class="bash"><b>se</b> find-mismatched-diacritics</code> can help find instances of these. These commit(s) should be editorial.</p>
				<p>The circumflex on hôtel is unnecessary…</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Is not that the hôtel in which is enclosed the garden of the Lingère du Louvre?”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				<p>…and therefore can be removed:</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Is not that the hotel in which is enclosed the garden of the Lingère du Louvre?”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
			</li>
		</ol>
	</section>
	<section>
		<h2 id="headers">Headers</h2>
		<ol>
			<li>
				<p>Periods that appear after the chapter number or title should be removed. This…</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">h3</span><span class="p">&gt;</span>A Gascon, and a Gascon and a Half.<span class="p">&lt;/</span><span class="nt">h3</span><span class="p">&gt;</span></code></figure>
				<p>…should be changed to this.</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">h3</span><span class="p">&gt;</span>A Gascon, and a Gascon and a Half<span class="p">&lt;/</span><span class="nt">h3</span><span class="p">&gt;</span></code></figure>
			</li>
		</ol>
	</section>
	<section>
		<h2 id="italics">Italics</h2>
		<ol>
			<li>
				<p>If italicized non-English words are found in Merriam-Webster, the italics should be removed.</p>
				<p>Here, “sotto voce” appears in the standard Merriam-Webster dictionary.</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“No, you certainly have not, old man,” put in Rogers <span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span>sotto voce<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;.</span><span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				<p>Therefore, the italics should be removed:</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“No, you certainly have not, old man,” put in Rogers sotto voce.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
			</li>
			<li>
				<p>Words and/or phrases that are italicized in the source, or italicized and quoted, should be changed to match S.E. standards. For example, it may be italicized in the source, but should be quoted according to our style manual. Or, an English phrase may be quoted and italicized, and only one is necessary (usually the quotes).</p>
				<p>Here, song lyrics are both quoted and italicized.</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">blockquote</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span><span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>At nighttime in the moon’s fair glow<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span class="i1"</span><span class="p">&gt;</span>How sweet, as fancies wander free,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>To feel that in this world there’s one<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span class="i1"</span><span class="p">&gt;</span>Who still is thinking but of thee!<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span><span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
				<p>Per S.E. standards, we remove the italics.</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">blockquote</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>At nighttime in the moon’s fair glow<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>How sweet, as fancies wander free,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>To feel that in this world there’s one<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Who still is thinking but of thee!<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
			</li>
		</ol>
	</section>
	</article>
</main>
<?= Template::Footer() ?>
