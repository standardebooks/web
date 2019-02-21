<?
require_once('Core.php');
?><?= Template::Header(['title' => 'Typography Manual', 'js' => true, 'highlight' => 'contribute', 'description' => 'The Standard Ebooks Typography Manual, containing details on specific typographic requirements for Standard Ebooks ebooks.']) ?>
<main>
	<article>
		<h1>Typography Manual</h1>
		<aside class="alert">
			<p>Standard Ebooks is a brand-new project&mdash;this manual is a pre-alpha, and much of it is incomplete. If you have a question, need clarification, or run in to an issue not yet covered here, please <a href="https://groups.google.com/forum/#!forum/standardebooks">contact us</a> so we can update this manual.</p>
		</aside>
		<h2>The Standard Ebooks Style Philosophy</h2>
		<p>Standard Ebooks’ goal is to bring classic public domain literature into the digital era by making it accessible, attractive, and by maintaining a high standard of quality. To that end we’re not interested in slavishly reproducing the formatting quirks, transcription errors, publisher’s ephemera, or other inconsequential style decisions of the past. While we strive to be good custodians of the literature entrusted to the public, we recognize that the freedom of the public domain intersects with the Internet era in a way that allows us to present that literature in an attractive, modern, and and high-quality way.</p>
		<h2>Your task</h2>
		<p>As an editor, proofreader, or producer of a Standard Ebook, part of your task is to take the source transcription of your ebook and apply this typography manual to it. This manual outlines various standardizations and modernizations to old typography practices, making older texts easier to read for modern readers.</p>
		<p>Many of the rules below have been accepted standards for a hundred years or more. That means that many of the ebooks you produce may not need that much adjustment. The <code class="program">typogrify</code> tool in the <a href="https://github.com/standardebooks/tools">Standard Ebooks toolset</a> also automatically takes care of some (but not all!) of these rules. Typically, the older the source, the more of these rules you’ll have to check during production.</p>
		<h2>Normalizing different formatting styles</h2>
		<p>Our general rule is: <b>If it doesn’t affect the meaning of the work, then normalize it according to these standards.</b></p>
		<section id="common-problems">
			<h2>Common problems to keep an eye out for</h2>
			<ul>
				<li>
					<p>Some sources use a two-em-dash to interrupt dialog. You should replace such two-em-dashes with a single em-dash according to the <a href="#dashes">section on dashes</a>.</p>
					<figure class="text">
						<p class="wrong">&ldquo;Why, I never&mdash;&mdash;&rdquo; she cried.</p>
						<p class="corrected">&ldquo;Why, I never&mdash;&rdquo; she cried.</p>
					</figure>
					<p>Note that a two-em-dash is <em>also</em> used to signify a missing or purposefully obscured word. This is correct, but you should ensure that instead of two consecutive em-dashes, you use the two-em-dash glyph (⸺ or U+2E3A) for <em>partially</em>-obscured words, the three-em-dash glyph (⸻ or U+2E3B) for <em>completely</em>-obscured words, and a single em-dash for partially-obscured <em>years</em>.</p>
					<figure class="text">
						<p class="corrected">Sally J⸺ walked through the town of ⸻ in the year 19—.</p>
					</figure>
				</li>
				<li>
					<p>Small caps are commonly used instead of italics for emphasis in older texts, and some transcriptions use all caps instead of italics. These should generally be converted to regular case and wrapped in <code class="html">&lt;em&gt;</code> tags. If a text truly does call for extreme emphasis, the <code class="html">&lt;strong&gt;</code> tag can be used&mdash;but think twice about using it, and use it sparingly. See the <a href="#text-in-all-caps">section on text in all caps</a>.</p>
					<figure class="text">
						<p class="wrong">That donut was DELICIOUS!</p>
						<p class="corrected">That donut was &lt;em&gt;delicious&lt;/em&gt;!</p>
					</figure>
				</li>
			</ul>
		</section>
		<section id="general">
			<h2>General</h2>
			<ul>
				<li>
					<p>Our general style guide is the Chicago Manual of Style, 16th edition, with a few tweaks outlined below. Work following a different style guide should be converted to conform to ours, unless it changes the meaning of the work.</p>
				</li>
				<li>
					<p><em>Do</em> convert from logical punctuation to American punctuation where possible.</p>
				</li>
				<li>
					<p><em>Do</em> convert from British quotation to American quotation where possible. The <code class="program">british2american</code> script is helpful for automating most (but not all!) of this.</p>
				</li>
			</ul>
		</section>
		<section id="section-endings">
			<h2>Section endings</h2>
			<p>Some older books end with “The End”, “Fin”, or some other equivalent. Remove these.</p>
			<p>Some books also end individual sections or chapters with “The end of such-and-such section”. Remove these as well.</p>
		</section>
		<section id="chapters">
			<h2>Chapter names and titles</h2>
			<ul>
				<li>
					<p>In the body text, always use Roman numerals for chapter numbers instead of Arabic numerals. But in an individual file’s <code class="html">&lt;title&gt;</code> tag, <em>do</em> use Arabic numbers instead of Roman numerals.</p>
					<p><em>Do not</em> use the Unicode Roman numeral glyphs, as they are deprecated; use regular letters.</p>
				</li>
				<li>
					<p>Convert all-caps or small-caps titles to title case. Use the <code class="program">se titlecase</code> script in the <a href="https://github.com/standardebooks/tools">Standard Ebooks toolset</a> for consistent titlecasing.</p>
				</li>
				<li>
					<p>Remove trailing periods from chapter titles.</p>
				</li>
				<li>
					<p>Omit the word “Chapter” from chapter titles.</p>
					<p>Some ebooks should keep “Chapter” in titles if clarity is necessary: for example, <i><a href="/ebooks/mary-shelley/frankenstein/">Frankenstein</a></i> has “Chapter” in titles to differentiate between the “Letter” sections.</p>
					<figure class="text">
						<p class="wrong">Chapter 33</p>
						<p class="corrected">XXXIII</p>
					</figure>
				</li>
			</ul>
		</section>
		<section id="italics-or-quotes">
			<h2>Italicizing or quoting newly-used words</h2>
			<ul>
				<li>
					<p>When introducing new terms, italicize foreign or technical terms, but use quotation marks for terms composed of regular English.</p>
					<figure class="text">
						<p class="corrected">English whalers have given this the name “ice blink.”</p>
						<p class="corrected">The soil consisted of that igneous gravel called <i>tuff</i>.</p>
					</figure>
				</li>
				<li>
					<p>Don’t italicize English neologisms in works where a special vocabulary is a regular part of the narrative; specifically, science fiction works that may necessarily contain made-up English technology words. However, <em>do</em> italicize “alien” language in such works.</p>
				</li>
				<li>
					<p>Including <em>both</em> italics <em>and</em> quotes, outside of the context of quoted dialog, is usually not necessary. Use one or the other based on the rules above.</p>
				</li>
			</ul>
		</section>
		<section id="names-and-titles">
			<h2>Names and titles: Italicize or quote?</h2>
			<ul>
				<li>
					<p>Names and titles are usually <em>either</em> italicized <em>or</em> quoted, but almost never both. Pick one or the other based on the rules below.</p>
				</li>
				<li>
					<p>Older work may pick the opposite of the rules below; change such texts to match this manual.</p>
				</li>
				<li>
					<p>Older work may use quotation marks around proper names, like pub, bar, building, or company names. Remove those quotes.</p>
					<figure class="text">
						<p class="wrong">He read &ldquo;Candide&rdquo; while having a pint at the &ldquo;King’s Head.&rdquo;</p>
						<p class="corrected">He read <i>Candide</i> while having a pint at the King’s Head.</p>
					</figure>
				</li>
				<li>
					<p>In general, italicize things that can stand alone. Specifically:</p>
					<ul>
						<li>
							<p>Magazines</p>
						</li>
						<li>
							<p>Plays</p>
						</li>
						<li>
							<p>Books and novels <em>except</em> “holy texts,” like the Bible or books within the Bible</p>
						</li>
						<li>
							<p>Long musical compositions, like operas</p>
						</li>
						<li>
							<p>Albums</p>
						</li>
						<li>
							<p>Films</p>
						</li>
						<li>
							<p>TV shows</p>
						</li>
						<li>
							<p>Radio shows</p>
						</li>
						<li>
							<p>Titles of artwork</p>
						</li>
						<li>
							<p>Long poems and ballads, like the <i>Iliad</i></p>
						</li>
						<li>
							<p>Pamphlets</p>
						</li>
						<li>
							<p>Journals</p>
						</li>
						<li>
							<p>Newspapers</p>
						</li>
						<li>
							<p>Names of ships</p>
						</li>
						<li>
							<p>Names of sculptures</p>
						</li>
					</ul>
				</li>
				<li>
					<p>In general, quote things that are short or parts of longer work. Specifically:</p>
					<ul>
						<li>
							<p>Short musical compositions, like pop songs</p>
						</li>
						<li>
							<p>Chapter titles</p>
						</li>
						<li>
							<p>Short stories</p>
						</li>
						<li>
							<p>Individual newspaper or journal articles</p>
						</li>
						<li>
							<p>Essays</p>
						</li>
						<li>
							<p>Short films</p>
						</li>
						<li>
							<p>Episodes in a TV or radio series</p>
						</li>
					</ul>
				</li>
			</ul>
		</section>
		<section id="capitalization">
			<h2>Capitalization</h2>
			<section id="capitalization-general">
				<h3>General</h3>
				<ul>
					<li>
						<p>Some very old works frequently capitalize nouns that today we no longer capitalize. In general, only capitalize the beginnings of clauses, and proper nouns in the way that you would in modern English writing. Remove archaic capitalization unless doing so would change the meaning of the work.</p>
					</li>
				</ul>
			</section>
			<section id="text-in-all-caps">
				<h3>Text in all caps</h3>
				<ul>
					<li>
						<p>Text in all caps is almost never correct typography. Instead, convert such text to the correct case and surround it with a semantically-meaningful tag like <code class="html">&lt;em&gt;</code> (for emphasis), <code class="html">&lt;strong&gt;</code> (for strong emphasis, like shouting) or <code class="html">&lt;b&gt;</code> (for unsemantic formatting required by the text). Then, use <code class="css">font-weight: normal; font-variant: small-caps;</code> styling to render those tags in small caps.</p>
					</li>
				</ul>
				<figure class="text">
					<p class="corrected">The sign read &lt;b&gt;Bob’s Restaurant&lt;/b&gt;.</p>
					<p class="corrected">&ldquo;&lt;strong&gt;Charge!&lt;/strong&gt;&rdquo; he cried.</p>
				</figure>
			</section>
			<section>
				<h3>Apostrophes</h3>
				<p>When addressing something as an apostrophe, “O” is capitalized.</p>
				<figure class="text">
					<p class="corrected">I carried the bodies into the sea, O walker in the sea!</p>
				</figure>
			</section>
		</section>
		<section id="spacing">
			<h2>Spacing</h2>
			<ul>
				<li>Sentences should be single-spaced. Convert double-spaced sentences to single-space.</li>
			</ul>
		</section>
		<section id="italics">
			<h2>Italics</h2>
			<ul>
				<li>
					<p>Italics should generally be used for emphasis. Some older texts make frequent use of small caps for emphasis; change these to italics. Italics indicating emphasis must be wrapped with the <code class="html">&lt;em&gt;</code> tag.</p>
				</li>
				<li>
					<p>Set individual letters that are read as letters in italics...</p>
					<figure class="text">
						<p class="corrected">He often rolled his <i>r</i>’s.</p>
					</figure>
					<p><em>Unless</em> referring to a name that happens to be a single letter or composed of single letters, or if that letter is standing in for a name...</p>
					<figure class="text">
						<p class="corrected">...due to the loss of what is known in New England as the “L”: that long deep roofed adjunct usually built at right angles to the main house...</p>
						<p class="corrected">She was learning her A B Cs.</p>
					</figure>
					<p>Or if the letter is meant to be a comparison to a shape:</p>
					<figure class="text">
						<p class="corrected">His trident had the shape of an E.</p>
					</figure>
					<p>When using the ordinal “nth,” italicize the n, and do not include a hyphen:</p>
					<figure class="text">
						<p class="corrected">The <i>n</i>th degree.</p>
					</figure>
				</li>
				<li>
					<p>Words written to be read as sounds are set in italics using <code class="html">&lt;i&gt;</code>:</p>
					<figure class="text">
						<p class="corrected">He could hear the dog barking: <i>Ruff, ruff, ruff!</i></p>
					</figure>
				</li>
				<li>
					<p><a href="#names-and-titles">See here for italicizing names and titles</a>.</p>
				</li>
			</ul>
			<section id="italics-language">
				<h3>Italics for foreign languages</h3>
				<ul>
					<li>
						<p>Set foreign words and phrases <em>that are not in the <a href="http://www.merriam-webster.com/">Merriam-Webster</a> dictionary</em> in italics. If the foreign word or phrase <em>is</em> in Merriam-Webster, <em>don’t</em> set it in italics.</p>
						<figure class="text">
							<p class="corrected">The pièce de résistance of the dessert course was a <i>mousse au chocolat</i>.</p>
						</figure>
					</li>
					<li>
						<p><em>Don’t</em> italicize foreign words in proper names, unless the name itself would be italicized according to the rules for <a href="#names-and-titles">italicizing or quoting names and titles</a>.</p>
						<figure class="text">
							<p class="wrong">She got off the metro at the <i>Place de Clichy</i> stop, next to the <i>Le Bon Petit Déjeuner</i> restaurant.</p>
							<p class="corrected">“<i>Où est le métro?</i>” he asked, and she pointed to Place de Clichy, next to the Le Bon Petit Déjeuner restaurant.</p>
						</figure>
					</li>
					<li>
						<p>If a certain foreign word is used so frequently in the text that italicizing it would be distracting to the reader, then only italicize the first instance. However, wrap the following instances in <code class="html">&lt;span xml:lang="LANGUAGE"&gt;</code>.</p>
					</li>
					<li>
						<p>Certain exceptions to italicizing foreign words can be made if a specific word is in Merriam-Webster, but in the producer’s opinion is still too obscure for the general reader and thus should be italicized anyway. In this case ask the Standard Ebooks editor-in-chief for how to proceed.</p>
					</li>
				</ul>
			</section>
			<section id="italics-punctuation">
				<h3>Punctuation in italics</h3>
				<ul>
					<li>
						<p>If italicizing a short phrase within a longer clause, don’t italicize trailing punctuation that may belong to the containing clause.</p>
						<figure class="text">
							<p class="wrong">“Look at <em>that!</em>” she shouted.</p>
							<p class="corrected">“Look at <em>that</em>!” she shouted.</p>
						</figure>
					</li>
					<li>
						<p>However, if an entire clause is italicized for emphasis, then <em>do</em> include the trailing punctuation in the italics, <em>unless</em> that trailing punctuation is a comma at the end of some dialog.</p>
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
										<p>“<em>Charge!</em>” she shouted.</p>
									</td>
									<td><code class="html full">“&lt;em&gt;Charge!&lt;/em&gt;” she shouted.</code></td>
								</tr>
								<tr>
									<td>
										<p><em>“But I want to</em>,” she said.</p>
									</td>
									<td><code class="html full">“&lt;em&gt;But I want to&lt;/em&gt;,” she said.</code></td>
								</tr>
							</tbody>
						</table>
					</li>
				</ul>
			</section>
			<section id="taxonomy">
				<h3>Taxonomy</h3>
				<ul>
					<li>
						<p>Binomial names (generic, specific, and subspecific) are italicized with the <code class="html">&lt;i&gt;</code> tag and with the <code class="html">z3998:taxonomy</code> semantic inflection.</p>
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
										<p>A bonobo monkey is <i>Pan paniscus</i>.</p>
									</td>
									<td><code class="html full">A bonobo monkey is &lt;i epub:type="z3998:taxonomy"&gt;Pan paniscus&lt;/i&gt;.</code></td>
								</tr>
							</tbody>
						</table>
					</li>
					<li>
						<p>Family, order, class, phylum or division, and kingdom names are capitalized but not italicized.</p>
						<figure class="text">
							<p class="corrected">A bonobo monkey is in the phylum Chordata, class Mammalia, order Primates.</p>
						</figure>
					</li>
					<li>
						<p>Modern usage requires that the second part in a binomial name be set in lowercase. Older texts may set it in uppercase. Use the style in the source text.</p>
					</li>
				</ul>
			</section>
		</section>
		<section id="indentation">
			<h2>Indentation</h2>
			<ul>
				<li>
					<p>Body text in a new paragraph that directly follows earlier body text is indented by 1em.</p>
				</li>
				<li>
					<p>The initial line of body text in a section, or any text following a visible break in text flow, like a header, a scene break, a figure, a block quotation, etc., is not indented.</p>
					<p>For example: in a block quotation, there is a margin before the quotation and after the quotation. Thus, the first line of the quotation is not indented, and the first line of body text after the block quotation is also not indented.</p>
				</li>
			</ul>
		</section>
		<section id="punctuation">
			<h2>Punctuation</h2>
			<ul>
				<li>
					<p><a href="#italics-punctuation">See here for punctuation in italics</a>.</p>
				</li>
			</ul>
			<section id="spaces">
				<h3>Spaces</h3>
				<ul>
					<li>
						<p>Use single spaces between sentences.</p>
					</li>
				</ul>
			</section>
			<section id="quotation-marks">
				<h3>Quotation marks</h3>
				<ul>
					<li>
						<p>“Curly” or typographer’s quotes should always be used.</p>
						<figure class="text">
							<p class="corrected">“Don’t do it!” she shouted.</p>
						</figure>
					</li>
					<li>
						<p>Quotation marks that are directly side-by-side must be separated by a hair space (U+200A) character.</p>
						<figure class="text">
							<p class="corrected">“<span class="utf">hairsp</span>‘Green?’ Is that what you said?” asked Dave.</p>
						</figure>
					</li>
					<li>
						<p>Words with missing letters should use the right single quotation mark (’ or U+2019) character to indicate ommission.</p>
						<figure class="text">
							<p class="corrected">He had pork ’n’ beans for dinner</p>
						</figure>
					</li>
				</ul>
			</section>
			<section id="ellipses">
				<h3>Ellipses</h3>
				<ul>
					<li>
						<p>Use the ellipses (U+2026) glyph instead of consecutive or spaced periods.</p>
					</li>
					<li>
						<p>When used as suspension points (for example, to indicate dialog that pauses or trails off), don’t precede the ellipses with a comma. Commas followed by ellipses were sometimes used in older texts, but are now redundant to modern readers; remove them.</p>
						<p>Note that ellipses used to indicate missing words in a quotation still require keeping surrounding punctuation, including commas, because that punctuation is in the original quotation.</p>
					</li>
					<li>
						<p>Place a hair space (U+200A) glyph before all ellipses that are not directly preceded by punctuation, or that are directly preceded by an em-dash or a two- or three-em-dash. Place a regular space after all ellipses that are not followed by punctuation. If the ellipses is followed by punctuation, place a hair space between the ellipses and punctuation, <em>unless</em> the punctuation is a quotation mark, in which case don’t put a space at all.</p>
						<figure class="text">
							<p class="corrected">“I’m so hungry<span class="utf">hairsp</span>&hellip; What were you saying about eating<span class="utf">hairsp</span>&hellip;<span class="utf">hairsp</span>?”</p>
						</figure>
					</li>
				</ul>
			</section>
			<section id="dashes">
				<h3>Dashes</h3>
				<p>There are many kinds of dashes, and your run-of-the-mill hyphen is often not what you should use. In particular, don’t use the hyphen for things like date ranges, phone numbers, or negative numbers.</p>
				<ul>
					<li>
						<p>Do not put spaces around em-dashes. Remove spaces if in the original text.</p>
					</li>
					<li>
						<p>Use em-dashes (— or U+2014) to offset parenthetical phrases. These are usually the most common kind of dash.</p>
					</li>
					<li>
						<p>Use an em-dash for partially-obscured years.</p>
						<figure class="text">
							<p class="corrected">It was the year 19— in the town of Metrolopis.</p>
						</figure>
						<p>Use a regular hyphen if only the last digit of the year is obscured.</p>
						<figure class="text">
							<p class="corrected">It was the year 186- in the town of Metrolopis.</p>
						</figure>
					</li>
					<li>
						<p>Some older texts use two em-dashes to indicate an interruption in thought or speech. Our style is to replace two em-dashes used as an interruption marker with a single em-dash. However, <em>don’t</em> replace two em-dashes used to indicate the omission of a word (like an anonymous name or an expletive); see below.</p>
					</li>
					<li>
						<p>Use a two-em-dash glyph (⸺ or U+2E3A) to signify a purposefully <em>partially</em> obscured word. The two-em-dash glyph isn’t available in some fonts, but include it anyway; our build process will convert it later.</p>
						<figure class="text">
							<p class="corrected">Sally J⸺ walked through town.</p>
						</figure>
					</li>
					<li>
						<p>Use a three-em-dash glyph (⸻ or U+2E3B) for <em>completely</em> obscured words.</p>
						<figure class="text">
							<p class="corrected">It was night in the town of ⸻.</p>
						</figure>
					</li>
					<li>
						<p>En-dashes (– or U+2013) are used to indicate a numerical or date range; when you can substitute the word “to,” for example between locations; or to indicate a connection in location between two places.</p>
						<figure class="text">
							<p class="corrected">We talked 2–3 days ago.</p>
							<p class="corrected">We took the Berlin–Munich train yesterday.</p>
							<p class="corrected">I saw the torpedo-boat in the Ems⁠–⁠Jade Canal.</p>
						</figure>
					</li>
					<li>
						<p>Figure dashes (‒ or U+2012) are used to indicate a dash in numbers that aren’t a range, like phone numbers.</p>
						<figure class="text">
							<p class="corrected">His number is 555‒1234.</p>
						</figure>
					</li>
					<li>
						<p>Minus dashes (− or U+2212) are used to indicate negative numbers and are used in mathematical equations instead of hyphens.</p>
						<figure class="text">
							<p class="corrected">It was −5° out yesterday!</p>
						</figure>
					</li>
					<li>
						<p>Many older texts use archaic spelling and hyphenate compound words that are no longer hyphenated today. Use the <code class="program">modernize-spelling</code> script to automatically find and correct candidates. Note that this script isn’t perfect, and proofreading is required after using it to make sure it didn’t wrongly remove a hyphen!</p>
						<p><em>Do not</em> use the <code class="program">modernize-spelling</code> script on poetry.</p>
					</li>
				</ul>
			</section>
		</section>
		<section id="latinisms">
			<h2>Latinisms</h2>
			<ul>
				<li>
					<p><em>Don’t</em> italicize Latinisms that can be found in a modern dictionary, like e.g., i.e., ad hoc, viz., ibid., etc. <em>except</em> sic, which should always be italicized. Some older works might italicize these kinds of Latinisms; remove the italics.</p>
				</li>
				<li>
					<p><em>Do</em> italicize whole passages of Latin language (as you would italicize any passages of foreign text in a work) and Latinisms that <em>aren’t</em> found in a modern dictionary.</p>
				</li>
				<li>
					<p>Latinisms that are abbreviations should be set in lowercase with periods between words and no spaces between them, <em>except</em>:</p>
					<ul>
						<li>
							<p><abbr class="era">BC</abbr>, <abbr class="era">AD</abbr>, <abbr class="era">BCE</abbr>, and <abbr class="era">CE</abbr> should be set without periods and in small caps and wrapped with the <code class="html">&lt;abbr class="era"&gt;</code> tag.</p>
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
											<p>Julius Caesar was born around 100 <abbr class="era">BC</abbr>.</p>
										</td>
										<td><code class="css full">abbr.era{
font-variant: all-small-caps;
}</code> <code class="html full">Julius Caesar was born around 100 &lt;abbr class="era"&gt;BC&lt;/abbr&gt;.</code></td>
									</tr>
								</tbody>
							</table>
						</li>
					</ul>
				</li>
				<li>
					<p>Always use “etc.” instead of “&amp;c;”. Some older works use the latter; convert them to the former.</p>
				</li>
				<li>
					<p>For “Ibid.,” see <a href="#endnotes">endnotes</a>.</p>
				</li>
			</ul>
		</section>
		<section id="initials">
			<h2>Initials and Abbreviations</h2>
			<ul>
				<li>
					<p><a href="#temperatures">See here for temperatures</a>.</p>
				</li>
				<li>
					<p><a href="#times">See here for times</a>.</p>
				</li>
				<li>
					<p><a href="#latinisms">See here for Latinisms</a> including <abbr class="era">BC</abbr> and <abbr class="era">AD</abbr>.</p>
				</li>
				<li>
					<p>&ldquo;OK&rdquo; is set without periods or spaces. It is <em>not</em> an abbreviation.</p>
				</li>
				<li>
					<p>An acronym is a term made up of initials and pronounced as one word: <abbr class="acronym long">NASA</abbr>, <abbr class="acronym long">SCUBA</abbr>, <abbr class="acronym long">TASER</abbr>.</p>
					<p>An initialism is a term made up of initials in which each initial is pronounced separately: <abbr class="initialism">ABC</abbr>, <abbr class="initialism">HTML</abbr>, <abbr class="initialism">CSS</abbr>.</p>
					<p>A contraction is an abbreviation of a longer word: Mr., Mrs., lbs.</p>
				</li>
				<li>
					<p>In general, abbreviations ending in a lowercase letter should be set without spaces and followed by a period. Abbreviations without lowercase letters should be set without spaces and without a trailing period. Always use a no-break space after an abbreviation that describes the next word, like Mr., Mrs., Mt., St., etc. A few exceptions follow.</p>
				</li>
				<li>
					<p>Initials of people’s names should be separated by periods and spaces. Wrap such initials in <code class="html">&lt;abbr class="name"&gt;</code>.</p>
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
									<p><abbr class="name">H. P.</abbr> Lovecraft</p>
								</td>
								<td><code class="html full">&lt;abbr class="name"&gt;H. P.&lt;/abbr&gt; Lovecraft</code></td>
							</tr>
						</tbody>
					</table>
				</li>
				<li>
					<p>Compass directions should be wrapped in <code class="html">&lt;abbr class="compass"&gt;</code>, with periods and spaces.</p>
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
									<p>He traveled <abbr class="compass">N. W.</abbr>, then <abbr class="compass eoc">E. S. E.</abbr></p>
								</td>
								<td><code class="html full">He traveled &lt;abbr class="compass"&gt;N. W.&lt;/abbr&gt;, then &lt;abbr class="compass eoc"&gt;E. S. E.&lt;/abbr&gt;</code></td>
							</tr>
						</tbody>
					</table>
				</li>
				<li>
					<p>For acronyms, initialisms, postal codes, temperatures, and abbreviated US states, remove periods and spaces. All of these are set in caps, except for temperatures and acronyms, which are set in small caps. The source code should represent the abbreviations in caps, but wrapped in an <code class="html">&lt;abbr&gt;</code> tag.</p>
					<p>All acronyms are set in small caps. Because acroynms are capitalized in the source code, use the <abbr class="initialism">CSS</abbr> style <code class="css">font-variant: all-small-caps;</code> to properly set them in small caps.</p>
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
									<p><abbr>Dr.</abbr> <abbr class="name">J. D.</abbr> Ross, <abbr class="degree">MD</abbr>, served on the <abbr class="initialism">HMS</abbr> <i epub:type="se:name.vessel.ship">Bounty</i> as a <abbr class="acronym">NASA</abbr> liaison.</p>
								</td>
								<td><code class="css full">abbr.acronym{
font-variant: all-small-caps;
}</code> <code class="html full">&lt;abbr&gt;Dr.&lt;/abbr&gt; &lt;abbr class=&quot;name&quot;&gt;J. D.&lt;/abbr&gt; Ross, &lt;abbr class=&quot;degree&quot;&gt;MD&lt;/abbr&gt;, served on the &lt;abbr class=&quot;initialism&quot;&gt;HMS&lt;/abbr&gt; &lt;i epub:type=&quot;se:name.vessel.ship&quot;&gt;Bounty&lt;/i&gt; as a &lt;abbr class=&quot;acronym&quot;&gt;NASA&lt;/abbr&gt; laison.</code></td>
							</tr>
						</tbody>
					</table>
				</li>
				<li>
					<p>Unless mentioned above, <em>do not</em> set initialisms in small caps.</p>
				</li>
			</ul>
		</section>
		<section id="chemicals-and-compounds">
			<h2>Chemicals and compounds</h2>
			<ul>
				<li>
					<p>Set molecular compounds in regular type without spaces.</p>
				</li>
				<li>
					<p>Elements should be capitalized according to their listing in the periodic table.</p>
				</li>
				<li>
					<p>Amounts of an element should be set in subscript using the <code class="html">&lt;sub&gt;</code> tag.</p>
				</li>
			</ul>
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
							<p>H<sub>2</sub>O</p>
						</td>
						<td><code class="html full">H&lt;sub&gt;2&lt;/sub&gt;O</code></td>
					</tr>
				</tbody>
			</table>
		</section>
		<section id="temperatures">
			<h2>Temperatures</h2>
			<ul>
				<li>
					<p>Use the minus glyph (− or U+2212), not the hyphen glyph, to indicate negative numbers.</p>
				</li>
				<li>
					<p>Using either the degree glyph (° or U+00B0) or the word “degrees” is acceptable, but if a work uses both methods, normalize the work to use the dominant method.</p>
				</li>
				<li>
					<p>If listing temperature as a digit followed by “F.”, “C.”, or another abbreviation, remove the trailing period and precede the letter by a hair space (U+200A). Wrap the letter in <code class="html">&lt;abbr class="temperature"&gt;</code> styled with <code class="css">abbr.temperature{ font-variant: all-small-caps; }</code></p>
				</li>
			</ul>
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
							<p>It was −23.33° Celsius (or −10°&#x200a;<abbr class="temperature">F</abbr>) last night.</p>
						</td>
						<td><code class="css full">abbr.temperature{
font-variant: all-small-caps;
}</code> <code class="html full">It was −23.33° Celsius (or −10°<span class="utf">hairsp</span>&lt;abbr class="temperature"&gt;F&lt;/abbr&gt;) last night.</code></td>
					</tr>
				</tbody>
			</table>
		</section>
		<section id="epigraphs">
			<h2>Epigraphs in chapter headers</h2>
			<ul>
				<li>
					<p>The source of the epigraph is set in small caps, without a leading em-dash and without a trailing period.</p>
				</li>
			</ul>
		</section>
		<section id="bridgehead">
			<h2>Bridgeheads in chapter headers</h2>
			<ul>
				<li>
					<p>Bridgeheads are centered in the header.</p>
				</li>
				<li>
					<p>Always include a trailing period at the end of the bridgehead.</p>
				</li>
			</ul>
		</section>
		<section id="times">
			<h2>Times</h2>
			<ul>
				<li>
					<p>Times in a.m. and p.m. format should have the letters a.m. and p.m. set in lowercase, with periods, and without spaces. “a.m.” and “p.m.” should be wrapped in an <code class="html">&lt;abbr class="time"&gt;</code> tag. If “a.m.” or “p.m.” are the last word in a sentence, omit a second period, but add the “eoc” (end-of-clause) class to the <code class="html">&lt;abbr&gt;</code> tag.</p>
				</li>
				<li>
					<p>Seperate times written in digits followed by a.m. or p.m. with a no-break space. If the time is written out in words, use a regular space.</p>
				</li>
				<li>
					<p>Separate the hour and minute with a colon, not a period or comma.</p>
				</li>
				<li>
					<p>Do not hyphenate times when spelled out, unless they appear before a noun.</p>
					<figure class="text">
						<p class="corrected">He arrived at five thirty.</p>
						<p class="corrected">They took the twelve-thirty train.</p>
					</figure>
				</li>
				<li>
					<p>Military time that is spelled out (for example, in dialog) is set with dashes. Leading zeros are spelled out as “oh”.</p>
					<figure class="text">
						<p class="corrected">He arrived at oh-nine-hundred.</p>
					</figure>
				</li>
			</ul>
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
							<p>He called at 6:40&nbsp; <abbr class="time">a.m.</abbr>, but she wasn’t up till seven <abbr class="time eoc">a.m.</abbr></p>
						</td>
						<td>
							<code class="html full">He called at 6:40<span class="utf">nbsp</span>&lt;abbr class="time"&gt;a.m.&lt;/abbr&gt;, but she wasn’t up till seven &lt;abbr class="time eoc"&gt;a.m.&lt;/abbr&gt;</code>
							<p class="note">Note how the last &lt;abbr&gt; contains the period for the entire sentence, and consequently also has the “eoc” (end-of-clause) class.</p>
						</td>
					</tr>
				</tbody>
			</table>
		</section>
		<section id="ampersands-in-names">
			<h2>Ampersands in names</h2>
			<ul>
				<li>
					<p>Ampersands in names of things like firms should be separated by no-break spaces.</p>
					<figure class="text">
						<p class="corrected">The firm of Hawkins<span class="utf">nbsp</span>&amp;amp;<span class="utf">nbsp</span>Harker.</p>
					</figure>
				</li>
			</ul>
		</section>
		<section id="ligatures">
			<h2>Ligatures</h2>
			<p>Ligatures are symbols which combine two or more characters into one.</p>
		<ul>
			<li>
				<p>Some older texts use ligatures like æ and œ to represent dipthongs. The modernize-spelling tool will replace many of these for you, but keep an eye out for other instances, particularly in Latin phrases and in classical names such as Œdipus. These should be either be replaced with “ae” and “oe” or with alternative modern spellings of the word they are in (check Merriam-Webster for these).</p>
			</li>
			<li>
			<p>It’s very unlikely that you will encounter stylistic ligatures such as ﬂ or ﬃ in the source text, but if you do they should be replaced by the individual characters they represent.</p>
			</li>
		</ul>
		</section>
		<section id="numbers-measurements-and-math">
			<h2>Numbers, measurements, and math</h2>
			<ul>
				<li>
					<p>Roman numerals should not be followed by periods, unless the period is there for grammatical reasons. Some European texts include a trailing period after Roman numerals as a matter of course; remove them.</p>
				</li>
				<li>
					<p>Fractions should be written using the Unicode glyphs (½, ¼, ¾, etc., or U+00BC–U+00BE and U+2150–U+2189), if a glyph exists for your fraction.</p>
					<figure class="text">
						<p class="corrected">I need ¼ cup of sugar.</p>
					</figure>
					<p>If a glyph for a fraction doesn&rsquo;t exist, compose it using the fraction slash Unicode glyph (⁄ or U+2044) and superscript/subscript Unicode numbers. See <a href="https://en.wikipedia.org/wiki/Unicode_subscripts_and_superscripts">this Wikipedia entry for more details</a>.</p>
					<figure class="text">
						<p class="corrected">Roughly ⁶⁄₁₀ of a mile.</p>
					</figure>
				</li>
				<li>
					<p>Dimensions and equations should use the Unicode multiplication glyph (× or U+00D7) instead of the letters x or X.</p>
					<figure class="text">
						<p class="corrected">The board was 4 × 3 × 7 feet.</p>
					</figure>
				</li>
				<li>
					<p>Feet and inches in shorthand are set with the prime (′ or U+2032) or double prime (″ or U+2033) glyphs, <em>not</em> single or double quotes, and with a no-break space separating feet and inches.</p>
					<figure class="text">
						<p class="corrected">He was 6′<span class="utf">nbsp</span>1″ in height.</p>
					</figure>
				</li>
				<li id="coordinates">
					<p>Coordinates should be noted with the prime (′ or U+2032) or double prime (″ or U+2033) glyphs, <em>not</em> single or double quotes.</p>
					<figure class="text">
						<p class="corrected">lat. 27° 0′ N., long. 20° 1′ W.</p>
					</figure>
					<p>(Note that in the above example your font might render the two glyphs in the same way, but they’re different Unicode glyphs.)</p>
				</li>
				<li>
					<p>Operators and operands in mathematical equations should be separated by a space.</p>
					<p>Remember to use minus dashes (− or U+2212) instead of regular hyphens, both for negative numbers and for mathematical operations.</p>
					<figure class="text">
						<p class="corrected">6 − 2 + 2 = 6</p>
					</figure>
				</li>
				<li>
					<p>When forming a compound of a number + unit of measurement, and the measurement is abbreviated, separate the two with a no-break space, <em>not</em> a dash.</p>
					<figure class="text">
						<p class="corrected">A 12<span class="utf">nbsp</span>mm pistol.</p>
					</figure>
				</li>
			</ul>
		</section>
		<section id="endnotes">
			<h2>Footnotes and endnotes</h2>
			<ul>
				<li>
					<p>All footnotes should be converted to a single endnotes file. For more information on the structure of that file, see our <a href="/contribute/semantics#endnotes">structure and semantics manual</a>.</p>
				</li>
				<li>
					<p>“Ibid.” is a Latinism commonly used in endnotes to indicate that the source for a quotation or reference is the same as the last-mentioned source.</p>
					<p>In the case where the last-mentioned source is in the previous endnote, we must replace Ibid. by the full reference; since ebooks use popup endnotes, “Ibid.” becomes meaningless in that context.</p>
					<p>In the case where the last-mentioned source is in the same endnote as Ibid., we can leave Ibid. untouched.</p>
				</li>
				<li>
					<p>The endnote reference number goes after ending punctuation. If the endnote references an entire sentence in quotation marks, or the last word in a sentence in quotation marks, then the endnote reference number goes outside the quotation marks.</p>
				</li>
				<li>
					<p>Within an endnote, a backlink to where the endnote occurred in the text must be the last item. It is preceded by exactly one space.</p>
				</li>
			</ul>
		</section>
		<section id="legal">
			<h2>Legal cases and terms</h2>
			<ul>
				<li>
					<p>Legal cases are set in italic. Either “versus” or “v.” are acceptable; if using “v.”, a period must follow the “v.”</p>
					<figure class="text">
						<p class="corrected">He prosecuted <i>Johnson v. Smith</i>.</p>
					</figure>
				</li>
			</ul>
		</section>
		<aside class="alert">
			<p>Standard Ebooks is a brand-new project&mdash;this manual is a pre-alpha, and much of it is incomplete. If you have a question, need clarification, or run in to an issue not yet covered here, please <a href="https://groups.google.com/forum/#!forum/standardebooks">contact us</a> so we can update this manual.</p>
		</aside>
	</article>
</main>
<?= Template::Footer() ?>
