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
				<p>Run <code class="bash"><b>se</b> typogrify .</code> at the root of the project directory. The <code>typogrify</code> command is almost always correct, but sometimes not all changes it makes should be accepted. To go over the changes <code>typogrify</code> may have made, run the following <code><b>git</b></code> command to also highlight changes made to invisible or hard to differentiate Unicode characters:</p>
				<code class="terminal"><b>git</b> diff -U0 --word-diff-regex=.</code>
			</li>
			<li>
				<h2>Step 2</h2>
				<p>Run <code class="bash"><b>se</b> modernize-spelling .</code> at the root of the project directory. Note that this tool may not catch all archaic words. Prudent use of text editor spellcheckers can help picking up some of these. When in doubt, refer to the appropriate authority of spelling as noted in the <a href="/manual/latest/single-page#8.2.9">Manual</a>. Many words that are hyphenated in the past (<abbr>e.g.</abbr> <em>to-morrow</em>) are in modern times concatenated. <em>However</em>, these hyphens should all be retained in poetry . That said, obvious sound-alike spelling modernization should be made and accepted.</p>
			</li>
			<li>
				<h2>Step 3</h2>
				<p>Run <code class="bash"><b>se</b> semanticate .</code> at the root of the project directory. Unlike <code>typogrify</code> or <code>modernize-spelling</code>, <code>semanticate</code> is more prone to error or false positives. Judicious use of the <code><b>git</b> diff</code> command listed in Step 1 would be needed to prevent any unwanted changes.</p>
			</li>
			<li>
				<h2>Step 4</h2>
				<p>Run <code class="bash"><b>se</b> clean .</code> at the root of the project directory. Ideally the producer of the ebook would have ran this multiple times during their production process. However, since changes may have been made since then by the reviewer and stylistic deviations may be been inadvertently introduced, this will clean those potential errors up. After each step so far, it is recommended to use the <code><b>git</b> diff</code> commanded listed in Step 1 to review all changes.</p>
			</li>
		</ol>
	</article>
</main>
<?= Template::Footer() ?>
