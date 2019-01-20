<?
require_once('Core.php');
?><?= Template::Header(['title' => 'Structure and Semantics Manual', 'js' => true, 'highlight' => 'contribute', 'description' => 'The Standard Ebooks Structure and Semantics Manual, containing details on semantic patterns used in ebook production.']) ?>
<main>
	<article>
		<h1>Structure and Semantics Manual</h1>
		<aside class="alert">
			<p>Standard Ebooks is a brand-new project&mdash;this manual is a pre-alpha, and much of it is incomplete. If you have a question, need clarification, or run in to an issue not yet covered here, please <a href="https://groups.google.com/forum/#!forum/standardebooks">contact us</a> so we can update this manual.</p>
		</aside>
		<section id="general">
			<h2>General do’s and don’ts</h2>
			<ul>
				<li>
					<p>Don’t wrap source code to a certain column width. This makes it difficult to search through source code for a particular sentence, because a line break could be anywhere. Instead, use the <code class="program">clean</code> tool in the <a href="https://github.com/standardebooks/tools">Standard Ebooks toolset</a> to format <abbr class="initialism">XHTML</abbr> source code, and the word wrap feature in your text editor to make long paragraphs readable in the source code.</p>
				</li>

				<li><p>You have the full vocabulary of <abbr class="initialism">HTML5</abbr> at your disposal, so use semantically-appropriate elements whenever possible.  Don’t settle for a <code class="html">&lt;div&gt;</code> when a <code class="html">&lt;blockquote&gt;</code> or <code class="html">&lt;section&gt;</code> would be more descriptive.</p>
				<figure class="text">
                                                <p class="wrong">&lt;div class="quotation"&gt;</p>
                                                <p class="corrected">&lt;blockquote&gt;</p>
                                        </figure>

				<li><p>Don’t style elements with inline <abbr class="initialism">CSS</abbr>.  Prefer clever <abbr class="initialism">CSS</abbr> selectors first, then prefer <abbr class="initialism">CSS</abbr> classes.</p></li>
				<li><p>When styling with <abbr class="initialism">CSS</abbr> classes, use semantic class names.  Name classes based on what they’re styling, not based on a description of how their style looks.</p>
				<figure class="text">
                                                <p class="wrong">&lt;div class="small-caps"&gt;</p>
                                                <p class="corrected">&lt;blockquote class="inscription"&gt;</p>
                                </figure>
                                </li>
                                <li>
					<p>Don’t use <code class="html">&lt;pre&gt;</code> elements to format text requiring tricky spacing, like poetry. There should never be a <code class="html">&lt;pre&gt;</code> element in a Standard Ebook. See the <a href="#poetry">poetry section</a> for patterns to use to format poetry. Anything can be formatted with <abbr class="initialism">CSS</abbr> if you give it a little thought!</p>
				</li>

			</ul>
		</section>
		<section id="semantics-inflection">
			<h2>Semantic inflection</h2>
			<p>The epub spec allows for <a href="http://www.idpf.org/accessibility/guidelines/content/semantics/epub-type.php">semantic inflection</a>, which is a way of adding pertinent semantic metadata to certain elements. For example, you may want to convey that the contents of a certain <code class="html">&lt;section&gt;</code> are actually a part of a chapter. You would do that by using the <code class="html">epub:type</code> attribute:</p>
			<figure>
				<code class="html">&lt;section epub:type="chapter"&gt;...&lt;/section&gt;</code>
			</figure>
			<p>The epub spec includes a <a href="http://www.idpf.org/epub/vocab/structure/">list of supported keywords</a> that you can use in the <code class="html">epub:type</code> attribute. Many of these keywords apply to content divisions, like chapter breaks, prefaces, introductions, and so on.</p>
			<p>An additional spec, the sexily-named <a href="http://www.daisy.org/z3998/2012/vocab/structure/">z39.98-2012 Structural Semantics vocabulary</a>, gives us a more robust vocabulary for adding semantic inflection. This vocabulary includes ways of marking fiction vs. non-fiction, letters, poetry, and so on. All Standard Ebooks include a reference to this vocabulary by default, so you should use it if the regular epub vocabulary isn’t enough.</p>
			<p>Finally, Standard Ebooks <a href="/vocab/1.0">has its own vocabulary</a> to add even more finely grained semantics. For example, names of ships are italicized with the <code class="html">&lt;i&gt;</code> element. But to convey that the otherwise-meaningless <code class="html">&lt;i&gt;</code> element contains the name of a ship, we would add the Standard Ebooks semantic inflection of <code class="html">se:name.vessel.ship</code>:</p>
			<figure>
				<code class="html">They set sail on the &lt;abbr class="initialism"&gt;HMS&lt;/abbr&gt; &lt;i epub:type="se:name.vessel.ship"&gt;Bounty&lt;/i&gt;.</code>
			</figure>
			<p>In a perfect world, Standard Ebooks wouldn’t have to maintain its own list of semantic vocabulary. We’re actively looking for a suitable replacement&mdash;if you have a suggestion, <a href="/contribute/">get in touch</a>!</p>
			<h3>Semantic inflection in Standard Ebooks</h3>
			<p>Part of the Standard Ebooks mission is to add as much semantic information to ebooks as possible. To that end, use semantic inflection liberally and in detail. Since we have so many vocabulary options to use, use them in this order of preference:</p>
			<ol>
				<li>
					<p><a href="http://www.idpf.org/epub/vocab/structure/">The built-in epub vocabulary</a>. If what you’re trying to mark up is here, use this first.</p>
				</li>
				<li>
					<p><a href="http://www.daisy.org/z3998/2012/vocab/structure/">The z3998 vocabulary</a>. If something isn’t included in the regular epub vocabulary, stop here next.</p>
				</li>
				<li>
					<p><a href="/vocab/1.0">The Standard Ebooks vocabulary</a>. If neither the regular epub vocabulary nor the z33998 vocabulary have a keyword you’re looking for, check our own vocabulary. You can also <a href="/contribute/">suggest additions to this vocabulary</a>.</p>
				</li>
			</ol>
		</section>
		<section id="style">
			<h2>XHTML and CSS code formatting style</h2>
			<h3>In XHTML</h3>
			<p>The <code class="path">clean</code> tool does a good job of pretty-printing your XHTML according to our requirements, so make sure to run it often.  In case you want to review the style requirements, they are:</p>
			<ul>
				<li><p>Use tabs for indentation.</p></li>
				<li><p>Tags whose content is <a href="https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/Content_categories#Phrasing_content">phrasing content</a> should be on a single line.  So, don’t open a <code class="html">&lt;p&gt;</code> tag, then move to the next line for the tag’s contents; put it all on the same line.</p></li>
				<li><p>Attributes should be in alphabetical order.</p></li>
			</ul>
			<h3>In CSS</h3>
			<ul>
				<li><p>Use tabs for indentation.</p></li>
				<li><p>Always move to a new line for CSS properties.  Even if the selector only has one property, don’t put the selector and the property on one line.</p></li>
				<li><p>Where possible, properties should be in alphabetical order.  (This isn’t always possible if you’re attempting to override a previous style in the same selector; in those cases that’s OK.)</p></li>
			</ul>
		</section>
		<section id="abbreviations">
			<h2>Abbreviation semantic patterns</h2>
			<ul>
				<li>
					<p>There are three types of abbreviations:</p>
					<p>An acronym is a term made up of initials and pronounced as one word: <abbr class="acronym">NASA</abbr>, <abbr class="acronym">SCUBA</abbr>, <abbr class="acronym">TASER</abbr>.</p>
					<p>An initialism is a term made up of initials in which each initial is pronounced separately: <abbr class="initialism">ABC</abbr>, <abbr class="initialism">HTML</abbr>, <abbr class="initialism">CSS</abbr>.</p>
					<p>A contraction is an abbreviation of a longer word: Mr., Mrs., lbs.</p>
				</li>
				<li>
					<p>All abbreviations must be wrapped in an <code class="html">&lt;abbr&gt;</code> element.</p>
				</li>
				<li>
					<p>All abbreviations that include periods (for example, <a href="/contribute/typography#latinisms">Latinisms</a>) <em>and</em> terminate a clause must include the “eoc” (end-of-clause) class in the <code class="html">&lt;abbr&gt;</code> element. Since a clause ending in an abbreviation omits the trailing period, it’s useful for us to know when such an abbreviation marks the end of a clause.</p>
						<table>
							<thead>
								<tr>
									<td>Result</td>
									<td>Code</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										He wanted to meet at 6:40&nbsp;
										<abbr class="time eoc">p.m.</abbr> I was excited to see him!</td>
									<td>
										<code class="html full">He wanted to meet at 6:40<span class="utf">nbsp</span>&lt;abbr class="time eoc"&gt;p.m.&lt;/abbr&gt; I was excited to see him!</code>
									</td>
								</tr>
							</tbody>
						</table>
				</li>
				<li>
					<p>Certain abbreviations should be marked up with a semantic class:</p>
					<ul>
						<li>
							<h3>Acronyms</h3>
							<p>Any acronym (defined above) that doesn’t fit in the categories below.</p>
							<figure><code class="html">&lt;abbr class="acronym"&gt;NASA&lt;/abbr&gt; received less funding than usual this year.</code></figure>
						</li>
						<li>
							<h3>Initialisms</h3>
							<p>Any initialism (defined above) that doesn’t fit in the categories below.</p>
							<figure><code class="html">There are harder languages than &lt;abbr class="initialism"&gt;HTML&lt;/abbr&gt;.</code></figure>
						</li>
						<li>
							<h3>Abbreviated compass directions</h3>
							<p>For example: <abbr>N.</abbr>, <abbr>S.</abbr>, <abbr>S.W.</abbr></p>
							<figure><code class="html">He traveled &lt;abbr class="compass"&gt;N. W.&lt;/abbr&gt;, then &lt;abbr class="compass eoc"&gt;E. S. E.&lt;/abbr&gt;</code></figure>
							<p>This regex is helpful in finding compass directions: <code class="regex">[NESW]\.([NESW]\.)*?</code></p>
						</li>
						<li>
							<h3>Compounds</h3>
							<p>Molecular compounds.</p>
							<figure><code class="html">&lt;abbr class="compound"&gt;H&lt;sub&gt;2&lt;/sub&gt;O&lt;/abbr&gt;</code></figure>
						</li>
						<li>
							<h3>Academic degrees</h3>
							<p>Academic degrees, <em>except</em> ones that, like PhD, include a lowercase letter: BA, BD, BFA, BM, BS, DB, DD, DDS, DO, DVM, JD, LHD, LLB, LLD, LLM, MA, MBA, MD, MFA, MS, MSN.</p>
							<figure><code class="html">Judith Douglas, &lt;abbr class="degree"&gt;DDS&lt;/abbr&gt;.</code></figure>
						</li>
						<li>
							<h3>Eras</h3>
							<p>The abbreviations
								<abbr class="era">AD</abbr>,
								<abbr class="era">BC</abbr>,
								<abbr class="era">CE</abbr>,
								<abbr class="era">BCE</abbr>.</p>
							<figure><code class="html">Julius Caesar was born around 100 &lt;abbr class="era"&gt;BC&lt;/abbr&gt;.</code></figure>
						</li>
						<li>
							<h3>Initialized names</h3>
							<p>A person’s initials, either first name, last name, or both.</p>
							<figure><code class="html">&lt;abbr class="name"&gt;J. P.&lt;/abbr&gt; Morgan was a wealthy man.</code></figure>
						</li>
						<li>
							<h3>State names and postal codes</h3>
							<p>Abbreviated state names and postal codes: NY, Washington DC.</p>
							<figure><code class="html">Washington &lt;abbr class="postal"&gt;DC&lt;/abbr&gt;</code></figure>
						</li>
						<li>
							<h3>Temperatures</h3>
							<p>Abbreviated temperature scales: F, C. Also see the <a href="/contribute/typography#temperatures">typography manual</a>.</p>
							<figure><code class="html">It was 5°<span class="utf">hairsp</span>&lt;abbr class="temperature"&gt;C&lt;/abbr&gt; last night.</code></figure>
						</li>
						<li>
							<h3>Times</h3>
							<p>Time-related Latinisms: a.m., p.m. Also see the <a href="/contribute/typography#times">typography manual</a>.</p>
							<figure><code class="html">5<span class="utf">nbsp</span>&lt;abbr class="time"&gt;p.m.&lt;/abbr&gt;</code></figure>
						</li>
						<li>
							<h3>Timezones</h3>
							<p>PST, CST, EST, etc.</p>
							<figure><code class="html">5<span class="utf">nbsp</span>&lt;abbr class="time"&gt;p.m.&lt;/abbr&gt; &lt;abbr class="timezone"&gt;PST&lt;/abbr&gt;</code></figure>
						</li>
					</ul>
				</li>
			</ul>
		</section>
		<section id="titles">
			<h2>The &lt;title&gt; tag</h2>
			<p>The <code class="html">&lt;title&gt;</code> tag should contain an appropriate description of the local file only.</p>
			<h3>Titles for files that are an individual chapter</h3>
			<p>In most ebook productions, each chapter will be its own file.  In that case, follow these rules:</p>
			<ul>
				<li><p>Don’t include the book title in individual chapter <code class="html">&lt;title&gt;</code> tags.</p></li>
				<li><p>Convert chapter numbers that are in Roman numerals to decimal numbers:</p>
				<code class="html full">&lt;title&gt;Chapter 10&lt;/title&gt;</code>
				</li>

				<li><p>If a chapter has a subtitle, add a colon after the chapter number and place the subtitle after that:</p>
				<code class="html full">&lt;title&gt;Chapter 10: A Dark and Stormy Night&lt;/title&gt;</code>
				</li>
				<li><p>Subtitles may often contain subtags, like <code class="html">&lt;i&gt;</code>.  Because <code class="html">&lt;title&gt;</code> can’t contain subtags, simply remove them when copying into <code class="html">&lt;title&gt;</code>:</p>
				<code class="html full">&lt;title&gt;Chapter 8: Mobilis in Mobili&lt;/title&gt;</code></li>
			</ul>
		</section>
		<section id="ids">
			<h2>Ids</h2>
			<p>Each <code class="html">&lt;section&gt;</code> should have an <code class="html">id</code> attribute corresponding to a URL-friendly version of the <code class="html">&lt;section&gt;</code>&rsquo;s name.  For example:</p>
			<code class="html full">&lt;section id="introduction" epub:type="introduction"&gt;
