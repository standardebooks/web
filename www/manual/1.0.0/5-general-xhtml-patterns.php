<?
require_once('Core.php');
?><?= Template::Header(['title' => '5. General XHTML Patterns - The Standard Ebooks Manual', 'highlight' => 'contribute', 'manual' => true]) ?>
	<main class="manual"><nav><p><a href="/manual/1.0.0">The Standard Ebooks Manual of Style</a></p><ol><li><p><a href="/manual/1.0.0/1-code-style">1. XHTML, CSS, and SVG Code Style</a></p><ol><li><p><a href="/manual/1.0.0/1-code-style#1.1">1.1 XHTML formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.2">1.2 CSS formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.3">1.3 SVG Formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.4">1.4 Commits and Commit Messages</a></p></li></ol></li><li><p><a href="/manual/1.0.0/2-filesystem">2. Filesystem Layout and File Naming Conventions</a></p><ol><li><p><a href="/manual/1.0.0/2-filesystem#2.1">2.1 File locations</a></p></li><li><p><a href="/manual/1.0.0/2-filesystem#2.2">2.2 XHTML file naming conventions</a></p></li><li><p><a href="/manual/1.0.0/2-filesystem#2.3">2.3 The se-lint-ignore.xml file</a></p></li></ol></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook">3. The Structure of an Ebook</a></p><ol><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.1">3.1 Front matter</a></p></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.2">3.2 Body matter</a></p></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.3">3.3 Back matter</a></p></li></ol></li><li><p><a href="/manual/1.0.0/4-semantics">4. Semantics</a></p><ol><li><p><a href="/manual/1.0.0/4-semantics#4.1">4.1 Semantic Tags</a></p></li><li><p><a href="/manual/1.0.0/4-semantics#4.2">4.2 Semantic Inflection</a></p></li></ol></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns">5. General XHTML Patterns</a></p><ol><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.1">5.1 id attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.2">5.2 class attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.3">5.3 xml:lang attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.4">5.4 The &lt;title&gt; element</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.5">5.5 Ordered/numbered and unordered lists</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.6">5.6 Tables</a></p></li></ol></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns">6. Standard Ebooks Section Patterns</a></p><ol><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.1">6.1 The title string</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.2">6.2 The table of contents</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.3">6.3 The titlepage</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.4">6.4 The imprint</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.5">6.5 The half title page</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.6">6.6 The colophon</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.7">6.7 The Uncopyright</a></p></li></ol></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns">7. High Level Structural Patterns</a></p><ol><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.1">7.1 Sectioning</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.2">7.2 Headers</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.3">7.3 Dedications</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.4">7.4 Epigraphs</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.5">7.5 Poetry, verse, and songs</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.6">7.6 Plays and drama</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.7">7.7 Letters</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.8">7.8 Images</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.9">7.9 List of Illustrations (the LoI)</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.10">7.10 Endnotes</a></p></li></ol></li><li><p><a href="/manual/1.0.0/8-typography">8. Typography</a></p><ol><li><p><a href="/manual/1.0.0/8-typography#8.1">8.1 Section titles and ordinals</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.2">8.2 Italics</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.3">8.3 Capitalization</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.4">8.4 Indentation</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.5">8.5 Chapter headers</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.6">8.6 Ligatures</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.7">8.7 Punctuation and spacing</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.8">8.8 Numbers, measurements, and math</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.9">8.9 Latinisms</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.10">8.10 Initials and abbreviations</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.11">8.11 Times</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.12">8.12 Chemicals and compounds</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.13">8.13 Temperatures</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.14">8.14 Scansion</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.15">8.15 Legal cases and terms</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.16">8.16 Morse code</a></p></li></ol></li><li><p><a href="/manual/1.0.0/9-metadata">9. Metadata</a></p><ol><li><p><a href="/manual/1.0.0/9-metadata#9.1">9.1 General URL rules</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.2">9.2 The ebook identifier</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.3">9.3 Publication date and release identifiers</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.4">9.4 Book titles</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.5">9.5 Book subjects</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.6">9.6 Book descriptions</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.7">9.7 Book language</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.8">9.8 Book transcription and page scan sources</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.9">9.9 Additional book metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.10">9.10 General contributor rules</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.11">9.11 The author metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.12">9.12 The translator metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.13">9.13 The illustrator metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.14">9.14 The cover artist metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.15">9.15 Metadata for additional contributors</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.16">9.16 Transcriber metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.17">9.17 Producer metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.18">9.18 The ebook manifest</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.19">9.19 The ebook spine</a></p></li></ol></li><li><p><a href="/manual/1.0.0/10-art-and-images">10. Art and Images</a></p><ol><li><p><a href="/manual/1.0.0/10-art-and-images#10.1">10.1 Complete list of files</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.2">10.2 SVG patterns</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.3">10.3 The cover image</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.4">10.4 The titlepage image</a></p></li></ol></li></ol></nav>
		<article>

