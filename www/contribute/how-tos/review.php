<?
require_once('Core.php');
?><?= Template::Header(['title' => 'How to review a production before it is published', 'manual' => true, 'highlight' => 'contribute', 'description' => 'A guide to proofread and review an ebook production before it is published.']) ?>
<main class="manual">
	<article class="step-by-step-guide">
		<h1>How to review a production before it is published</h1>
		<p>After an ebook production is completed, it must go through rounds of review before it is published to ensure appropriate the appropriate production quality is assured. Reviewers are assigned by the <a href="/about#editor-in-chief">editor-in-chief</a>, and may be a member of the <a href="/about#editors">editorial staff</a>, or an experienced Standard Ebooks producer. Below is a helpful step-by-step checklist for reviewers of ebook productions. The checklist is by no means exhaustive, but serves as a good starting point of the proofreading process. Reviewers to keep in mind the standards enumerated in the <a href="/manual">Manual of Style</a>, and note any discrepancies not listed below.</p>
		<ol>
			<li>
				<h2>Step 1</h2>
				<p>Before any review step, ensure the most recent version of the SE toolset is installed. Run <code class="bash"><b>se</b> typogrify .</code> at the root of the project directory. The <code>typogrify</code> command is almost always correct, but sometimes not all changes it makes should be accepted. To go over the changes <code>typogrify</code> may have made, run the following <code><b>git</b></code> command to also highlight changes made to invisible or hard to differentiate Unicode characters:</p>
				<code class="terminal"><b>git</b> diff -U0 --word-diff-regex=.</code>
			</li>
			<li>
				<h2>Step 2</h2>
				<p>Run <code class="bash"><b>se</b> modernize-spelling .</code> at the root of the project directory. Note that this tool may not catch all archaic words. Prudent use of text editor spellcheckers can help picking up some of these. When in doubt, refer to the appropriate authority of spelling as noted in the <a href="/manual/latest/single-page#8.2.9">Manual</a>. Many words that are hyphenated in the past (<abbr>e.g.</abbr> <em>to-morrow</em>) are in modern times concatenated. <em>However</em>, these hyphens should all be retained in poetry . That said, obvious sound-alike spelling modernization should be made and accepted.</p>
			</li>
			<li>
				<h2>Step 3</h2>
				<p>Run <code class="bash"><b>se</b> semanticate .</code> at the root of the project directory. Unlike <code>typogrify</code> or <code>modernize-spelling</code>, <code>semanticate</code> is more prone to error or false positives. Judicious use of the <code class="bash"><b>git</b> diff</code> command listed in Step 1 would be needed to prevent any unwanted changes.</p>
			</li>
			<li>
				<h2>Step 4</h2>
				<p>Run <code class="bash"><b>se</b> clean .</code> at the root of the project directory. Ideally the producer of the ebook would have ran this multiple times during their production process. However, since changes may have been made since then by the reviewer and stylistic deviations may be been inadvertently introduced, this will clean those potential errors up. After each step so far, it is recommended to use the <code class="bash"><b>git</b> diff</code> commanded listed in Step 1 to review all changes.</p>
			</li>
			<li>
				<h2>Step 5</h2>
				<p>Run both <code class="bash"><b>se</b> find-mismatched-dashes .</code> and <code class="bash"><b>se</b> find-mismatched-diacritics .</code> at the root of the project directory. Either of these tools will make actual changes to the project, but will list words that have inconsistent application of dashes/diacritics. Check with the language authority listed in the <a href="/manual/latest/single-page#8.2.9">Manual</a> to decide on the recommendations.</p>
			</li>
			<li>
				<h2>Step 6</h2>
				<p>Run <code class="bash"><b>se</b> build-title .</code>, <code class="bash"><b>se</b> build-toc .</code> and <code class="bash"><b>se</b> build-images .</code> at the root of the project directory. No changes should be made by these tools if the producer correctly generated the title page, table of content, and image files. Any discrepancies can be highlighted and investigated via the <code class="bash"><b>git</b> diff</code> listed in Step 1.</p>
			</li>
			<li>
				<h2>Step 7</h2>
				<p>Use the following <code class="bash"><b>git</b></code> command to highlight all editorial commits:</p>
				<code class="terminal"><b>git</b> log --pretty="format:%h: %s" --grep="\[Editorial\]"</code>
				<p>Once identified, these commits can be reviewed via:</p>
				<code class="terminal"><b>git</b> diff "$COMMIT~" "$COMMIT";</code>
				<p>Alternatively, a graphical <code class="bash"><b>git</b></code> client can be used instead.</p>
			</li>
			<li>
				<h2>Step 8</h2>
				<p>Run the following command to check for incorrect en dash use:</p>
				<code class="terminal"><b>grep</b> --recursive --line-number "[a-z]–[a-z]" .</code>
				<p>Note that the dash in between the two square bracket is an <em>en dash</em> (U+2013), and not a hyphen. Check the <a href="/manual/latest/single-page#8.7.7">Manual</a> for the correct type of dashes to use in different cases. Alternatively, use your text editor's regular expression search to find potential incorrect usage instead of <code class="bash"><b>grep</b></code>.</p>
			</li>
			<li>
				<h2>Step 9</h2>
				<p>Run the following command to example possible miscurled sing-quote marks:</p>
				<code class="terminal"><b>se</b> interactive-replace "(\s)‘([a-z])" "\1’\2"</code>
				<p>Note the use of <code>‘</code> (left single-quotation mark, U+2018) and <code>’</code> (right single-quotation mark, U+2019) in the command above, and not <code>'</code> or <code>`</code>. Using <code>interactive-replace</code> is the safest here as there are many potential false positive cases here that should not be change. Refer the the relevant section of the <a href="/manual/latest/single-page#8.7.5">Manual</a>.</p>
			</li>
			<li>
				<h2>Step 10</h2>
				<p>As noted in the <a href="/manual/latest/single-page#8.3.3">Manual</a> text in call caps is rarely correct. Use the following command to check for instance of all caps:</p>
				<code class="terminal"><b>se</b> xpath "//p//text()[re:test(., '[A-Z]{2,}') and not(contains(., 'OK') or contains(., 'SOS')) and not(parent::abbr or parent::var or parent::a or parent::*[contains(@epub:type, 'z3998:roman')])]" src/epub/text/*.xhtml</code>
				<p>If any such instances is found, check with the <a href="/manual/latest/single-page#8.3">Manual</a> for the correct capitalization style in all cases.</p>
			</li>
			<li>
				<h2>Step 11</h2>
				<p>Most of the time punctuation such as periods and commas should be included within quotations, except for some rare occasions. See the relevant section of the <a href="/manual/latest/single-page#8.7.2">Manual</a> for reference. Use the following command to see if any of these needs to be noted for change:</p>
				<code class="terminal"><b>se</b> interactive-replace "’([\.\,])" "\1’" .</code>
			</li>
			<li>
				<h2>Step 12</h2>
				<p>Era abbreviations do not have punctuation in them, unlike other common abbreviations (see <a href="/manual/latest/single-page#8.9.5">Manual</a>). Use the following command to check each of such cases:</p>
				<code class="terminal">se interactive-replace "(&lt;abbr epub:type=\"se:era[^\"]*?\"&gt;)(BC|AD)&lt;/abbr&gt;\." "\1\2&lt;/abbr&gt;" .</code>
			</li>
		</ol>
	</article>
</main>
<?= Template::Footer() ?>