&lt;h2 epub:type="title"&gt;Introduction&lt;/h2&gt;
&lt;!--snip--&gt;
&lt;/section&gt;</code>
			<p>Occasionally you might need to give other elements IDs, for example when an endnote references a specific line or paragraph in the work.  In these cases, name the IDs by their tag name, then a dash, then a number representing the tag’s sequential numerical order from the beginning of the containing document.</p>
			<code class="html full">&lt;section id="introduction" epub:type="introduction"&gt;
&lt;h2 epub:type="title"&gt;Introduction&lt;/h2&gt;
&lt;p&gt;Some text...&lt;/p&gt;
&lt;!--snip 10 more &lt;p&gt; tags--&gt;
&lt;p id="p-12"&gt;Some text...&lt;/p&gt;
&lt;/section&gt;</code>

		</section>
		<section id="lists">
			<h2>Ordered/numbered and unordered lists</h2>
			<p>All <code class="html">&lt;li&gt;</code> children of <code class="html">&lt;ol&gt;</code> and <code class="html">&lt;ul&gt;</code> tags <em>must</em> have at least one direct child block-level tag.  This is usually a <code class="html">&lt;p&gt;</code> tag.  (But not necessarily; for example, a <code class="html">&lt;blockquote&gt;</code> tag might also be appropriate.)</p>
				<code class="html full">&lt;ul&gt;