<section id="5"><aside class="number">5</aside>
<h1>General XHTML Patterns</h1>
<p>This section covers general patterns used when producing XHTML that are not specific to ebooks.</p>
<section id="5.1"><aside class="number">5.1</aside>
<h2><code class="html">id</code> attributes</h2>
<section id="5.1.1"><aside class="number">5.1.1</aside>
<h3><code class="html">id</code> attributes of <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> and <code class="html"><span class="p">&lt;</span><span class="nt">article</span><span class="p">&gt;</span></code> elements</h3>
<ol type="1">
<li id="5.1.1.1"><aside class="number">5.1.1.1</aside><p>Each <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> has an <code class="html">id</code> attribute.</p></li>
<li id="5.1.1.2"><aside class="number">5.1.1.2</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> or <code class="html"><span class="p">&lt;</span><span class="nt">article</span><span class="p">&gt;</span></code> elements that are direct children of the <code class="html"><span class="p">&lt;</span><span class="nt">body</span><span class="p">&gt;</span></code> element have an <code class="html">id</code> attribute identical to the filename containing that <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> or <code class="html"><span class="p">&lt;</span><span class="nt">article</span><span class="p">&gt;</span></code>, without the trailing extension.</p></li>
<li id="5.1.1.3"><aside class="number">5.1.1.3</aside><p>In files containing multiple <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> or <code class="html"><span class="p">&lt;</span><span class="nt">article</span><span class="p">&gt;</span></code> elements, each of those elements has an <code class="html">id</code> attribute identical to what the filename <em>would</em> be if the section was in an individual file, without the trailing extension.
						</p><figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"bodymatter z3998:fiction"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">article</span> <span class="na">id</span><span class="o">=</span><span class="s">"the-fox-and-the-grapes"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"se:short-story"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>The Fox and the Grapes<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">article</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">article</span> <span class="na">id</span><span class="o">=</span><span class="s">"the-goose-that-laid-the-golden-eggs"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"se:short-story"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>The Goose That Laid the Golden Eggs<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">article</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">body</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="5.1.2"><aside class="number">5.1.2</aside>
<h3><code class="html">id</code> attributes of other elements</h3>
<ol type="1">
<li id="5.1.2.1"><aside class="number">5.1.2.1</aside><p><code class="html">id</code> attributes are generally used to identify parts of the document that a reader may wish to navigate to using a hash in the URL. That generally means major structural divisions. Therefore, elements that are not <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> or <code class="html"><span class="p">&lt;</span><span class="nt">article</span><span class="p">&gt;</span></code> elements do not have an <code class="html">id</code> attribute, unless a part of the ebook, like an endnote, refers to a specific point in the book, and a direct link is desirable.</p></li>
<li id="5.1.2.2"><aside class="number">5.1.2.2</aside><p><code class="html">id</code> attributes are not used as hooks for CSS styling.</p></li>
<li id="5.1.2.3"><aside class="number">5.1.2.3</aside><p>If an element that is not a <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> or <code class="html"><span class="p">&lt;</span><span class="nt">article</span><span class="p">&gt;</span></code> requires an <code class="html">id</code> attribute, the attribute’s value is the name of the tag followed by <code class="path">-N</code>, where <code class="path">N</code> is the sequence number of the tag start at <code class="path">1</code>.
						</p><figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>See <span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">"#p-4"</span><span class="p">&gt;</span>this paragraph<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span> for more details.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span> <span class="na">id</span><span class="o">=</span><span class="s">"p-4"</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
