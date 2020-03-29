<?
require_once('Core.php');
?><?= Template::Header(['title' => '7. High Level Structural Patterns - The Standard Ebooks Manual', 'highlight' => 'contribute', 'manual' => true]) ?>
	<main class="manual"><nav><p><a href="/manual/1.0.0">The Standard Ebooks Manual of Style</a></p><ol><li><p><a href="/manual/1.0.0/1-code-style">1. XHTML, CSS, and SVG Code Style</a></p><ol><li><p><a href="/manual/1.0.0/1-code-style#1.1">1.1 XHTML formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.2">1.2 CSS formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.3">1.3 SVG Formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.4">1.4 Commits and Commit Messages</a></p></li></ol></li><li><p><a href="/manual/1.0.0/2-filesystem">2. Filesystem Layout and File Naming Conventions</a></p><ol><li><p><a href="/manual/1.0.0/2-filesystem#2.1">2.1 File locations</a></p></li><li><p><a href="/manual/1.0.0/2-filesystem#2.2">2.2 XHTML file naming conventions</a></p></li><li><p><a href="/manual/1.0.0/2-filesystem#2.3">2.3 The se-lint-ignore.xml file</a></p></li></ol></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook">3. The Structure of an Ebook</a></p><ol><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.1">3.1 Front matter</a></p></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.2">3.2 Body matter</a></p></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.3">3.3 Back matter</a></p></li></ol></li><li><p><a href="/manual/1.0.0/4-semantics">4. Semantics</a></p><ol><li><p><a href="/manual/1.0.0/4-semantics#4.1">4.1 Semantic Elements</a></p></li><li><p><a href="/manual/1.0.0/4-semantics#4.2">4.2 Semantic Inflection</a></p></li></ol></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns">5. General XHTML Patterns</a></p><ol><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.1">5.1 id attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.2">5.2 class attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.3">5.3 xml:lang attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.4">5.4 The &lt;title&gt; element</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.5">5.5 Ordered/numbered and unordered lists</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.6">5.6 Tables</a></p></li></ol></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns">6. Standard Ebooks Section Patterns</a></p><ol><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.1">6.1 The title string</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.2">6.2 The table of contents</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.3">6.3 The titlepage</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.4">6.4 The imprint</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.5">6.5 The half title page</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.6">6.6 The colophon</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.7">6.7 The Uncopyright</a></p></li></ol></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns">7. High Level Structural Patterns</a></p><ol><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.1">7.1 Sectioning</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.2">7.2 Headers</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.3">7.3 Dedications</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.4">7.4 Epigraphs</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.5">7.5 Poetry, verse, and songs</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.6">7.6 Plays and drama</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.7">7.7 Letters</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.8">7.8 Images</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.9">7.9 List of Illustrations (the LoI)</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.10">7.10 Endnotes</a></p></li></ol></li><li><p><a href="/manual/1.0.0/8-typography">8. Typography</a></p><ol><li><p><a href="/manual/1.0.0/8-typography#8.1">8.1 Section titles and ordinals</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.2">8.2 Italics</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.3">8.3 Capitalization</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.4">8.4 Indentation</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.5">8.5 Chapter headers</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.6">8.6 Ligatures</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.7">8.7 Punctuation and spacing</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.8">8.8 Numbers, measurements, and math</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.9">8.9 Latinisms</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.10">8.10 Initials and abbreviations</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.11">8.11 Times</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.12">8.12 Chemicals and compounds</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.13">8.13 Temperatures</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.14">8.14 Scansion</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.15">8.15 Legal cases and terms</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.16">8.16 Morse code</a></p></li></ol></li><li><p><a href="/manual/1.0.0/9-metadata">9. Metadata</a></p><ol><li><p><a href="/manual/1.0.0/9-metadata#9.1">9.1 General URL rules</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.2">9.2 The ebook identifier</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.3">9.3 Publication date and release identifiers</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.4">9.4 Book titles</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.5">9.5 Book subjects</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.6">9.6 Book descriptions</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.7">9.7 Book language</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.8">9.8 Book transcription and page scan sources</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.9">9.9 Additional book metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.10">9.10 General contributor rules</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.11">9.11 The author metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.12">9.12 The translator metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.13">9.13 The illustrator metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.14">9.14 The cover artist metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.15">9.15 Metadata for additional contributors</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.16">9.16 Transcriber metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.17">9.17 Producer metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.18">9.18 The ebook manifest</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.19">9.19 The ebook spine</a></p></li></ol></li><li><p><a href="/manual/1.0.0/10-art-and-images">10. Art and Images</a></p><ol><li><p><a href="/manual/1.0.0/10-art-and-images#10.1">10.1 Complete list of files</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.2">10.2 SVG patterns</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.3">10.3 The cover image</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.4">10.4 The titlepage image</a></p></li></ol></li></ol></nav>
		<article>

<section id="7"><aside class="number">7</aside>
<h1>High Level Structural Patterns</h1>
<p>Section should contain high-level structural patterns for common formatting situations.</p>
<section id="7.1"><aside class="number">7.1</aside>
<h2>Sectioning</h2>
<ol type="1">
<li id="7.1.1"><aside class="number">7.1.1</aside><p>Major structural divisions of a larger work, like parts, volumes, books, chapters, or subchapters, are contained in a <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> element.</p></li>
<li id="7.1.2"><aside class="number">7.1.2</aside><p>Individual items in a larger collection (like a poem in a poetry collection) are contained in a <code class="html"><span class="p">&lt;</span><span class="nt">article</span><span class="p">&gt;</span></code> element.</p></li>
<li id="7.1.3"><aside class="number">7.1.3</aside><p>In <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> or <code class="html"><span class="p">&lt;</span><span class="nt">articles</span><span class="p">&gt;</span></code> elements that have titles, the first child element is an <code class="html"><span class="p">&lt;</span><span class="nt">h1</span><span class="p">&gt;</span></code>–<code class="html"><span class="p">&lt;</span><span class="nt">h6</span><span class="p">&gt;</span></code> element, or a <code class="html"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span></code> element containing the section’s title.</p></li>
</ol>
<section id="7.1.4"><aside class="number">7.1.4</aside>
<h3>Recomposability</h3>
<p>“Recomposability” is the concept of generating a single structurally-correct HTML5 file out of an epub file. All Standard Ebooks are recomposable.</p>
<ol type="1">
<li id="7.1.4.1"><aside class="number">7.1.4.1</aside><p>XHTML files that contain <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> or <code class="html"><span class="p">&lt;</span><span class="nt">articles</span><span class="p">&gt;</span></code> elements that are semantic children of <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> or <code class="html"><span class="p">&lt;</span><span class="nt">articles</span><span class="p">&gt;</span></code> elements in other files, are wrapped in stubs of all parent <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> or <code class="html"><span class="p">&lt;</span><span class="nt">articles</span><span class="p">&gt;</span></code> elements, up to the root.</p></li>
<li id="7.1.4.2"><aside class="number">7.1.4.2</aside><p>Each such included parent element has the identical <code class="html"><span class="na">id</span></code> and <code class="html"><span class="na">epub:type</span></code> attributes of its real counterpart.</p></li>
</ol>
<section id="examples">
<h4>Examples</h4>
<p>Consider a book that contains several top-level subdivisions: Books 1–4, with each book having 3 parts, and each part having 10 chapters. Below is an example of three files demonstrating the structure necessary to achieve recomposability:</p>
<p>Book 1 (<code class="path">book-1.xhtml</code>):</p>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"book-1"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"division"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>Book <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:roman"</span><span class="p">&gt;</span>I<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code></figure>
<p>Book 1, Part 2 (<code class="path">part-1-2.xhtml</code>):</p>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"book-1"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"division"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"part-1-2"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"part"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>Part <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:roman"</span><span class="p">&gt;</span>II<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code></figure>
<p>Book 1, Part 2, Chapter 3 (<code class="path">chapter-1-2-3.xhtml</code>):</p>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"book-1"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"division"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"part-1-2"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"part"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"chapter-3"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"chapter"</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>Chapter <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:roman"</span><span class="p">&gt;</span>III<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code></figure>
</section>
</section>
</section>
<section id="7.2"><aside class="number">7.2</aside>
<h2>Headers</h2>
<ol type="1">
<li id="7.2.1"><aside class="number">7.2.1</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">h1</span><span class="p">&gt;</span></code>–<code class="html"><span class="p">&lt;</span><span class="nt">h6</span><span class="p">&gt;</span></code> elements are used for headers of sections that are structural divisions of a document, i.e., divisions that appear in the table of contents. <code class="html"><span class="p">&lt;</span><span class="nt">h1</span><span class="p">&gt;</span></code>–<code class="html"><span class="p">&lt;</span><span class="nt">h6</span><span class="p">&gt;</span></code> elements <em>are not</em> used for headers of components that are not in the table of contents. For example, they are not used to mark up the title of a short poem in a chapter, where the poem itself is not a structural component of the larger ebook.</p></li>
<li id="7.2.2"><aside class="number">7.2.2</aside><p>A section containing an <code class="html"><span class="p">&lt;</span><span class="nt">h1</span><span class="p">&gt;</span></code>–<code class="html"><span class="p">&lt;</span><span class="nt">h6</span><span class="p">&gt;</span></code> appears in the table of contents.</p></li>
<li id="7.2.3"><aside class="number">7.2.3</aside><p>The book’s title is implicitly at the <code class="html"><span class="p">&lt;</span><span class="nt">h1</span><span class="p">&gt;</span></code> level, even if <code class="html"><span class="p">&lt;</span><span class="nt">h1</span><span class="p">&gt;</span></code> is not present in the ebook. An <code class="html"><span class="p">&lt;</span><span class="nt">h1</span><span class="p">&gt;</span></code> element is only present if the ebook contains a half title page. Because of the implicit <code class="html"><span class="p">&lt;</span><span class="nt">h1</span><span class="p">&gt;</span></code>, all other sections begin at <code class="html"><span class="p">&lt;</span><span class="nt">h2</span><span class="p">&gt;</span></code>.</p></li>
<li id="7.2.4"><aside class="number">7.2.4</aside><p>Each <code class="html"><span class="p">&lt;</span><span class="nt">h1</span><span class="p">&gt;</span></code>–<code class="html"><span class="p">&lt;</span><span class="nt">h6</span><span class="p">&gt;</span></code> element uses the correct number for the section’s heading level in the overall book, <em>not</em> the section’s heading level in the individual file. For example, given an ebook with a file named <code class="path">part-2.xhtml</code> containing:
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"part-2"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"part"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>Part <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:roman"</span><span class="p">&gt;</span>II<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code></figure>
<p>Consider this example for the file <code class="path">chapter-2-3.xhtml</code>:</p>
<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"part-2"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"part"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"chapter-2-3"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"chapter"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title z3998:roman"</span><span class="p">&gt;</span>III<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
		...
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"part-2"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"part"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"chapter-2-3"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"chapter"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">h3</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title z3998:roman"</span><span class="p">&gt;</span>III<span class="p">&lt;/</span><span class="nt">h3</span><span class="p">&gt;</span>
		...
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code></figure>
</li>
<li id="7.2.5"><aside class="number">7.2.5</aside><p>Each <code class="html"><span class="p">&lt;</span><span class="nt">h1</span><span class="p">&gt;</span></code>–<code class="html"><span class="p">&lt;</span><span class="nt">h6</span><span class="p">&gt;</span></code> element has a direct parent <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> or <code class="html"><span class="p">&lt;</span><span class="nt">article</span><span class="p">&gt;</span></code> element.</p></li>
</ol>
<section id="7.2.6"><aside class="number">7.2.6</aside>
<h3>Header patterns</h3>
<ol type="1">
<li id="7.2.6.1"><aside class="number">7.2.6.1</aside><p>Sections without titles:
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title z3998:roman"</span><span class="p">&gt;</span>XI<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code></figure>
</li>
<li id="7.2.6.2"><aside class="number">7.2.6.2</aside><p>Sections with titles but no ordinal (i.e. chapter) numbers:
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>A Daughter of Albion<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code></figure>
</li>
<li id="7.2.6.3"><aside class="number">7.2.6.3</aside><p>Sections with titles and ordinal (i.e. chapter) numbers:
						</p><figure><code class="css full"><span class="nt">span</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"subtitle"</span><span class="o">]</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">block</span><span class="p">;</span>
	<span class="k">font-weight</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:roman"</span><span class="p">&gt;</span>XI<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"subtitle"</span><span class="p">&gt;</span>Who Stole the Tarts?<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code></figure>