&lt;li&gt;
	&lt;p&gt;&lt;b&gt;Miss Oranthy Bluggage&lt;/b&gt;, the accomplished Strong-Minded Lecturer, will deliver her famous Lecture on “&lt;b&gt;Woman and Her Position&lt;/b&gt;,” at Pickwick Hall, next Saturday Evening, after the usual performances.&lt;/p&gt;
&lt;/li&gt;
&lt;/ul&gt;</code>
		</section>
		<section id="blockquotes">
			<h2>Blockquotes</h2>
			<p>In most prose works, we generally want to offset long quotations in the <code class="html">&lt;blockquote&gt;</code> element.  However, we want to be able to distinguish when a quotation is from a real-life source (like a quotation from a Shakespeare play), and when the quotation is fictional within the context of the work.  To make this distinction, we assume that the <code class="html">&lt;blockquote&gt;</code> inherits the <code class="html">z3998:fiction</code> or <code class="html">z3998:non-fiction</code> semantic inflection of its parent.  Thus, if the <code class="html">&lt;blockquote&gt;</code> contents differ from that inherited semantic inflection, we specify whether it’s <code class="html">z3998:fiction</code> or <code class="html">z3998:non-fiction</code> in the blockquote element itself.</p>
			<p>For example, if a <code class="html">&lt;blockquote&gt;</code> doesn’t have semantic inflection specified, and it’s within a <code class="html">&lt;section epub:type="z3998:fiction"&gt;</code> parent, then the <code class="html">&lt;blockquote&gt;</code> is <em>also</em> fictional within the context of the work.</p>
			<p>In this first example from <i><a href="/ebooks/bram-stoker/dracula">Dracula</a></i>, we have a fictional character quoting a real-life source.  Since <i>Dracula</i> is fiction but the quotation source <a href="https://en.wikipedia.org/wiki/Lenore_%28ballad%29">exists in the real world</a>, we include the <code class="html">z3998:non-fiction</code> semantic inflection:</p>
			<code class="html full">&lt;section epub:type="chapter z3998:fiction"&gt;
