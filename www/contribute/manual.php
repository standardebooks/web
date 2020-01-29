<?
require_once('Core.php');
?><?= Template::Header(['title' => 'Style Manual', 'js' => true, 'highlight' => 'contribute', 'description' => 'The working draft of the new Standard Ebooks Style Manual.']) ?>
<main>
	<article class="manual">
		<h1>Style Manual</h1>

		<aside class="alert">
			<p>This manual is an incomplete draft. Do not use this for production work. Formatting is a work-in-progress.</p>
		</aside>
		<h2>Table of Contents</h2>
		<p>Coming soon...</p>

		<section>
			<h2>XHTML and CSS code formatting style</h2>
			<p>The <code class="path">clean</code> tool in the Standard Ebooks toolset formats XHTML code according to our style guidelines. The vast majority of the time its output is correct and no further modifications to code style are necessary.</p>
			<section>
				<h3>XHTML formatting</h3>
				<ol>
					<li>
						<p>The first line of all XHTML files is:</p>
						<figure>
							<code class="html">&lt;?xml version="1.0" encoding="utf-8"?&gt;</code>
						</figure>
					</li>
					<li>
						<p>The second line of all XHTML files is (replace <code class="html">LANG</code> with the <a href="https://en.wikipedia.org/wiki/IETF_language_tag">appropriate language tag</a> for the file):</p>
						<figure>
							<code class="html">&lt;html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" epub:prefix="z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: https://standardebooks.org/vocab/1.0" xml:lang="LANG"&gt;</code>
						</figure>
					</li>
					<li>
						<p>Tabs are used for indentation.</p>
					</li>
					<li>
						<p>Tag names are lowercase.</p>
					</li>
					<li>
						<p>Tags whose content is <a href="https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/Content_categories#Phrasing_content">phrasing content</a> are on a single line, with no whitespace between the opening and closing tags and the content.</p>
						<figure class="wrong">
							<code class="html">&lt;p&gt;
It was a dark and stormy night...
&lt;/p&gt;</code>
						</figure>
						<figure class="corrected">
							<code class="html">&lt;p&gt;It was a dark and stormy night...&lt;/p&gt;</code>
						</figure>
					</li>
				</ol>
				<section>
					<h3>Attributes</h3>
					<ol>
						<li>
							<p>Attributes are in alphabetical order.</p>
						</li>
						<li>
							<p>Attributes, their namespaces, and their values are lowercase, except for values which are IETF language tags, where subtags are capitalized.</p>
							<figure class="wrong">
								<code class="html">&lt;section EPUB:TYPE="CHAPTER" XML:LANG="EN-US"&gt;...&lt;/section&gt;</code>
							</figure>
							<figure class="corrected">
								<code class="html">&lt;section epub:type="chapter" xml:lang="en-US"&gt;...&lt;/section&gt;</code>
							</figure>
						</li>
						<li>
							<p>The string <code class="html">utf-8</code> is lowercase.</p>
						</li>
					</ol>
				</section>
			</section>
			<section>
				<h3>CSS formatting</h3>
				<ol>
					<li>
						<p>The first two lines of all CSS files are:</p>
						<figure>
							<code class="css">@charset "utf-8";
@namespace epub "http://www.idpf.org/2007/ops";</code>
						</figure>
					</li>
					<li>
						<p>Tabs are used for indentation.</p>
					</li>
					<li>
						<p>Selectors, properties, and values are lowercase.</p>
					</li>
				</ol>
				<section>
					<h3>Selectors</h3>
					<ol>
						<li>
							<p>Selectors are each on their own line, directly followed by a comma or a brace with no whitespace inbetween.</p>
						</li>
						<li>
							<p>Complete selectors are separated by exactly one blank line.</p>
							<figure class="wrong">
								<code class="css">abbr.name{
white-space: nowrap;
}



strong{
font-weight: normal;
font-variant: small-caps;
}</code>
							</figure>
							<figure class="corrected">
								<code class="css">abbr.name{
white-space: nowrap;
}