</li>
<li id="7.2.6.4"><aside class="number">7.2.6.4</aside><p>Sections titles and subtitles but no ordinal (i.e. chapter) numbers:
						</p><figure><code class="css full"><span class="nt">span</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"subtitle"</span><span class="o">]</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">block</span><span class="p">;</span>
	<span class="k">font-weight</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>An Adventure<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"subtitle"</span><span class="p">&gt;</span>(A Driver’s Story)<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code></figure>
</li>
<li id="7.2.6.5"><aside class="number">7.2.6.5</aside><p>Sections that have a non-unique title, but that are required to be identifed in the ToC with a unique title (e.g., multiple poems identified as “Sonnet” in the body matter, which require their ToC entry to contain the poem’s first line to differentiate them):
						</p><figure><code class="css full"><span class="nt">span</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"subtitle"</span><span class="o">]</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">block</span><span class="p">;</span>
	<span class="k">font-weight</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Sonnet<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span> <span class="na">hidden</span><span class="o">=</span><span class="s">"hidden"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"subtitle"</span><span class="p">&gt;</span>Happy Is England!<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code></figure>
</li>
<li id="7.2.6.6"><aside class="number">7.2.6.6</aside><p>Sections that require titles, but that are not in the table of contents:
						</p><figure><code class="css full"><span class="nt">header</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">small-caps</span><span class="p">;</span>
	<span class="k">margin</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">center</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The Title of a Short Poem<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">header</span><span class="p">&gt;</span></code></figure>
</li>
<li id="7.2.6.7"><aside class="number">7.2.6.7</aside><p>Half title pages without subtitles:
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">h1</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"fulltitle"</span><span class="p">&gt;</span>Eugene Onegin<span class="p">&lt;/</span><span class="nt">h1</span><span class="p">&gt;</span></code></figure>
</li>
<li id="7.2.6.8"><aside class="number">7.2.6.8</aside><p>Half title pages with subtitles:
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">h1</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"fulltitle"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>His Last Bow<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"subtitle"</span><span class="p">&gt;</span>Some Reminiscences of Sherlock Holmes<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">h1</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="7.2.7"><aside class="number">7.2.7</aside>
<h3>Bridgeheads</h3>
<p>Bridgeheads are sections in a chapter header that give an abstract or summary of the following chapter. They may be in prose or in a short list with clauses separated by em dashes.</p>
<ol type="1">
<li id="7.2.7.1"><aside class="number">7.2.7.1</aside><p>The last clause in a bridgehead ends in appropriate punctuation, like a period.</p></li>
<li id="7.2.7.2"><aside class="number">7.2.7.2</aside><p>Bridgeheads have the following CSS and HTML structure:
						</p><figure><code class="css full"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"bridgehead"</span><span class="o">]</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">inline-block</span><span class="p">;</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">italic</span><span class="p">;</span>
	<span class="k">max-width</span><span class="p">:</span> <span class="mi">60</span><span class="kt">%</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">justify</span><span class="p">;</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"bridgehead"</span><span class="o">]</span> <span class="nt">i</span><span class="p">{</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title z3998:roman"</span><span class="p">&gt;</span>I<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"bridgehead"</span><span class="p">&gt;</span>Which treats of the character and pursuits of the famous gentleman Don Quixote of La Mancha.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">header</span><span class="p">&gt;</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title z3998:roman"</span><span class="p">&gt;</span>X<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"bridgehead"</span><span class="p">&gt;</span>Our first night⁠<span class="ws">wj</span>—Under canvas⁠<span class="ws">wj</span>—An appeal for help⁠<span class="ws">wj</span>—Contrariness of teakettles, how to overcome⁠<span class="ws">wj</span>—Supper⁠<span class="ws">wj</span>—How to feel virtuous⁠<span class="ws">wj</span>—Wanted! a comfortably-appointed, well-drained desert island, neighbourhood of South Pacific Ocean preferred⁠<span class="ws">wj</span>—Funny thing that happened to George’s father⁠<span class="ws">wj</span>—A restless night.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">header</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
</section>
<section id="7.3"><aside class="number">7.3</aside>
<h2>Dedications</h2>
<ol type="1">
<li id="7.3.1"><aside class="number">7.3.1</aside><p>Dedications are typically full-page, centered on the page for ereaders that support advanced CSS. For all other ereaders, the dedication is horizontally centered with a small margin above it.</p></li>
<li id="7.3.2"><aside class="number">7.3.2</aside><p>All dedications include this base CSS:
					</p><figure><code class="css full"><span class="c">/* All dedications */</span>
<span class="nt">section</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"dedication"</span><span class="o">]</span> <span class="o">&gt;</span> <span class="o">*</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">inline-block</span><span class="p">;</span>
	<span class="k">margin</span><span class="p">:</span> <span class="kc">auto</span><span class="p">;</span>
	<span class="k">margin-top</span><span class="p">:</span> <span class="mi">3</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">max-width</span><span class="p">:</span> <span class="mi">80</span><span class="kt">%</span><span class="p">;</span>
<span class="p">}</span>

<span class="p">@</span><span class="k">supports</span><span class="o">(</span><span class="nt">display</span><span class="o">:</span> <span class="nt">flex</span><span class="o">)</span><span class="p">{</span>
	<span class="nt">section</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"dedication"</span><span class="o">]</span><span class="p">{</span>
		<span class="k">align-items</span><span class="p">:</span> <span class="kc">center</span><span class="p">;</span>
		<span class="k">box-sizing</span><span class="p">:</span> <span class="kc">border-box</span><span class="p">;</span>
		<span class="k">display</span><span class="p">:</span> <span class="kc">flex</span><span class="p">;</span>
		<span class="k">flex-direction</span><span class="p">:</span> <span class="kc">column</span><span class="p">;</span>
		<span class="k">justify-content</span><span class="p">:</span> <span class="kc">center</span><span class="p">;</span>
		<span class="k">min-height</span><span class="p">:</span> <span class="nb">calc</span><span class="p">(</span><span class="mi">98</span><span class="kt">vh</span> <span class="o">-</span> <span class="mi">3</span><span class="kt">em</span><span class="p">);</span>
		<span class="k">padding-top</span><span class="p">:</span> <span class="mi">3</span><span class="kt">em</span><span class="p">;</span>
	<span class="p">}</span>

	<span class="nt">section</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"dedication"</span><span class="o">]</span> <span class="o">&gt;</span> <span class="o">*</span><span class="p">{</span>
		<span class="k">margin</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
	<span class="p">}</span>