&lt;p&gt;One of my companions whispered to another the line from Burger’s “Lenore”:―&lt;/p&gt;
&lt;blockquote epub:type="z3998:non-fiction"&gt;
	&lt;p&gt;“&lt;span xml:lang="de"&gt;Denn die Todten reiten schnell&lt;/span&gt;”⁠—&lt;br/&gt;
	(“For the dead travel fast.”)&lt;/p&gt;
&lt;/blockquote&gt;
&lt;/section&gt;</code>
			<p>In this second example from <i><a href="/ebooks/jules-verne/twenty-thousand-leagues-under-the-seas/f-p-walter">Twenty Thousand Leagues Under the Seas</a></i>, a fictional character quotes another fictional character.  We still use the <code class="html">&lt;blockquote&gt;</code> element, but since the work itself is fiction, the <code class="html">&lt;blockquote&gt;</code> “inherits” the semantic inflection of <code class="html">z3998:fiction</code>:</p>
			<code class="html full">&lt;section epub:type="chapter z3998:fiction"&gt;
&lt;p&gt;Every morning, it was repeated under the same circumstances. It ran like this:&lt;/p&gt;
&lt;blockquote xml:lang="x-nemo"&gt;
	&lt;p&gt;“Nautron respoc lorni virch.”&lt;/p&gt;
&lt;/blockquote&gt;
&lt;/section&gt;</code>
		</section>
		<section id="halftitlepages">
			<h2>Half title pages</h2>
			<p>When a work contains frontmatter like an epigraph or introduction, a half title page is required before the body matter begins.</p>
			<h3>Half title pages without subtitles</h3>
			<code class="html full">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" epub:prefix="z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: http://standardebooks.org/vocab/1.0" xml:lang="en-GB"&gt;
&lt;head&gt;
	&lt;title&gt;Half Title&lt;/title&gt;
	&lt;link href="../css/core.css" rel="stylesheet" type="text/css"/&gt;
	&lt;link href="../css/local.css" rel="stylesheet" type="text/css"/&gt;
&lt;/head&gt;
&lt;body epub:type="frontmatter"&gt;
	&lt;section id="halftitlepage" epub:type="halftitlepage"&gt;
		&lt;h1 epub:type="fulltitle"&gt;Don Quixote&lt;/h1&gt;
	&lt;/section&gt;
&lt;/body&gt;
&lt;/html&gt;</code>

			<h3>Half title pages with subtitles</h3>
				<code class="css full">section[epub|type~="halftitlepage"] span[epub|type~="subtitle"]{
display: block;
font-size: .75em;
font-weight: normal;
}</code>
			<code class="html full">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" epub:prefix="z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: http://standardebooks.org/vocab/1.0" xml:lang="en-GB"&gt;
&lt;head&gt;
	&lt;title&gt;Half Title&lt;/title&gt;
	&lt;link href="../css/core.css" rel="stylesheet" type="text/css"/&gt;
	&lt;link href="../css/local.css" rel="stylesheet" type="text/css"/&gt;
&lt;/head&gt;
&lt;body epub:type="frontmatter"&gt;
	&lt;section id="halftitlepage" epub:type="halftitlepage"&gt;
		&lt;h1 epub:type="fulltitle"&gt;
			&lt;span epub:type="title"&gt;The Book of Wonder&lt;/span&gt;
			&lt;span epub:type="subtitle"&gt;A Chronicle of Little Adventures at the Edge of the World&lt;/span&gt;
		&lt;/h1&gt;
	&lt;/section&gt;
&lt;/body&gt;
&lt;/html&gt;</code>
		</section>
		<section id="endnotes">
			<h2>Footnotes and endnotes</h2>
			<p>Since there’s no concept of a “page” in an ebook, the concept of “footnotes” isn’t very useful.  (Where would a footnote go if there’s no bottom of the page?)</p>
			<p>Modern ereading systems do, however, offer popup notes.  Our task is to combine all footnotes present in a source text into a single endnotes file that provides popup notes to supported readers, and clearly listed notes for other readers.</p>
			<p>Endnotes must be numbers that are sequential throughout the entire text.  Since many books just used “*” to denote a footnote, when converting to endnotes we have to assign those a number.</p>
			<h3>Linking to endnotes</h3>
			<p>In the body text, you refer to an endnote using this pattern:</p>
			<code class="html full">&lt;p&gt;This is some text followed by a reference to an endnote.&lt;a href="endnotes.xhtml#note-1" id="noteref-1" epub:type="noteref"&gt;1&lt;/a&gt;&lt;/p&gt;</code>
			<ul>
			<li><p>The <code class="html">id</code> attribute is always “noteref-N” where N is the number of the endnote.</p></li>
			<li><p>The <code class="html">epub:type</code> attribute is set to “noteref”.</p></li>
			<li><p>The endnote goes after ending punctuation.</p></li>
			</ul>
			<h3>The endnotes file</h3>
			<p>The endnotes file is called <code class="path">endnotes.xhtml</code> and looks like this:</p>
			<code class="html full">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" epub:prefix="z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: http://standardebooks.org/vocab/1.0" xml:lang="en-GB"&gt;
&lt;head&gt;
	&lt;title&gt;Endnotes&lt;/title&gt;
	&lt;link href="../css/core.css" rel="stylesheet" type="text/css"/&gt;
	&lt;link href="../css/local.css" rel="stylesheet" type="text/css"/&gt;
&lt;/head&gt;
&lt;body epub:type="backmatter z3998:fiction"&gt;
	&lt;section id="endnotes" epub:type="rearnotes"&gt;
		&lt;h2 epub:type="title"&gt;Endnotes&lt;/h2&gt;
		&lt;ol&gt;
			&lt;li id="note-1" epub:type="rearnote"&gt;
				&lt;p&gt;The first endnote goes here.&lt;/p&gt;
				&lt;p&gt;Here's another line for the first endnote. &lt;a href="chapter-1.xhtml#noteref-1" epub:type="se:referrer"&gt;↩&lt;/a&gt;&lt;/p&gt;
			&lt;/li&gt;
			&lt;li id="note-2" epub:type="rearnote"&gt;
				&lt;p&gt;The second endnote goes here. &lt;a href="chapter-1.xhtml#noteref-2" epub:type="se:referrer"&gt;↩&lt;/a&gt;&lt;/p&gt;
			&lt;/li&gt;
		&lt;/ol&gt;
	&lt;/section&gt;