strong{
font-weight: normal;
font-variant: small-caps;
}</code>
							</figure>
						</li>
						<li>
							<p>Closing braces are on their own line.</p>
						</li>
					</ol>
				</section>
				<section>
					<h3>Properties</h3>
					<ol>
						<li>
							<p>Properties are each on their own line (even if the selector only has one property) and indented with a single tab.</p>
							<figure class="wrong">
								<code class="css">abbr.name{ white-space; nowrap; }</code>
							</figure>
							<figure class="corrected">
								<code class="css">abbr.name{
white-space: nowrap;
}</code>
							</figure>
						</li>
						<li>
							<p>Properties are in alphabetical order, <em>where possible</em>.</p>
							<p>This isn’t always possible if you’re attempting to override a previous style in the same selector, and in some other cases.</p>
						</li>
						<li>
							<p>Properties are directly followed by a colon, then a single space, then the property value.</p>
						</li>
						<li>
							<p>Property values are directly followed by a semicolon, even if it's the last value in a selector.</p>
						</li>
					</ol>
				</section>
			</section>
		</section>
		<section>
			<h2>Filesystem layout</h2>
			<section>
				<h3>How to name files</h3>
				<ol>

					<li>
						<p>Works that are divided into larger parts (sometimes called "parts", "books", "volumes", "sections", etc.) have their part divisions contained in individual files named after the type of part, followed by a number starting at <code class="html">1</code>.</p>
						<figure class="text">
							<p class="corrected"><code class="path">book-1.xhtml</code></p>
							<p class="corrected"><code class="path">book-2.xhtml</code></p>
							<p class="corrected"><code class="path">part-1.xhtml</code></p>
							<p class="corrected"><code class="path">part-2.xhtml</code></p>
						</figure>
					</li>
					<li>
						<p>Works that are composed of chapters, short stories, essays, or other short- to medium-length sections have each of those sections in an individual file.</p>
						<ol>
							<li>
								<p>Chapters <em>not</em> contained in separate volumes are named <code class="path">chapter-N.xhtml</code>, where <code class="path">N</code> is the chapter number starting at <code class="html">1</code>.</p>
							</li>
							<li>
								<p>Chapters contained in separate volumes, where the chapter number starts at 1 for each separate volume, are named <code class="path">chapter-X-N.xhtml</code>, where <code class="path">X</code> is the part number starting at <code class="html">1</code>, and <code class="path">N</code> is the chapter number <em>within the part</em>, starting at <code class="html">1</code>.</p>
								<figure class="text">
									<table>
										<thead>
											<tr>
												<td><p>Example</p></td>
												<td><p>Filename</p></td>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Part 1</td>
												<td><code class="path">part-1.xhtml</code></td>
											</tr>
											<tr>
												<td>Chapter 1</td>
												<td><code class="path">chapter-1-1.xhtml</code></td>
											</tr>
											<tr>
												<td>Chapter 2</td>
												<td><code class="path">chapter-1-2.xhtml</code></td>
											</tr>
											<tr>
												<td>Chapter 3</td>
												<td><code class="path">chapter-1-3.xhtml</code></td>
											</tr>
											<tr>
												<td>Part 2</td>
												<td><code class="path">part-2.xhtml</code></td>
											</tr>
											<tr>
												<td>Chapter 1</td>
												<td><code class="path">chapter-2-1.xhtml</code></td>
											</tr>
											<tr>
												<td>Chapter 2</td>
												<td><code class="path">chapter-2-2.xhtml</code></td>
											</tr>
											<tr>
												<td>Chapter 3</td>
												<td><code class="path">chapter-2-3.xhtml</code></td>
											</tr>
										</tbody>
									</table>
								</figure>
							</li>
							<li>
								<p>Chapters contained in separate volumes, where the chapter number does not re-start at 1 in each volume, are named <code class="path">chapter-N.xhtml</code>, where <code class="path">N</code> is the chapter number, starting at <code class="html">1</code>.</p>
								<figure class="text">
									<table>
										<thead>
											<tr>
												<td><p>Example</p></td>
												<td><p>Filename</p></td>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Part 1</td>
												<td><code class="path">part-1.xhtml</code></td>
											</tr>
											<tr>
												<td>Chapter 1</td>
												<td><code class="path">chapter-1.xhtml</code></td>
											</tr>
											<tr>
												<td>Chapter 2</td>
												<td><code class="path">chapter-2.xhtml</code></td>
											</tr>
											<tr>
												<td>Chapter 3</td>
												<td><code class="path">chapter-3.xhtml</code></td>
											</tr>
											<tr>
												<td>Part 2</td>
												<td><code class="path">part-2.xhtml</code></td>
											</tr>
											<tr>
												<td>Chapter 4</td>
												<td><code class="path">chapter-4.xhtml</code></td>
											</tr>
											<tr>
												<td>Chapter 5</td>
												<td><code class="path">chapter-5.xhtml</code></td>
											</tr>
											<tr>
												<td>Chapter 6</td>
												<td><code class="path">chapter-6.xhtml</code></td>
											</tr>
										</tbody>
									</table>
								</figure>
							</li>
						</ol>
					<li>
						<p>Files containing a short story, essay, or other short work in a larger collection, are named with the URL-safe title of the work, excluding any subtitles.</p>
						<figure class="text">
							<table>
								<thead>
									<tr>
										<td><p>Example</p></td>
										<td><p>Filename</p></td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><p>A short story named "The Variable Man"</p></td>
										<td><code class="path">the-variable-man.xhtml</code></td>
									</tr>
									<tr>
										<td><p>A short story named "The Sayings of Limpang-Tung (The God of Mirth and of Melodious Minstrels)"</p></td>
										<td><code class="path">the-sayings-of-limpang-tung.xhtml</code></td>
									</tr>
								</tbody>
							</table>
						</figure>
					</li>
					<li>
						<p>Works that are composed of extremely short sections, like a volume of short poems, are in a single file containing all of those short sections. The filename is the URL-safe name of the work.</p>
						<figure class="text">
							<p class="corrected"><code class="path">north-of-boston.xhtml</code></p>
						</figure>
					</li>
					<li>
						<p>Frontmatter and backmatter sections have filenames that are named after the type of section, regardless of what the actual title of the section is.</p>
						<figure class="text">
							<table>
								<thead>
									<tr>
										<td><p>Example</p></td>
										<td><p>Filename</p></td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><p>A preface titled "Note from the author"</p></td>
										<td><code class="path">preface.xhtml</code></td>
									</tr>
								</tbody>
							</table>
						</figure>
						<ol>
							<li>
								<p>If an ebook contains more than one section of the same type (for example multiple prefaces), the filename is followed by <code class="html">-N</code>, where <code class="html">N</code> is a number representing the order of the section, starting at <code class="html">1</code>.</p>
								<figure class="text">
									<table>
										<thead>
											<tr>
												<td><p>Example</p></td>
												<td><p>Filename</p></td>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><p>The first preface in an ebook, titled "Preface to the 1850 Edition"</p></td>
												<td><code class="path">preface-1.xhtml</code></td>
											</tr>
											<tr>
												<td><p>The second preface in an ebook, titled "Preface to the Charles Dickens Edition"</p></td>
												<td><code class="path">preface-2.xhtml</code></td>
											</tr>
										</tbody>
									</table>
								</figure>
							</li>
						</ol>
					</li>
				</ol>
			</section>
		</section>
		<section>
			<h2>Semantic inflection</h2>
			<p>The epub spec allows for <a href="http://www.idpf.org/accessibility/guidelines/content/semantics/epub-type.php">semantic inflection</a>, which is a way of adding pertinent semantic metadata to certain elements. For example, you may want to convey that the contents of a certain <code class="html">&lt;section&gt;</code> are actually a part of a chapter. You would do that by using the <code class="html">epub:type</code> attribute:</p>
			<figure>
				<code class="html">&lt;section epub:type="chapter"&gt;...&lt;/section&gt;</code>
			</figure>
			<ol>
				<li>
					<p>The epub spec includes a <a href="http://www.idpf.org/epub/vocab/structure/">list of supported keywords</a> that you can use in the <code class="html">epub:type</code> attribute. Use this vocabulary first, even if other vocabularies contain the same keyword.</p>
				</li>
				<li>
					<p>If there's no appropriate keyword in the epub spec, next consult the <a href="http://www.daisy.org/z3998/2012/vocab/structure/">z3998 vocabulary</a>.</p>
					<p>Keywords using this vocabulary are preceded by the <code class="html">z3998</code> namespace.</p>
					<figure>
						<code class="html">&lt;blockquote epub:type="z3998:letter"&gt;...&lt;/blockquote&gt;</code>
					</figure>
				</li>
				<li>
					<p>If the z3998 vocabulary doesn't have an appropriate keyword, next consult the <a href="/vocab/1.0">Standard Ebooks vocabulary</a>.</p>
					<p>Keywords using this vocabulary are preceded by the <code class="html">se</code> namespace.</p>
					<p>Unlike other vocabularies, the Standard Ebooks vocabulary is organized heirarchically. A complete vocabulary entry begins with the root vocabulary entry, with subsequent children separated by <code class="html">.</code>.</p>
					<figure>
						<code class="html">The &lt;i epub:type="se:name.vessel.ship"&gt;&lt;abbr class="initialism"&gt;HMS&lt;/abbr&gt; Bounty&lt;/i&gt;.</code>
					</figure>
				</li>
				<li>
					<p>The <code class="html">epub:type</code> attribute can have multiple keywords separated by spaces, even if the vocabularies are different.</p>
					<figure>
						<code class="html">&lt;section epub:type="chapter z3998:letter"&gt;...&lt;/section&gt;</code>
					</figure>
				</li>
			</ol>
		</section>
		<section>
			<h2>General XHTML patterns</h2>
			<section>
				<h3>The id attribute</h3>
				<section>
					<h4>ids of &lt;section&gt;s</h4>
					<ol>
						<li>
							<p>Each <code class="html">&lt;section&gt;</code> has an <code class="html">id</code> attribute identical to the filename containing the <code class="html">&lt;section&gt;</code>, without the trailing extension.</p>
						</li>
						<li>
							<p>In files containing multiple <code class="html">&lt;section&gt;</code>s, each <code class="html">&lt;section&gt;</code> has an <code class="html">id</code> attribute identical to what the filename <em>would</em> be if the section was in an individual file, without the trailing extension.</p>
						</li>
					</ol>
				</section>
				<section>
					<h4>ids of other elements</h4>
					<p>Generally, elements that are not <code class="html">&lt;section&gt;</code>s do not have an <code class="html">id</code> attribute. However an <code class="html">id</code> attribute might be necessary if a particular element is referenced in a link in a different location in the ebook; for example, if a certain paragraph is linked from an endnote.</p>
					<ol>
						<li>
							<p>If an element requires an <code class="html">id</code> attribute, the attribute's value is the name of the tag followed by <code class="path">-N</code>, where <code class="path">N</code> is the sequence number of the tag start at <code class="html">1</code>.</p>
							<figure class="corrected">
								<code class="html">&lt;p&gt;See &lt;a href="#p-4"&gt;this paragraph&lt;/a&gt; for more details.&lt;/p&gt;