<span class="p">}</span>
<span class="c">/* End all dedications */</span></code></figure>
</li>
<li id="7.3.3"><aside class="number">7.3.3</aside><p>Dedications are frequently styled uniquely by the authors. Therefore Standard Ebooks producers have freedom to style dedications to match page scans, for example by including small caps, different font sizes, alignments, etc.</p></li>
</ol>
</section>
<section id="7.4"><aside class="number">7.4</aside>
<h2>Epigraphs</h2>
<ol type="1">
<li id="7.4.1"><aside class="number">7.4.1</aside><p>All epigraphs include this CSS:
					</p><figure><code class="css full"><span class="c">/* All epigraphs */</span>
<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"epigraph"</span><span class="o">]</span><span class="p">{</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">italic</span><span class="p">;</span>
	<span class="k">hyphens</span><span class="p">:</span> <span class="kc">none</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"epigraph"</span><span class="o">]</span> <span class="nt">em</span><span class="o">,</span>
<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"epigraph"</span><span class="o">]</span> <span class="nt">i</span><span class="p">{</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"epigraph"</span><span class="o">]</span> <span class="nt">cite</span><span class="p">{</span>
	<span class="k">margin-top</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">small-caps</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"epigraph"</span><span class="o">]</span> <span class="nt">cite</span> <span class="nt">i</span><span class="p">{</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">italic</span><span class="p">;</span>
<span class="p">}</span>
<span class="c">/* End all epigraphs */</span></code></figure>
</li>
</ol>
<section id="7.4.2"><aside class="number">7.4.2</aside>
<h3>Epigraphs in section headers</h3>
<ol type="1">
<li id="7.4.2.1"><aside class="number">7.4.2.1</aside><p>Epigraphs in section headers have the quote source in a <code class="html"><span class="p">&lt;</span><span class="nt">cite</span><span class="p">&gt;</span></code> element set in small caps, without a leading em-dash and without a trailing period.
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
<li id="7.4.2.2"><aside class="number">7.4.2.2</aside><p>In addition to the <a href="/manual/1.0.0/7-high-level-structural-patterns#7.3.1">CSS used for all epigraphs</a>, this additional CSS is included for epigraphs in section headers:
						</p><figure><code class="css full"><span class="c">/* Epigraphs in section headers */</span>
<span class="nt">section</span> <span class="o">&gt;</span> <span class="nt">header</span> <span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"epigraph"</span><span class="o">]</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">inline-block</span><span class="p">;</span>
	<span class="k">margin</span><span class="p">:</span> <span class="kc">auto</span><span class="p">;</span>
	<span class="k">max-width</span><span class="p">:</span> <span class="mi">80</span><span class="kt">%</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">left</span><span class="p">;</span>
<span class="p">}</span>

<span class="nt">section</span> <span class="o">&gt;</span> <span class="nt">header</span> <span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"epigraph"</span><span class="o">]</span> <span class="o">+</span> <span class="o">*</span><span class="p">{</span>
	<span class="k">margin-top</span><span class="p">:</span> <span class="mi">3</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span>

<span class="p">@</span><span class="k">supports</span><span class="o">(</span><span class="nt">display</span><span class="o">:</span> <span class="nt">table</span><span class="o">)</span><span class="p">{</span>
	<span class="nt">section</span> <span class="o">&gt;</span> <span class="nt">header</span> <span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"epigraph"</span><span class="o">]</span><span class="p">{</span>
		<span class="k">display</span><span class="p">:</span> <span class="kc">table</span><span class="p">;</span>
	<span class="p">}</span>
<span class="p">}</span>
<span class="c">/* End epigraphs in section headers */</span></code></figure>
</li>
</ol>
</section>
<section id="7.4.3"><aside class="number">7.4.3</aside>
<h3>Full-page epigraphs</h3>
<ol type="1">
<li id="7.4.3.1"><aside class="number">7.4.3.1</aside><p>In full-page epigraphs, the epigraph is centered on the page for ereaders that support advanced CSS. For all other ereaders, the epigraph is horizontally centered with a small margin above it.</p></li>
<li id="7.4.3.2"><aside class="number">7.4.3.2</aside><p>Full-page epigraphs that contain multiple quotations are represented by multiple <code class="html"><span class="p">&lt;</span><span class="nt">blockquote</span><span class="p">&gt;</span></code> elements.</p></li>
<li id="7.4.3.3"><aside class="number">7.4.3.3</aside><p>In addition to the <a href="/manual/1.0.0/7-high-level-structural-patterns#7.3.1">CSS used for all epigraphs</a>, this additional CSS is included for full-page epigraphs:
						</p><figure><code class="css full"><span class="c">/* Full-page epigraphs */</span>
<span class="nt">section</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"epigraph"</span><span class="o">]</span><span class="p">{</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">center</span><span class="p">;</span>
<span class="p">}</span>

<span class="nt">section</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"epigraph"</span><span class="o">]</span> <span class="o">&gt;</span> <span class="o">*</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">inline-block</span><span class="p">;</span>
	<span class="k">margin</span><span class="p">:</span> <span class="kc">auto</span><span class="p">;</span>
	<span class="k">margin-top</span><span class="p">:</span> <span class="mi">3</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">max-width</span><span class="p">:</span> <span class="mi">80</span><span class="kt">%</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">left</span><span class="p">;</span>
<span class="p">}</span>

<span class="p">@</span><span class="k">supports</span><span class="o">(</span><span class="nt">display</span><span class="o">:</span> <span class="nt">flex</span><span class="o">)</span><span class="p">{</span>
	<span class="nt">section</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"epigraph"</span><span class="o">]</span><span class="p">{</span>
		<span class="k">align-items</span><span class="p">:</span> <span class="kc">center</span><span class="p">;</span>
		<span class="k">box-sizing</span><span class="p">:</span> <span class="kc">border-box</span><span class="p">;</span>
		<span class="k">display</span><span class="p">:</span> <span class="kc">flex</span><span class="p">;</span>
		<span class="k">flex-direction</span><span class="p">:</span> <span class="kc">column</span><span class="p">;</span>
		<span class="k">justify-content</span><span class="p">:</span> <span class="kc">center</span><span class="p">;</span>
		<span class="k">min-height</span><span class="p">:</span> <span class="nb">calc</span><span class="p">(</span><span class="mi">98</span><span class="kt">vh</span> <span class="o">-</span> <span class="mi">3</span><span class="kt">em</span><span class="p">);</span>
		<span class="k">padding-top</span><span class="p">:</span> <span class="mi">3</span><span class="kt">em</span><span class="p">;</span>
	<span class="p">}</span>

	<span class="nt">section</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"epigraph"</span><span class="o">]</span> <span class="o">&gt;</span> <span class="o">*</span><span class="p">{</span>
		<span class="k">margin</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
	<span class="p">}</span>

	<span class="nt">section</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"epigraph"</span><span class="o">]</span> <span class="o">&gt;</span> <span class="o">*</span> <span class="o">+</span> <span class="o">*</span><span class="p">{</span>
		<span class="k">margin-top</span><span class="p">:</span> <span class="mi">3</span><span class="kt">em</span><span class="p">;</span>
	<span class="p">}</span>
<span class="p">}</span>
<span class="c">/* End full-page epigraphs */</span></code></figure>
</li>
<li id="7.4.3.4"><aside class="number">7.4.3.4</aside><p>Example HTML:
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"frontmatter"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"epigraph"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"epigraph"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">blockquote</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Reorganisation, irrespectively of God or king, by the worship of Humanity, systematically adopted.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Man’s only right is to do his duty.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The Intellect should always be the servant of the Heart, and should never be its slave.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">blockquote</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“We tire of thinking and even of acting; we never tire of loving.”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">body</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
</section>
<section id="7.5"><aside class="number">7.5</aside>
<h2>Poetry, verse, and songs</h2>
<p>Unfortunately there’s no great way to semantically format poetry in HTML. As such, unrelated elements are conscripted for use in poetry.</p>
<ol type="1">
<li id="7.5.1"><aside class="number">7.5.1</aside><p>A stanza is represented by a <code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code> element styled with this CSS:
					</p><figure><code class="css full"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:poem"</span><span class="o">]</span> <span class="nt">p</span><span class="p">{</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">left</span><span class="p">;</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:poem"</span><span class="o">]</span> <span class="nt">p</span> <span class="o">+</span> <span class="nt">p</span><span class="p">{</span>
	<span class="k">margin-top</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:poem"</span><span class="o">]</span> <span class="o">+</span> <span class="nt">p</span><span class="p">{</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span></code></figure>
</li>
<li id="7.5.2"><aside class="number">7.5.2</aside><p>Each stanza contains <code class="html"><span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span></code> elements, each one representing a line in the stanza, styled with this CSS:
					</p><figure><code class="css full"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:poem"</span><span class="o">]</span> <span class="nt">p</span> <span class="o">&gt;</span> <span class="nt">span</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">block</span><span class="p">;</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">-1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">padding-left</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span></code></figure>
</li>
<li id="7.5.3"><aside class="number">7.5.3</aside><p>Each <code class="html"><span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span></code> line is followed by a <code class="html"><span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span></code> element, except for the last line in a stanza, styled with this CSS:
					</p><figure><code class="css full"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:poem"</span><span class="o">]</span> <span class="nt">p</span> <span class="o">&gt;</span> <span class="nt">span</span> <span class="o">+</span> <span class="nt">br</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">none</span><span class="p">;</span>
