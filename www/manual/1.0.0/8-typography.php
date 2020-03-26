<?
require_once('Core.php');
?><?= Template::Header(['title' => '8. Typography - The Standard Ebooks Manual', 'highlight' => 'contribute', 'manual' => true]) ?>
	<main class="manual"><nav><p><a href="/manual/1.0.0">The Standard Ebooks Manual of Style</a></p><ol><li><p><a href="/manual/1.0.0/1-code-style">1. XHTML, CSS, and SVG Code Style</a></p><ol><li><p><a href="/manual/1.0.0/1-code-style#1.1">1.1 XHTML formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.2">1.2 CSS formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.3">1.3 SVG Formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.4">1.4 Commits and Commit Messages</a></p></li></ol></li><li><p><a href="/manual/1.0.0/2-filesystem">2. Filesystem Layout and File Naming Conventions</a></p><ol><li><p><a href="/manual/1.0.0/2-filesystem#2.1">2.1 File locations</a></p></li><li><p><a href="/manual/1.0.0/2-filesystem#2.2">2.2 XHTML file naming conventions</a></p></li><li><p><a href="/manual/1.0.0/2-filesystem#2.3">2.3 The se-lint-ignore.xml file</a></p></li></ol></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook">3. The Structure of an Ebook</a></p><ol><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.1">3.1 Front matter</a></p></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.2">3.2 Body matter</a></p></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.3">3.3 Back matter</a></p></li></ol></li><li><p><a href="/manual/1.0.0/4-semantics">4. Semantics</a></p><ol><li><p><a href="/manual/1.0.0/4-semantics#4.1">4.1 Semantic Tags</a></p></li><li><p><a href="/manual/1.0.0/4-semantics#4.2">4.2 Semantic Inflection</a></p></li></ol></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns">5. General XHTML Patterns</a></p><ol><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.1">5.1 id attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.2">5.2 class attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.3">5.3 xml:lang attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.4">5.4 The &lt;title&gt; element</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.5">5.5 Ordered/numbered and unordered lists</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.6">5.6 Tables</a></p></li></ol></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns">6. Standard Ebooks Section Patterns</a></p><ol><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.1">6.1 The title string</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.2">6.2 The table of contents</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.3">6.3 The titlepage</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.4">6.4 The imprint</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.5">6.5 The half title page</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.6">6.6 The colophon</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.7">6.7 The Uncopyright</a></p></li></ol></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns">7. High Level Structural Patterns</a></p><ol><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.1">7.1 Sectioning</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.2">7.2 Headers</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.3">7.3 Dedications</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.4">7.4 Epigraphs</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.5">7.5 Poetry, verse, and songs</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.6">7.6 Plays and drama</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.7">7.7 Letters</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.8">7.8 Images</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.9">7.9 List of Illustrations (the LoI)</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.10">7.10 Endnotes</a></p></li></ol></li><li><p><a href="/manual/1.0.0/8-typography">8. Typography</a></p><ol><li><p><a href="/manual/1.0.0/8-typography#8.1">8.1 Section titles and ordinals</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.2">8.2 Italics</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.3">8.3 Capitalization</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.4">8.4 Indentation</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.5">8.5 Chapter headers</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.6">8.6 Ligatures</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.7">8.7 Punctuation and spacing</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.8">8.8 Numbers, measurements, and math</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.9">8.9 Latinisms</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.10">8.10 Initials and abbreviations</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.11">8.11 Times</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.12">8.12 Chemicals and compounds</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.13">8.13 Temperatures</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.14">8.14 Scansion</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.15">8.15 Legal cases and terms</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.16">8.16 Morse code</a></p></li></ol></li><li><p><a href="/manual/1.0.0/9-metadata">9. Metadata</a></p><ol><li><p><a href="/manual/1.0.0/9-metadata#9.1">9.1 General URL rules</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.2">9.2 The ebook identifier</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.3">9.3 Publication date and release identifiers</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.4">9.4 Book titles</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.5">9.5 Book subjects</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.6">9.6 Book descriptions</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.7">9.7 Book language</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.8">9.8 Book transcription and page scan sources</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.9">9.9 Additional book metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.10">9.10 General contributor rules</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.11">9.11 The author metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.12">9.12 The translator metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.13">9.13 The illustrator metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.14">9.14 The cover artist metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.15">9.15 Metadata for additional contributors</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.16">9.16 Transcriber metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.17">9.17 Producer metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.18">9.18 The ebook manifest</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.19">9.19 The ebook spine</a></p></li></ol></li><li><p><a href="/manual/1.0.0/10-art-and-images">10. Art and Images</a></p><ol><li><p><a href="/manual/1.0.0/10-art-and-images#10.1">10.1 Complete list of files</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.2">10.2 SVG patterns</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.3">10.3 The cover image</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.4">10.4 The titlepage image</a></p></li></ol></li></ol></nav>
		<article>

<section id="8"><aside class="number">8</aside>
<h1>Typography</h1>
<section id="8.1"><aside class="number">8.1</aside>
<h2>Section titles and ordinals</h2>
<ol type="1">
<li id="8.1.1"><aside class="number">8.1.1</aside><p>Section ordinals in the body text are set in Roman numerals.</p></li>
<li id="8.1.2"><aside class="number">8.1.2</aside><p>Section ordinals in a file’s <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element are set in Arabic numerals.
					</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>Chapter VII<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>Chapter 7<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.1.3"><aside class="number">8.1.3</aside><p>Section titles are titlecased according to the output of <code class="bash"><span>se</span> titlecase</code>. Section titles are <em>not</em> all-caps or small-caps.</p></li>
<li id="8.1.4"><aside class="number">8.1.4</aside><p>Section titles do not have trailing periods.</p></li>
<li id="8.1.5"><aside class="number">8.1.5</aside><p>Chapter titles omit the word <code class="html">Chapter</code>, unless the word used is a stylistic choice for prose style purposes. Chapters with unique identifiers (i.e. not <code class="html">Chapter</code>, but something unique to the style of the book, like <code class="html">Book</code> or <code class="html">Stave</code>) <em>do</em> include that unique identifier in the title.
					</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>Chapter <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:roman"</span><span class="p">&gt;</span>II<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title z3998:roman"</span><span class="p">&gt;</span>II<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>Stave <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:roman"</span><span class="p">&gt;</span>III<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code></figure>
