<?
require_once('Core.php');
?><?= Template::Header(['title' => '1. XHTML, CSS, and SVG Code Style - The Standard Ebooks Manual', 'highlight' => 'contribute', 'manual' => true]) ?>
	<main class="manual"><nav><p><a href="/manual/1.0.0">The Standard Ebooks Manual of Style</a></p><ol><li><p><a href="/manual/1.0.0/1-code-style">1. XHTML, CSS, and SVG Code Style</a></p><ol><li><p><a href="/manual/1.0.0/1-code-style#1.1">1.1 XHTML formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.2">1.2 CSS formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.3">1.3 SVG Formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.4">1.4 Commits and Commit Messages</a></p></li></ol></li><li><p><a href="/manual/1.0.0/2-filesystem">2. Filesystem Layout and File Naming Conventions</a></p><ol><li><p><a href="/manual/1.0.0/2-filesystem#2.1">2.1 File locations</a></p></li><li><p><a href="/manual/1.0.0/2-filesystem#2.2">2.2 XHTML file naming conventions</a></p></li><li><p><a href="/manual/1.0.0/2-filesystem#2.3">2.3 The se-lint-ignore.xml file</a></p></li></ol></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook">3. The Structure of an Ebook</a></p><ol><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.1">3.1 Front matter</a></p></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.2">3.2 Body matter</a></p></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.3">3.3 Back matter</a></p></li></ol></li><li><p><a href="/manual/1.0.0/4-semantics">4. Semantics</a></p><ol><li><p><a href="/manual/1.0.0/4-semantics#4.1">4.1 Semantic Tags</a></p></li><li><p><a href="/manual/1.0.0/4-semantics#4.2">4.2 Semantic Inflection</a></p></li></ol></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns">5. General XHTML Patterns</a></p><ol><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.1">5.1 id attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.2">5.2 class attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.3">5.3 xml:lang attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.4">5.4 The &lt;title&gt; element</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.5">5.5 Ordered/numbered and unordered lists</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.6">5.6 Tables</a></p></li></ol></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns">6. Standard Ebooks Section Patterns</a></p><ol><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.1">6.1 The title string</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.2">6.2 The table of contents</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.3">6.3 The titlepage</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.4">6.4 The imprint</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.5">6.5 The half title page</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.6">6.6 The colophon</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.7">6.7 The Uncopyright</a></p></li></ol></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns">7. High Level Structural Patterns</a></p><ol><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.1">7.1 Sectioning</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.2">7.2 Headers</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.3">7.3 Dedications</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.4">7.4 Epigraphs</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.5">7.5 Poetry, verse, and songs</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.6">7.6 Plays and drama</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.7">7.7 Letters</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.8">7.8 Images</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.9">7.9 List of Illustrations (the LoI)</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.10">7.10 Endnotes</a></p></li></ol></li><li><p><a href="/manual/1.0.0/8-typography">8. Typography</a></p><ol><li><p><a href="/manual/1.0.0/8-typography#8.1">8.1 Section titles and ordinals</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.2">8.2 Italics</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.3">8.3 Capitalization</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.4">8.4 Indentation</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.5">8.5 Chapter headers</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.6">8.6 Ligatures</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.7">8.7 Punctuation and spacing</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.8">8.8 Numbers, measurements, and math</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.9">8.9 Latinisms</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.10">8.10 Initials and abbreviations</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.11">8.11 Times</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.12">8.12 Chemicals and compounds</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.13">8.13 Temperatures</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.14">8.14 Scansion</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.15">8.15 Legal cases and terms</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.16">8.16 Morse code</a></p></li></ol></li><li><p><a href="/manual/1.0.0/9-metadata">9. Metadata</a></p><ol><li><p><a href="/manual/1.0.0/9-metadata#9.1">9.1 General URL rules</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.2">9.2 The ebook identifier</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.3">9.3 Publication date and release identifiers</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.4">9.4 Book titles</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.5">9.5 Book subjects</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.6">9.6 Book descriptions</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.7">9.7 Book language</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.8">9.8 Book transcription and page scan sources</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.9">9.9 Additional book metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.10">9.10 General contributor rules</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.11">9.11 The author metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.12">9.12 The translator metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.13">9.13 The illustrator metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.14">9.14 The cover artist metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.15">9.15 Metadata for additional contributors</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.16">9.16 Transcriber metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.17">9.17 Producer metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.18">9.18 The ebook manifest</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.19">9.19 The ebook spine</a></p></li></ol></li><li><p><a href="/manual/1.0.0/10-art-and-images">10. Art and Images</a></p><ol><li><p><a href="/manual/1.0.0/10-art-and-images#10.1">10.1 Complete list of files</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.2">10.2 SVG patterns</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.3">10.3 The cover image</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.4">10.4 The titlepage image</a></p></li></ol></li></ol></nav>
		<article>