<span class="p">}</span></code></figure>
</li>
<li id="7.5.4"><aside class="number">7.5.4</aside><p>Indented <code class="html"><span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span></code> lines have the <code class="bash"><span class="s">i1</span></code> class. <i>Do not</i> use <code class="ws">nbsp</code> for indentation. Indenting to different levels is done by incrementing the class to <code class="css"><span class="nt">i2</span></code>, <code class="css"><span class="nt">i3</span></code>, and so on, and including the appropriate CSS.
					</p><figure><code class="css full"><span class="nt">p</span> <span class="nt">span</span><span class="p">.</span><span class="nc">i1</span><span class="p">{</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">-1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">padding-left</span><span class="p">:</span> <span class="mi">2</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span>

<span class="nt">p</span> <span class="nt">span</span><span class="p">.</span><span class="nc">i2</span><span class="p">{</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">-1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">padding-left</span><span class="p">:</span> <span class="mi">3</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span></code></figure>
</li>
<li id="7.5.5"><aside class="number">7.5.5</aside><p>Poems, songs, and verse that are shorter part of a longer work, like a novel, are wrapped in a <code class="html"><span class="p">&lt;</span><span class="nt">blockquote</span><span class="p">&gt;</span></code> element.
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:poem"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">br</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
</li>
<li id="7.5.6"><aside class="number">7.5.6</aside><p>The parent element of poetry, verse, or song, has the semantic inflection of <code class="bash"><span class="s">z3998:poem</span></code>, <code class="bash"><span class="s">z3998:verse</span></code>, <code class="bash"><span class="s">z3998:song</span></code>, or <code class="bash"><span class="s">z3998:hymn</span></code>.</p></li>
<li id="7.5.7"><aside class="number">7.5.7</aside><p>If a poem is quoted and has one or more lines removed, the removed lines are represented with a vertical ellipses (<code class="utf">⋮</code> or U+22EE) in a <code class="html"><span class="p">&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">"elision"</span><span class="p">&gt;</span></code> element styled with this CSS:
					</p><figure><code class="css full"><span class="nt">span</span><span class="p">.</span><span class="nc">elision</span><span class="p">{</span>
	<span class="k">margin</span><span class="p">:</span> <span class="mf">.5</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">margin-left</span><span class="p">:</span> <span class="mi">3</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span>

<span class="c">/* If eliding within an epigraph, include this additional style: */</span>
<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"epigraph"</span><span class="o">]</span> <span class="nt">span</span><span class="p">.</span><span class="nc">elision</span><span class="p">{</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:verse"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>O Lady! we receive but what we give,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>And in our life alone does nature live:<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Ours is her wedding garments, ours her shroud!<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">"elision"</span><span class="p">&gt;</span>⋮<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">"i1"</span><span class="p">&gt;</span>Ah! from the soul itself must issue forth<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>A light, a glory, a fair luminous cloud,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
<section id="id1">
<h3>Examples</h3>
<p>Note that below we include CSS for the <code class="css"><span class="p">.</span><span class="nc">i2</span></code> class, even though it’s not used in the example. It’s included to demonstrate how to adjust the CSS for indentation levels after the first.</p>
<figure><code class="css full"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:poem"</span><span class="o">]</span> <span class="nt">p</span><span class="p">{</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">left</span><span class="p">;</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:poem"</span><span class="o">]</span> <span class="nt">p</span> <span class="o">&gt;</span> <span class="nt">span</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">block</span><span class="p">;</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">-1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">padding-left</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:poem"</span><span class="o">]</span> <span class="nt">p</span> <span class="o">&gt;</span> <span class="nt">span</span> <span class="o">+</span> <span class="nt">br</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">none</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:poem"</span><span class="o">]</span> <span class="nt">p</span> <span class="o">+</span> <span class="nt">p</span><span class="p">{</span>
	<span class="k">margin-top</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:poem"</span><span class="o">]</span> <span class="o">+</span> <span class="nt">p</span><span class="p">{</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="nt">p</span> <span class="nt">span</span><span class="p">.</span><span class="nc">i1</span><span class="p">{</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">-1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">padding-left</span><span class="p">:</span> <span class="mi">2</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span>

<span class="nt">p</span> <span class="nt">span</span><span class="p">.</span><span class="nc">i2</span><span class="p">{</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">-1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">padding-left</span><span class="p">:</span> <span class="mi">3</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:poem"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>“How doth the little crocodile<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">"i1"</span><span class="p">&gt;</span>Improve his shining tail,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>And pour the waters of the Nile<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">"i1"</span><span class="p">&gt;</span>On every golden scale!<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>“How cheerfully he seems to grin,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">"i1"</span><span class="p">&gt;</span>How neatly spread his claws,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>And welcome little fishes in<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">"i1"</span><span class="p">&gt;&lt;</span><span class="nt">em</span><span class="p">&gt;</span>With gently smiling jaws!<span class="p">&lt;/</span><span class="nt">em</span><span class="p">&gt;</span>”<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
</section>
</section>
<section id="7.6"><aside class="number">7.6</aside>
<h2>Plays and drama</h2>
<ol type="1">
<li id="7.6.1"><aside class="number">7.6.1</aside><p>Dialog in plays is structured using <code class="html"><span class="p">&lt;</span><span class="nt">table</span><span class="p">&gt;</span></code> elements.</p></li>
<li id="7.6.2"><aside class="number">7.6.2</aside><p>Each <code class="html"><span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span></code> is either a block of dialog or a standalone stage direction.</p></li>
<li id="7.6.3"><aside class="number">7.6.3</aside><p>Works that are plays or that contain sections of dramatic dialog have this core CSS:
					</p><figure><code class="css full"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:drama"</span><span class="o">]</span><span class="p">{</span>
	<span class="k">border-collapse</span><span class="p">:</span> <span class="kc">collapse</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:drama"</span><span class="o">]</span> <span class="nt">tr</span><span class="p">:</span><span class="nd">first-child</span> <span class="nt">td</span><span class="p">{</span>
	<span class="k">padding-top</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:drama"</span><span class="o">]</span> <span class="nt">tr</span><span class="p">:</span><span class="nd">last-child</span> <span class="nt">td</span><span class="p">{</span>
	<span class="k">padding-bottom</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:drama"</span><span class="o">]</span> <span class="nt">td</span><span class="p">{</span>
	<span class="k">vertical-align</span><span class="p">:</span> <span class="kc">top</span><span class="p">;</span>
	<span class="k">padding</span><span class="p">:</span> <span class="mf">.5</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:drama"</span><span class="o">]</span> <span class="nt">td</span><span class="p">:</span><span class="nd">last-child</span><span class="p">{</span>
	<span class="k">padding-right</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:drama"</span><span class="o">]</span> <span class="nt">td</span><span class="p">:</span><span class="nd">first-child</span><span class="p">{</span>
	<span class="k">padding-left</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:drama"</span><span class="o">]</span> <span class="nt">td</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:persona"</span><span class="o">]</span><span class="p">{</span>
	<span class="k">hyphens</span><span class="p">:</span> <span class="kc">none</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">right</span><span class="p">;</span>
	<span class="k">width</span><span class="p">:</span> <span class="mi">20</span><span class="kt">%</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:drama"</span><span class="o">]</span> <span class="nt">td</span> <span class="nt">p</span><span class="p">{</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="nt">table</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:drama"</span><span class="o">],</span>
<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:drama"</span><span class="o">]</span> <span class="nt">table</span><span class="p">{</span>
	<span class="k">margin</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span> <span class="kc">auto</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:stage-direction"</span><span class="o">]</span><span class="p">{</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">italic</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:stage-direction"</span><span class="o">]</span><span class="p">::</span><span class="nd">before</span><span class="p">{</span>
	<span class="k">content</span><span class="p">:</span> <span class="s2">"("</span><span class="p">;</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:stage-direction"</span><span class="o">]</span><span class="p">::</span><span class="nd">after</span><span class="p">{</span>
	<span class="k">content</span><span class="p">:</span> <span class="s2">")"</span><span class="p">;</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:persona"</span><span class="o">]</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
</li>
</ol>
<section id="7.6.4"><aside class="number">7.6.4</aside>
<h3>Dialog rows</h3>
<ol type="1">
<li id="7.6.4.1"><aside class="number">7.6.4.1</aside><p>The first child of a row of dialog is a <code class="html"><span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span></code> element with the semantic inflection of <code class="bash"><span class="s">z3998:persona</span></code>.</p></li>
<li id="7.6.4.2"><aside class="number">7.6.4.2</aside><p>The second child of a row of dialog is a <code class="html"><span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span></code> element containing the actual dialog. Elements that contain only one line of dialog do not have a block-level child (like <code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code>).
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span>Algernon<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>Did you hear what I was playing, Lane?<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span>Lane<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>I didn’t think it polite to listen, sir.<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span></code></figure>
</li>
<li id="7.6.4.3"><aside class="number">7.6.4.3</aside><p>When several personas speak at once, or a group of personas ("The Actors") speaks at once, the containing <code class="html"><span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span></code> element has the <code class="bash"><span class="s">together</span></code> class, and the first <code class="html"><span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span></code> child has a <code class="html"><span class="na">rowspan</span></code> attribute corresponding to the number of lines spoken together.
						</p><figure><code class="css full"><span class="nt">tr</span><span class="p">.</span><span class="nc">together</span> <span class="nt">td</span><span class="p">{</span>
	<span class="k">padding</span><span class="p">:</span> <span class="mi">0</span> <span class="mf">.5</span><span class="kt">em</span> <span class="mi">0</span> <span class="mi">0</span><span class="p">;</span>
	<span class="k">vertical-align</span><span class="p">:</span> <span class="kc">middle</span><span class="p">;</span>
<span class="p">}</span>

<span class="nt">tr</span><span class="p">.</span><span class="nc">together</span> <span class="nt">td</span><span class="p">:</span><span class="nd">only-child</span><span class="o">,</span>
<span class="nt">tr</span><span class="p">.</span><span class="nc">together</span> <span class="nt">td</span> <span class="o">+</span> <span class="nt">td</span><span class="p">{</span>
	<span class="k">border-left</span><span class="p">:</span> <span class="mi">1</span><span class="kt">px</span> <span class="kc">solid</span><span class="p">;</span>
<span class="p">}</span>

<span class="p">.</span><span class="nc">together</span> <span class="o">+</span> <span class="p">.</span><span class="nc">together</span> <span class="nt">td</span><span class="o">[</span><span class="nt">rowspan</span><span class="o">],</span>
<span class="p">.</span><span class="nc">together</span> <span class="o">+</span> <span class="p">.</span><span class="nc">together</span> <span class="nt">td</span><span class="o">[</span><span class="nt">rowspan</span><span class="o">]</span> <span class="o">+</span> <span class="nt">td</span><span class="p">{</span>
	<span class="k">padding-top</span><span class="p">:</span> <span class="mf">.5</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:drama"</span><span class="o">]</span> <span class="p">.</span><span class="nc">together</span> <span class="nt">td</span><span class="p">:</span><span class="nd">last-child</span><span class="p">{</span>
	<span class="k">padding-left</span><span class="p">:</span> <span class="mf">.5</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">tr</span> <span class="na">class</span><span class="o">=</span><span class="s">"together"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span> <span class="na">rowspan</span><span class="o">=</span><span class="s">"3"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span>The Actors<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>Oh, what d’you think of that?<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">tr</span> <span class="na">class</span><span class="o">=</span><span class="s">"together"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>Only the mantle?<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">tr</span> <span class="na">class</span><span class="o">=</span><span class="s">"together"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>He must be mad.<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">tr</span> <span class="na">class</span><span class="o">=</span><span class="s">"together"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span> <span class="na">rowspan</span><span class="o">=</span><span class="s">"2"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span>Some Actresses<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>But why?<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">tr</span> <span class="na">class</span><span class="o">=</span><span class="s">"together"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>Mantles as well?<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="7.6.5"><aside class="number">7.6.5</aside>
<h3>Stage direction rows</h3>
<ol type="1">
<li id="7.6.5.1"><aside class="number">7.6.5.1</aside><p>The first child of a row of stage direction is an empty <code class="html"><span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span></code> element.</p></li>
<li id="7.6.5.2"><aside class="number">7.6.5.2</aside><p>The second child of a row of dialog is a <code class="html"><span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span></code> element containing the stage direction</p></li>
<li id="7.6.5.3"><aside class="number">7.6.5.3</aside><p>Stage direction is wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:stage-direction"</span><span class="p">&gt;</span></code> element.</p></li>
<li id="7.6.5.4"><aside class="number">7.6.5.4</aside><p>Personas mentioned in stage direction are wrapped in a <code class="html"><span class="p">&lt;</span><span class="nt">b</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span></code> element.</p></li>
</ol>
<section id="id2">
<h4>Examples</h4>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:stage-direction"</span><span class="p">&gt;&lt;</span><span class="nt">b</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span>Lane<span class="p">&lt;/</span><span class="nt">b</span><span class="p">&gt;</span> is arranging afternoon tea on the table, and after the music has ceased, <span class="p">&lt;</span><span class="nt">b</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span>Algernon<span class="p">&lt;/</span><span class="nt">b</span><span class="p">&gt;</span> enters.<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span></code></figure>
</section>
</section>
<section id="7.6.6"><aside class="number">7.6.6</aside>
<h3>Works that are complete plays</h3>
<ol type="1">
<li id="7.6.6.1"><aside class="number">7.6.6.1</aside><p>The top-level element (usually <code class="html"><span class="p">&lt;</span><span class="nt">body</span><span class="p">&gt;</span></code>) has the <code class="bash"><span class="s">z3998:drama</span></code> semantic inflection.</p></li>
<li id="7.6.6.2"><aside class="number">7.6.6.2</aside><p>Acts are <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> elements containing at least one <code class="html"><span class="p">&lt;</span><span class="nt">table</span><span class="p">&gt;</span></code> for dialog, and optionally containing an act title and other top-level stage direction.</p></li>
<li id="7.6.6.3"><aside class="number">7.6.6.3</aside><p>Introductory or high-level stage direction is presented using <code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code> elements outside of the dialog table.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"bodymatter z3998:fiction z3998:drama"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"act-1"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"chapter z3998:scene"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>Act <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:roman"</span><span class="p">&gt;</span>I<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Scene: Morning-room in Algernon’s flat in Half-Moon Street. The room is luxuriously and artistically furnished. The sound of a piano is heard in the adjoining room.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">table</span><span class="p">&gt;</span>
			...
		<span class="p">&lt;/</span><span class="nt">table</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:stage-direction"</span><span class="p">&gt;</span>Act Drop<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">body</span><span class="p">&gt;</span></code></figure>
</li>
<li id="7.6.6.4"><aside class="number">7.6.6.4</aside><p>Dramatis personae are presented as a <code class="html"><span class="p">&lt;</span><span class="nt">ul</span><span class="p">&gt;</span></code> element listing the characters.
						</p><figure><code class="css full"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:dramatis-personae"</span><span class="o">]</span><span class="p">{</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">center</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:dramatis-personae"</span><span class="o">]</span> <span class="nt">ul</span><span class="p">{</span>
	<span class="k">list-style</span><span class="p">:</span> <span class="kc">none</span><span class="p">;</span>
	<span class="k">margin</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
	<span class="k">padding</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:dramatis-personae"</span><span class="o">]</span> <span class="nt">ul</span> <span class="nt">li</span><span class="p">{</span>
	<span class="k">margin</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">italic</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"dramatis-personae"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:dramatis-personae"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>Dramatis Personae<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">ul</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>King Henry <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:roman"</span><span class="p">&gt;</span>V<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Duke of Clarence, brother to the King<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span>
		...
	<span class="p">&lt;/</span><span class="nt">ul</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
</section>
<section id="7.7"><aside class="number">7.7</aside>
<h2>Letters</h2>
<p>Letters require particular attention to styling and semantic inflection. Letters may not exactly match the formatting in the source scans, but they are in visual sympathy with the source.</p>
<ol type="1">
<li id="7.7.1"><aside class="number">7.7.1</aside><p>Letters are wrapped in a <code class="html"><span class="p">&lt;</span><span class="nt">blockquote</span><span class="p">&gt;</span></code> element with the appropriate semantic inflection, usually <code class="bash"><span class="s">z3998:letter</span></code>.</p></li>
</ol>
<section id="7.7.2"><aside class="number">7.7.2</aside>
<h3>Letter headers</h3>
<ol type="1">
<li id="7.7.2.1"><aside class="number">7.7.2.1</aside><p>Parts of a letter prior to the body of the letter, for example the location where it is written, the date, and the salutation, are wrapped in a <code class="html"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span></code> element.</p></li>
<li id="7.7.2.2"><aside class="number">7.7.2.2</aside><p>If there is only a salutation and no other header content, the <code class="html"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span></code> element is omitted.</p></li>
<li id="7.7.2.3"><aside class="number">7.7.2.3</aside><p>The location and date of a letter have the semantic inflection of <code class="bash"><span class="s">se:letter.dateline</span></code>. Dates are in a <code class="html"><span class="p">&lt;</span><span class="nt">time</span><span class="p">&gt;</span></code> element with a computer-readable date.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"se:letter.dateline"</span><span class="p">&gt;</span>Blarney Castle, <span class="p">&lt;</span><span class="nt">time</span> <span class="na">datetime</span><span class="o">=</span><span class="s">"1863-10-11"</span><span class="p">&gt;</span>11th of October, 1863<span class="p">&lt;/</span><span class="nt">time</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">header</span><span class="p">&gt;</span></code></figure>
</li>
<li id="7.7.2.4"><aside class="number">7.7.2.4</aside><p>The salutation (for example, “Dear Sir” or “My dearest Jane”) has the semantic inflection of <code class="bash"><span class="s">z3998:salutation</span></code>.</p></li>
<li id="7.7.2.5"><aside class="number">7.7.2.5</aside><p>The first line of a letter after the salutation is not indented.</p></li>
<li id="7.7.2.6"><aside class="number">7.7.2.6</aside><p>Salutations that are within the first line of the letter are wrapped in a <code class="html"><span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:salutation</span><span class="p">&gt;</span></code> element (or a <code class="html"><span class="p">&lt;</span><span class="nt">b</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:salutation</span><span class="p">&gt;</span></code> element if small-caps are desired).
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;&lt;</span><span class="nt">b</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:salutation"</span><span class="p">&gt;</span>Dear Mother<span class="p">&lt;/</span><span class="nt">b</span><span class="p">&gt;</span>, I was so happy to hear from you.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="7.7.2.7"><aside class="number">7.7.2.7</aside><p>The name of the recipient of the letter, when set out other than within a saluation (for example a letter headed “To: John Smith Esquire”), is given the semantic inflection of <code class="bash"><span class="s">z3998:recipient</span></code>. Sometimes this may occur at the end of a letter, particularly for more formal communications, in which case it is placed within a <code class="html"><span class="p">&lt;</span><span class="nt">footer</span><span class="p">&gt;</span></code> element.</p></li>
</ol>
</section>
<section id="7.7.3"><aside class="number">7.7.3</aside>
<h3>Letter footers</h3>
<ol type="1">
<li id="7.7.3.1"><aside class="number">7.7.3.1</aside><p>Parts of a letter after the body of the letter, for example the signature or postscript, are wrapped in a <code class="html"><span class="p">&lt;</span><span class="nt">footer</span><span class="p">&gt;</span></code> element.</p></li>
<li id="7.7.3.2"><aside class="number">7.7.3.2</aside><p>The <code class="html"><span class="p">&lt;</span><span class="nt">footer</span><span class="p">&gt;</span></code> element has the following CSS:
						</p><figure><code class="css full"><span class="nt">footer</span><span class="p">{</span>
	<span class="k">margin-top</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">right</span><span class="p">;</span>
<span class="p">}</span></code></figure>
</li>
<li id="7.7.3.3"><aside class="number">7.7.3.3</aside><p>The valediction (for example, “Yours Truly” or “With best regards”) has the semantic inflection of <code class="bash"><span class="s">z3998:valediction</span></code>.</p></li>
<li id="7.7.3.4"><aside class="number">7.7.3.4</aside><p>The sender’s name has semantic inflection of <code class="bash"><span class="s">z3998:sender</span></code>. If the name appears to be a signature to the letter, it has the <code class="bash"><span class="s">signature</span></code> class and the corresponding <code class="css"><span class="p">.</span><span class="nc">signature</span></code> CSS.
						</p><figure><code class="css full"><span class="p">.</span><span class="nc">signature</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">footer</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span> <span class="na">class</span><span class="o">=</span><span class="s">"signature z3998:sender"</span><span class="p">&gt;&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"name"</span><span class="p">&gt;</span>R. A.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> Johnson<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">footer</span><span class="p">&gt;</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">footer</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span> <span class="na">class</span><span class="o">=</span><span class="s">"z3998:sender"</span><span class="p">&gt;&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">"signature"</span><span class="p">&gt;</span>John Doe<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>, President<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">footer</span><span class="p">&gt;</span></code></figure>
</li>
<li id="7.7.3.5"><aside class="number">7.7.3.5</aside><p>Postscripts have the semantic inflection of <code class="bash"><span class="s">z3998:postscript</span></code> and the following CSS:
						</p><figure><code class="css full"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:postscript"</span><span class="o">]</span><span class="p">{</span>
	<span class="k">margin-top</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">left</span><span class="p">;</span>
<span class="p">}</span></code></figure>
</li>
</ol>
</section>
<section id="id3">
<h3>Examples</h3>
<figure><code class="css full"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:letter"</span><span class="o">]</span> <span class="nt">header</span><span class="p">{</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">right</span><span class="p">;</span>
<span class="p">}</span>

<span class="nt">footer</span><span class="p">{</span>
	<span class="k">margin-top</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">right</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:salutation"</span><span class="o">]</span> <span class="o">+</span> <span class="nt">p</span><span class="o">,</span>
<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:letter"</span><span class="o">]</span> <span class="nt">header</span> <span class="o">+</span> <span class="nt">p</span><span class="p">{</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:sender"</span><span class="o">],</span>
<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:recipient"</span><span class="o">],</span>
<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:salutation"</span><span class="o">]</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">small-caps</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">"z3998:postscript"</span><span class="o">]</span><span class="p">{</span>
	<span class="k">margin-top</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">left</span><span class="p">;</span>
<span class="p">}</span>