&lt;/body&gt;
&lt;/html&gt;</code>
			<h3>An endnote dissected</h3>
			<ul>
			<li><p>Each individual endnote is a <code class="html">&lt;li&gt;</code> element containing one or more <code class="html">&lt;p&gt;</code> elements.</p></li>
			<li><p>Each <code class="html">&lt;li&gt;</code> requires the following attributes:</p>
			<ul>
			<li><p><code class="html">id</code> is set to the string “note-” followed by the sequential endnote number, beginning with 1.</p></li>
			<li><p><code class="html">epub:type</code> is set to “rearnote”.</p></li>

			</ul>
			<li><p>The <code class="html">href</code> attribute points to the direct anchor reference to the endnote.  </p></li>

			<li><p>If an endnote contains a citation offset with a dash (for example, “&mdash;Ed.”), separate the citation from the text with a single space and enclose it in the <code class="html">&lt;cite&gt;</code> tag:</p>
			<code class="html full">&lt;li id="note-1" epub:type="rearnote"&gt;
&lt;p&gt;Here’s an endnote. &lt;cite&gt;&mdash;&lt;abbr class="eoc"&gt;Ed.&lt;/abbr&gt;&lt;/cite&gt; &lt;a href="chapter-1.xhtml#note-1" epub:type="se:referrer"&gt;↩&lt;/a&gt;&lt;/p&gt;
&lt;/li&gt;</code>
			</li>

			<li><p>The final <code class="html">&lt;p&gt;</code> element in an endnote contains a link back to the referring anchor.  Don’t just point it to the file, make sure it points to the exact link that we came from. For example, <code class="path">chapter-1.xhtml#note-1</code>, <em>not</em> <code class="path">chapter-1.xhtml</code>.  If the link is the last element in a longer <code class="html">&lt;p&gt;</code> tag, it must be preceded by one space character; if it is the only child of a <code class="html">&lt;p&gt;</code> tag (for example if the previous text was a <code class="html">&lt;blockquote&gt;</code>) then it can be on its own line.  It must have the <code class="html">epub:type</code> set to <code class="html">se:referrer</code>.  The text of the link is always the “↩” character.</p></li>
			</ul>
		</section>
		<section id="thought-and-section-breaks">
			<h2>Thought and section breaks</h2>
			<p>In printed material, thought and section breaks are typically denoted with a large space between paragraphs, or by a symbol like “* * *”.  In Standard Ebooks, use the <code class="html">&lt;hr/&gt;</code> element to denote thought and section breaks.</p>
		</section>
		<section id="chapter-headers">
			<h2>Section header semantic patterns</h2>
			<p>There’s a lot of ways authors choose to format chapter headers.  Below are patterns for all of the types of chapter headers we’ve encountered so far.  If nothing in this list fits the book you’re producing, <a href="/contribute/">contact us</a> and we’ll work on a new standard.</p>
			<h3>General outline</h3>
			<ul>
				<li><p>The title of the book is an implied <code class="html">&lt;h1&gt;</code> element.  Therefore, all chapters titles are <code class="html">&lt;h2&gt;</code> elements or lower.</p></li>
				<li><p>It’s extremely rare to go down to <code class="html">&lt;h3&gt;</code> and below, but you may do so if, for example, the chapter is part of a volume whose title would occupy the <code class="html">&lt;h2&gt;</code> level.  Otherwise, if you feel the need to use <code class="html">&lt;h3&gt;</code>, ask yourself if the header is a structural division of the document.  For example, in a work of fiction where a fictional newspaper clipping is presented, the headline <em>would not</em> be set in an <code class="html">&lt;h3&gt;</code> element, because the clipping is part of the body text, not a structural division of the book.</p></li>
				</ul>
			<h3>Sections without titles</h3>
			<code class="html full">&lt;h2 epub:type="title z3998:roman"&gt;XI&lt;/h2&gt;</code>
			<h3>Sections with titles but no chapter numbers</h3>
			<code class="html full">&lt;h2 epub:type="title"&gt;A Daughter of Albion&lt;/h2&gt;</code>
			<h3>Sections with titles and chapter numbers</h3>
			<code class="html full">&lt;h2 epub:type="title"&gt;
&lt;span epub:type="z3998:roman"&gt;XI&lt;/span&gt;
&lt;span epub:type="subtitle"&gt;Who Stole the Tarts?&lt;/span&gt;
&lt;/h2&gt;</code>
			<code class="css full">span[epub|type~="subtitle"]{
display: block;
font-weight: normal;
}</code>
			<h3>Sections with unnumbered titles and subtitles</h3>
			<code class="html full">&lt;h2 epub:type="title"&gt;
&lt;span&gt;An Adventure&lt;/span&gt;
&lt;span epub:type="subtitle"&gt;(A Driver’s Story)&lt;/span&gt;
&lt;/h2&gt;</code>
			<code class="css full">span[epub|type~="subtitle"]{
display: block;
font-weight: normal;
}</code>
			<h3>Sections with bridgeheads</h3>
			<p>Note that we include trailing punctuation at the end of the bridgehead.  If it’s not present in the source text, add it.</p>
			<p>Since the text in the bridgehead is italicized, we include <abbr class="initialism">CSS</abbr> to render actual <code class="html">&lt;i&gt;</code> elements contained in the bridgehead as normal text.</p>

			<code class="html full">&lt;header&gt;
