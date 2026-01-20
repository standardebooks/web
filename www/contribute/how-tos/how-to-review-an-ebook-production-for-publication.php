<?= Template::Header(title: 'How to Review an Ebook Production for Publication', isManual: true, highlight: 'contribute', description: 'A guide to proofread and review an ebook production for publication.') ?>
<main class="manual">
	<article class="step-by-step-guide">
		<h1>How to Review an Ebook Production for Publication</h1>
		<p>After an ebook production is completed, it must go through rounds of review before it is published to ensure the appropriate production quality is assured. Reviewers are assigned by the <a href="/about#editor-in-chief">editor-in-chief</a>, and may be a member of the <a href="/about#editors">editorial staff</a>, or an experienced Standard Ebooks producer. Below is a helpful step-by-step checklist for reviewers of ebook productions. The checklist is by no means exhaustive, but serves as a good starting point for the proofreading process. Reviewers should keep in mind the standards enumerated in the <a href="/manual">Manual of Style</a>, and note any discrepancies not listed below. Before any review steps, ensure the most recent version of the SE toolset is installed.</p>
		<ol>
			<li>
				<h2>Lint</h2>
				<p>Run <code class="bash"><b>se</b> lint <u>.</u></code> at the root of the project directory. If <code>lint</code> surfaces any nontrivial errors, you should direct the producer to fix the errors before proceeding. If there are false positives, the producer should create an <code>se-lint-ignore.xml</code> to suppress the false positives.</p>
			</li>
			<li>
				<h2>Typogrify</h2>
				<p>Run <code class="bash"><b>se</b> typogrify <u>.</u></code> at the root of the project directory. The <code>typogrify</code> command is almost always correct, but sometimes not all changes it makes should be accepted. To go over the changes <code>typogrify</code> may have made, run the following <code class="bash"><b>git</b></code> command to also highlight changes made to invisible or hard to differentiate Unicode characters:</p>
				<code class="terminal"><b>git</b> diff -U0 --word-diff-regex=.</code>
			</li>
			<li>
				<h2>Modernize spelling</h2>
				<p>Run <code class="bash"><b>se</b> modernize-spelling <u>.</u></code> at the root of the project directory. Note that this tool may not catch all archaic words. Prudent use of text editor spellcheckers can help picking up some of these. When in doubt, refer to the appropriate authority of spelling as noted in the <a href="/manual/latest/single-page#8.2.9">Manual</a>. Many words that are hyphenated in the past (<abbr>e.g.</abbr> <em>to-morrow</em>) are in modern times concatenated. <em>However</em>, these hyphens should all be retained in poetry . That said, obvious sound-alike spelling modernization should be made and accepted.</p>
			</li>
			<li>
				<h2>Semanticate</h2>
				<p>Run <code class="bash"><b>se</b> semanticate <u>.</u></code> at the root of the project directory. Unlike <code class="bash"><b>se</b> typogrify</code> or <code class="bash"><b>se</b> modernize-spelling</code>, <code class="bash"><b>se</b> semanticate</code> is more prone to error or false positives. Judicious use of the <code class="bash"><b>git</b> diff</code> command listed in Step 1 would be needed to prevent and revert any unwanted changes.</p>
			</li>
			<li>
				<h2>Clean</h2>
				<p>Run <code class="bash"><b>se</b> clean <u>.</u></code> at the root of the project directory. Ideally the producer of the ebook would have ran this multiple times during their production process. However, since changes may have been made since then by the producer and stylistic deviations may be been inadvertently introduced, this will clean those potential errors up. After each step so far, it is recommended to use the <code class="bash"><b>git</b> diff</code> commanded listed in Step 1 to review and record all changes that are needed.</p>
			</li>
			<li>
				<h2>Check dashes and diacritics</h2>
				<p>Run both <code class="bash"><b>se</b> find-mismatched-dashes <u>.</u></code> and <code class="bash"><b>se</b> find-mismatched-diacritics <u>.</u></code> at the root of the project directory. Neither of these tools will make actual changes to the project, but they will list words that have inconsistent application of dashes/diacritics. Check with the language authority listed in the <a href="/manual/latest/single-page#8.2.9">Manual</a> to decide on what recommendations should be made.</p>
			</li>
			<li>
				<h2>Build title, table of contents, and images</h2>
				<p>Run <code class="bash"><b>se</b> build-title <u>.</u></code>, <code class="bash"><b>se</b> build-toc <u>.</u></code> and <code class="bash"><b>se</b> build-images <u>.</u></code> at the root of the project directory. No changes should be made by these tools if the producer correctly generated the title page, table of content, and image files. Any discrepancies can be highlighted and investigated via the <code class="bash"><b>git</b> diff</code> listed in Step 1.</p>
			</li>
			<li>
				<h2>Review initial commit</h2>
				<p>Check out the initial commit and review <code>body.xhtml</code> with your text editor of choice to make sure that the Project Gutenberg license text wasn’t accidentally committed to the repository:</p>
				<code class="terminal"><b>git</b> checkout <i>"$COMMIT"</i></code>
				<p>If the license text was committed, the producer will have to rebase it out. Once you are done reviewing the initial commit, use the following command to leave the detached <code>HEAD</code> state:</p>
				<code class="terminal"><b>git</b> switch -</code>
			</li>
			<li>
				<h2>Review editorial commits</h2>
				<p>Use the following <code class="bash"><b>git</b></code> command to highlight all editorial commits:</p>
				<code class="terminal"><b>git</b> log --pretty=<i>"format:%h: %s"</i> --grep=<i>"\[Editorial\]"</i></code>
				<p>Once identified, these commits can be reviewed via:</p>
				<code class="terminal"><b>git</b> diff <i>"$COMMIT~"</i> <i>"$COMMIT"</i>;</code>
				<p>Alternatively, a graphical <code class="bash"><b>git</b></code> client can be used instead.</p>
			</li>
			<li>
				<h2>Review en dash uses</h2>
				<p>Run the following command to check for incorrect en dash use:</p>
				<code class="terminal"><b>grep</b> --recursive --line-number <i>"[a-z]–[a-z]"</i> <u>.</u></code>
				<p>Note that the dash in between the two square bracket is an <em>en dash</em> (<code>–</code> U+2013), and not a hyphen. Check the <a href="/manual/latest/single-page#8.7.7">Manual</a> for the correct type of dashes to use in different cases. Alternatively, use your text editor's regular expression search to find potential incorrect usage instead of <code class="bash"><b>grep</b></code>.</p>
			</li>
			<li>
				<h2>Review miscurled quotes</h2>
				<p>Run the following command to examine possible miscurled single quotes:</p>
				<code class="terminal"><b>se</b> interactive-replace <i>"(\s)‘([a-z])"</i> <i>"\1’\2"</i> <u>.</u></code>
				<p>Note the use of <code>‘</code> (left single-quotation mark, U+2018) and <code>’</code> (right single-quotation mark, U+2019) in the command above, and not <code>'</code> or <code>`</code>. Using <code class="bash"><b>se</b> interactive-replace</code> is the safest as there are many potential false positive cases here that should not be change. Refer the the relevant section of the <a href="/manual/latest/single-page#8.7.5">Manual</a>. Note that sometimes this type of mistakes might be committed by <code class="bash"><b>se</b> typogrify</code>. For example:</p>
				<figure class="wrong html full">
					<code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He had pork ‘n’ beans for dinner<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code>
				</figure>
				<figure class="corrected html full">
					<code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He had pork ’n’ beans for dinner<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code>
				</figure>
				<p>Note the right single-quotation mark is applied twice under this situation. More cases like this is in the section of the Manual linked above.</p>
			</li>
			<li>
				<h2>Check for punctuation outside quotation marks</h2>
				<p>Run the following command to examine punctuation that might have to be moved inside quotation marks:</p>
				<code class="terminal"><span><b>se</b> interactive-replace <i>"([’”])([,.])" "\2\1"</i> src/epub/text/<i class="glob">*</i></span></code>
				<p>In general, periods and commas always go inside quotation marks, both single and double. For example:</p>
				<figure class="wrong html full">
					<code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He pronounced it “pleasure”, and as he said it he licked his lips.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code>
				</figure>
				<figure class="corrected html full">
					<code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He pronounced it “pleasure,” and as he said it he licked his lips.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code>
				</figure>
				<p>Note the right single-quotation mark is applied twice under this situation. More cases like this is in the section of the Manual linked above.</p>
			</li>

			<li>
				<h2>Review capitalization</h2>
				<p>As noted in the <a href="/manual/latest/single-page#8.3.3">Manual</a> text in call caps is rarely correct. Use the following command to check for instance of all caps:</p>
				<code class="terminal"><b>se</b> xpath <i>"//p//text()[re:test(., '[A-Z]{2,}') and not(contains(., 'OK') or contains(., 'SOS')) and not(parent::abbr or parent::var or parent::a or parent::*[contains(@epub:type, 'z3998:roman')])]"</i> <u>src/epub/text/<i class="glob">*</i>.xhtml</u></code>
				<p>If any such instances is found, check with the <a href="/manual/latest/single-page#8.3">Manual</a> for the correct capitalization style in all cases.</p>
			</li>
			<li>
				<h2>Review era abbreviation elements</h2>
				<p>Era abbreviations do not have punctuation in them unlike other common abbreviations (see <a href="/manual/latest/single-page#8.9.5">Manual</a>). Use the following command to check each of such cases:</p>
				<code class="terminal"><b>se</b> interactive-replace <i>"(&lt;abbr epub:type=\"se:era[^\"]*?\"&gt;)(BC|AD)&lt;/abbr&gt;\."</i> <i>"\1\2&lt;/abbr&gt;"</i> <u>.</u></code>
			</li>
			<li>
				<h2>Review italics and emphasis elements</h2>
				<p>The <code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code> and <code><span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span></code> elements are not to be used interchangeably (see relevant section of the Manual <a href="/manual/latest/single-page#4.1.2">here</a> and <a href="/manual/latest/single-page#8.2">here</a>). Use the following command to check their usage in the production (alternatively, use the regular expression search function in a text editor):</p>
				<code class="terminal"><b>grep</b> --recursive --line-number --extended-regexp <i>"&lt;i|&lt;em"</i> <u>src/epub/text/<i class="glob">*</i>.xhtml</u></code>
				<p>Are there any <code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code> elements that lack semantics?</p>
				<code class="terminal"><b>grep</b> --recursive --line-number <i>"&lt;i&gt;"</i> <u>src/epub/text/<i class="glob">*</i>.xhtml</u></code>
			</li>
			<li>
				<h2>Review bold and strong elements</h2>
				<p>The <code class="html"><span class="p">&lt;</span><span class="nt">b</span><span class="p">&gt;</span></code> and <code><span class="p">&lt;</span><span class="nt">strong</span><span class="p">&gt;</span></code> elements are not to be used interchangeably (see relevant section of the Manual <a href="manual/latest/single-page#8.3.3">here</a>). Use the following command to check their usage in the production (alternatively, use the regular expression search function in a text editor):</p>
				<code class="terminal"><b>grep</b> --recursive --line-number --extended-regexp <i>"&lt;(b\W|strong)"</i> <u>src/epub/text/<i class="glob">*</i>.xhtml</u></code>
			</li>
			<li>
				<h2>Review XHTML file structure</h2>
				<p>Do a final look through of each XHTML file for spelling, styling, and formatting discrepancies. Possible things to look out for:</p>
				<ul>
					<li>
						<p>Check that the correct semantics for elements are used. (<abbr>e.g.</abbr> correct usage of <code class="html"><span class="p">&lt;</span><span class="nt">blockquote</span><span class="p">&gt;</span></code>, no <code class="html"><span class="p">&lt;</span><span class="nt">div</span><span class="p">&gt;</span></code> blocks are used, <abbr>etc.</abbr></p>
					</li>
					<li>
						<p>If the book is in ”parts,” “books,” or “volumes”:</p>
						<ul>
							<li>
								<p>Do the chapters have the right filenames? (See <a href="/manual/latest/single-page#2.2">Manual</a>)</p>
							</li>
							<li>
								<p>Does each chapter file include the wrapping <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> element for its corresponding part, for recomposition? (See <a href="/manual/latest/single-page#4.1.1.1">Manual</a>)</p>
							</li>
						</ul>
					</li>
					<li>
						<p>If the book has illustrations, check that the illustration names and ids are correct. (See <a href="/manual/latest/single-page#7.8">manual</a>)</p>
					</li>
				</ul>
			</li>
			<li>
				<h2>Review local CSS</h2>
				<p>Review the <code>local.css</code> file. Possible things to look out for:</p>
				<ul>
					<li>
						<p>Any styles that have no effect? (<abbr>e.g.</abbr>, Setting <code class="css"><span class="k">text-indent</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span></code> on an element which already inherits that property from <code>core.css</code>)</p>
					</li>
					<li>
						<p>Any unusual classes that can be converted to more clever selectors instead?</p>
					</li>
					<li>
						<p>Any selectors that should use a different pattern according to previous standards? (<abbr>e.g.</abbr>, setting small caps by targeting a valediction, instead of targeting <code class="html"><span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:signature"</span></code></p>
					</li>
				</ul>
			</li>
			<li>
				<h2>Review colophon</h2>
				<p>Review the colophon. Possible things to look out for:</p>
				<ul>
					<li>
						<p>Are names without links wrapped in <code class="html"><span class="p">&lt;</span><span class="nt">b</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:personal-name"</span><span class="p">&gt;</span></code>?</p>
					</li>
					<li>
						<p>Are abbreviated names wrapped in <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:given-name"</span><span class="p">&gt;</span></code>?</p>
					</li>
				</ul>
			</li>
			<li>
				<h2>Review metadata</h2>
				<p>Review all metadata including those in <code>content.opf</code>. See <a href="/manual/latest/single-page#9">this</a> section of the Manual for reference. Possible things to look out for:</p>
				<ul>
					<li>
						<p>Is author, translator(s), cover artist, <abbr>etc.</abbr> in the correct order and in expected style?</p>
					</li>
					<li>
						<p>Check all links, such as author/work Wikipedia links, are correct by <em>opening them in a browser</em>. Sometimes <code class="bash"><b>se</b> create-draft</code> guesses the wrong Wikipedia link for a book or person with the same name as another book or person.</p>
					</li>
					<li>
						<p>If the book has a subtitle, check that it is represented as expected. See <a href="/manual/latest/single-page#9.4.2">here</a> for reference.</p>
					</li>
					<li>
						<p>Confirm that the long description of the book in the metadata was not copy and pasted from Wikipedia or other third-party sources.</p>
					</li>
					<li>
						<p>Check that the short description of the book is valid (<abbr>i.e.</abbr> a single complete sentence.)</p>
					</li>
					<li>
						<p>If the book is part of any sets or series, check that all necessary metadata is included. See <a href="/manual/latest/single-page#9.3.3">here</a> for reference.</p>
					</li>
				</ul>
			</li>
			<li>
				<h2>Review cover art</h2>
				<p>Open <code>./images/cover.jpg</code> in your image editor of choice and examine it to make sure it is cropped correctly (<abbr>i.e.</abbr> there is no whitespace on the margins).</p>
			</li>
			<li>
				<h2>Lint again</h2>
				<p>Run a final <code class="bash"><b>se</b> lint <u>.</u></code> check, and ensure it is silent. If any warnings and errors are produced, they must be noted and addressed.</p>
			</li>
			<li>
				<h2>Test epub build</h2>
				<p>Run the following command at the root of the project directory to ensure the epub builds:</p>
				<code class="terminal"><b>se</b> build --check-only <u>.</u></code>
				<p>Note all errors if they are produced by the command.</p>
			</li>
			<li>
				<h2>Submit review comments</h2>
				<p>Log all review notes and recommendations on the production's GitHub repository issue tracker, and inform the producer and the rest of the editorial team the review has been completed, with a short summary of the results of the review and changes that may be needed before publishing.</p>
			</li>
		</ol>
	</article>
</main>
<?= Template::Footer() ?>