<span class="p">.</span><span class="nc">signature</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:letter"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:salutation"</span><span class="p">&gt;</span>Dearest Auntie,<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Please may we have some things for a picnic? Gerald will bring them. I would come myself, but I am a little tired. I think I have been growing rather fast.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">footer</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:valediction"</span><span class="p">&gt;</span>Your loving niece,<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span> <span class="na">class</span><span class="o">=</span><span class="s">"signature"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:sender"</span><span class="p">&gt;</span>Mabel<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:postscript"</span><span class="p">&gt;&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span>P.S.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span><span class="ws">wj</span>—Lots, please, because some of us are very hungry.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">footer</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:letter"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"se:letter.dateline"</span><span class="p">&gt;</span>Gracechurch-street, <span class="p">&lt;</span><span class="nt">time</span> <span class="na">datetime</span><span class="o">=</span><span class="s">"08-02"</span><span class="p">&gt;</span>August 2<span class="p">&lt;/</span><span class="nt">time</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">header</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:salutation"</span><span class="p">&gt;</span>My dear Brother<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>, At last I am able to send you some tidings of my niece, and such as, upon the whole, I hope will give you satisfaction. Soon after you left me on Saturday, I was fortunate enough to find out in what part of London they were. The particulars, I reserve till we meet. It is enough to know they are discovered, I have seen them both⁠<span class="ws">wj</span>—<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>I shall write again as soon as anything more is determined on.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">footer</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:valediction"</span><span class="p">&gt;</span>Yours, etc.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span> <span class="na">class</span><span class="o">=</span><span class="s">"signature"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:sender"</span><span class="p">&gt;</span>Edward Gardner<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">footer</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
</section>
</section>
<section id="7.8"><aside class="number">7.8</aside>
<h2>Images</h2>
<ol type="1">
<li id="7.8.1"><aside class="number">7.8.1</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">img</span><span class="p">&gt;</span></code> elements have an <code class="html"><span class="na">alt</span></code> attribute that uses prose to describe the image in detail; this is what screen reading software will read aloud.
					</p><ol type="1">