&lt;h2 epub:type="title z3998:roman"&gt;I&lt;/h2&gt;
&lt;p epub:type="bridgehead"&gt;Which treats of the character and pursuits of the famous gentleman Don Quixote of La Mancha.&lt;/p&gt;
&lt;/header&gt;</code>
<code class="css full">[epub|type~="bridgehead"]{
display: inline-block;
font-style: italic;
max-width: 60%;
text-align: justify;
text-indent: 0;
}

[epub|type~="bridgehead"] i{
font-style: normal;
}</code>
			<h3>Sections with epigraph in section headers</h3>

<!-- Once RMSDK supports display: table; we can do this much nicer combination:
/* All epigraphs */
section > header blockquote[epub|type~="epigraph"],
section[epub|type~="epigraph"] > *{
	display: table;
	margin: auto;
	max-width: 60%;
	margin-top: 3em;
}

[epub|type~="epigraph"]{
	font-style: italic;
	hyphens: none;
}

[epub|type~="epigraph"] i{
	font-style: normal;
}

[epub|type~="epigraph"] cite{
	margin-top: 1em;
	font-style: normal;
	font-variant: small-caps;
}

[epub|type~="epigraph"] cite i{
	font-style: italic;
}

/* Full-page epigraphs */
@supports(display: flex){
	section[epub|type~="epigraph"]{
		align-items: center;
		box-sizing: border-box;
		display: flex;
		flex-direction: column;
		justify-content: center;
		min-height: calc(98vh - 3em);
		padding-top: 3em;
	}

	section[epub|type~="epigraph"] > *{
		margin: 0;
	}

	section[epub|type~="epigraph"] > * + *{
		margin-top: 3rem;
	}
}
-->

			<code class="html full">&lt;header&gt;
&lt;h2 epub:type="title z3998:roman"&gt;XXVIII&lt;/h2&gt;
&lt;blockquote epub:type="epigraph"&gt;
	&lt;p&gt;Brief, I pray for you; for you see, ’tis a busy time with me.&lt;/p&gt;
	&lt;cite&gt;&lt;i epub:type="se:name.publication.play"&gt;Much Ado About Nothing&lt;/i&gt;&lt;/cite&gt;
&lt;/blockquote&gt;
&lt;/header&gt;</code>
<code class="css full">/* All epigraphs */
[epub|type~="epigraph"]{
font-style: italic;
hyphens: none;
}

[epub|type~="epigraph"] em,
[epub|type~="epigraph"] i{
font-style: normal;
}

[epub|type~="epigraph"] cite{
margin-top: 1em;
font-style: normal;
font-variant: small-caps;
}

[epub|type~="epigraph"] cite i{
font-style: italic;
}
/* End all epigraphs */

/* Epigraphs in section headers */
section &gt; header [epub|type~="epigraph"]{
display: inline-block;
margin: auto;
max-width: 80%;
text-align: left;
}

section &gt; header [epub|type~="epigraph"] + *{
margin-top: 3em;
}

@supports(display: table){
section &gt; header [epub|type~="epigraph"]{
	display: table;
}
}
/* End epigraphs in section headers */</code>
		</section>
		<section id="epigraphs">
			<h2>Full-page epigraphs</h2>
			<p>Full-page epigraphs have the epigraph centered on the page, for ereaders who support advanced CSS. For all other ereaders, the epigraph is horizontally centered with a small margin above it.</p>
			<p>Additional epigraphs on the same page can be represented with additional <code class="html">&lt;blockquote&gt;</code> elements.</p>
			<code class="html full">&lt;body epub:type="frontmatter"&gt;
&lt;section id="epigraph" epub:type="epigraph"&gt;
	&lt;blockquote&gt;
		&lt;p&gt;“I was born in Boston, New England, and owe my first instructions in literature to the free grammar-schools established there. I therefore give one hundred pounds sterling to my executors, to be by them … paid over to the managers or directors of the free schools in my native town of Boston, to be by them … put out to interest, and so continued at interest forever, which interest annually shall be laid out in silver medals, and given as honorary rewards annually by the directors of the said free schools belonging to the said town, in such manner as to the discretion of the selectmen of the said town shall seem meet.”&lt;/p&gt;
	&lt;/blockquote&gt;
&lt;/section&gt;
&lt;/body&gt;</code>
			<code class="css full">/* All epigraphs */
[epub|type~="epigraph"]{
font-style: italic;
hyphens: none;
}

[epub|type~="epigraph"] em,
[epub|type~="epigraph"] i{
font-style: normal;
}

[epub|type~="epigraph"] cite{
margin-top: 1em;
font-style: normal;
font-variant: small-caps;
}

[epub|type~="epigraph"] cite i{
font-style: italic;
}
/* End all epigraphs */

/* Full-page epigraphs */
section[epub|type~="epigraph"]{
text-align: center;
}

section[epub|type~="epigraph"] &gt; *{
display: inline-block;
margin: auto;
margin-top: 3em;
max-width: 80%;
text-align: left;
}