&lt;p&gt;...&lt;/p&gt;
&lt;p&gt;...&lt;/p&gt;
&lt;p id="p-4"&gt;...&lt;/p&gt;
&lt;p&gt;...&lt;/p&gt;</code>
							</figure>
						</li>
					</ol>
				</section>
			</section>
			<section>
				<h3>&lt;title&gt; tags</h3>
				<ol>
					<li>
						<p>The <code class="html">&lt;title&gt;</code> tag contains an appropriate description of the local file only. It does not contain the book title.</p>
					</li>
				</ol>
				<section>
					<h4>Titles of files that are an individual chapter or part division</h4>
					<ol>
						<li>
							<p>Convert chapter or part numbers that are in Roman numerals to decimal numbers. Do not convert other Roman numerals that may be in the chapter title.</p>
							<figure>
								<code class="html">&lt;title&gt;Chapter 10&lt;/title&gt;</code>
							</figure>
						</li>
						<li>
							<p>If a chapter or part has no subtitle, the <code class="html">&lt;title&gt;</code> tag contains <code class="html">Chapter</code> followed by the chapter number.</p>
							<figure>
								<code class="html">&lt;title&gt;Chapter 4&lt;/title&gt;</code>
							</figure>
						</li>
						<li>
							<p>If a chapter or part has a subtitle, the <code class="html">&lt;title&gt;</code> tag contains <code class="html">Chapter</code>, followed by the chapter number, followed by a colon and a single space, followed by the subtitle.</p>
							<figure>
								<code class="html">&lt;title&gt;Chapter 4: The Reign of Louis XVI&lt;/title&gt;</code>
							</figure>
						</li>
					</ol>
				</section>
				<section>
					<h4>Titles of files that are not chapter or part divisions</h4>
					<ol>
						<li>
							<p>Files that are not a chapter or a part division, like a preface, introduction, or epigraph, have a <code class="html">&lt;title&gt;</code> tag that contains the complete title of the section.</p>
							<figure>
								<code class="html">&lt;title&gt;Preface&lt;/title&gt;</code>
							</figure>
						</li>
						<li>
							<p>If a file contains a section with a subtitle, the <code class="html">&lt;title&gt;</code> tag contains the title, followed by a colon and a single space, followed by the subtitle.</p>
							<figure>
								<code class="html">&lt;title&gt;Quevedo and His Works: With an Essay on the Picaresque Novel&lt;/title&gt;</code>
							</figure>
						</li>
					</ol>
				</section>
			</section>
			<section>
				<h3>Ordered/numbered and unordered lists</h3>
				<p>All <code class="html">&lt;li&gt;</code> children of <code class="html">&lt;ol&gt;</code> and <code class="html">&lt;ul&gt;</code> tags have at least one direct child block-level tag.  This is usually a <code class="html">&lt;p&gt;</code> tag.  (But not necessarily; for example, a <code class="html">&lt;blockquote&gt;</code> tag might also be appropriate.)</p>
				<figure class="wrong">
					<code class="html">&lt;ul&gt;