<li id="7.8.1.1"><aside class="number">7.8.1.1</aside><p>The <code class="html"><span class="na">alt</span></code> attribute describes the visual image itself in words, which is not the same as writing a caption or describing its place in the book.
							</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">img</span> <span class="na">alt</span><span class="o">=</span><span class="s">"The illustration for chapter 10"</span> <span class="na">src</span><span class="o">=</span><span class="s">"..."</span> <span class="p">/&gt;</span></code></figure>
<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">img</span> <span class="na">alt</span><span class="o">=</span><span class="s">"Pierre’s fruit-filled dinner"</span> <span class="na">src</span><span class="o">=</span><span class="s">"..."</span> <span class="p">/&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">img</span> <span class="na">alt</span><span class="o">=</span><span class="s">"An apple and a pear inside a bowl, resting on a table."</span> <span class="na">src</span><span class="o">=</span><span class="s">"..."</span> <span class="p">/&gt;</span></code></figure>
</li>
<li id="7.8.1.2"><aside class="number">7.8.1.2</aside><p>The <code class="html"><span class="na">alt</span></code> attribute is one or more complete sentences ended with periods or other appropriate punctuation. It is not composed of sentence fragments or complete sentences without ending punctuation.</p></li>
<li id="7.8.1.3"><aside class="number">7.8.1.3</aside><p>The <code class="html"><span class="na">alt</span></code> attribute is not necessarily the same as text in the image’s sibling <code class="html"><span class="p">&lt;</span><span class="nt">figcaption</span><span class="p">&gt;</span></code> element, if one is present.</p></li>
</ol>
</li>
<li id="7.8.2"><aside class="number">7.8.2</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">img</span><span class="p">&gt;</span></code> elements have semantic inflection denoting the type of image. Common values are <code class="bash"><span class="s">z3998:illustration</span></code> or <code class="bash"><span class="s">z3998:photograph</span></code>.</p></li>
<li id="7.8.3"><aside class="number">7.8.3</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">img</span><span class="p">&gt;</span></code> element whose image is black-on-white line art (i.e. exactly two colors, <strong>not</strong> grayscale!) are PNG files with a transparent background. They have the <code class="bash"><span class="s">se:image.color-depth.black-on-transparent</span></code> semantic inflection.</p></li>
<li id="7.8.4"><aside class="number">7.8.4</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">img</span><span class="p">&gt;</span></code> elements that are meant to be aligned on the block level or displayed as full-page images are contained in a parent <code class="html"><span class="p">&lt;</span><span class="nt">figure</span><span class="p">&gt;</span></code> element, with an optional <code class="html"><span class="p">&lt;</span><span class="nt">figcaption</span><span class="p">&gt;</span></code> sibling.
					</p><ol type="1">
