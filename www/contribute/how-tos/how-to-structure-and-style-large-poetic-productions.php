<?= Template::Header(title: 'How to Structure and Style Large Poetic Productions', isManual: true, highlight: 'contribute', description: 'A guide to formatting poetry collections, long narrative poems, and unusual poetic features.') ?>
<main class="manual">
	<article class="step-by-step-guide">
		<h1>How to Structure and Style Large Poetic Productions</h1>
		<p>The presentation of poems can take various styles and forms. Unlike prose, the structure of a poem adds additional meaning through indentations, line breaks, caesuras, spacing, and even the <i>shape</i> of the text. Here is a guide to help with some of poetry’s less intuitive formatting.</p>
		<aside class="alert">
			<p class="warning">Before you read</p>
			<p>
				<strong>Before consulting this guide, make sure to read the <a href="https://standardebooks.org/manual/latest">Standard Ebooks Manual of Style</a> on the structural patterns, semantics, and CSS of poetry, verse, and songs.</strong>
			</p>
		</aside>
		<details id="toc">
			<summary>Table of Contents</summary>
			<ol>
				<li><p><a href="#splitting-files">Splitting files</a></p></li>
				<ol>
					<li><p><a href="#a-single-collections-file">A single collections file</a></p></li>
					<li><p><a href="#multiple-files">Multiple files</a></p></li>
				</ol>
				<li><p><a href="#manual-transcription">Manual transcription</a></p></li>
				<li><p><a href="#modernizing-poetic-works">Modernizing poetic works</a></p></li>
				<ol>
					<li><p><a href="#modernize-prose-frontmatter-and-backmatter">Modernize prose frontmatter and backmatter</a></p></li>
					<li><p><a href="#modernize-poems-carefully">Modernize poems <i>carefully</i>!</a></p></li>
					<li><p><a href="#modernization-exceptions">Modernization exceptions</a></p></li>
					<ol>
						<li><p><a href="#hyphenations-and-spacings">Hyphenations and spacings</a></p></li>
						<li><p><a href="#mismatched-diacritics">Mismatched diacritics</a></p></li>
						<li><p><a href="#unusual-pronunciations">Unusual pronunciations</a></p></li>
						<li><p><a href="#eye-rhymes">Eye rhymes</a></p></li>
						<li><p><a href="#uncommon-contractions">Uncommon contractions</a></p></li>
						<li><p><a href="#archaisms">Archaisms</a></p></li>
					</ol>
				</ol>
				<li><p><a href="#unusual-formatting">Unusual formatting</a></p></li>
				<ol>
					<li><p><a href="#numbered-stanzas">Numbered stanzas</a></p></li>
					<li><p><a href="#verse-paragraphs">Verse paragraphs</a></p></li>
					<li><p><a href="#caesuras">Caesuras</a></p></li>
					<li><p><a href="#dropped-lines">Dropped lines</a></p></li>
				</ol>
			</ol>
		</details>
		<ol>
			<li>
				<h2 id="splitting-files">Splitting files</h2>
				<p>Unlike prose, short poems are not placed in individual files like chapters. The rule of thumb is to split poetic productions by the <i>highest-level division(s) possible</i>. A production can result in a single collections file of an author’s short poems or multiple files for longer poetic works, like long narrative poems, that have main divisions.</p>
				<h3 id="a-single-collections-file">A single collections file</h3>
				<p>For most poetry collections, they utilize a single collections file that contains all of an author’s short poems, ordered by date of publication. If the collection is made up of multiple books, you don’t need to group the poems by the books’ titles. This file is named <code class="path">poetry.xhtml</code> and has a <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element with the value <code class="string">Poetry</code>.</p>
				<figure class="html full">
