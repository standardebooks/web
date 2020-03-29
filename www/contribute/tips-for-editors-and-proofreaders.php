<?
require_once('Core.php');
?><?= Template::Header(['title' => 'Tips for Editors and Proofreaders', 'manual' => true, 'highlight' => 'contribute', 'description' => 'A list of tips and tricks for people who’d like to proofread a Standard Ebooks ebook.']) ?>
<main>
	<article>
		<h1>Tips for Editors and Proofreaders</h1>
		<p>Advances in ereading devices and software have made proofreading ebooks a whole lot easier than in the past.</p>
		<p>Most ereading software allows you to highlight text and add notes to those highlights.  If you’re using a device like a Kindle or a phone or tablet with the Google Play Books app, try holding your finger on some text.  It’ll become highlighted, and you can drag the highlight to include more text if you like.</p>
		<p>That means the quickest way for you to proofread an ebook is to <a href="/help/how-to-use-our-ebooks">transfer it to your ereader</a> and start reading!  Once you find an error, use the highlight feature to mark it, and keep on reading.  Many errors, like mis-curled quotation marks or obvious spelling errors, don’t need a written note to accompany the highlight.  But you should make a brief written note if the error wouldn’t be clear to a passing reader.</p>
		<p>Once you’ve finished the ebook, use your ereader’s “view all notes” option to find all of your highlights in one place.  Then you can either <a href="/contribute/report-errors">report them to us</a>, or if you’re technically-minded, correct them directly in the ebook’s <a href="http://github.com/standardebooks">GitHub repository</a>.  Remember to read the <a href="/manual">Standard Ebooks Manual of Style</a> to make sure the error you found is covered.</p>
		<section id="common-errors">
			<h2>Common errors to watch out for</h2>
			<p>Lots of different errors can occur during the long and complex process of digitizing a print book, but here are some of the more common ones:</p>
			<ul>
				<li><h3>Mis-curled quotation marks</h3>
					<p>Here we see two frequent errors: a mis-curled double quotation mark following the em-dash, and a mis-curled single quotation mark before the &ldquo;n&rdquo;:</p>
					<figure class="text">
						<p class="wrong">I was putting on some Bach when he interrupted with&mdash;&rdquo;Put on some rock ‘n’ roll!&rdquo;</p>
						<p class="corrected">I was putting on some Bach when he interrupted with&mdash;&ldquo;Put on some rock ’n’ roll!&rdquo;</p>
					</figure>
				</li>
				<li><h3>Incorrect or archaic use of quotation marks</h3>
					<p>Older texts frequently use quotation marks for names of books and periodicals, or for the names of pubs, inns, and other places.  Our <a href="/manual/latest/8-typography">typography manual</a> requires that certain standalone media be in italics instead, and that place names <em>not</em> be set in quotes.</p>
					<figure class="text">
						<p class="wrong">He read &ldquo;Candide&rdquo; while having a pint at the &ldquo;King’s Head.&rdquo;</p>
						<p class="corrected">He read <i>Candide</i> while having a pint at the King’s Head.</p>
					</figure>
				</li>
				<li>
					<h3>Missing italics</h3>
					<p>Often transcribers just don’t include italics at all in their work.  A very quick visual scan of a HathiTrust or Google Books copy of the book you’re proofing should bring any sections in italics to your attention.  Make sure to confirm them in the transcription, so that we’re not missing italics that should be there.</p>
				</li>
				<li>
					<h3>Ending dialog with a double-em-dash</h3>
					<p>Some authors were in the habit of showing a sudden break in dialog with an extra-wide double-em-dash.  Our <a href="/manual/latest/8-typography">typography manual</a> requires that these be replaced by single em-dashes, so mark them for correction.</p>
					<figure class="text">
                                                        <p class="wrong">“Why, I never——” she cried.</p>
                                                        <p class="corrected">“Why, I never—” she cried.</p>
                                                </figure>
					<p>Note that a double-em-dash is appropriate when purposefully obscuring a word or place.  In this case, use the two-em-dash glyph (⸻ or U+2E3A) instead of two consecutive em-dashes:</p>
					 <figure class="text">
                                                <p class="corrected">Sally J⸺ walked through the town of ⸻ in the year 19—.</p>
                                        </figure>
				</li>
				<li>
					<h3>Using &amp;c. instead of etc.</h3>
					<p>&ldquo;etc.&rdquo; is an abbreviation of the Latin <i>et cetera</i>; In Latin, <i>et</i> means &ldquo;and&rdquo;, so older texts often abbreviated <i>et cetera</i> as &ldquo;&amp;c.&rdquo;</p>
					<p>Our <a href="/manual/latest/8-typography">typography manual</a> requires a change from &amp;c. to etc., so make sure to mark these corrections.</p>
				</li>
				<li>
					<h3>Use of &ldquo;ibid.&rdquo; in footnotes or endnotes</h3>
					<p>In work with footnotes or endnotes, &ldquo;ibid.&rdquo; means that the source for this note is the same as the previous note on the page.</p>
					<p>Since Standard Ebooks consolidate all footnotes and endnotes into popup footnotes, ibid. becomes meaningless&mdash;there’s no concept of a &ldquo;page&rdquo; anymore.  If you encounter ibid., replace it with the complete reference from the previous note so readers using popup footnotes won’t get confused.</p>
				</li>
				<li>
					<h3>Text in all caps</h3>
					<p>Many transcriptions of older texts were made in a time when rich <abbr class="initialism">HTML</abbr> markup wasn’t yet available.  Those transcriptions sometimes used ALL CAPS to indicate small caps or boldface in the source text.</p>
					<p>All caps is almost never correct typography.  Mark text in all caps for conversion to small caps or boldface.</p>
				</li>
				<li>
					<h3>Section dividers as text instead of as markup</h3>
					<p>There are lots of ways authors mark section breaks in text.  A common way to do this is with three or more asterisks:</p>
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Some text in the first section...<span class="p">&lt;/</span><span class="" ass="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>***<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The second section begins...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>

<p>In Standard Ebooks, sections must be marked with the <code class="html">&lt;hr/&gt;</code> tag:</p>
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Some text in the first section...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">hr</span><span class="p">/&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The second section begins...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					<p>As you’re reading, the <code class="html"><span class="p">&lt;</span><span class="nt">hr</span><span class="p">/&gt;</span></code> element appears as a short black line dividing sections.</p>
					<p>Mark for correction any section breaks that don’t use the <code class="html"><span class="p">&lt;</span><span class="nt">hr</span><span class="p">/&gt;</span></code> element.</p>
				</li>
			</ul>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