<li id="7.8.4.1"><aside class="number">7.8.4.1</aside><p>When contained in a <code class="html"><span class="p">&lt;</span><span class="nt">figure</span><span class="p">&gt;</span></code> element, the <code class="html"><span class="p">&lt;</span><span class="nt">img</span><span class="p">&gt;</span></code> element does not have an <code class="html"><span class="na">id</span></code> attribute; instead the <code class="html"><span class="p">&lt;</span><span class="nt">figure</span><span class="p">&gt;</span></code> element has the <code class="html"><span class="na">id</span></code> attribute.</p></li>
<li id="7.8.4.2"><aside class="number">7.8.4.2</aside><p>An optional <code class="html"><span class="p">&lt;</span><span class="nt">figcaption</span><span class="p">&gt;</span></code> element containing a concise context-dependent caption may follow the <code class="html"><span class="p">&lt;</span><span class="nt">img</span><span class="p">&gt;</span></code> element within a <code class="html"><span class="p">&lt;</span><span class="nt">figure</span><span class="p">&gt;</span></code> element. This caption depends on the surrounding context, and is not necessarily (or even ideally) identical to the <code class="html"><span class="p">&lt;</span><span class="nt">img</span><span class="p">&gt;</span></code> element’s <code class="html"><span class="na">alt</span></code> attribute.</p></li>
<li id="7.8.4.3"><aside class="number">7.8.4.3</aside><p>All figure elements, regardless of positioning, have this CSS:
							</p><figure><code class="css full"><span class="nt">figure</span> <span class="nt">img</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">block</span><span class="p">;</span>
	<span class="k">margin</span><span class="p">:</span> <span class="kc">auto</span><span class="p">;</span>
	<span class="k">max-width</span><span class="p">:</span> <span class="mi">100</span><span class="kt">%</span><span class="p">;</span>
<span class="p">}</span>