&lt;li&gt;Don’t forget to feed the pigs.&lt;/li&gt;
&lt;/ul&gt;</code>
				</figure>
				<figure class="corrected">
					<code class="html">&lt;ul&gt;
&lt;li&gt;
	&lt;p&gt;Don’t forget to feed the pigs.&lt;/p&gt;
&lt;/li&gt;
&lt;/ul&gt;</code>
				</figure>
			</section>
		</section>
		<section>
			<h2>Sectioning</h2>
			<ol>
				<li>
					<p>Structural divisions in an ebook are each contained in their own <code class="html">&lt;section&gt;</code> tag.</p>
				</li>
				<li>
					<p>In <code class="html">&lt;section&gt;</code>s that have titles, the first child element is an <code class="html">&lt;h#&gt;</code> or a <code class="html">&lt;header&gt;</code> element containing the section's title.</p>
				</li>
				<li>
					<p>Typically an epub is divided into many different files. However, these files must be "recomposable" into a single HTML file by the <code class="path">recompose-epub</code> tool.</p>
					<p>To achieve recomposability, individual files that contain subsections also contain all of the direct parent <code class="html">&lt;section&gt;</code> tags, with <code class="html">id</code> and <code class="html">epub:type</code> attributes identical to those parents.</p>
					<p>For example, consider an ebook containing both a file named <code class="path">part-1.xhtml</code>, the first part, and a file named <code class="path">chapter-1-1.xhtml</code>, the first chapter of the first part. <code class="path">chapter-1-1.xhtml</code> contains a <code class="html">&lt;section&gt;</code> element for <em>both</em> the parent part, <em>and</em> the actual chapter:</p>
					<figure class="corrected">
						<code class="html">&lt;section id="part-1" epub:type="part"&gt;