<code class="html full"><span class="cp">&lt;?xml version="1.0" encoding="utf-8"?&gt;</span>
<span class="p">&lt;</span><span class="nt">html</span> <span class="na">xmlns</span><span class="o">=</span><span class="s">"http://www.w3.org/1999/xhtml"</span> <span class="na">xmlns:epub</span><span class="o">=</span><span class="s">"http://www.idpf.org/2007/ops"</span> <span class="na">epub:prefix</span><span class="o">=</span><span class="s">"z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: https://standardebooks.org/vocab/1.0"</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"en-US"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">head</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>Poetry<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">link</span> <span class="na">href</span><span class="o">=</span><span class="s">"../css/core.css"</span> <span class="na">rel</span><span class="o">=</span><span class="s">"stylesheet"</span> <span class="na">type</span><span class="o">=</span><span class="s">"text/css"</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">link</span> <span class="na">href</span><span class="o">=</span><span class="s">"../css/local.css"</span> <span class="na">rel</span><span class="o">=</span><span class="s">"stylesheet"</span> <span class="na">type</span><span class="o">=</span><span class="s">"text/css"</span><span class="p">/&gt;</span>
	<span class="p">&lt;/</span><span class="nt">head</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"bodymatter z3998:fiction"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">article</span> <span class="na">id</span><span class="o">=</span><span class="s">"imitation-of-spenser"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:poem"</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>Imitation of Spenser<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
			<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">article</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">article</span> <span class="na">id</span><span class="o">=</span><span class="s">"on-death"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:poem"</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>On Death<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
			<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">article</span><span class="p">&gt;</span>
		...
	<span class="p">&lt;/</span><span class="nt">body</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">html</span><span class="p">&gt;</span></code>
				</figure>
				<h3 id="multiple-files">Multiple files</h3>
				<p>In the world of poetry, long narrative poems are a unique form of expression that often use books or cantos as the main divisions, serving a similar purpose to chapters in a novel. These divisions are placed in their own individual files. These files have both <code class="bash"><span class="s">chapter</span></code> and <code class="bash"><span class="s">z3998:poem</span></code> semantics.</p>
				<figure class="html full">
<code class="html full"><span class="cp">&lt;?xml version="1.0" encoding="utf-8"?&gt;</span>
<span class="p">&lt;</span><span class="nt">html</span> <span class="na">xmlns</span><span class="o">=</span><span class="s">"http://www.w3.org/1999/xhtml"</span> <span class="na">xmlns:epub</span><span class="o">=</span><span class="s">"http://www.idpf.org/2007/ops"</span> <span class="na">epub:prefix</span><span class="o">=</span><span class="s">"z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: https://standardebooks.org/vocab/1.0"</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"en-GB"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">head</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>Book I<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">link</span> <span class="na">href</span><span class="o">=</span><span class="s">"../css/core.css"</span> <span class="na">rel</span><span class="o">=</span><span class="s">"stylesheet"</span> <span class="na">type</span><span class="o">=</span><span class="s">"text/css"</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">link</span> <span class="na">href</span><span class="o">=</span><span class="s">"../css/local.css"</span> <span class="na">rel</span><span class="o">=</span><span class="s">"stylesheet"</span> <span class="na">type</span><span class="o">=</span><span class="s">"text/css"</span><span class="p">/&gt;</span>
	<span class="p">&lt;/</span><span class="nt">head</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"bodymatter z3998:fiction"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"book-1"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"chapter z3998:poem"</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">h2</span><span class="p">&gt;</span>
					<span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"label"</span><span class="p">&gt;</span>Book<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
					<span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"ordinal z3998:roman"</span><span class="p">&gt;</span>I<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
				<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"bridgehead"</span><span class="p">&gt;</span>The Trojans, after a seven years’ voyage...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
			<span class="p">&lt;/</span><span class="nt">header</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
			<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">body</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">html</span><span class="p">&gt;</span></code>
				</figure>
			</li>
			<li>
				<h2 id="manual-transcription">Manual transcription</h2>
				<p>In some cases, working on a large omnibus compilation involves working with poems whose HTML transcriptions are crude or nonexistent. Transcribing poetry, while an advanced production technique, is somewhat more feasible than transcribing prose because of the much smaller amount of text compared to prose. However, poetry formatting (e.g. indentation) poses some challenges for transcription. Manually inserting the whole array of <code class="html"><span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span></code> elements and breaks would be unwieldy at best. Instead, it can be easier to use a shell script to convert temporary markup into the final desired HTML:</p>
				<code class="terminal">
					<span class="cp">#!/bin/bash</span>
					<br/>
					<span class="cp"># Repeat this descending pattern for more indentation levels</span>
					<span><b>se</b> interactive-replace <i>'v####(.*)'</i> <i>'&lt;span class="i3"&gt;\1&lt;/span&gt;&lt;br/&gt;'</i> $1</span>
					<span><b>se</b> interactive-replace <i>'v###(.*)'</i> <i>'&lt;span class="i2"&gt;\1&lt;/span&gt;&lt;br/&gt;'</i> $1</span>
					<span><b>se</b> interactive-replace <i>'v##(.*)'</i> <i>'&lt;span class="i1"&gt;\1&lt;/span&gt;&lt;br/&gt;'</i> $1</span>
					<span><b>se</b> interactive-replace <i>'v#(.*)'</i> <i>'&lt;span&gt;\1&lt;/span&gt;&lt;br/&gt;'</i> $1</span>
					<span><b>se</b> interactive-replace <i>'&lt;br/&gt;\s+&lt;/p&gt;'</i> <i>'&lt;/p&gt;'</i> $1</span>
					<span><b>se</b> clean $1</span>
				</code>
				<p>Here is an example of the temporary markup:</p>
				<figure class="html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