@supports(display: flex){
section[epub|type~="epigraph"]{
	align-items: center;
	box-sizing: border-box;
	display: flex;
	flex-direction: column;
	justify-content: center;
	min-height: calc(98vh - 3em);
	padding-top: 3em;
}

section[epub|type~="epigraph"] &gt; *{
	margin: 0;
}

section[epub|type~="epigraph"] &gt; * + *{
	margin-top: 3em;
}
}
/* End full-page epigraphs */</code>

		</section>
		<section id="letters">
			<h2>Letter semantic patterns</h2>
			<p>Coming soon!</p>
		</section>
		<section id="poetry">
			<h2>Poetry, verse, and song semantic patterns</h2>
			<p>Unfortunately there’s no great way to semantically format poetry in <abbr class="initialism">HTML</abbr>.  We have to conscript unrelated elements for use in poetry.</p>
			<h3>General outline</h3>
			<ul>
			<li><p>A stanza is represented by a <code class="html">&lt;p&gt;</code> element.</p></li>
			<li><p>Each stanza contains <code class="html">&lt;span&gt;</code> elements, each one representing a line in the stanza.  Delimiting lines in <code class="html">&lt;span&gt;</code> elements allows us to use <abbr class="initialism">CSS</abbr> to automatically indent long lines that wrap across the page.</p></li>
			<li><p>Each line is followed by a <code class="html">&lt;br/&gt;</code> element, <em>except</em> for the last line in a stanza.  Since <code class="html">&lt;span&gt;</code> is an inline element, unstyled <code class="html">&lt;span&gt;</code>s don’t have line breaks.  Including a <code class="html">&lt;br/&gt;</code> emulates line breaks for readers that for some crazy reason might not support <abbr class="initialism">CSS</abbr>.</p></li>
			<li><p>For indented lines, add the <code class="html">i1</code> class to the <code class="html">&lt;span&gt;</code> element. <em>Do not</em> use <span class="utf">nbsp</span> for indentation.  You can indent to multiple levels by incrementing the class to <code class="html">i2</code>, <code class="html">i3</code>, and so on, and including the appropriate <abbr class="initialism">CSS</abbr>.</p></li>
			<li><p>If the poem is a shorter part of a longer work, like a novel, then wrap the stanzas in a <code class="html">&lt;blockquote&gt;</code> element.</p></li>
			<li><p>If the poem is a standalone composition and part of a larger collection of poetry, wrap it in an <code class="html">&lt;article&gt;</code> element instead.  The semantics of <code class="html">&lt;article&gt;</code> imply that the poem can be pulled out of the collection as a standalone item.</p></li>
			<li><p>Give the containing element the semantic inflection of <code class="html">z3998:poem</code>, <code class="html">z3998:verse</code>, or <code class="html">z3998:song</code>.</p></li>
			</ul>
			<h3>Complete <abbr class="initialism">HTML</abbr> and <abbr class="initialism">CSS</abbr> markup examples</h3>
			<p>Note that below we include <abbr class="initialism">CSS</abbr> for the <code class="html">i2</code> class, even though it’s not used in the example; it’s included to demonstrate how to adjust the CSS for indentation levels after the first.</p>
			<code class="css full">[epub|type~="z3998:poem"] p{
text-align: left;
text-indent: 0;
}

[epub|type~="z3998:poem"] p &gt; span{
display: block;
text-indent: -1em;
padding-left: 1em;
}

[epub|type~="z3998:poem"] p &gt; span + br{
display: none;
}

[epub|type~="z3998:poem"] p + p{
margin-top: 1em;
}

[epub|type~="z3998:poem"] + p{
text-indent: 0;
}

p span.i1{
text-indent: -1em;
padding-left: 2em;
}

p span.i2{
text-indent: -1em;
padding-left: 3em;
}</code>
			<code class="html full">&lt;blockquote epub:type="z3998:poem"&gt;
&lt;p&gt;
	&lt;span&gt;“How doth the little crocodile&lt;/span&gt;
	&lt;br/&gt;
	&lt;span class="i1"&gt;Improve his shining tail,&lt;/span&gt;
	&lt;br/&gt;
	&lt;span&gt;And pour the waters of the Nile&lt;/span&gt;
	&lt;br/&gt;
	&lt;span class="i1"&gt;On every golden scale!&lt;/span&gt;
&lt;/p&gt;
&lt;p&gt;
	&lt;span&gt;“How cheerfully he seems to grin,&lt;/span&gt;
	&lt;br/&gt;
	&lt;span class="i1"&gt;How neatly spread his claws,&lt;/span&gt;
	&lt;br/&gt;
	&lt;span&gt;And welcome little fishes in&lt;/span&gt;
	&lt;br/&gt;
	&lt;span class="i1"&gt;&lt;em&gt;With gently smiling jaws!&lt;/em&gt;”&lt;/span&gt;
&lt;/p&gt;
&lt;/blockquote&gt;</code>
			<h3>Elision in poetry</h3>
			<p>If a poem is quoted with one or more lines removed, the removed lines are represented with a vertical ellipses (⋮, U+22EE) in <code class="html">&lt;span class="elision"&gt;</code>, styled like so:</p>
			<code class="html full">&lt;blockquote epub:type="z3998:verse"&gt;
&lt;p&gt;
	&lt;span&gt;O Lady! we receive but what we give,&lt;/span&gt;
	&lt;br/&gt;
	&lt;span&gt;And in our life alone does nature live:&lt;/span&gt;
	&lt;br/&gt;
	&lt;span&gt;Ours is her wedding garments, ours her shroud!&lt;/span&gt;
	&lt;br/&gt;
	&lt;span class="elision"&gt;⋮&lt;/span&gt;
	&lt;br/&gt;
	&lt;span class="i1"&gt;Ah! from the soul itself must issue forth&lt;/span&gt;
	&lt;br/&gt;
	&lt;span&gt;A light, a glory, a fair luminous cloud,&lt;/span&gt;
&lt;/p&gt;
&lt;/blockquote&gt;</code>
			<code class="css full">span.elision{
margin: .5em;
margin-left: 3em;
}