&lt;section id="chapter-1-1" epub:type="chapter"&gt;
	&lt;h3 epub:type="title z3998:roman">I&lt;/h3&gt;
	...
&lt;/section&gt;
&lt;/section&gt;</code>
					</figure>
				</li>
			</ol>
		</section>
		<section>
			<h2>Parts of an ebook and their order</h2>
			<section>
				<h3>Frontmatter</h3>
				<p>Frontmatter includes these parts of a book, in no particular order:</p>
				<ul>
					<li>
						<p>Titlepage: An SE template containing an image with the title, author, and translator.</p>
					</li>
					<li>
						<p>Imprint: An SE template containing information about Standard Ebooks and links to the source transcription and scans used to produce this ebook.</p>
					</li>
					<li>
						<p>Dedication: A dedication from the author.</p>
					</li>
					<li>
						<p>Epigraph: An opening poem or quotation.</p>
					</li>
					<li>
						<p>Acknowledgements: A list of people that the author wishes to thank.</p>
					</li>
					<li>
						<p>Foreword: An introduction to the text, written by someone besides the author.</p>
					</li>
					<li>
						<p>Preface: An introduction to the text but standing outside of the text itself, written by the author. Often describes how a book came into being or to establish the author's credentials. "About the book as a book."</p>
					</li>
					<li>
						<p>Introduction: An introduction to the text written, by the author. "About the content of the book."</p>
					</li>
					<li>
						<p>Prologue: An opening to the text that establishes setting or mood, usually in the voice of the text.</p>
					</li>
				</ul>
				<p>Note that in a Standard Ebooks ebook, the copyright page is part of the backmatter and not the frontmatter.</p>
			</section>
			<section>
				<h3>Backmatter</h3>
				<p>Backmatter includes these parts of a book, in no particular order:</p>
				<ul>
					<li>
						<p>Epilogue: A conclusion to the text, usually in the voice of the text..</p>
					</li>
					<li>
						<p>Afterword: A note from the author to conclude the text, outside of the text itself.</p>
					</li>
					<li>
						<p>Endnotes: Any supplementary notes referenced in the text.</p>
					</li>
					<li>
						<p>Colophon: A description of publishing details regarding the specific edition of the text, including credits or contributors.</p>
					</li>
					<li>
						<p>Copyright page: Usually considered frontmatter in print texts, but considered backmatter in Standard Ebooks ebooks.</p>
					</li>
				</ul>
			</section>
			<section>
				<h3>Ordering of sections</h3>
				<ol>
					<li>
						<p>The first section of an ebook is <code class="path">titlepage.xhtml</code>. This file is auto-generated by <code class="path">create-draft</code>.</p>
					</li>
					<li>
						<p>The second section of an ebook is <code class="path">imprint.xhtml</code>. The imprint contains links to the source transcription and scans used to produce the Standard Ebooks edition.</p>
					</li>
					<li>
						<p>Any frontmatter relevant to the text follows.</p>
					</li>
					<li>
						<p>If there is frontmatter, then a half title page follows.</p>
						<section>
							<h4>Half title page patterns</h4>
							<p>The half title page contains the work title and subtitle.</p>
							<ol>
								<li>
									<p>Half title pages without subtitles:</p>
									<figure>
										<code class="html full">&lt;?xml version="1.0" encoding="utf-8"?&gt;