v#This line is not indented.
v##This line is at i1.
v###This line is at i2.
v####This line is at i3.
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code>
				</figure>
				<p>Running the script on this file produces this HTML:</p>
				<figure class="html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>This line is not indented.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">"i1"</span><span class="p">&gt;</span>This line is at i1.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">"i2"</span><span class="p">&gt;</span>This line is at i2.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">"i3"</span><span class="p">&gt;</span>This line is at i3.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code>
				</figure>
			</li>
			<li>
				<h2 id="modernizing-poetic-works">Modernizing poetic works</h2>
				<p>Do not run <code class="bash"><b>se</b> modernize-spelling <u>.</u></code> on poetry projects. Because changes can affect the rhythm of poetry, we generally do much less modernization than for other projects, so words like “to-day” and “some one” wouldn’t be modernized as they usually would be.</p>
				<aside class="alert">
					<p>If you’re not sure about whether it makes sense to modernize a specific word, please ask on the <a href="https://groups.google.com/g/standardebooks">mailing list</a>.</p>
				</aside>
				<h3 id="modernize-prose-frontmatter-and-backmatter">Modernize prose frontmatter and backmatter</h3>
				<p>Prose elements, such as poem titles, dedications, prefaces, and endnotes should have spelling, hyphenation, spacing in select words modernized as normal. See the step “<a href="https://standardebooks.org/contribute/producing-an-ebook-step-by-step#modernize">Modernizing Spelling and Hyphenation</a>” in the Step by Step guide.</p>
				<p>You can target specific files with the <code class="bash"><b>se</b> modernize-spelling</code> tool.</p>
				<code class="terminal">
					<span><b>se</b> modernize-spelling src/epub/text/preface.xhtml</span>
					<span><b>se</b> modernize-spelling src/epub/text/endnotes.xhtml</span>
				</code>
				<h3 id="modernize-poems-carefully">Modernize poems <i>carefully</i>!</h3>
				<p>The <code class="bash"><b>se</b> modernize-spelling</code> tool can modernize word spellings without dehyphenating words, but for poetry you must still carefully review each change to ensure it does not affect the sound of the line.</p>
				<code class="terminal"><span><b>se</b> modernize-spelling --no-hyphens src/epub/text/book<i class="glob">*</i></span></code>
				<h3 id="modernization-exceptions">Modernization exceptions</h3>
				<p>While there are no set guidelines, it’s worth noting that there are certain situations where it is advisable to preserve the original spelling. To help with this, here’s a list of specific scenarios where modernizing the spelling may be incorrect.</p>
				<ol>
					<li>
						<h4 id="hyphenations-and-spacings">Hyphenations and spacings</h4>
						<p>Hyphenations and spaces in poetry are fundamental elements that contribute to the visual and auditory experience of a poem. Hyphenations can serve to emphasize certain syllables or create rhythmic patterns within lines. The placement of spaces can influence the pacing, tone, and meaning of a poem. Together, they convey the poet’s intended message or evoke certain emotions in the reader. Modernizing spelling can erase a poet’s intended styling.</p>
						<aside class="tip">
							<p>If a poetic work is translated from its original language, dashes can be normalized.</p>
							<code class="terminal"><span><b>se</b> find-mismatched-dashes <u>.</u></span></code>
						</aside>
					</li>
					<li>
						<h4 id="mismatched-diacritics">Mismatched diacritics</h4>
						<p>In the realm of poetry, diacritics play an important role. They are utilized for multiple reasons, such as marking stresses on less common syllables, clarifying emphasis in cases where the distinction is metrically significant, or indicating the pronunciation of typically silent letters.</p>
						<p>The <code class="bash"><b>se</b> find-mismatched-diacritics</code> tool will flag these for review. Make sure to verify that the diacritics match the source scans.</p>
						<figure class="html full">