<section id="1"><aside class="number">1</aside>
<h1>XHTML, CSS, and SVG Code Style</h1>
<p>The <code class="bash"><span>se</span> clean</code> tool in the <a href="https://github.com/standardebooks/tools">Standard Ebooks toolset</a> formats XHTML, CSS, and SVG code according to our style guidelines. The vast majority of the time its output is correct and no further modifications to code style are necessary.</p>
<section id="1.1"><aside class="number">1.1</aside>
<h2>XHTML formatting</h2>
<ol type="1">
<li id="1.1.1"><aside class="number">1.1.1</aside><p>The first line of all XHTML files is:
					</p><figure><code class="html full"><span class="cp">&lt;?xml version="1.0" encoding="utf-8"?&gt;</span></code></figure>
</li>
<li id="1.1.2"><aside class="number">1.1.2</aside><p>The second line of all XHTML files is (replace <code class="html">xml:lang="en-US"</code> with the <a href="https://en.wikipedia.org/wiki/IETF_language_tag">appropriate language tag</a> for the file):
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">html</span> <span class="na">xmlns</span><span class="o">=</span><span class="s">"http://www.w3.org/1999/xhtml"</span> <span class="na">xmlns:epub</span><span class="o">=</span><span class="s">"http://www.idpf.org/2007/ops"</span> <span class="na">epub:prefix</span><span class="o">=</span><span class="s">"z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: https://standardebooks.org/vocab/1.0"</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"en-US"</span><span class="p">&gt;</span></code></figure>
</li>
<li id="1.1.3"><aside class="number">1.1.3</aside><p>Tabs are used for indentation.</p></li>
<li id="1.1.4"><aside class="number">1.1.4</aside><p>Tag names are lowercase.</p></li>
<li id="1.1.5"><aside class="number">1.1.5</aside><p>Tags whose content is <a href="https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/Content_categories#Phrasing_content">phrasing content</a> are on a single line, with no whitespace between the opening and closing tags and the content.
					</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	It was a dark and stormy night...
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>It was a dark and stormy night...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
<section id="1.1.6"><aside class="number">1.1.6</aside>
<h3><code class="html"><span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span></code> elements</h3>
<ol type="1">
<li id="1.1.6.1"><aside class="number">1.1.6.1</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span></code> elements within <a href="https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/Content_categories#Phrasing_content">phrasing content</a> are on the same line as the preceding phrasing content, and are followed by a newline.
						</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Pray for the soul of the
<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
Demoiselle Jeanne D’Ys.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Pray for the soul of the
<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>Demoiselle Jeanne D’Ys.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Pray for the soul of the<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
Demoiselle Jeanne D’Ys.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="1.1.6.2"><aside class="number">1.1.6.2</aside><p>The next indentation level after a <code class="html"><span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span></code> element is the same as the previous indentation level.
						</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Pray for the soul of the<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	Demoiselle Jeanne D’Ys,<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	who died<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	in her youth for love of<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	Philip, a Stranger.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Pray for the soul of the<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
Demoiselle Jeanne D’Ys,<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
who died<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
in her youth for love of<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
Philip, a Stranger.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="1.1.6.3"><aside class="number">1.1.6.3</aside><p>The closing tag of the phrasing content broken by a <code class="html"><span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span></code> element is on the same line as the last line of the phrasing content.
						</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Pray for the soul of the<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
Demoiselle Jeanne D’Ys.
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Pray for the soul of the<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
Demoiselle Jeanne D’Ys.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
<li id="1.1.6.4"><aside class="number">1.1.6.4</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span></code> elements have phrasing content both before and after; they don’t appear with phrasing content only before, or only after.
						</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Pray for the soul of the<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
Demoiselle Jeanne D’Ys<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Pray for the soul of the<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
Demoiselle Jeanne D’Ys<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
<section id="1.1.7"><aside class="number">1.1.7</aside>
<h3>Attributes</h3>
<ol type="1">
<li id="1.1.7.1"><aside class="number">1.1.7.1</aside><p>Attributes are in alphabetical order.</p></li>
<li id="1.1.7.2"><aside class="number">1.1.7.2</aside><p>Attributes, their namespaces, and their values are lowercase, except for values which are IETF language tags. In IETF language tags, the language subtag is capitalized.
						</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">EPUB:TYPE</span><span class="o">=</span><span class="s">"CHAPTER"</span> <span class="na">XML:LANG</span><span class="o">=</span><span class="s">"EN-US"</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"chapter"</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"en-US"</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code></figure>
</li>
<li id="1.1.7.3"><aside class="number">1.1.7.3</aside><p>The string <code class="html">utf-8</code> is lowercase.</p></li>
</ol>
<section id="1.1.7.4"><aside class="number">1.1.7.4</aside>
<h4>Classes</h4>
<ol type="1">
<li id="1.1.7.4.1"><aside class="number">1.1.7.4.1</aside><p>Classes are not used as one-time style hooks. There is almost always a clever selector that can be constructed instead of taking the shortcut of adding a class to an element.</p></li>
<li id="1.1.7.4.2"><aside class="number">1.1.7.4.2</aside><p>Classes are named semantically, describing <em>what they are styling</em> instead of the <em>resulting visual style</em>.
							</p><figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>There was one great tomb more lordly than all the rest; huge it was, and nobly proportioned. On it was but one word<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">class</span><span class="o">=</span><span class="s">"small-caps"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Dracula.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>There was one great tomb more lordly than all the rest; huge it was, and nobly proportioned. On it was but one word<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">class</span><span class="o">=</span><span class="s">"tomb"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Dracula.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