<span class="nt">figure</span> <span class="o">+</span> <span class="nt">p</span><span class="p">{</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="nt">figcaption</span><span class="p">{</span>
	<span class="k">font-size</span><span class="p">:</span> <span class="mf">.75</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">italic</span><span class="p">;</span>
	<span class="k">margin</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span></code></figure>
</li>
<li id="7.8.4.4"><aside class="number">7.8.4.4</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">figure</span><span class="p">&gt;</span></code> elements that are meant to be displayed as full-page images have the <code class="bash"><span class="s">full-page</span></code> class and this additional CSS:
							</p><figure><code class="css full"><span class="nt">figure</span><span class="p">.</span><span class="nc">full-page</span><span class="p">{</span>
	<span class="k">margin</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
	<span class="k">max-height</span><span class="p">:</span> <span class="mi">100</span><span class="kt">%</span><span class="p">;</span>
	<span class="k">page-break-before</span><span class="p">:</span> <span class="kc">always</span><span class="p">;</span>
	<span class="k">page-break-after</span><span class="p">:</span> <span class="kc">always</span><span class="p">;</span>
	<span class="k">page-break-inside</span><span class="p">:</span> <span class="kc">avoid</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">center</span><span class="p">;</span>
<span class="p">}</span></code></figure>
</li>
<li id="7.8.4.5"><aside class="number">7.8.4.5</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">figure</span><span class="p">&gt;</span></code> elements that meant to be aligned block-level with the text have this additional CSS:
							</p><figure><code class="css full"><span class="nt">figure</span><span class="p">{</span>
	<span class="k">margin</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span> <span class="kc">auto</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">center</span><span class="p">;</span>
<span class="p">}</span></code></figure>
</li>
</ol>
</li>
</ol>
<section id="id4">
<h3>Examples</h3>
<figure><code class="css full"><span class="c">/* If the image is meant to be on its own page, use this selector... */</span>
<span class="nt">figure</span><span class="p">.</span><span class="nc">full-page</span><span class="p">{</span>
	<span class="k">margin</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
	<span class="k">max-height</span><span class="p">:</span> <span class="mi">100</span><span class="kt">%</span><span class="p">;</span>
	<span class="k">page-break-before</span><span class="p">:</span> <span class="kc">always</span><span class="p">;</span>
	<span class="k">page-break-after</span><span class="p">:</span> <span class="kc">always</span><span class="p">;</span>
	<span class="k">page-break-inside</span><span class="p">:</span> <span class="kc">avoid</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">center</span><span class="p">;</span>
<span class="p">}</span>

<span class="c">/* If the image is meant to be inline with the text, use this selector... */</span>
<span class="nt">figure</span><span class="p">{</span>
	<span class="k">margin</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span> <span class="kc">auto</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">center</span><span class="p">;</span>
<span class="p">}</span>

<span class="c">/* In all cases, also include the below styles */</span>
<span class="nt">figure</span> <span class="nt">img</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">block</span><span class="p">;</span>
	<span class="k">margin</span><span class="p">:</span> <span class="kc">auto</span><span class="p">;</span>
	<span class="k">max-width</span><span class="p">:</span> <span class="mi">100</span><span class="kt">%</span><span class="p">;</span>
<span class="p">}</span>

<span class="nt">figure</span> <span class="o">+</span> <span class="nt">p</span><span class="p">{</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="nt">figcaption</span><span class="p">{</span>
	<span class="k">font-size</span><span class="p">:</span> <span class="mf">.75</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">italic</span><span class="p">;</span>
	<span class="k">margin</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">figure</span> <span class="na">id</span><span class="o">=</span><span class="s">"illustration-10"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">img</span> <span class="na">alt</span><span class="o">=</span><span class="s">"An apple and a pear inside a bowl, resting on a table."</span> <span class="na">src</span><span class="o">=</span><span class="s">"../images/illustration-10.jpg"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:photograph"</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">figcaption</span><span class="p">&gt;</span>The Monk’s Repast<span class="p">&lt;/</span><span class="nt">figcaption</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">figure</span><span class="p">&gt;</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">figure</span> <span class="na">class</span><span class="o">=</span><span class="s">"full-page"</span> <span class="na">id</span><span class="o">=</span><span class="s">"image-11"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">img</span> <span class="na">alt</span><span class="o">=</span><span class="s">"A massive whale breaching the water, with a sailor floating in the water directly within the whale’s mouth."</span> <span class="na">src</span><span class="o">=</span><span class="s">"../images/illustration-11.jpg"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:illustration"</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">figcaption</span><span class="p">&gt;</span>The Whale eats Sailor Jim.<span class="p">&lt;/</span><span class="nt">figcaption</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">figure</span><span class="p">&gt;</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He saw strange alien text that looked like this: <span class="p">&lt;</span><span class="nt">img</span> <span class="na">alt</span><span class="o">=</span><span class="s">"A line of alien heiroglyphs."</span> <span class="na">src</span><span class="o">=</span><span class="s">"../images/alien-text.svg"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:illustration se:color-depth.black-on-transparent"</span> <span class="p">/&gt;</span>. There was nothing else amongst the ruins.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</section>
</section>
<section id="7.9"><aside class="number">7.9</aside>
<h2>List of Illustrations (the LoI)</h2>
<p>If an ebook has any illustrations that are <em>major structural components</em> of the work (even just one!), then the ebook includes an <code class="path">loi.xhtml</code> file at the end of the ebook. This file lists the illustrations in the ebook, along with a short caption or description.</p>
<ol type="1">
<li id="7.9.1"><aside class="number">7.9.1</aside><p>The LoI is an XHTML file located in <code class="path">./src/epub/text/loi.xhtml</code>.</p></li>
<li id="7.9.2"><aside class="number">7.9.2</aside><p>The LoI file has the <code class="bash"><span class="s">backmatter</span></code> semantic inflection.</p></li>
<li id="7.9.3"><aside class="number">7.9.3</aside><p>The LoI only contains links to images that are major structural components of the work.
					</p><ol type="1">
<li id="7.9.3.1"><aside class="number">7.9.3.1</aside><p>An illustration is a major structural component if, for example: it is an illustration of events in the book, like a full-page drawing or end-of-chapter decoration; it is essential to the plot, like a diagram of a murder scene or a map; or it is a component of the text, like photographs in a documentary narrative.</p></li>
<li id="7.9.3.2"><aside class="number">7.9.3.2</aside><p>An illustration is <em>not</em> a major structural components if, for example: it is a drawing used to represent a person’s signature, like an X mark; it is an inline drawing representing text in alien languages; it is a drawing used as a layout element to illustrate forms, tables, or diagrams.</p></li>
</ol>
</li>
<li id="7.9.4"><aside class="number">7.9.4</aside><p>The LoI file contains a single <code class="html"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"loi"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"loi"</span><span class="p">&gt;</span></code> element, which in turn contains an <code class="html"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>List of Illustrations<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code> element, followed by a <code class="html"><span class="p">&lt;</span><span class="nt">nav</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"loi"</span><span class="p">&gt;</span></code> element containing an <code class="html"><span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span></code> element, which in turn contains list items representing the images.</p></li>
<li id="7.9.5"><aside class="number">7.9.5</aside><p>If an image listed in the LoI has a <code class="html"><span class="p">&lt;</span><span class="nt">figcaption</span><span class="p">&gt;</span></code> element, then that caption is used in the anchor text for that LoI entry. If not, the image’s <code class="html"><span class="na">alt</span></code> attribute is used. If the <code class="html"><span class="p">&lt;</span><span class="nt">figcaption</span><span class="p">&gt;</span></code> element is too long for a concise LoI entry, the <code class="html"><span class="na">alt</span></code> attribute is used instead.</p></li>
<li id="7.9.6"><aside class="number">7.9.6</aside><p>Links to the images go directly to the image’s corresponding <code class="html"><span class="na">id</span></code> hashes, not just the top of the containing file.</p></li>
</ol>
<section id="id5">
<h3>Examples</h3>
<figure><code class="html full"><span class="cp">&lt;?xml version="1.0" encoding="UTF-8"?&gt;</span>
<span class="p">&lt;</span><span class="nt">html</span> <span class="na">xmlns</span><span class="o">=</span><span class="s">"http://www.w3.org/1999/xhtml"</span> <span class="na">xmlns:epub</span><span class="o">=</span><span class="s">"http://www.idpf.org/2007/ops"</span> <span class="na">epub:prefix</span><span class="o">=</span><span class="s">"z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: http://standardebooks.org/vocab/1.0"</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"en-GB"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">head</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>List of Illustrations<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">link</span> <span class="na">href</span><span class="o">=</span><span class="s">"../css/core.css"</span> <span class="na">rel</span><span class="o">=</span><span class="s">"stylesheet"</span> <span class="na">type</span><span class="o">=</span><span class="s">"text/css"</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">link</span> <span class="na">href</span><span class="o">=</span><span class="s">"../css/local.css"</span> <span class="na">rel</span><span class="o">=</span><span class="s">"stylesheet"</span> <span class="na">type</span><span class="o">=</span><span class="s">"text/css"</span><span class="p">/&gt;</span>
	<span class="p">&lt;/</span><span class="nt">head</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"backmatter"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"loi"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"loi"</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">nav</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"loi"</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>List of Illustrations<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span>
					<span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
						<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">"../text/preface.xhtml#the-edge-of-the-world"</span><span class="p">&gt;</span>The Edge of the World<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
					<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span>
					...
				<span class="p">&lt;/</span><span class="nt">ol</span><span class="p">&gt;</span>
			<span class="p">&lt;/</span><span class="nt">nav</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">body</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">html</span><span class="p">&gt;</span></code></figure>
</section>
</section>
<section id="7.10"><aside class="number">7.10</aside>
<h2>Endnotes</h2>
<ol type="1">
<li id="7.10.1"><aside class="number">7.10.1</aside><p>Ebooks do not have footnotes, only endnotes. Footnotes are instead converted to endnotes.</p></li>
<li id="7.10.2"><aside class="number">7.10.2</aside><p>“Ibid.” is a Latinism commonly used in endnotes to indicate that the source for a quotation or reference is the same as the last-mentioned source.
					</p><p>When the last-mentioned source is in the previous endnote, Ibid. is replaced by the full reference; otherwise Ibid. is left as-is. Since ebooks use popup endnotes, “Ibid.” becomes meaningless without context.</p>
</li>
</ol>
<section id="7.10.3"><aside class="number">7.10.3</aside>
<h3>Noterefs</h3>
<p>The noteref is the superscripted number in the body text that links to the endnote at the end of the book.</p>
<ol type="1">
<li id="7.10.3.1"><aside class="number">7.10.3.1</aside><p>Endnotes are referenced in the text by an <code class="html"><span class="p">&lt;</span><span class="nt">a</span><span class="p">&gt;</span></code> element with the semantic inflection <code class="bash"><span class="s">noteref</span></code>.
						</p><ol type="1">
<li id="7.10.3.1.1"><aside class="number">7.10.3.1.1</aside><p>Noterefs point directly to the corresponding endnote <code class="html"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span></code> element in the endnotes file.</p></li>
<li id="7.10.3.1.2"><aside class="number">7.10.3.1.2</aside><p>Noterefs have an <code class="html"><span class="na">id</span></code> attribute like <code class="bash"><span class="s">noteref-n</span></code>, where <code class="bash"><span class="s">n</span></code> is identical to the endnote number.</p></li>
<li id="7.10.3.1.3"><aside class="number">7.10.3.1.3</aside><p>The text of the noteref is the endnote number.</p></li>
</ol>
</li>
<li id="7.10.3.2"><aside class="number">7.10.3.2</aside><p>If located at the end of a sentence, noterefs are placed after ending punctuation.</p></li>
<li id="7.10.3.3"><aside class="number">7.10.3.3</aside><p>If the endnote references an entire sentence in quotation marks, or the last word in a sentence in quotation marks, then the noteref is placed outside the quotation marks.</p></li>
</ol>
</section>
<section id="7.10.4"><aside class="number">7.10.4</aside>
<h3>The endnotes file</h3>
<ol type="1">
<li id="7.10.4.1"><aside class="number">7.10.4.1</aside><p>Endnotes are in an XHTML file located in <code class="path">./src/epub/text/endnotes.xhtml</code>.</p></li>
<li id="7.10.4.2"><aside class="number">7.10.4.2</aside><p>The endnotes file has the <code class="bash"><span class="s">backmatter</span></code> semantic inflection.</p></li>
<li id="7.10.4.3"><aside class="number">7.10.4.3</aside><p>The endnotes file contains a single <code class="html"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"endnotes"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"endnotes"</span><span class="p">&gt;</span></code> element, which in turn contains an <code class="html"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>Endnotes<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code> element, followed by an <code class="html"><span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span></code> element containing list items representing the endnotes.</p></li>
<li id="7.10.4.4"><aside class="number">7.10.4.4</aside><p>Each endnote’s <code class="html"><span class="na">id</span></code> attribute is in sequential ascending order.</p></li>
</ol>
</section>
<section id="7.10.5"><aside class="number">7.10.5</aside>
<h3>Individual endnotes</h3>
<ol type="1">
<li id="7.10.5.1"><aside class="number">7.10.5.1</aside><p>An endnote is an <code class="html"><span class="p">&lt;</span><span class="nt">li</span> <span class="na">id</span><span class="o">=</span><span class="s">"note-n"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"endnote"</span><span class="p">&gt;</span></code> element containing one or more block-level text elements and one backlink element.</p></li>
<li id="7.10.5.2"><aside class="number">7.10.5.2</aside><p>Each endnote’s contains a backlink, which has the semantic inflection <code class="bash"><span class="s">backlink</span></code>, contains the text <code class="string">↩</code>, and has the <code class="html"><span class="na">href</span></code> attribute pointing to the corresponding noteref hash.
						</p><ol type="1">
<li id="7.10.5.2.1"><aside class="number">7.10.5.2.1</aside><p>In endnotes where the last block-level element is a <code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code> element, the backlink goes at the end of the <code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code> element, preceded by exactly one space.</p></li>
<li id="7.10.5.2.2"><aside class="number">7.10.5.2.2</aside><p>In endnotes where the last block-level element is verse, quotation, or otherwise not plain prose text, the backlink goes in its own <code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code> element following the last block-level element in the endnote.</p></li>
</ol>
</li>
<li id="7.10.5.3"><aside class="number">7.10.5.3</aside><p>Endnotes with ending citations have those citations are wrapped in a <code class="html"><span class="p">&lt;</span><span class="nt">cite</span><span class="p">&gt;</span></code> element, including any em-dashes. A space follows the <code class="html"><span class="p">&lt;</span><span class="nt">cite</span><span class="p">&gt;</span></code> element, before the backlink.</p></li>
</ol>
</section>
<section id="id6">
<h3>Examples</h3>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>... a continent that was not rent asunder by volcanic forces as was that legendary one of Atlantis in the Eastern Ocean.<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">"endnotes.xhtml#note-1"</span> <span class="na">id</span><span class="o">=</span><span class="s">"noteref-1"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"noteref"</span><span class="p">&gt;</span>1<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span> My work in Java, in Papua, ...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure><code class="html full"><span class="cp">&lt;?xml version="1.0" encoding="UTF-8"?&gt;</span>
<span class="p">&lt;</span><span class="nt">html</span> <span class="na">xmlns</span><span class="o">=</span><span class="s">"http://www.w3.org/1999/xhtml"</span> <span class="na">xmlns:epub</span><span class="o">=</span><span class="s">"http://www.idpf.org/2007/ops"</span> <span class="na">epub:prefix</span><span class="o">=</span><span class="s">"z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: http://standardebooks.org/vocab/1.0"</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"en-GB"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">head</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>Endnotes<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">link</span> <span class="na">href</span><span class="o">=</span><span class="s">"../css/core.css"</span> <span class="na">rel</span><span class="o">=</span><span class="s">"stylesheet"</span> <span class="na">type</span><span class="o">=</span><span class="s">"text/css"</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">link</span> <span class="na">href</span><span class="o">=</span><span class="s">"../css/local.css"</span> <span class="na">rel</span><span class="o">=</span><span class="s">"stylesheet"</span> <span class="na">type</span><span class="o">=</span><span class="s">"text/css"</span><span class="p">/&gt;</span>
	<span class="p">&lt;/</span><span class="nt">head</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"backmatter"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"endnotes"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"endnotes"</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>Endnotes<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">li</span> <span class="na">id</span><span class="o">=</span><span class="s">"note-1"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"endnote"</span><span class="p">&gt;</span>
					<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>For more detailed observations on these points refer to <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"name"</span><span class="p">&gt;</span>G.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> Volkens, “Uber die Karolinen Insel Yap.” <span class="p">&lt;</span><span class="nt">cite</span><span class="p">&gt;</span>—<span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"name eoc"</span><span class="p">&gt;</span>W. T. G.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">cite</span><span class="p">&gt;</span> <span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">"chapter-2.xhtml#noteref-1"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"backlink"</span><span class="p">&gt;</span>↩<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
				<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span>
			<span class="p">&lt;/</span><span class="nt">ol</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">li</span> <span class="na">id</span><span class="o">=</span><span class="s">"note-2"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"endnote"</span><span class="p">&gt;</span>
					<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:verse"</span><span class="p">&gt;</span>
						<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
							<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>“Who never ceases still to strive,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
							<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
							<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>’Tis him we can deliver.”<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
						<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
					<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span>
					<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
						<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">"chapter-4.xhtml#noteref-2"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"backlink"</span><span class="p">&gt;</span>↩<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
					<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
				<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span>
			<span class="p">&lt;/</span><span class="nt">ol</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">body</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">html</span><span class="p">&gt;</span></code></figure>
</section>
</section>
</section>
		</article>
	</main>
<?= Template::Footer() ?>