<code class="html full">...
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>The <b>wingèd</b> hounds of Winter ceased to bay.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>The stupor of a doom completed lay<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
...</code>
						</figure>
					</li>
					<li>
						<h4 id="unusual-pronunciations">Unusual pronunciations</h4>
						<p>In poetry, a perfect rhyme scheme is sometimes achieved by using archaic words that may have a different pronunciation from their modern counterparts. This technique enables the poet to maintain a consistent rhyme pattern throughout their work.</p>
						<p>In the example, <code class="bash"><b>se</b> modernize-spelling</code> will want to change “sate” to “sat” and “shew” to “show”, but these words are not sound-alike changes.</p>
						<figure class="html full">
<code class="html full">...
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Where Dankwart with his yeomen still at the table <b>sate</b>;<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>There rose between the heroes a strife of deadly <b>hate</b>.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
...
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Shone never on a braver scene than <b>that</b>.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Here was a prison, there a Man who <b>sat</b><span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
...
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>The flowers de luce, and the sparks of <b>dew</b><span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>That hung upon their azure leaves did <b>shew</b><span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
...
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Its solid truth seems change to <b>undergo</b>;<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Its largest square doth yet no corner <b>show</b><span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
...</code>
						</figure>
					</li>
					<li>
						<h4 id="eye-rhymes">Eye rhymes</h4>
						<p>In some cases, a poet is unable to create a perfect rhyme, resorting to an eye rhyme. An <i>eye rhyme</i>, also called a <i>visual rhyme</i>, is when two words are spelled similarly but pronounced differently.</p>
						<p>In the example, the poet uses the obsolete spelling of “die” to create a visual rhyme.</p>
						<figure class="html full">
<code class="html full">...
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>The beasts we daily see massacred <b>dy</b><span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>And men themselves do change <b>continually</b>,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
...</code>
						</figure>
					</li>
					<li>
						<h4 id="uncommon-contractions">Uncommon contractions</h4>
						<p>Poetry often employs uncommon contractions that are considered as an essential part of its style. These contractions are best left unchanged.</p>
						<figure class="html full">