</section>
</section>
<section id="1.2"><aside class="number">1.2</aside>
<h2>CSS formatting</h2>
<ol type="1">
<li id="1.2.1"><aside class="number">1.2.1</aside><p>The first two lines of all CSS files are:
					</p><figure><code class="css full"><span class="p">@</span><span class="k">charset</span> <span class="s2">"utf-8"</span><span class="p">;</span>
<span class="p">@</span><span class="k">namespace</span> <span class="nt">epub</span> <span class="s2">"http://www.idpf.org/2007/ops"</span><span class="p">;</span></code></figure>
</li>
<li id="1.2.2"><aside class="number">1.2.2</aside><p>Tabs are used for indentation.</p></li>
<li id="1.2.3"><aside class="number">1.2.3</aside><p>Selectors, properties, and values are lowercase.</p></li>
</ol>
<section id="1.2.4"><aside class="number">1.2.4</aside>
<h3>Selectors</h3>
<ol type="1">
<li id="1.2.4.1"><aside class="number">1.2.4.1</aside><p>Selectors are each on their own line, directly followed by a comma or a brace with no whitespace in between.
						</p><figure class="wrong"><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">era</span><span class="o">,</span> <span class="p">.</span><span class="nc">signature</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure class="corrected"><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">era</span><span class="o">,</span>
<span class="p">.</span><span class="nc">signature</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
</li>
<li id="1.2.4.2"><aside class="number">1.2.4.2</aside><p>Complete selectors are separated by exactly one blank line.
						</p><figure class="wrong"><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">era</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span>


<span class="nt">strong</span><span class="p">{</span>
	<span class="k">font-weight</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure class="corrected"><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">era</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span>

<span class="nt">strong</span><span class="p">{</span>
	<span class="k">font-weight</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
</li>
<li id="1.2.4.3"><aside class="number">1.2.4.3</aside><p>Closing braces are on their own line.</p></li>
</ol>
</section>
<section id="1.2.5"><aside class="number">1.2.5</aside>
<h3>Properties</h3>
<ol type="1">
<li id="1.2.5.1"><aside class="number">1.2.5.1</aside><p>Properties are each on their own line (even if the selector only has one property) and indented with a single tab.
						</p><figure class="wrong"><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">era</span><span class="p">{</span> <span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span> <span class="p">}</span></code></figure>
<figure class="corrected"><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">era</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
</li>
<li id="1.2.5.2"><aside class="number">1.2.5.2</aside><p><em>Where possible</em>, properties are in alphabetical order.
						</p><p>This isn’t always possible if a property is attempting to override a previous property in the same selector, and in some other cases.</p>
</li>
<li id="1.2.5.3"><aside class="number">1.2.5.3</aside><p>Properties are directly followed by a colon, then a single space, then the property value.
						</p><figure class="wrong"><code class="css full"><span class="nt">blockquote</span><span class="p">{</span>
	<span class="k">margin-left</span><span class="p">:</span>	<span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">margin-right</span><span class="p">:</span>   <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">border</span><span class="p">:</span><span class="kc">none</span><span class="p">;</span>
<span class="p">}</span></code></figure>
<figure class="corrected"><code class="css full"><span class="nt">blockquote</span><span class="p">{</span>
	<span class="k">margin-left</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">margin-right</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">border</span><span class="p">:</span> <span class="kc">none</span><span class="p">;</span>
<span class="p">}</span></code></figure>
</li>
<li id="1.2.5.4"><aside class="number">1.2.5.4</aside><p>Property values are directly followed by a semicolon, even if it’s the last value in a selector.
						</p><figure class="wrong"><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">era</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span>
<span class="p">}</span></code></figure>
<figure class="corrected"><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">era</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
</li>
</ol>
</section>
</section>
<section id="1.3"><aside class="number">1.3</aside>
<h2>SVG Formatting</h2>
<ol type="1">
<li id="1.3.1"><aside class="number">1.3.1</aside><p>SVG formatting follows the same directives as <a href="/manual/1.0.0/1-code-style#1.1">XHTML formatting</a>.</p></li>
</ol>
</section>
<section id="1.4"><aside class="number">1.4</aside>
<h2>Commits and Commit Messages</h2>
<ol type="1">
<li id="1.4.1"><aside class="number">1.4.1</aside><p>Commits are broken into single units of work. A single unit of work may be, for example, "fixing typos across 10 files", or "adding cover art", or "working on metadata".</p></li>
<li id="1.4.2"><aside class="number">1.4.2</aside><p>Commits that introduce material changes to the ebook text (for example modernizing spelling or fixing a probable printer’s typo; but not fixing a transcriber’s typo) are prefaced with the string <code class="html">[Editorial]</code>, followed by a space, then the commit message. This makes it easy to search the repo history for commits that make editorial changes to the work.</p></li>
</ol>
</section>
</section>
		</article>
	</main>
<?= Template::Footer() ?>