&lt;html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" epub:prefix="z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: https://standardebooks.org/vocab/1.0" xml:lang="en-GB"&gt;
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
									</figure>
								</li>
								<li>
									<p>Half title pages with subtitles:</p>
									<figure>
										<code class="css full">section[epub|type~="halftitlepage"] span[epub|type~="subtitle"]{
display: block;
font-size: .75em;
font-weight: normal;
}</code>
			<code class="html full">&lt;?xml version="1.0" encoding="utf-8"?&gt;
&lt;html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" epub:prefix="z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: https://standardebooks.org/vocab/1.0" xml:lang="en-GB"&gt;
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
									</figure>
								</li>
							</ol>
						</section>
					</li>
					<li>
						<p>Bodymatter (the main text) follows the frontmatter and half title page.</p>
					</li>
					<li>
						<p>After the bodymatter, backmatter within the text follows, for example an afterword, epilogue, or conclusion.</p>
					</li>
					<li>
						<p>Endnotes follow the text's backmatter. Endnotes files are named <code class="path">endnotes.xhtml</code>.</p>
					</li>
					<li>
						<p>The colophon follows.</p>
					</li>
					<li>
						<p>The uncopyright page follows the colophon.</p>
					</li>
				</ol>
			</section>
		</section>
		<section>
			<h2>Headers</h2>
			<ol>
				<li>
					<p><code class="html">&lt;h#&gt;</code> tags (i.e., <code class="html">&lt;h1&gt;</code>–<code class="html">&lt;h6&gt;</code>)  are used for headers of sections that are structural divisions of a document.</p>
					<p><code class="html">&lt;h#&gt;</code> tags <em>are not</em> used for headers of components that are not in the table of contents. For example, they would not be used to mark up the title of a short poem in a chapter, where the poem itself is not a structural component of the larger ebook.</p>
				</li>
				<li>
					<p>A section containing an <code class="html">&lt;h#&gt;</code> appears in the table of contents.</p>
				</li>
				<li>
					<p>The book's title is implicitly at the <code class="html">&lt;h1&gt;</code> level, even if not present in the ebook. Because of the implicit <code class="html">&lt;h1&gt;</code>, all other sections begin at <code class="html">&lt;h2&gt;</code>.</p>
				</li>
				<li>
					<p>Each <code class="html">&lt;h#&gt;</code> tag uses the correct number for the section's heading level in the overall book, <em>not</em> the section's heading level in the individual file.</p>
					<p>For example, given an ebook with a file named <code class="path">part-2.xhtml</code> containing:</p>
					<figure>
						<code class="html">&lt;section id="part-2" epub:type="part"&gt;
&lt;h2 epub:type="title"&gt;Part &lt;span epub:type="z3998:roman"&gt;II&lt;/span&gt;&lt;/h2&gt;
&lt;/section&gt;</code>
					</figure>
					<p>Consider this example for the file <code class="path">chapter-2-3.xhtml</code>:</p>
					<figure class="wrong">
						<code class="html">&lt;section id="part-2" epub:type="part"&gt;