<code class="html full">...
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Thanks, <b>i’ faith</b>, for silence is only commendable<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
...
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>That he is constant; for <b>i’faith</b> I swear,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
...
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Stand <b>to ’t</b> (quoth she) or yield to mercy:<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
...
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>I have bound thee <b>to’t</b> by death.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
...</code>
						</figure>
					</li>
					<li>
						<h4 id="archaisms">Archaisms</h4>
						<p>Some poets use archaisms to imitate very old or antiquated styles of English spellings to make the work seem older.</p>
						<figure class="html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>The Patron of true Holinesse<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Foule Errour doth defeate;<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Hypocrisie, him to entrappe,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Doth to his home entreate.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code>
						</figure>
					</li>
				</ol>
			</li>
			<li>
				<h2 id="unusual-formatting">Unusual formatting</h2>
				<p>Poetry is a diverse art form that offers a wide range of distinctive types, each with its own unique visual appeal. In fact, poetry can take on various unconventional formats that add to its artistic charm. Below are some uncommon formatting styles you may come across while producing a poetry production.</p>
				<h3 id="numbered-stanzas">Numbered stanzas</h3>
				<p>Hymns, songs, and occasionally epic poems have numbered sections for single or multiple stanzas. To format them correctly, place each stanza or group of stanzas, along with their numbered header, in a <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> element. All SE editions should center the header numbers, regardless of whether the stanza numbers are on the left or right side of the page scans. Use Roman numerals for stanza numbering up to 100. For numbers above 100, use Arabic numerals.</p>
				<figure class="html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"stanza-5"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"ordinal z3998:roman"</span><span class="p">&gt;</span>V<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">header</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
...
<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"stanza-256"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>256<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">header</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code>
				</figure>
				<h3 id="verse-paragraphs">Verse paragraphs</h3>
				<p>In poetic composition, some authors opt to use stanzas of varying length. While traditional poetry forms such as sonnets and haikus have a set structure, free verse poetry allows poets to experiment with different stanza lengths. To visually differentiate between stanzas, some poets choose to remove the blank space between them and instead indent the first line of each subsequent stanza (excluding the first stanza). This technique serves a similar function to paragraphs in prose.</p>
				<picture>
					<source srcset="images/verse-paragraphs@2x.avif 2x, images/verse-paragraphs.avif 1x" type="image/avif"/>
					<source srcset="images/verse-paragraphs@2x.jpg 2x, images/verse-paragraphs.jpg 1x" type="image/jpg"/>
					<img src="images/verse-paragraphs.jpg" alt="Each stanza is indented, similar to paragraphs."/>
				</picture>
				<p>With a clever CSS selector, you don’t need to add the <code class="bash"><span class="s">i1</span></code> class to each stanza. To remove spacing between stanzas, delete <code class="css"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:poem"</span><span class="o">]</span> <span class="nt">p</span> <span class="o">+</span> <span class="nt">p</span><span class="p">{</span> <span class="k">margin-top</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span> <span class="p">}</span></code> that’s provided by the SEMoS.</p>
				<figure class="html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>O Goddess! Sing the wrath of Peleus’ son,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>And great Achilles, parted first as foes.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Which of the gods put strife between the chiefs,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Of Atreus, the two leaders of the host:⁠—<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
...</code>
				</figure>
				<figure class="css full">
<code class="css full"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:poem"</span><span class="o">]</span> <span class="nt">p</span> <span class="o">+</span> <span class="nt">p</span> <span class="o">&gt;</span> <span class="nt">span</span><span class="p">:</span><span class="nd">first-child</span><span class="p">{</span>
	<span class="k">padding-left</span><span class="p">:</span> <span class="mi">2</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">-1</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span></code>
				</figure>
				<h3 id="caesuras">Caesuras</h3>
				<p>In poetry, a caesura is a pause or break in a verse that marks the end of one phrase and the beginning of another. This pause can be expressed by a large space and is used by poets to create a rhythmic effect in their work.</p>
				<picture>
					<source srcset="images/caesuras@2x.avif 2x, images/caesuras.avif 1x" type="image/avif"/>
					<source srcset="images/caesuras@2x.jpg 2x, images/caesuras.jpg 1x" type="image/jpg"/>
					<img src="images/caesuras.jpg" alt="Each line is split into two phrases separated by a large space."/>
				</picture>
				<p>In the files, wrap each phrase in a <code class="html"><span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span></code> element inside of the <code class="html"><span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span></code> element that represents the complete verse line.</p>
				<figure class="html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>To us, in olden legends,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>is many a marvel told<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Of praise-deserving heroes,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>of labours manifold,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Of weeping and of wailing,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>of joy and festival;<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Ye shall of bold knights’ battling<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>now hear a wondrous tale.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code>
				</figure>
				<figure class="css full">