<p>In special cases it may be desirable to retain <code class="html">Chapter</code> for clarity. For example, <a href="/ebooks/mary-shelley/frankenstein/">Frankenstein</a> has “Chapter” in titles to differentiate between the “Letter” sections.</p>
</li>
</ol>
</section>
<section id="8.2"><aside class="number">8.2</aside>
<h2>Italics</h2>
<ol type="1">
<li id="8.2.1"><aside class="number">8.2.1</aside><p>Using both italics <em>and</em> quotes (outside of the context of quoted dialog) is usually not necessary. Either one or the other is used, with rare exceptions.</p></li>
<li id="8.2.2"><aside class="number">8.2.2</aside><p>Words and phrases that require emphasis are italicized with the <code class="html"><span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span></code> element.
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Perhaps <span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span>he<span class="p">&lt;/</span><span class="nt">em</span><span class="p">&gt;</span> was there,” Raoul said, at last.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.2.3"><aside class="number">8.2.3</aside><p>Strong emphasis, like shouting, may be set in small caps with the <code class="html"><span class="p">&lt;</span><span class="nt">strong</span><span class="p">&gt;</span></code> element.
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“<span class="p">&lt;</span><span class="nt">strong</span><span class="p">&gt;</span>Can’t<span class="p">&lt;/</span><span class="nt">strong</span><span class="p">&gt;</span> I?” screamed the unhappy creature to himself.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.2.4"><aside class="number">8.2.4</aside><p>When a short phrase within a longer clause is italicized, trailing punctuation that may belong to the containing clause is not italicized.
					</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Look at <span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span>that!<span class="p">&lt;/</span><span class="nt">em</span><span class="p">&gt;</span>” she shouted.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Look at <span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span>that<span class="p">&lt;/</span><span class="nt">em</span><span class="p">&gt;</span>!” she shouted.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.2.5"><aside class="number">8.2.5</aside><p>When an entire clause is italicized, trailing punctuation <em>is</em> italicized, <em>unless</em> that trailing punctuation is a comma at the end of dialog.
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“<span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span>Charge!<span class="p">&lt;/</span><span class="nt">em</span><span class="p">&gt;</span>” she shouted.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“<span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span>But I want to<span class="p">&lt;/</span><span class="nt">em</span><span class="p">&gt;</span>,” she said.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.2.6"><aside class="number">8.2.6</aside><p>Words written to be read as sounds are italicized with <code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code>.
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He could hear the dog barking: <span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span>Ruff, ruff, ruff!<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
<section id="8.2.7"><aside class="number">8.2.7</aside>
<h3>Italicizing individual letters</h3>
<ol type="1">
<li id="8.2.7.1"><aside class="number">8.2.7.1</aside><p>Individual letters that are read as referring to letters in the alphabet are italicized with the <code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code> element.</p></li>
</ol>
<blockquote>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He often rolled his <span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span>r<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>’s.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</blockquote>
<ol type="1">
<li id="8.2.7.2"><aside class="number">8.2.7.2</aside><p>Individual letters that are read as referring names or the shape of the letterform are <em>not</em> italicized.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...due to the loss of what is known in New England as the “L”: that long deep roofed adjunct usually built at right angles to the main house...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>She was learning her A B Cs.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>His trident had the shape of an E.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.2.7.3"><aside class="number">8.2.7.3</aside><p>The ordinal <code class="html">nth</code> is set with an italicized <code class="html">n</code>, and without a hyphen.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The <span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span>n<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>th degree.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="8.2.8"><aside class="number">8.2.8</aside>
<h3>Italicizing non-English words and phrases</h3>
<ol type="1">
<li id="8.2.8.1"><aside class="number">8.2.8.1</aside><p>Non-English words and phrases that are not in <a href="https://www.merriam-webster.com">Merriam-Webster</a> are italicized.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The <span class="p">&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"fr"</span><span class="p">&gt;</span>corps de ballet<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span> was flung into consternation.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.2.8.2"><aside class="number">8.2.8.2</aside><p>Non-English words that are proper names, or are in proper names, are not italicized, unless the name itself would be italicized according to the rules for italicizing or quoting names and titles. Such words are wrapped in a <code class="html"><span class="p">&lt;</span><span class="nt">span</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"LANGUAGE"</span><span class="p">&gt;</span></code> element, to assist screen readers with pronunciation.
						</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>She got off the metro at the <span class="p">&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"fr"</span><span class="p">&gt;</span>Place de Clichy<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span> stop, next to the <span class="p">&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"fr"</span><span class="p">&gt;</span>Le Bon Petit Déjeuner restaurant<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“<span class="p">&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"fr"</span><span class="p">&gt;</span>Où est le métro?<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>” he asked, and she pointed to <span class="p">&lt;</span><span class="nt">span</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"fr"</span><span class="p">&gt;</span>Place de Clichy<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>, next to the <span class="p">&lt;</span><span class="nt">span</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"fr"</span><span class="p">&gt;</span>Le Bon Petit Déjeuner<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span> restaurant.</code></figure>
</li>
<li id="8.2.8.3"><aside class="number">8.2.8.3</aside><p>If certain non-English words are used so frequently in the text that italicizing them at each instance would be distracting to the reader, then only the first instance is italicized. Subsequent instances are wrapped in a <code class="html"><span class="p">&lt;</span><span class="nt">span</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"LANGUAGE"</span><span class="p">&gt;</span></code> element.</p></li>
<li id="8.2.8.4"><aside class="number">8.2.8.4</aside><p>Words and phrases that are originally non-English in origin, but that can now be found in <a href="https://www.merriam-webster.com">Merriam-Webster</a>, are not italicized.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Sir Percy’s bon mot had gone the round of the brilliant reception-rooms.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.2.8.5"><aside class="number">8.2.8.5</aside><p>Inline-level italics are set using the <code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code> element with an <code class="html">xml:lang</code> attribute corresponding to the correct <a href="https://en.wikipedia.org/wiki/IETF_language_tag">IETF language tag</a>.</p></li>
<li id="8.2.8.6"><aside class="number">8.2.8.6</aside><p>Block-level italics are set using an <code class="html">xml:lang</code> attribute on the closest encompassing block element, with the style of <code class="css"><span class="nt">font-style</span><span class="o">:</span> <span class="nt">italic</span></code>.
						</p><p>In this example, note the additional namespace declaration, and that we target <em>descendants</em> of the <code class="html"><span class="p">&lt;</span><span class="nt">body</span><span class="p">&gt;</span></code> element; otherwise, the entire <code class="html"><span class="p">&lt;</span><span class="nt">body</span><span class="p">&gt;</span></code> element would receive italics!</p>
<figure><code class="css full"><span class="p">@</span><span class="k">namespace</span> <span class="nt">xml</span> <span class="s2">"http://www.w3.org/XML/1998/namespace"</span><span class="p">;</span>