/* If eliding within an epigraph, include this additional style: */
[epub|type~="epigraph"] span.elision{
font-style: normal;
}</code>
		</section>
		<section id="images">
			<h2>Images</h2>
			<ul>
				<li><p>All <code class="html">&lt;img&gt;</code> tags are required to have an <code class="html">alt</code> attribute that uses prose to describe the image in detail; this is what screen reading software will be read aloud.</p>
				<ul>
				<li><p>Describe the image itself in words, which is not the same as writing a caption or describing its place in the book.</p></li>
				<li><p>Alt text must be full sentences ended with periods or other appropriate punctuation. Sentence fragments, or complete sentences without ending punctuation, are not acceptable.</p></li>
				</ul>
				<p>For example:</p>
				<figure class="text">
                                        <p class="wrong">&lt;img alt="The illustration for chapter 10" src="..."&gt;</p>
                                        <p class="wrong">&lt;img alt="Pierre's fruit-filled dinner" src="..."&gt;</p>
                                        <p class="corrected">&lt;img alt="An apple and a pear inside a bowl, resting on a table." src="..."&gt;</p>
                                </figure>
                                <p>Note that the <code class="html">alt</code> text does not necessarily have to be the same as text in the image’s <code class="html">&lt;figcaption&gt;</code> element.  You can use <code class="html">&lt;figcaption&gt;</code> to write a concise context-dependent caption.</p>
                                </li>
                                <li><p>Include an <code class="html">epub:type</code> attribute to denote the type of image.  Common values are <code class="html">z3998:illustration</code> or <code class="html">z3998:photograph</code>.</p></li>
                                <li><p>For some images, it’s helpful to invert their colors when the ereader enters night mode.  This is particularly true for black-and-white line art and woodcuts. (Note <em>black-and-white</em>, i.e. only two colors, <strong>not</strong> grayscale!)  Include the <code class="html">se:image.color-depth.black-on-transparent</code> semantic in the <code class="html">&lt;img&gt;</code> tag’s <code class="html">epub:type</code> to enable color inversion in some ereaders.</p>
                                <p>For that sort of art, save the images as PNG files with a transparent background.  You can make the background transparent by using the “Color to alpha” tool available in many image editing programs, like <a href="https://www.gimp.org/">the GIMP</a>.</p></li>
                                <li><p><code class="html">&lt;img&gt;</code> tags that are meant to be aligned on the block level should be contained in a parent <code class="html">&lt;figure&gt;</code> tag, with an optional <code class="html">&lt;figcaption&gt;</code> sibling.</p>
                                <ul>
                                <li><p>If contained in a <code class="html">&lt;figure&gt;</code> tag, the image’s <code class="html">id</code> attribute must be on the <code class="html">&lt;figure&gt;</code> tag.</p></li></ul>
                                </li>
                                <li><p>Some sources of illustrations may have scanned them directly from the page of an old book, resulting in yellowed, dingy-looking scans of grayscale art.  In these cases, convert the image to grayscale to remove the yellow tint.</p></li>
                              </ul>
                              <h3>Complete <abbr class="initialism">HTML</abbr> and <abbr class="initialism">CSS</abbr> markup examples</h3>
<code class="css full">/* If the image is meant to be on its own page, use this selector... */
figure.full-page{
margin: 0;
max-height: 100%;
page-break-before: always;
page-break-after: always;
page-break-inside: avoid;
text-align: center;
}

/* If the image is meant to be inline with the text, use this selector... */
figure{
margin: 1em auto;
text-align: center;
}

/* In all cases, also include the below styles */
figure img{
display: block;
margin: auto;
max-width: 100%;
}

figure + p{
text-indent: 0;
}

figcaption{
font-size: .75em;
font-style: italic;
}</code>
                                <code class="html full">&lt;figure id="image-10"&gt;
&lt;img alt="An apple and a pear inside a bowl, resting on a table." src="../images/image-10.jpg" epub:type="z3998:photograph"/&gt;
&lt;figcaption&gt;The Monk’s Repast&lt;/figcaption&gt;
&lt;/figure&gt;</code>
                                <code class="html full">&lt;figure class="full-page" id="image-11"&gt;
&lt;img alt="A massive whale breaching the water, with a sailor floating in the water directly within the whale’s mouth." src="../images/image-11.jpg" epub:type="z3998:illustration"/&gt;
&lt;figcaption&gt;The Whale eats Sailor Jim.&lt;/figcaption&gt;
&lt;/figure&gt;</code>
		</section>
		<section id="loi">
			<h2>List of Illustrations (the LoI)</h2>
			<p>If an ebook has any illustrations that are major structural components of the work (even just one!), then we must include an <code class="path">loi.xhtml</code> file at the end of the ebook.  This file lists the illustrations in the ebook, along with a short caption or description.</p>
			<p>An illustration is a major strucutral component if, for example: it is an illustration of events in the book, like a full-page drawing or end-of-chapter decoration; it is essential to the plot, like a diagram of a murder scene or a map; or it is a component of the text, like photographs in a documentary narrative.</p>
			<p>Illustration that are <em>not</em> major structural components would be, for example: drawings used to represent a person's signature, like an X mark; inline drawings representing text in alien languages; drawings used as layout elements to illustrate diagrams.</p>
			<p>If the image has a <code class="html">&lt;figcaption&gt;</code> element, then use that caption in the LoI.  If not, use the image’s <code class="html">alt</code> tag, which should be a short prose description of the image used by screen readers.</p>
			<p>Links to the images should go directly to their IDs, not just the top of the containing file.</p>
			<p>The code below is the template for a basic LoI skeleton.  Please copy and paste the entire thing as a starting point for your own LoI:</p>
			<code class="html full">&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" epub:prefix="z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: http://standardebooks.org/vocab/1.0" xml:lang="en-GB"&gt;
&lt;head&gt;
	&lt;title&gt;List of Illustrations&lt;/title&gt;
	&lt;link href="../css/core.css" rel="stylesheet" type="text/css"/&gt;
	&lt;link href="../css/local.css" rel="stylesheet" type="text/css"/&gt;
&lt;/head&gt;
&lt;body epub:type="backmatter"&gt;
	&lt;section id="loi" epub:type="loi"&gt;
		&lt;nav epub:type="loi"&gt;
			&lt;h2 epub:type="title"&gt;List of Illustrations&lt;/h2&gt;
			&lt;ol&gt;
				&lt;li&gt;
					&lt;a href="preface.xhtml#the-edge-of-the-world"&gt;The Edge of the World&lt;/a&gt;
				&lt;/li&gt;

				&lt;!--snip all the way to the end--&gt;
			&lt;/ol&gt;
		&lt;/nav&gt;
	&lt;/section&gt;
&lt;/body&gt;
&lt;/html&gt;
			</code>
		</section>
		<aside class="alert">
			<p>Standard Ebooks is a brand-new project&mdash;this manual is a pre-alpha, and much of it is incomplete. If you have a question, need clarification, or run in to an issue not yet covered here, please <a href="https://groups.google.com/forum/#!forum/standardebooks">contact us</a> so we can update this manual.</p>
		</aside>
	</article>
</main>
<?= Template::Footer() ?>