<code class="css full"><span class="nt">span</span> <span class="o">&gt;</span> <span class="nt">span</span> <span class="o">+</span> <span class="nt">span</span><span class="p">{</span>
	<span class="k">margin-left</span><span class="p">:</span> <span class="mi">1.5</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span></code>
				</figure>
				<h3 id="dropped-lines">Dropped lines</h3>
				<p>A dropped line is a stylistic technique in which a single line of verse is divided into two or three distinct phrases. This technique is similar to the use of caesuras, although it differs in that the breaks do not appear in every line, and the phrases themselves are separated by line breaks rather than a large space.</p>
				<aside class="tip">
					<p>If a line of text is broken into <i>more than three phrases</i>, you should use the classes <code class="bash"><span class="s">i1</span></code>, <code class="bash"><span class="s">i2</span></code>, <code class="bash"><span class="s">i3</span></code>, etc. for indentation.</p>
				</aside>
				<p>When dealing with two separate phrases, the parent <code class="html"><span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span></code> has a <code class="html">class="dl2"</code> attribute.</p>
				<picture>
					<source srcset="images/dropped-lines-1@2x.avif 2x, images/dropped-lines-1.avif 1x" type="image/avif"/>
					<source srcset="images/dropped-lines-1@2x.jpg 2x, images/dropped-lines-1.jpg 1x" type="image/jpg"/>
					<img src="images/dropped-lines-1.jpg" alt="This is an example of a dropped line with two phrases."/>
				</picture>
				<figure class="html full">
<code class="html full">...
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Rooms in the house for sleeping. The old men<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
<span class="p">&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">"dl2"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>And ladies slept within the mansion.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Thaddeus<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Received command to lead the younger men<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
...</code>
				</figure>
				<figure class="css full">
<code class="css full"><span class="p">.</span><span class="nc">dl2</span> <span class="nt">span</span><span class="p">:</span><span class="nd">first-child</span><span class="p">{</span>
	<span class="k">vertical-align</span><span class="p">:</span> <span class="mi">100</span><span class="kt">%</span><span class="p">;</span>
<span class="p">}</span></code>
				</figure>
				<p>In the rare case that a line is broken into three phrases, the parent <code class="html"><span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span></code> has a <code class="html">class="dl3"</code> attribute.</p>
				<picture>
					<source srcset="images/dropped-lines-2@2x.avif 2x, images/dropped-lines-2.avif 1x" type="image/avif"/>
					<source srcset="images/dropped-lines-2@2x.jpg 2x, images/dropped-lines-2.jpg 1x" type="image/jpg"/>
					<img src="images/dropped-lines-2.jpg" alt="This is an example of a dropped line with three phrases."/>
				</picture>
				<figure class="html full">
<code class="html full">...
<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>But blow one blaring trumpet note of sun<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
<span class="p">&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">"dl3"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>To go with me<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>to the darkness<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>where I go.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
...</code>
				</figure>
				<figure class="css full">
<code class="css full"><span class="p">.</span><span class="nc">dl3</span> <span class="nt">span</span><span class="p">:</span><span class="nd">first-child</span><span class="p">{</span>
	<span class="k">vertical-align</span><span class="p">:</span> <span class="mi">200</span><span class="kt">%</span><span class="p">;</span>
<span class="p">}</span>

<span class="p">.</span><span class="nc">dl3</span> <span class="nt">span</span><span class="p">:</span><span class="nd">nth-child</span><span class="p">(</span><span class="mi">2</span><span class="p">)</span><span class="p">{</span>
	<span class="k">vertical-align</span><span class="p">:</span> <span class="mi">100</span><span class="kt">%</span><span class="p">;</span>
<span class="p">}</span></code>
				</figure>
			</li>
		</ol>
	</article>
</main>
<?= Template::Footer() ?>