<span class="nt">body</span> <span class="o">[</span><span class="nt">xml</span><span class="o">|</span><span class="nt">lang</span><span class="o">]</span><span class="p">{</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">italic</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:verse"</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"la"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>—gelidas leto scrutata medullas,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Pulmonis rigidi stantes sine vulnere fibras<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Invenit, et vocem defuncto in corpore quaerit.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.2.8.7"><aside class="number">8.2.8.7</aside><p>Words that are in a non-English “alien” language (i.e. one that is made up, like in a science fiction or fantasy work) are italicized and given an IETF languate tag in a custom namespace. Custom namespaces begin consist of <code class="html">x-TAG</code>, where <code class="html">TAG</code> is a custom descriptor of 8 characters or less.</p></li>
</ol>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“<span class="p">&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"x-arcturan"</span><span class="p">&gt;</span>Dolm<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>,” said Haunte.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</section>
<section id="8.2.9"><aside class="number">8.2.9</aside>
<h3>Italicizing or quoting newly-used English words</h3>
<ol type="1">
<li id="8.2.9.1"><aside class="number">8.2.9.1</aside><p>When introducing new terms, non-English or technical terms are italicized, but terms composed of common English are set in quotation marks.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>English whalers have given this the name “ice blink.”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>

<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The soil consisted of that igneous gravel called <span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span>tuff<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.2.9.2"><aside class="number">8.2.9.2</aside><p>English neologisms in works where a special vocabulary is a regular part of the narrative are not italicized. For example science fiction works may necessarily contain made-up English technology words, and those are not italicized.</p></li>
</ol>
</section>
<section id="8.2.10"><aside class="number">8.2.10</aside>
<h3>Italics in names and titles</h3>
<ol type="1">
<li id="8.2.10.1"><aside class="number">8.2.10.1</aside><p>Place names, like pubs, bars, or buildings, are not quoted.</p></li>
<li id="8.2.10.2"><aside class="number">8.2.10.2</aside><p>The names of publications, music, and art that can stand alone are italicized; additionally, the names of transport vessels are italicized. These include, but are not limited to:
						</p><ul>
<li><p>Periodicals like magazines, newspapers, and journals.</p></li>
<li><p>Publications like books, novels, plays, and pamphlets, <em>except</em> “holy texts,” like the Bible or books within the Bible.</p></li>
<li><p>Long poems and ballads, like the <a href="/ebooks/homer/the-iliad/william-cullen-bryant">Iliad</a>, that are book-length.</p></li>
<li><p>Long musical compositions or audio, like operas, music albums, or radio shows.</p></li>
<li><p>Long visual art, like films or a TV show series.</p></li>
<li><p>Visual art, like paintings or sculptures.</p></li>
<li><p>Transport vessels, like ships.</p></li>
</ul>
</li>
<li id="8.2.10.3"><aside class="number">8.2.10.3</aside><p>The names of short publications, music, or art, that cannot stand alone and are typically part of a larger collection or work, are quoted. These include, but are not limited to:
						</p><ul>
<li><p>Short musical compositions or audio, like pop songs, arias, or an episode in a radio series.</p></li>
<li><p>Short prose like novellas, shot stories, or short (i.e. not epic) poems.</p></li>
<li><p>Chapter titles in a prose work.</p></li>
<li><p>Essays or individual articles in a newspaper or journal.</p></li>
<li><p>Short visual art, like short films or episodes in a TV series.</p></li>
</ul>
</li>
</ol>
<section id="examples">
<h4>Examples</h4>
<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He read “Candide” while having a pint at the “King’s Head.”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He read <span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"se:name.publication.book"</span><span class="p">&gt;</span>Candide<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span> while having a pint at the King’s Head.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</section>
</section>
<section id="8.2.11"><aside class="number">8.2.11</aside>
<h3>Taxonomy</h3>
<ol type="1">
<li id="8.2.11.1"><aside class="number">8.2.11.1</aside><p>Binomial names (generic, specific, and subspecific) are italicized with a <code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code> element having the <code class="html">z3998:taxonomy</code> semantic inflection.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>A bonobo monkey is <span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:taxonomy"</span><span class="p">&gt;</span>Pan paniscus<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.2.11.2"><aside class="number">8.2.11.2</aside><p>Family, order, class, phylum or division, and kingdom names are capitalized but not italicized.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>A bonobo monkey is in the phylum Chordata, class Mammalia, order Primates.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.2.11.3"><aside class="number">8.2.11.3</aside><p>If a taxonomic name is the same as the common name, it is not italicized.</p></li>
<li id="8.2.11.4"><aside class="number">8.2.11.4</aside><p>The second part of the binomial name follows the capitalization style of the source text. Modern usage requires lowercase, but older texts may set it in uppercase.</p></li>
</ol>
</section>
</section>
<section id="8.3"><aside class="number">8.3</aside>
<h2>Capitalization</h2>
<ol type="1">
<li id="8.3.1"><aside class="number">8.3.1</aside><p>In general, capitalization follows modern English style. Some very old works frequently capitalize nouns that today are no longer capitalized. These archaic capitalizations are removed, unless doing so would change the meaning of the work.</p></li>
<li id="8.3.2"><aside class="number">8.3.2</aside><p>Titlecasing, or the capitalization of titles, follows the formula used in the <code class="bash"><span>se</span> titlecase</code> tool.</p></li>
<li id="8.3.3"><aside class="number">8.3.3</aside><p>Text in all caps is almost never correct typography. Instead, such text is changed to the correct case and surround with a semantically-meaningful element like <code class="html"><span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span></code> (for emphasis), <code class="html"><span class="p">&lt;</span><span class="nt">strong</span><span class="p">&gt;</span></code> (for strong emphasis, like shouting) or <code class="html"><span class="p">&lt;</span><span class="nt">b</span><span class="p">&gt;</span></code> (for unsemantic formatting required by the text). <code class="html"><span class="p">&lt;</span><span class="nt">strong</span><span class="p">&gt;</span></code> and <code class="html"><span class="p">&lt;</span><span class="nt">b</span><span class="p">&gt;</span></code> are styled in small-caps by default in Standard Ebooks.
					</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The sign read BOB’S RESTAURANT.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“CHARGE!” he cried.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The sign read <span class="p">&lt;</span><span class="nt">b</span><span class="p">&gt;</span>Bob’s Restaurant<span class="p">&lt;/</span><span class="nt">b</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“<span class="p">&lt;</span><span class="nt">strong</span><span class="p">&gt;</span>Charge!<span class="p">&lt;/</span><span class="nt">strong</span><span class="p">&gt;</span>” he cried.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.3.4"><aside class="number">8.3.4</aside><p>When something is addressed as an <a href="https://www.merriam-webster.com/dictionary/apostrophe#dictionary-entry-2">apostrophe</a>, <code class="html">O</code> is capitalized.
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>I carried the bodies into the sea, O walker in the sea!<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="8.4"><aside class="number">8.4</aside>
<h2>Indentation</h2>
<ol type="1">
<li id="8.4.1"><aside class="number">8.4.1</aside><p>Paragraphs that directly follow another paragraph are indented by 1em.</p></li>
<li id="8.4.2"><aside class="number">8.4.2</aside><p>The first line of body text in a section, or any text following a visible break in text flow (like a header, a scene break, a figure, a block quotation, etc.), is not indented.
					</p><p>For example: in a block quotation, there is a margin before the quotation and after the quotation. Thus, the first line of the quotation is not indented, and the first line of body text after the block quotation is also not indented.</p>
</li>
</ol>
</section>
<section id="8.5"><aside class="number">8.5</aside>
<h2>Chapter headers</h2>
<ol type="1">
<li id="8.5.1"><aside class="number">8.5.1</aside><p>Epigraphs in chapters have the quote source set in small caps, without a leading em-dash and without a trailing period.
					</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title z3998:roman"</span><span class="p">&gt;</span>II<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"epigraph"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Desire no more than to thy lot may fall. …”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">cite</span><span class="p">&gt;</span>—Chaucer.<span class="p">&lt;/</span><span class="nt">cite</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">header</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="css full"><span class="nt">header</span> <span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"epigraph"</span><span class="o">]</span> <span class="nt">cite</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title z3998:roman"</span><span class="p">&gt;</span>II<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"epigraph"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Desire no more than to thy lot may fall. …”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">cite</span><span class="p">&gt;</span>Chaucer<span class="p">&lt;/</span><span class="nt">cite</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">header</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="8.6"><aside class="number">8.6</aside>
<h2>Ligatures</h2>
<p>Ligatures are two or more letters that are combined into a single letter, usually for stylistic purposes. In general they are not used, and are replaced with their respective characters.</p>
<blockquote>
<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Œdipus Rex<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Archæology<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Oedipus Rex<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Archaeology<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</blockquote>
</section>
<section id="8.7"><aside class="number">8.7</aside>
<h2>Punctuation and spacing</h2>
<ol type="1">
<li id="8.7.1"><aside class="number">8.7.1</aside><p>Sentences are single-spaced.</p></li>
<li id="8.7.2"><aside class="number">8.7.2</aside><p>Periods and commas are placed within quotation marks; i.e. American-style punctuation is used, not logical (AKA “British” or “new”) style.
					</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Bosinney ventured: “It’s the first spring day”.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Bosinney ventured: “It’s the first spring day.”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.7.3"><aside class="number">8.7.3</aside><p>Ampersands in names of things, like firms, are surrounded by no-break spaces (<span class="utf"> </span> or U+00A0).
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The firm of Hawkins<span class="ws">nbsp</span><span class="ni">&amp;amp;</span><span class="ws">nbsp</span>Harker.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.7.4"><aside class="number">8.7.4</aside><p>Some older works include spaces in common contractions; these spaces are removed.
					
					</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Would n’t it be nice to go out? It ’s such a nice day.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Wouldn’t it be nice to go out? It’s such a nice day.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
<section id="8.7.5"><aside class="number">8.7.5</aside>
<h3>Quotation marks</h3>
<ol type="1">
<li id="8.7.5.1"><aside class="number">8.7.5.1</aside><p>“Curly” or typographer’s quotes, both single and double, are always used instead of straight quotes. This is known as “American-style” quotation, which is different from British-style quotation which is also commonly found in both older and modern books.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Don’t do it!” she shouted.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.7.5.2"><aside class="number">8.7.5.2</aside><p>Quotation marks that are directly side-by-side are separated by a hair space (<span class="utf"> </span> or U+200A) character.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“<span class="ws">hairsp</span>‘Green?’ Is that what you said?” asked Dave.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.7.5.3"><aside class="number">8.7.5.3</aside><p>Words with missing letters represent the missing letters with a right single quotation mark (<code class="utf">’</code> or U+2019) character to indicate elision.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He had pork ’n’ beans for dinner<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="8.7.6"><aside class="number">8.7.6</aside>
<h3>Ellipses</h3>
<ol type="1">
<li id="8.7.6.1"><aside class="number">8.7.6.1</aside><p>The ellipses glyph (<code class="utf">…</code> or U+2026) is used for ellipses, instead of consecutive or spaced periods.</p></li>
<li id="8.7.6.2"><aside class="number">8.7.6.2</aside><p>When ellipses are used as suspension points (for example, to indicate dialog that pauses or trails off), the ellipses are not preceded by a comma.
						</p><p>Ellipses used to indicate missing words in a quotation require keeping surrounding punctuation, including commas, as that punctuation is in the original quotation.</p>
</li>
<li id="8.7.6.3"><aside class="number">8.7.6.3</aside><p>A hair space (<span class="utf"> </span> or U+200A) glyph is located <em>before</em> all ellipses that are not directly preceded by punctuation, or that are directly preceded by an em-dash or a two- or three-em-dash.</p></li>
<li id="8.7.6.4"><aside class="number">8.7.6.4</aside><p>A regular space is located <em>after</em> all ellipses that are not followed by punctuation.</p></li>
<li id="8.7.6.5"><aside class="number">8.7.6.5</aside><p>A hair space (<span class="utf"> </span> or U+200A) glyph is located between an ellipses and any punctuation that follows directly after the ellipses, <em>unless</em> that punctuation is a quotation mark, in which case there is no space at all between the ellipses and the quotation mark.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“I’m so hungry<span class="ws">hairsp</span>… What were you saying about eating<span class="ws">hairsp</span>…?”</code></figure>
</li>
</ol>
</section>
<section id="8.7.7"><aside class="number">8.7.7</aside>
<h3>Dashes</h3>
<p>There are many kinds of dashes, and the run-of-the-mill hyphen is often not the correct dash to use. In particular, hyphens are not used for things like date ranges, phone numbers, or negative numbers.</p>
<ol type="1">
<li id="8.7.7.1"><aside class="number">8.7.7.1</aside><p>Dashes of all types do not have white space around them.</p></li>
<li id="8.7.7.2"><aside class="number">8.7.7.2</aside><p>Figure dashes (<code class="utf">‒</code> or U+2012) are used to indicate a dash in numbers that aren’t a range, like phone numbers.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>His number is 555‒1234.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.7.7.3"><aside class="number">8.7.7.3</aside><p>Hyphens (<code class="utf">-</code> or U+002D) are used to join words, including double-barrel names, or to separate syllables in a word.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Pre- and post-natal.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The Smoot-Hawley act.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.7.7.4"><aside class="number">8.7.7.4</aside><p>Minus sign glyphs (<code class="utf">−</code> or U+2212) are used to indicate negative numbers, and are used in mathematical equations instead of hyphens to represent the “subtraction” operator.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>It was −5° out yesterday!<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>5 − 2 = 3<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.7.7.5"><aside class="number">8.7.7.5</aside><p>En-dashes (<code class="utf">–</code> or U+2013) are used to indicate a numerical or date range; to indicate a relationships where two concepts are connected by the word “to,” for example a distance between locations or a range between numbers; or to indicate a connection in location between two places.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>We talked 2–3 days ago.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>We took the Berlin–Munich train yesterday.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>I saw the torpedo-boat in the Ems⁠–⁠Jade Canal.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
<section id="8.7.7.6"><aside class="number">8.7.7.6</aside>
<h4>Em-dashes</h4>
<p>Em-dashes (<code class="utf">—</code> or U+2014) are typically used to offset parenthetical phrases.</p>
<ol type="1">
<li id="8.7.7.6.1"><aside class="number">8.7.7.6.1</aside><p>Em-dashes are preceded by the invisible word joiner glyph (U+2060).</p></li>
<li id="8.7.7.6.2"><aside class="number">8.7.7.6.2</aside><p>Interruption in dialog is set by a single em-dash, not two em-dashes or a two-em-dash.
							</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“I wouldn’t go as far as that, not myself, but<span class="ws">wj</span>——”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“I wouldn’t go as far as that, not myself, but<span class="ws">wj</span>—”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="8.7.7.7"><aside class="number">8.7.7.7</aside>
<h4>Partially-obscured words</h4>
<ol type="1">
<li id="8.7.7.7.1"><aside class="number">8.7.7.7.1</aside><p>Em-dashes are used for partially-obscured years.
							</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>It was the year 19<span class="ws">wj</span>— in the town of Metropolis.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.7.7.7.2"><aside class="number">8.7.7.7.2</aside><p>A regular hyphen is used in partially obscured years where only the last number is obscured.
							</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>It was the year 192- in the town of Metropolis.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.7.7.7.3"><aside class="number">8.7.7.7.3</aside><p>A two-em-dash (<code class="utf">⸺</code> or U+2E3A) preceded by a word joiner glyph (U+2060) is used in <em>partially</em> obscured word.
							</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Sally J<span class="ws">wj</span>⸺ walked through town.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.7.7.7.4"><aside class="number">8.7.7.7.4</aside><p>A three-em-dash (<code class="utf">⸻</code> or U+2E3B) is used for <em>completely</em> obscured words.
							</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>It was night in the town of ⸻.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
</section>
</section>
<section id="8.8"><aside class="number">8.8</aside>
<h2>Numbers, measurements, and math</h2>
<ol type="1">
<li id="8.8.1"><aside class="number">8.8.1</aside><p>Coordinates are set with the prime (<code class="utf">′</code> or U+2032) or double prime (<code class="utf">″</code> or U+2033) glyphs, <em>not</em> single or double quotes.
					</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span>Lat.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> 27° 0' <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"compass"</span><span class="p">&gt;</span>N.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>, <span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span>long.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> 20° 1' <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"compass eoc"</span><span class="p">&gt;</span>W.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>

<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span>Lat.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> 27° 0’ <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"compass"</span><span class="p">&gt;</span>N.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>, <span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span>long.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> 20° 1’ <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"compass eoc"</span><span class="p">&gt;</span>W.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span>Lat.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> 27° 0′ <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"compass"</span><span class="p">&gt;</span>N.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>, <span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span>long.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> 20° 1′ <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"compass eoc"</span><span class="p">&gt;</span>W.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.8.2"><aside class="number">8.8.2</aside><p>Ordinals for Arabic numbers are as follows: st, nd, rd, th.
					</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The 1st, 2d, 3d, 4th.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The 1st, 2nd, 3rd, 4th.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
<section id="8.8.3"><aside class="number">8.8.3</aside>
<h3>Roman numerals</h3>
<ol type="1">
<li id="8.8.3.1"><aside class="number">8.8.3.1</aside><p>Roman numerals are not followed by trailing periods, except for grammatical reasons.</p></li>
<li id="8.8.3.2"><aside class="number">8.8.3.2</aside><p>Roman numerals are set using ASCII, not the Unicode Roman numeral glyphs.</p></li>
<li id="8.8.3.3"><aside class="number">8.8.3.3</aside><p>Roman numerals are not followed by ordinal indicators.
						</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Henry the <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:roman"</span><span class="p">&gt;</span>VIII<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>th had six wives.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Henry the <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:roman"</span><span class="p">&gt;</span>VIII<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span> had six wives.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="8.8.4"><aside class="number">8.8.4</aside>
<h3>Fractions</h3>
<ol type="1">
<li id="8.8.4.1"><aside class="number">8.8.4.1</aside><p>Fractions are set in their appropriate Unicode glyph, if a glyph available; for example, <code class="utf">½</code>, <code class="utf">¼</code>, <code class="utf">¾</code> and U+00BC–U+00BE and U+2150–U+2189.&lt;/p&gt;
						</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>I need 1/4 cup of sugar.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>I need ¼ cup of sugar.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.8.4.2"><aside class="number">8.8.4.2</aside><p>If a fraction doesn’t have a corresponding Unicode glyph, it is composed using the fraction slash Unicode glyph (<code class="utf">⁄</code> or U+2044) and superscript/subscript Unicode numbers. See <a href="https://en.wikipedia.org/wiki/Unicode_subscripts_and_superscripts">this Wikipedia entry for more details</a>.
						</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Roughly 6/10 of a mile.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Roughly ⁶⁄₁₀ of a mile.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="8.8.5"><aside class="number">8.8.5</aside>
<h3>Measurements</h3>
<ol type="1">
<li id="8.8.5.1"><aside class="number">8.8.5.1</aside><p>Dimension measurements are set using the Unicode multiplication glyph (<code class="utf">×</code> or U+00D7), <em>not</em> the ASCII letter <code class="utf">x</code> or <code class="utf">X</code>.
						</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The board was 4 x 3 x 7 feet.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The board was 4 × 3 × 7 feet.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.8.5.2"><aside class="number">8.8.5.2</aside><p>Feet and inches in shorthand are set using the prime (<code class="utf">′</code> or U+2032) or double prime (<code class="utf">″</code> or U+2033) glyphs (<em>not</em> single or double quotes), with a no-break space (<span class="utf"> </span> or U+00A0) separating consecutive feet and inch measurements.
						</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He was 6'<span class="ws">nbsp</span>1" in height.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>

<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He was 6’<span class="ws">nbsp</span>1” in height.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He was 6′<span class="ws">nbsp</span>1″ in height.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.8.5.3"><aside class="number">8.8.5.3</aside><p>When forming a compound of a number and unit of measurement in which the measurement is abbreviated, the number and unit of measurement are separated with a no-break space (<span class="utf"> </span> or U+00A0), <em>not</em> a dash.
						</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>A 12-mm pistol.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>A 12<span class="ws">nbsp</span>mm pistol.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="8.8.6"><aside class="number">8.8.6</aside>
<h3>Math</h3>
<ol type="1">
<li id="8.8.6.1"><aside class="number">8.8.6.1</aside><p>In works that are not math-oriented or that dont’t have a significant amount of mathematical equations, equations are set using regular HTML and Unicode.
						</p><ol type="1">
<li id="8.8.6.1.1"><aside class="number">8.8.6.1.1</aside><p>Operators and operands in mathematical equations are separated by a space.
								</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>6−2+2=6<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>6 − 2 + 2 = 6<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.8.6.1.2"><aside class="number">8.8.6.1.2</aside><p>Operators like subtraction (<code class="utf">−</code> or U+2212), multiplication (<code class="utf">×</code> or U+00D7), and equivalence (<code class="utf">≡</code> or U+2261) are set using their corresponding Unicode glyphs, <em>not</em> a hyphen or <code class="utf">x</code>. Almost all mathematical operators have a corresponding special Unicode glyph.
								</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>6 -  2 x 2 == 2<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>6 − 2 × 2 ≡ 2<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</li>
<li id="8.8.6.2"><aside class="number">8.8.6.2</aside><p>In works that are math-oriented or that have a significant amount of math, <em>all</em> variables, equations, and other mathematical objects are set using MathML.
						</p><ol type="1">
<li id="8.8.6.2.1"><aside class="number">8.8.6.2.1</aside><p>When MathML is used in a file, the <code class="html">m</code> namespace is declared at the top of the file and used for all subsequent MathML code, as follows:
								</p><figure><code class="html full">xmlns:m="http://www.w3.org/1998/Math/MathML"</code></figure>
<p>This namespace is declared and used even if there is just a single MathML equation in a file.</p>
<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">html</span> <span class="na">xmlns</span><span class="o">=</span><span class="s">"http://www.w3.org/1999/xhtml"</span> <span class="na">xmlns:epub</span><span class="o">=</span><span class="s">"http://www.idpf.org/2007/ops"</span> <span class="na">ub:prefix</span><span class="o">=</span><span class="s">"z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: https://standardebooks.org/vocab/1.0"</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"en-GB"</span><span class="p">&gt;</span>
...
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">math</span> <span class="na">xmlns</span><span class="o">=</span><span class="s">"http://www.w3.org/1998/Math/MathML"</span> <span class="na">alttext</span><span class="o">=</span><span class="s">"x"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">ci</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">ci</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">html</span> <span class="na">xmlns</span><span class="o">=</span><span class="s">"http://www.w3.org/1999/xhtml"</span> <span class="na">xmlns:epub</span><span class="o">=</span><span class="s">"http://www.idpf.org/2007/ops"</span> <span class="na">xmlns:m</span><span class="o">=</span><span class="s">"http://www.w3.org/1998/Math/MathML"</span> <span class="na">epub:prefix</span><span class="o">=</span><span class="s">"z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: https://standardebooks.org/vocab/1.0"</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"en-GB"</span><span class="p">&gt;</span>
...
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">"x"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:ci</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:ci</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.8.6.2.2"><aside class="number">8.8.6.2.2</aside><p>When possible, Content MathML is used over Presentational MathML. (This may not always be possible depending on the complexity of the work.)
								</p><figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">"x + 1 = y"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:apply</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:equals</span><span class="p">/&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:apply</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">m:plus</span><span class="p">/&gt;</span>
				<span class="p">&lt;</span><span class="nt">m:ci</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:ci</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">m:cn</span><span class="p">&gt;</span>1<span class="p">&lt;/</span><span class="nt">m:cn</span><span class="p">&gt;</span>
			<span class="p">&lt;/</span><span class="nt">m:apply</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:ci</span><span class="p">&gt;</span>y<span class="p">&lt;/</span><span class="nt">m:ci</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">m:apply</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.8.6.2.3"><aside class="number">8.8.6.2.3</aside><p>Each <code class="html"><span class="p">&lt;</span><span class="nt">m:math</span><span class="p">&gt;</span></code> element has an <code class="html">alttext</code> attribute.
								</p><ol type="1">
<li id="8.8.6.2.3.1"><aside class="number">8.8.6.2.3.1</aside><p>The <code class="html">alttext</code> attribute describes the contents in the element in plain-text Unicode according to the rules in <a href="https://www.unicode.org/notes/tn28/UTN28-PlainTextMath.pdf">this specification</a>.</p></li>
<li id="8.8.6.2.3.2"><aside class="number">8.8.6.2.3.2</aside><p>Operators in the <code class="html">alttext</code> attribute are surrounded by a single space.
										</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">"x+1=y"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:apply</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:equals</span><span class="p">/&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:apply</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">m:plus</span><span class="p">/&gt;</span>
				<span class="p">&lt;</span><span class="nt">m:ci</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:ci</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">m:cn</span><span class="p">&gt;</span>1<span class="p">&lt;/</span><span class="nt">m:cn</span><span class="p">&gt;</span>
			<span class="p">&lt;/</span><span class="nt">m:apply</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:ci</span><span class="p">&gt;</span>y<span class="p">&lt;/</span><span class="nt">m:ci</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">m:apply</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">"x + 1 = y"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:apply</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:equals</span><span class="p">/&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:apply</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">m:plus</span><span class="p">/&gt;</span>
				<span class="p">&lt;</span><span class="nt">m:ci</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:ci</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">m:cn</span><span class="p">&gt;</span>1<span class="p">&lt;/</span><span class="nt">m:cn</span><span class="p">&gt;</span>
			<span class="p">&lt;/</span><span class="nt">m:apply</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:ci</span><span class="p">&gt;</span>y<span class="p">&lt;/</span><span class="nt">m:ci</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">m:apply</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</li>
<li id="8.8.6.2.4"><aside class="number">8.8.6.2.4</aside><p>When using Presentational MathML, <code class="html"><span class="p">&lt;</span><span class="nt">m:mrow</span><span class="p">&gt;</span></code> is used to group subexpressions, but only when necessary. Many elements in MathML, like <code class="html"><span class="p">&lt;</span><span class="nt">m:math</span><span class="p">&gt;</span></code> and <code class="html"><span class="p">&lt;</span><span class="nt">m:mtd</span><span class="p">&gt;</span></code>, <em>imply</em> <code class="html"><span class="p">&lt;</span><span class="nt">m:mrow</span><span class="p">&gt;</span></code>, and redundant elements are not desirable. See <a href="https://www.w3.org/Math/draft-spec/mathml.html#chapter3_presm.reqarg">this section of the MathML spec</a> for more details.
								</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">"x"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mrow</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">m:mrow</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">"x"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.8.6.2.5"><aside class="number">8.8.6.2.5</aside><p>If a Presentational MathML expression contains a function, the invisible Unicode function application glyph (U+2061) is used as an operator between the function name and its operand. This element looks exactly like the following, including the comment for readability: <code class="html"><span class="p">&lt;</span><span class="nt">m:mo</span><span class="p">&gt;</span>⁡<span class="c">&lt;!--hidden U+2061 function application--&gt;</span><span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span></code>. (Note that the preceding element contains an <em>invisible</em> Unicode character! It can be revealed with the <code class="bash"><span>se</span> unicode-names</code> tool.)
								</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">"f(x)"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>f<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:row</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mo</span> <span class="na">fence</span><span class="o">=</span><span class="s">"true"</span><span class="p">&gt;</span>(<span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mo</span> <span class="na">fence</span><span class="o">=</span><span class="s">"true"</span><span class="p">&gt;</span>)<span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">m:row</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">"f(x)"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>f<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mo</span><span class="p">&gt;</span>⁡<span class="utf">U+2061</span><span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span><span class="c">&lt;!--hidden U+2061 function application--&gt;</span><span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:row</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mo</span> <span class="na">fence</span><span class="o">=</span><span class="s">"true"</span><span class="p">&gt;</span>(<span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mo</span> <span class="na">fence</span><span class="o">=</span><span class="s">"true"</span><span class="p">&gt;</span>)<span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">m:row</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.8.6.2.6"><aside class="number">8.8.6.2.6</aside><p>Expressions grouped by parenthesis or brackets are wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">m:row</span><span class="p">&gt;</span></code> element, and fence characters are set using the <code class="html"><span class="p">&lt;</span><span class="nt">m:mo</span> <span class="na">fence</span><span class="o">=</span><span class="s">"true"</span><span class="p">&gt;</span></code> element. Separators are set using the <code class="html"><span class="p">&lt;</span><span class="nt">m:mo</span> <span class="na">separator</span><span class="o">=</span><span class="s">"true"</span><span class="p">&gt;</span></code> element. <code class="html"><span class="p">&lt;</span><span class="nt">m:mfenced</span><span class="p">&gt;</span></code>, which used to imply both fences and separators, is deprecated in the MathML spec and thus is not used.
								</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">"f(x,y)"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>f<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mo</span><span class="p">&gt;</span>⁡<span class="utf">U+2061</span><span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span><span class="c">&lt;!--hidden U+2061 function application--&gt;</span><span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:fenced</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>y<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">m:fenced</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">"f(x,y)"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>f<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mo</span><span class="p">&gt;</span>⁡<span class="utf">U+2061</span><span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span><span class="c">&lt;!--hidden U+2061 function application--&gt;</span><span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:row</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mo</span> <span class="na">fence</span><span class="o">=</span><span class="s">"true"</span><span class="p">&gt;</span>(<span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mo</span> <span class="na">separator</span><span class="o">=</span><span class="s">"true"</span><span class="p">&gt;</span>,<span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mo</span> <span class="na">fence</span><span class="o">=</span><span class="s">"true"</span><span class="p">&gt;</span>)<span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">m:row</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.8.6.2.7"><aside class="number">8.8.6.2.7</aside><p>If a MathML variable includes an overline, it is set by combining the variable’s normal Unicode glyph and the Unicode overline glyph (<code class="utf">‾</code> or U+203E) in a <code class="html"><span class="p">&lt;</span><span class="nt">m:mover</span><span class="p">&gt;</span></code> element. However in the <code class="html">alttext</code> attribute, the Unicode overline combining mark (U+0305) is used to represent the overline in Unicode.
								</p><figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">"x̅"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mover</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mo</span><span class="p">&gt;</span>‾<span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">m:mover</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</li>
</ol>
</section>
</section>
<section id="8.9"><aside class="number">8.9</aside>
<h2>Latinisms</h2>
<ul>
<li><p><a href="/manual/1.0.0/8-typography#8.11">See here for times</a>.</p></li>
</ul>
<ol type="1">
<li id="8.9.1"><aside class="number">8.9.1</aside><p>Latinisms (except <i>sic</i>) that can be found in a modern dictionary are not italicized. Examples include e.g., i.e., ad hoc, viz., ibid., etc.. The exception is <i>sic</i>, which is always italicized.</p></li>
<li id="8.9.2"><aside class="number">8.9.2</aside><p>Whole passages of Latin language and Latinisms that aren’t found in a modern dictionary are italicized.</p></li>
<li id="8.9.3"><aside class="number">8.9.3</aside><p>“&amp;c;” is not used, and is replaced with “etc.”</p></li>
<li id="8.9.4"><aside class="number">8.9.4</aside><p>For “Ibid.,” <a href="/manual/1.0.0/7-high-level-structural-patterns#7.9">see Endnotes</a>.</p></li>
<li id="8.9.5"><aside class="number">8.9.5</aside><p>Latinisms that are abbreviations are set in lowercase with periods between words and no spaces between them, except BC, AD, BCE, and CE, which are set without periods, in small caps, and wrapped with <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"era"</span><span class="p">&gt;</span></code>:
					</p><figure><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">era</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Julius Caesar was born around 100 <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"era"</span><span class="p">&gt;</span>BC<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="8.10"><aside class="number">8.10</aside>
<h2>Initials and abbreviations</h2>
<ul>
<li><p><a href="/manual/1.0.0/8-typography#8.13">See here for temperatures</a>.</p></li>
<li><p><a href="/manual/1.0.0/8-typography#8.11">See here for times</a>.</p></li>
<li><p><a href="/manual/1.0.0/8-typography#8.9">See here for Latinisms including BC and AD</a>.</p></li>
</ul>
<ol type="1">
<li id="8.10.1"><aside class="number">8.10.1</aside><p>“OK” is set without periods or spaces. It is not an abbreviation.</p></li>
<li id="8.10.2"><aside class="number">8.10.2</aside><p>When an abbreviation contains a terminal period, its <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span></code> tag has the additional <code class="html">eoc</code> class (End of Clause) if the terminal period is also the last period in clause. Such sentences do not have two consecutive periods.
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>She loved Italian food like pizza, pasta, <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"eoc"</span><span class="p">&gt;</span>etc.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He lists his name alphabetically as Johnson, <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"name eoc"</span><span class="p">&gt;</span>R. A.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>His favorite hobby was <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"acronym"</span><span class="p">&gt;</span>SCUBA<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.10.3"><aside class="number">8.10.3</aside><p>Initialisms, postal codes, and abbreviated US states are set in all caps, without periods or spaces.</p></li>
<li id="8.10.4"><aside class="number">8.10.4</aside><p>Acronyms (terms made up of initials and pronounced as one word, like NASA, SCUBA, or NATO) are set in small caps, without periods, and are wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"acronym"</span><span class="p">&gt;</span></code> element.
					</p><figure><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">acronym</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He was hired by <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"acronym"</span><span class="p">&gt;</span>NASA<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> last week.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.10.5"><aside class="number">8.10.5</aside><p>Initialisms (terms made up of initials in which each initial is pronounced separately, like ABC, HTML, or CSS) are set with without periods and are wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"initialism"</span><span class="p">&gt;</span></code> element.
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He was hired by the <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"initialism"</span><span class="p">&gt;</span>FBI<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> last week.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.10.6"><aside class="number">8.10.6</aside><p>Initials of people’s names are each separated by periods and spaces. The group of initials is wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"name"</span><span class="p">&gt;</span></code> element.
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"name"</span><span class="p">&gt;</span>H. P.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> Lovecraft described himself as an aged antiquarian.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.10.7"><aside class="number">8.10.7</aside><p>Academic degrees, except ones that include a lowercase letter (like PhD) are wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"degree"</span><span class="p">&gt;</span></code> element. For example: BA, BD, BFA, BM, BS, DB, DD, DDS, DO, DVM, JD, LHD, LLB, LLD, LLM, MA, MBA, MD, MFA, MS, MSN.
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Judith Douglas, <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"degree"</span><span class="p">&gt;</span>DDS<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.10.8"><aside class="number">8.10.8</aside><p>Abbreviated state names, as well as postal codes, are wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"postal"</span><span class="p">&gt;</span></code> element.
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Washington <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"postal"</span><span class="p">&gt;</span>DC<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.10.9"><aside class="number">8.10.9</aside><p>Abbreviations that are abbreviations of a single word, and that are not acronyms or initialisms (like Mr., Mrs., or lbs.) are set with <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span></code>.
					</p><ol type="1">
<li id="8.10.9.1"><aside class="number">8.10.9.1</aside><p>Abbreviations ending in a lowercase letter are set without spaces between the letters, and are ended by a period.</p></li>
<li id="8.10.9.2"><aside class="number">8.10.9.2</aside><p>Abbreviations without lowercase letters are set without spaces and without a trailing period.</p></li>
<li id="8.10.9.3"><aside class="number">8.10.9.3</aside><p>Abbreviations that describes the next word, like Mr., Mrs., Mt., and St., are set with a no-break space (<span class="utf"> </span> or U+00A0) between the abbreviation and its target.
							</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He called on <span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span>Mrs.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span><span class="ws">nbsp</span>Jones yesterday.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</li>
<li id="8.10.10"><aside class="number">8.10.10</aside><p>Compass points are separated by periods and spaces. The group of points are wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"compass"</span><span class="p">&gt;</span></code> element.
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He traveled <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"compass"</span><span class="p">&gt;</span>S.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>, <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"compass"</span><span class="p">&gt;</span>N. W.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>, then <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"compass eoc"</span><span class="p">&gt;</span>E. S. E.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="8.11"><aside class="number">8.11</aside>
<h2>Times</h2>
<ol type="1">
<li id="8.11.1"><aside class="number">8.11.1</aside><p>Times in a.m. and p.m. format are set in lowercase, with periods, and without spaces.</p></li>
<li id="8.11.2"><aside class="number">8.11.2</aside><p>“a.m.” and “p.m.” are wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"time"</span><span class="p">&gt;</span></code> element.</p></li>
</ol>
<section id="8.11.3"><aside class="number">8.11.3</aside>
<h3>Times as digits</h3>
<ol type="1">
<li id="8.11.3.1"><aside class="number">8.11.3.1</aside><p>Digits in times are separated by a colon, not a period or comma.</p></li>
<li id="8.11.3.2"><aside class="number">8.11.3.2</aside><p>Times written in digits followed by “a.m.” or “p.m.” are set with a no-break space (<span class="utf"> </span> or U+00A0) between the digit and “a.m.” or “p.m.”.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He called at 6:40<span class="ws">nbsp</span><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"time eoc"</span><span class="p">&gt;</span>a.m.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="8.11.4"><aside class="number">8.11.4</aside>
<h3>Times as words</h3>
<ol type="1">
<li id="8.11.4.1"><aside class="number">8.11.4.1</aside><p>Words in a spelled-out time are separated by spaces, unless they appear before a noun, where they are separated by a hyphen.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He arrived at five thirty.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>They took the twelve-thirty train.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.11.4.2"><aside class="number">8.11.4.2</aside><p>Times written in words followed by “a.m.” or “p.m.” are set with a regular space between the time and “a.m.” or “p.m.”.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>She wasn’t up till seven <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"time eoc"</span><span class="p">&gt;</span>a.m.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.11.4.3"><aside class="number">8.11.4.3</aside><p>Military times that are spelled out (for example, in dialog) are set with dashes. Leading zeros are spelled out as “oh”.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He arrived at oh-nine-hundred.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
</section>
<section id="8.12"><aside class="number">8.12</aside>
<h2>Chemicals and compounds</h2>
<ol type="1">
<li id="8.12.1"><aside class="number">8.12.1</aside><p>Molecular compounds are set in Roman, without spaces, and wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"compound"</span><span class="p">&gt;</span></code> element.
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He put extra <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"compound"</span><span class="p">&gt;</span>NaCl<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> on his dinner.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.12.2"><aside class="number">8.12.2</aside><p>Elements in a molecular compound are capitalized according to their listing in the periodic table.</p></li>
<li id="8.12.3"><aside class="number">8.12.3</aside><p>Amounts of an element in a molecular compound are set in subscript with a <code class="html"><span class="p">&lt;</span><span class="nt">sub</span><span class="p">&gt;</span></code> element.
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>She drank eight glasses of <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"compound"</span><span class="p">&gt;</span>H<span class="p">&lt;</span><span class="nt">sub</span><span class="p">&gt;</span>2<span class="p">&lt;/</span><span class="nt">sub</span><span class="p">&gt;</span>O<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> a day.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="8.13"><aside class="number">8.13</aside>
<h2>Temperatures</h2>
<ol type="1">
<li id="8.13.1"><aside class="number">8.13.1</aside><p>The minus sign glyph (<code class="utf">−</code> or U+2212), not the hyphen glyph, is used to indicate negative numbers.</p></li>
<li id="8.13.2"><aside class="number">8.13.2</aside><p>Either the degree glyph (<code class="utf">°</code> or U+00B0) or the word “degrees” is acceptable. Works that use both are normalize to use the dominant method.</p></li>
</ol>
<section id="8.13.3"><aside class="number">8.13.3</aside>
<h3>Abbreviated units of temperature</h3>
<p>Units of temperature measurement, like Farenheit or Celcius, may be abbreviated to “F” or “C”.</p>
<ol type="1">
<li id="8.13.3.1"><aside class="number">8.13.3.1</aside><p>Units of temperature measurement do not have trailing periods.</p></li>
<li id="8.13.3.2"><aside class="number">8.13.3.2</aside><p>If an <em>abbreviated</em> unit of temperature measurement is preceded by a number, the unit of measurement is first preceded by a hair space (<span class="utf"> </span> or U+200A).</p></li>
<li id="8.13.3.3"><aside class="number">8.13.3.3</aside><p>Abbreviated units of measurement are set in small caps.</p></li>
<li id="8.13.3.4"><aside class="number">8.13.3.4</aside><p>Abbreviated units of measurement are wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"temperature"</span><span class="p">&gt;</span></code> element.
						</p><figure><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">temperature</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>It was −23.33° Celsius (or −10°<span class="ws">hairsp</span><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"temperature"</span><span class="p">&gt;</span>F<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>) last night.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
</section>
<section id="8.14"><aside class="number">8.14</aside>
<h2>Scansion</h2>
<p>Scansion is the representation of the metrical stresses in lines of verse.</p>
<ol type="1">
<li id="8.14.1"><aside class="number">8.14.1</aside><p><code class="utf">×</code> (U+00d7) indicates an unstressed sylllable and <code class="utf">/</code> (U+002f) indicates a stressed syllable. They are separated from each other with no-break spaces (<span class="utf"> </span> or U+00A0).
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Several of his types, however, constantly occur; <span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span>e.g.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> A and a variant (/ × | / ×) (/ × × | / ×); B and a variant (× / | × /) (× × / | × /); a variant of D (/ × | / × ×); E (/ × × | /). <span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="8.14.2"><aside class="number">8.14.2</aside><p>Lines of poetry listed on a single line (like in a quotation) are separated by a space, then a forward slash, then a space. Capitalization is preserved for each line.
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The famous lines “Wake! For the Sun, who scatter’d into flight / The Stars before him from the Field of Night” are from <span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"se:name.publication.book"</span><span class="p">&gt;</span>The Rubáiyát of Omar Khayyám<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="8.15"><aside class="number">8.15</aside>
<h2>Legal cases and terms</h2>
<ol type="1">
<li id="8.15.1"><aside class="number">8.15.1</aside><p>Legal cases are set in italics.</p></li>
<li id="8.15.2"><aside class="number">8.15.2</aside><p>Either “versus” or “v.” are acceptable in the name of a legal case; if using “v.”, a period follows the “v.”, and it is wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span></code> element.
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He prosecuted <span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"se:name.legal-case"</span><span class="p">&gt;</span>Johnson <span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span>v.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> Smith<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="8.16"><aside class="number">8.16</aside>
<h2>Morse code</h2>
<p>Any Morse code that appears in a book is changed to fit Standard Ebooks’ format.</p>
<section id="8.16.1"><aside class="number">8.16.1</aside>
<h3>American Morse Code</h3>
<ol type="1">
<li id="8.16.1.1"><aside class="number">8.16.1.1</aside><p>Middle dot glyphs (<code class="utf">·</code> or U+00B7) are used for the short mark or dot.</p></li>
<li id="8.16.1.2"><aside class="number">8.16.1.2</aside><p>En dash (<code class="utf">–</code> or U+2013) are used for the longer mark or short dash.</p></li>
<li id="8.16.1.3"><aside class="number">8.16.1.3</aside><p>Em dashes (<code class="utf">—</code> or U+2014) are used for the long dash (the letter L).</p></li>
<li id="8.16.1.4"><aside class="number">8.16.1.4</aside><p>If two en dashes are placed next to each other, a hair space (<span class="utf"> </span> or U+200A) is placed between them to keep the glyphs from merging into a longer dash.</p></li>
<li id="8.16.1.5"><aside class="number">8.16.1.5</aside><p>Only in American Morse Code, there are internal gaps used between glyphs in the letters C,O,R, or Z. No-break spaces (<span class="utf"> </span> or U+00A0) are used for these gaps.</p></li>
<li id="8.16.1.6"><aside class="number">8.16.1.6</aside><p>En spaces (U+2002) are used between letters.</p></li>
<li id="8.16.1.7"><aside class="number">8.16.1.7</aside><p>Em spaces (U+2003) are used between words.
						</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>--  .. ..   __  ..  - -  __  .   . ..  __  -..   .. .  .- -<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>My little old cat.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>– – ·· ·· — ·· – – — · · · — –·· ·· · ·– –<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>My little old cat.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
</section>
</section>
		</article>
	</main>
<?= Template::Footer() ?>