&lt;section id="chapter-2-3" epub:type="chapter"&gt;
	&lt;h1 epub:type="title z3998:roman"&gt;III&lt;/h1&gt;
	...
&lt;/section&gt;
&lt;/section&gt;</code>
					</figure>
					<figure class="corrected">
						<code class="html">&lt;section id="part-2" epub:type="part"&gt;
&lt;section id="chapter-2-3" epub:type="chapter"&gt;
	&lt;h3 epub:type="title z3998:roman"&gt;III&lt;/h3&gt;
	...
&lt;/section&gt;
&lt;/section&gt;</code>
					</figure>
				</li>
				<li>
					<p>Each <code class="html">&lt;h#&gt;</code> tag has a direct parent <code class="html">&lt;section&gt;</code> tag.</p>
				</li>
			</ol>
			<section>
				<h3>Header HTML patterns</h3>
				<ol>
					<li>
						<p>Sections without titles:</p>
						<figure class="corrected">
							<code class="html">&lt;h2 epub:type="title z3998:roman"&gt;XI&lt;/h2&gt;</code>
						</figure>
					</li>
					<li>
						<p>Sections with titles but no ordinal (i.e. chapter) numbers:</p>
						<figure class="corrected">
							<code class="html">&lt;h2 epub:type="title"&gt;A Daughter of Albion&lt;/h2&gt;</code>
						</figure>
					</li>
					<li>
						<p>Sections with titles and ordinal (i.e. chapter) numbers:</p>
						<figure>
							<code class="css full">span[epub|type~="subtitle"]{
display: block;
font-weight: normal;
}</code>
							<code class="html full">&lt;h2 epub:type="title"&gt;
&lt;span epub:type="z3998:roman"&gt;XI&lt;/span&gt;
&lt;span epub:type="subtitle"&gt;Who Stole the Tarts?&lt;/span&gt;
&lt;/h2&gt;</code>
						</figure>
					</li>
					<li>
						<p>Sections titles and subtitles but no ordinal (i.e. chapter) numbers:</p>
						<figure>
							<code class="css full">span[epub|type~="subtitle"]{
display: block;
font-weight: normal;
}</code>
							<code class="html full">&lt;h2 epub:type="title"&gt;
&lt;span&gt;An Adventure&lt;/span&gt;
&lt;span epub:type="subtitle"&gt;(A Driver’s Story)&lt;/span&gt;
&lt;/h2&gt;</code>
						</figure>
					</li>
					<li>
						<p>Sections that require titles, but that are not in the table of contents:</p>
						<figure>
							<code class="css full">header{
font-variant: small-caps;
margin: 1em;
text-align: center;
}</code>
							<code class="html full">&lt;header&gt;
&lt;p&gt;The Title of a Short Poem&lt;/p&gt;
&lt;/header&gt;</code>
						</figure>
					</li>
				</ol>
			</section>
		</section>





		<section>
			Play CSS:


			[epub|type~="z3998:drama"]{
				border-collapse: collapse;
			}

			[epub|type~="z3998:drama"] tr:first-child td{
				padding-top: 0;
			}

			[epub|type~="z3998:drama"] tr:last-child td{
				padding-bottom: 0;
			}

			[epub|type~="z3998:drama"] td{
				vertical-align: top;
				padding: .5em;
			}

			[epub|type~="z3998:drama"] td:last-child{
				padding-right: 0;
			}

			[epub|type~="z3998:drama"] td:first-child{
				padding-left: 0;
			}

			[epub|type~="z3998:drama"] td[epub|type~="z3998:persona"]{
				hyphens: none;
				text-align: right;
				width: 20%;
			}

			[epub|type~="z3998:drama"] td p{
				text-indent: 0;
			}

			table[epub|type~="z3998:drama"],
			[epub|type~="z3998:drama"] table{
				margin: 1em auto;
			}

			[epub|type~="z3998:stage-direction"]{
				font-style: italic;
			}

			[epub|type~="z3998:stage-direction"]::before{
				content: "(";
				font-style: normal;
			}

			[epub|type~="z3998:stage-direction"]::after{
				content: ")";
				font-style: normal;
			}

			[epub|type~="z3998:persona"]{
				font-variant: all-small-caps;
			}

		</section>
	</article>
</main>
<?= Template::Footer() ?>