</section>
<section id="5.2"><aside class="number">5.2</aside>
<h2><code class="html">class</code> attributes</h2>
<p>Classes denote a group of elements sharing a similar style.</p>
<ol type="1">
<li id="5.2.1"><aside class="number">5.2.1</aside><p>Classes are <em>not</em> used as single-use style hooks. There is almost always a way to compose a CSS selector to select a single element without the use of a one-off class.
					</p><figure class="wrong"><code class="css full"><span class="p">.</span><span class="nc">business-card</span><span class="p">{</span>
	<span class="k">border</span><span class="p">:</span> <span class="mi">1</span><span class="kt">px</span> <span class="kc">solid</span><span class="p">;</span>
	<span class="k">padding</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"bodymatter z3998:fiction"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"chapter"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">class</span><span class="o">=</span><span class="s">"business-card"</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>John Doe, <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"eoc"</span><span class="p">&gt;</span>Esq.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">body</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="css full"><span class="p">#</span><span class="nn">chapter-3</span> <span class="nt">blockquote</span><span class="p">{</span>
	<span class="k">border</span><span class="p">:</span> <span class="mi">1</span><span class="kt">px</span> <span class="kc">solid</span><span class="p">;</span>
	<span class="k">padding</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"bodymatter z3998:fiction"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"chapter-3"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"chapter"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">blockquote</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>John Doe, <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"eoc"</span><span class="p">&gt;</span>Esq.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">body</span><span class="p">&gt;</span></code></figure>
</li>
<li id="5.2.2"><aside class="number">5.2.2</aside><p>Classes are used to style a recurring <em>class</em> of elements, i.e. a class of element that appears more than once in an ebook.
					</p><figure class="corrected"><code class="css full"><span class="p">.</span><span class="nc">business-card</span><span class="p">{</span>
	<span class="k">border</span><span class="p">:</span> <span class="mi">1</span><span class="kt">px</span> <span class="kc">solid</span><span class="p">;</span>
	<span class="k">padding</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"bodymatter z3998:fiction"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"chapter-3"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"chapter"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">class</span><span class="o">=</span><span class="s">"business-card"</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Jane Doe, <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"eoc"</span><span class="p">&gt;</span>Esq.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">class</span><span class="o">=</span><span class="s">"business-card"</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>John Doe, <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"eoc"</span><span class="p">&gt;</span>Esq.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">body</span><span class="p">&gt;</span></code></figure>
</li>
<li id="5.2.3"><aside class="number">5.2.3</aside><p>Class names describe <em>what</em> they are styling semantically, <em>not</em> the actual style the class is applying.
					</p><figure class="wrong"><code class="css full"><span class="p">.</span><span class="nc">black-border</span><span class="p">{</span>
	<span class="k">border</span><span class="p">:</span> <span class="mi">1</span><span class="kt">px</span> <span class="kc">solid</span><span class="p">;</span>
	<span class="k">padding</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure class="corrected"><code class="css full"><span class="p">.</span><span class="nc">business-card</span><span class="p">{</span>
	<span class="k">border</span><span class="p">:</span> <span class="mi">1</span><span class="kt">px</span> <span class="kc">solid</span><span class="p">;</span>
	<span class="k">padding</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span></code></figure>
</li>
</ol>
</section>
<section id="5.3"><aside class="number">5.3</aside>
<h2><code class="html">xml:lang</code> attributes</h2>
<ol type="1">
<li id="5.3.1"><aside class="number">5.3.1</aside><p>When words are required to be pronounced in a language other than English, the <code class="html">xml:lang</code> attribute is used to indicate the IETF language tag in use.
					</p><ol type="1">
<li id="5.3.1.1"><aside class="number">5.3.1.1</aside><p>The <code class="html">xml:lang</code> attribute is used even if a word is not required to be italicized. This allows screen readers to understand that a particular word or phrase should be pronounced in a certain way. A <code class="html"><span class="p">&lt;</span><span class="nt">span</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"TAG"</span><span class="p">&gt;</span></code> element is used to wrap text that has non-English pronunciation but that does not need further visual styling.</p></li>
<li id="5.3.1.2"><aside class="number">5.3.1.2</aside><p>The <code class="html">xml:lang</code> attribute is included in <em>any</em> word that requires special pronunciation, including names of places and titles of books.</p></li>
</ol>
<figure class="corrected"><code class="html full">She opened the book titled <span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"se:name.publication.book"</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"la"</span><span class="p">&gt;</span>Mortis Imago<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>.</code></figure>
<ol type="1">
<li id="5.3.1.3"><aside class="number">5.3.1.3</aside><p>The <code class="html">xml:lang</code> attribute is applied to the highest-level tag possible. If italics are required and moving the <code class="html">xml:lang</code> attribute would also remove an <code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code> element, the parent element can be styled with <code class="css"><span class="nt">body</span> <span class="o">[</span><span class="nt">xml</span><span class="o">|</span><span class="nt">lang</span><span class="o">]</span><span class="p">{</span> <span class="k">font-style</span><span class="p">:</span> <span class="kc">italic</span><span class="p">;</span> <span class="p">}</span></code>.</p></li>
</ol>
<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">blockquote</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"es"</span><span class="p">&gt;</span>“Como estas?” el preguntó.<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"es"</span><span class="p">&gt;</span>“Bien, gracias,” dijo ella.<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"es"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Como estas?” el preguntó.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Bien, gracias,” dijo ella.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="5.4"><aside class="number">5.4</aside>
<h2>The <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element</h2>
<ol type="1">
<li id="5.4.1"><aside class="number">5.4.1</aside><p>The <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element contains an appropriate description of the local file only. It does not contain the book title.</p></li>
</ol>
<section id="5.4.2"><aside class="number">5.4.2</aside>
<h3>Titles of files that are an individual chapter or part division</h3>
<ol type="1">
<li id="5.4.2.1"><aside class="number">5.4.2.1</aside><p>Convert chapter or part numbers that are in Roman numerals to decimal numbers. Do not convert other Roman numerals that may be in the chapter title.
						</p><figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>Chapter 10<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span></code></figure>
</li>
<li id="5.4.2.2"><aside class="number">5.4.2.2</aside><p>If a chapter or part is only an ordinal and has no title or subtitle, the <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element is <code class="html">Chapter</code> followed by the chapter number.
						</p><figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>Chapter 4<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span>
...
<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title z3998:roman"</span><span class="p">&gt;</span>IV<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
...
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The chapter body...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="5.4.2.3"><aside class="number">5.4.2.3</aside><p>If a chapter or part has a title or subtitle, the <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element is <code class="html">Chapter</code>, followed by the chapter number in decimal, followed by a colon and a single space, followed by the title or subtitle.
						</p><figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>Chapter 6: The Reign of Louis XVI<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span>
...
<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:roman"</span><span class="p">&gt;</span>VI<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"subtitle"</span><span class="p">&gt;</span>The Reign of Louis <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:roman"</span><span class="p">&gt;</span>XVI<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
...
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The chapter body...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="5.4.3"><aside class="number">5.4.3</aside>
<h3>Titles of files that are not chapter or part divisions</h3>
<ol type="1">
<li id="5.4.3.1"><aside class="number">5.4.3.1</aside><p>Files that are not a chapter or a part division, like a preface, introduction, or epigraph, have a <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element that contains the complete title of the section.
						</p><figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>Preface<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span></code></figure>
</li>
<li id="5.4.3.2"><aside class="number">5.4.3.2</aside><p>If a file contains a section with a title or subtitle, the <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element contains the title, followed by a colon and a single space, followed by the title or subtitle.
						</p><figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>Quevedo and His Works: With an Essay on the Picaresque Novel<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
</section>
<section id="5.5"><aside class="number">5.5</aside>
<h2>Ordered/numbered and unordered lists</h2>
<ol type="1">
<li id="5.5.1"><aside class="number">5.5.1</aside><p>All <code class="html"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span></code> children of <code class="html"><span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span></code> and <code class="html"><span class="p">&lt;</span><span class="nt">ul</span><span class="p">&gt;</span></code> elements have at least one direct child block-level tag. This is usually a <code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code> element, but not necessarily; for example, a <code class="html"><span class="p">&lt;</span><span class="nt">blockquote</span><span class="p">&gt;</span></code> element might also be appropriate.
					</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">ul</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>Don’t forget to feed the pigs.<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">ul</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">ul</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Don’t forget to feed the pigs.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">ul</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="5.6"><aside class="number">5.6</aside>
<h2>Tables</h2>
<ol type="1">
<li id="5.6.1"><aside class="number">5.6.1</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">table</span><span class="p">&gt;</span></code> elements have a direct child <code class="html"><span class="p">&lt;</span><span class="nt">tbody</span><span class="p">&gt;</span></code> element.
					</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">table</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>1<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>2<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">table</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">table</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">tbody</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>1<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>2<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">tbody</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">table</span><span class="p">&gt;</span></code></figure>
</li>
<li id="5.6.2"><aside class="number">5.6.2</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">table</span><span class="p">&gt;</span></code> elements may have an optional direct child <code class="html"><span class="p">&lt;</span><span class="nt">thead</span><span class="p">&gt;</span></code> element, if a table heading is desired.</p></li>
<li id="5.6.3"><aside class="number">5.6.3</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">table</span><span class="p">&gt;</span></code> elements that are used to display tabular numerical data, for example columns of sums, have CSS styling for tabular numbers: <code class="css"><span class="p">{</span> <span class="k">font-variant-numeric</span><span class="p">:</span> <span class="n">tabular-nums</span><span class="p">;</span> <span class="p">}</span></code>.
					</p><figure class="corrected"><code class="css full"><span class="nt">table</span> <span class="nt">td</span><span class="p">:</span><span class="nd">last-child</span><span class="p">{</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">right</span><span class="p">;</span>
	<span class="k">font-variant-numeric</span><span class="p">:</span> <span class="n">tabular-nums</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">table</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">tbody</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>Amount 1<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>100<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>Amount 2<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>300<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>Total<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>400<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">tbody</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">table</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
</section>
		</article>
	</main>
<?= Template::Footer() ?>
