<?
require_once('Core.php');
?><?= Template::Header(['title' => '6. High Level Structural Patterns - The Standard Ebooks Manual', 'highlight' => 'contribute', 'manual' => true]) ?>
	<main>
		<article class="manual">

	<section data-start-at="6" id="high-level-structural-patterns">
		<h1>High Level Structural Patterns</h1>
		<p>Section should contain high-level structural patterns for common formatting situations.</p>
		<section id="sectioning">
			<h2>Sectioning</h2>
			<ol type="1">
				<li>Major structural divisions of a larger work, like parts, volumes, books, chapters, or subchapters, are contained in a <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> element.</li>
				<li>Individual items in a larger collection (like a poem in a poetry collection) are contained in a <code class="html"><span class="p">&lt;</span><span class="nt">article</span><span class="p">&gt;</span></code> element.</li>
				<li>In <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> or <code class="html"><span class="p">&lt;</span><span class="nt">articles</span><span class="p">&gt;</span></code> elements that have titles, the first child element is an <code class="html"><span class="p">&lt;</span><span class="nt">h1</span><span class="p">&gt;</span></code>–<code class="html"><span class="p">&lt;</span><span class="nt">h6</span><span class="p">&gt;</span></code> element, or a <code class="html"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span></code> element containing the section’s title.</li>
			</ol>
			<section id="recomposability">
				<h3>Recomposability</h3>
				<p>“Recomposability” is the concept of generating a single structurally-correct HTML5 file out of an epub file. All Standard Ebooks are recomposable.</p>
				<ol type="1">
					<li>XHTML files that contain <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> or <code class="html"><span class="p">&lt;</span><span class="nt">articles</span><span class="p">&gt;</span></code> elements that are semantic children of <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> or <code class="html"><span class="p">&lt;</span><span class="nt">articles</span><span class="p">&gt;</span></code> elements in other files, are wrapped in stubs of all parent <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> or <code class="html"><span class="p">&lt;</span><span class="nt">articles</span><span class="p">&gt;</span></code> elements, up to the root.</li>
					<li>Each such included parent element has the identical <code class="html">id</code> and <code class="html">epub:type</code> attributes of its real counterpart.</li>
				</ol>
				<section id="examples" class="no-numbering">
					<h4>Examples</h4>
					<p>Consider a book that contains several top-level subdivisions: Books 1–4, with each book having 3 parts, and each part having 10 chapters. Below is an example of three files demonstrating the structure necessary to achieve recomposability:</p>
					<p>Book 1 (<code class="path">book-1.xhtml</code>):</p>
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;book-1&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;division&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>Book <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>I<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code></figure>
					<p>Book 1, Part 2 (<code class="path">part-1-2.xhtml</code>):</p>
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;book-1&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;division&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;part-1-2&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;part&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>Part <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>II<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code></figure>
					<p>Book 1, Part 2, Chapter 3 (<code class="path">chapter-1-2-3.xhtml</code>):</p>
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;book-1&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;division&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;part-1-2&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;part&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;chapter-3&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;chapter&quot;</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>Chapter <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>III<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code></figure>
				</section>
			</section>
		</section>
		<section id="headers">
			<h2>Headers</h2>
			<ol type="1">
				<li><code class="html"><span class="p">&lt;</span><span class="nt">h1</span><span class="p">&gt;</span></code>–<code class="html"><span class="p">&lt;</span><span class="nt">h6</span><span class="p">&gt;</span></code> elements are used for headers of sections that are structural divisions of a document, i.e., divisions that appear in the table of contents. <code class="html"><span class="p">&lt;</span><span class="nt">h1</span><span class="p">&gt;</span></code>–<code class="html"><span class="p">&lt;</span><span class="nt">h6</span><span class="p">&gt;</span></code> elements <em>are not</em> used for headers of components that are not in the table of contents. For example, they are not used to mark up the title of a short poem in a chapter, where the poem itself is not a structural component of the larger ebook.</li>
				<li>A section containing an <code class="html"><span class="p">&lt;</span><span class="nt">h1</span><span class="p">&gt;</span></code>–<code class="html"><span class="p">&lt;</span><span class="nt">h6</span><span class="p">&gt;</span></code> appears in the table of contents.</li>
				<li>The book’s title is implicitly at the <code class="html"><span class="p">&lt;</span><span class="nt">h1</span><span class="p">&gt;</span></code> level, even if <code class="html"><span class="p">&lt;</span><span class="nt">h1</span><span class="p">&gt;</span></code> is not present in the ebook. Because of the implicit <code class="html"><span class="p">&lt;</span><span class="nt">h1</span><span class="p">&gt;</span></code>, all other sections begin at <code class="html"><span class="p">&lt;</span><span class="nt">h2</span><span class="p">&gt;</span></code>.</li>
				<li>Each <code class="html"><span class="p">&lt;</span><span class="nt">h1</span><span class="p">&gt;</span></code>–<code class="html"><span class="p">&lt;</span><span class="nt">h6</span><span class="p">&gt;</span></code> element uses the correct number for the section’s heading level in the overall book, <em>not</em> the section’s heading level in the individual file. For example, given an ebook with a file named <code class="path">part-2.xhtml</code> containing:
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;part-2&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;part&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>Part <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>II<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code></figure>
					<p>Consider this example for the file <code class="path">chapter-2-3.xhtml</code>:</p>
					<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;part-2&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;part&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;chapter-2-3&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;chapter&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title z3998:roman&quot;</span><span class="p">&gt;</span>III<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
		...
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code></figure>
					<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;part-2&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;part&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;chapter-2-3&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;chapter&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">h3</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title z3998:roman&quot;</span><span class="p">&gt;</span>III<span class="p">&lt;/</span><span class="nt">h3</span><span class="p">&gt;</span>
		...
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>Each <code class="html"><span class="p">&lt;</span><span class="nt">h1</span><span class="p">&gt;</span></code>–<code class="html"><span class="p">&lt;</span><span class="nt">h6</span><span class="p">&gt;</span></code> element has a direct parent <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> or <code class="html"><span class="p">&lt;</span><span class="nt">article</span><span class="p">&gt;</span></code> element.</li>
			</ol>
			<section id="header-patterns">
				<h3>Header patterns</h3>
				<ol type="1">
					<li>Sections without titles:
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title z3998:roman&quot;</span><span class="p">&gt;</span>XI<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Sections with titles but no ordinal (i.e. chapter) numbers:
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>A Daughter of Albion<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Sections with titles and ordinal (i.e. chapter) numbers:
						<figure><code class="css full"><span class="nt">span</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;subtitle&quot;</span><span class="o">]</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">block</span><span class="p">;</span>
	<span class="k">font-weight</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
<span class="p">}</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>XI<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;subtitle&quot;</span><span class="p">&gt;</span>Who Stole the Tarts?<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Sections titles and subtitles but no ordinal (i.e. chapter) numbers:
						<figure><code class="css full"><span class="nt">span</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;subtitle&quot;</span><span class="o">]</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">block</span><span class="p">;</span>
	<span class="k">font-weight</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
<span class="p">}</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>An Adventure<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;subtitle&quot;</span><span class="p">&gt;</span>(A Driver’s Story)<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Sections that require titles, but that are not in the table of contents:
						<figure><code class="css full"><span class="nt">header</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">small-caps</span><span class="p">;</span>
	<span class="k">margin</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">center</span><span class="p">;</span>
<span class="p">}</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The Title of a Short Poem<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">header</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
			<section id="bridgeheads">
				<h3>Bridgeheads</h3>
				<p>Bridgeheads are sections in a chapter header that give an abstract or summary of the following chapter. They may be in prose or in a short list with clauses separated by em dashes.</p>
				<ol type="1">
					<li>The last clause in a bridgehead ends in appropriate punctuation, like a period.</li>
					<li>Bridgeheads have the following CSS and HTML structure:
						<figure><code class="css full"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;bridgehead&quot;</span><span class="o">]</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">inline-block</span><span class="p">;</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">italic</span><span class="p">;</span>
	<span class="k">max-width</span><span class="p">:</span> <span class="mi">60</span><span class="kt">%</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">justify</span><span class="p">;</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;bridgehead&quot;</span><span class="o">]</span> <span class="nt">i</span><span class="p">{</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
<span class="p">}</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title z3998:roman&quot;</span><span class="p">&gt;</span>I<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;bridgehead&quot;</span><span class="p">&gt;</span>Which treats of the character and pursuits of the famous gentleman Don Quixote of La Mancha.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">header</span><span class="p">&gt;</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title z3998:roman&quot;</span><span class="p">&gt;</span>X<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;bridgehead&quot;</span><span class="p">&gt;</span>Our first night⁠<span class="ws">wj</span>—Under canvas⁠<span class="ws">wj</span>—An appeal for help⁠<span class="ws">wj</span>—Contrariness of teakettles, how to overcome⁠<span class="ws">wj</span>—Supper⁠<span class="ws">wj</span>—How to feel virtuous⁠<span class="ws">wj</span>—Wanted! a comfortably-appointed, well-drained desert island, neighbourhood of South Pacific Ocean preferred⁠<span class="ws">wj</span>—Funny thing that happened to George’s father⁠<span class="ws">wj</span>—A restless night.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">header</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
		</section>
		<section id="epigraphs">
			<h2>Epigraphs</h2>
			<ol type="1">
				<li>All epigraphs include this CSS:
					<figure><code class="css full"><span class="c">/* All epigraphs */</span>
<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;epigraph&quot;</span><span class="o">]</span><span class="p">{</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">italic</span><span class="p">;</span>
	<span class="k">hyphens</span><span class="p">:</span> <span class="kc">none</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;epigraph&quot;</span><span class="o">]</span> <span class="nt">em</span><span class="o">,</span>
<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;epigraph&quot;</span><span class="o">]</span> <span class="nt">i</span><span class="p">{</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;epigraph&quot;</span><span class="o">]</span> <span class="nt">cite</span><span class="p">{</span>
	<span class="k">margin-top</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">small-caps</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;epigraph&quot;</span><span class="o">]</span> <span class="nt">cite</span> <span class="nt">i</span><span class="p">{</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">italic</span><span class="p">;</span>
<span class="p">}</span>
<span class="c">/* End all epigraphs */</span></code></figure>
				</li>
			</ol>
			<section id="epigraphs-in-section-headers">
				<h3>Epigraphs in section headers</h3>
				<ol type="1">
					<li>Epigraphs in section headers have the quote source in a <code class="html"><span class="p">&lt;</span><span class="nt">cite</span><span class="p">&gt;</span></code> element set in small caps, without a leading em-dash and without a trailing period.
						<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title z3998:roman&quot;</span><span class="p">&gt;</span>II<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;epigraph&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Desire no more than to thy lot may fall. …”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">cite</span><span class="p">&gt;</span>—Chaucer.<span class="p">&lt;/</span><span class="nt">cite</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">header</span><span class="p">&gt;</span></code></figure>
						<figure class="corrected"><code class="css full"><span class="nt">header</span> <span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;epigraph&quot;</span><span class="o">]</span> <span class="nt">cite</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title z3998:roman&quot;</span><span class="p">&gt;</span>II<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;epigraph&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Desire no more than to thy lot may fall. …”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">cite</span><span class="p">&gt;</span>Chaucer<span class="p">&lt;/</span><span class="nt">cite</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">header</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>In addition to the <a href="/manual/1.0.0/high-level-structural-patterns#6.3.1">CSS used for all epigraphs</a>, this additional CSS is included for epigraphs in section headers:
						<figure><code class="css full"><span class="c">/* Epigraphs in section headers */</span>
<span class="nt">section</span> <span class="o">&gt;</span> <span class="nt">header</span> <span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;epigraph&quot;</span><span class="o">]</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">inline-block</span><span class="p">;</span>
	<span class="k">margin</span><span class="p">:</span> <span class="kc">auto</span><span class="p">;</span>
	<span class="k">max-width</span><span class="p">:</span> <span class="mi">80</span><span class="kt">%</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">left</span><span class="p">;</span>
<span class="p">}</span>

<span class="nt">section</span> <span class="o">&gt;</span> <span class="nt">header</span> <span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;epigraph&quot;</span><span class="o">]</span> <span class="o">+</span> <span class="o">*</span><span class="p">{</span>
	<span class="k">margin-top</span><span class="p">:</span> <span class="mi">3</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span>

<span class="p">@</span><span class="k">supports</span><span class="o">(</span><span class="nt">display</span><span class="o">:</span> <span class="nt">table</span><span class="o">)</span><span class="p">{</span>
	<span class="nt">section</span> <span class="o">&gt;</span> <span class="nt">header</span> <span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;epigraph&quot;</span><span class="o">]</span><span class="p">{</span>
		<span class="k">display</span><span class="p">:</span> <span class="kc">table</span><span class="p">;</span>
	<span class="p">}</span>
<span class="p">}</span>
<span class="c">/* End epigraphs in section headers */</span></code></figure>
					</li>
				</ol>
			</section>
			<section id="full-page-epigraphs">
				<h3>Full-page epigraphs</h3>
				<ol type="1">
					<li>In full-page epigraphs, the epigraph is centered on the page for ereaders that support advanced CSS. For all other ereaders, the epigraph is horizontally centered with a small margin above it.</li>
					<li>Full-page epigraphs that contain multiple quotations are represented by multiple <code class="html"><span class="p">&lt;</span><span class="nt">blockquote</span><span class="p">&gt;</span></code> elements.</li>
					<li>In addition to the <a href="/manual/1.0.0/high-level-structural-patterns#6.3.1">CSS used for all epigraphs</a>, this additional CSS is included for full-page epigraphs:
						<figure><code class="css full"><span class="c">/* Full-page epigraphs */</span>
<span class="nt">section</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;epigraph&quot;</span><span class="o">]</span><span class="p">{</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">center</span><span class="p">;</span>
<span class="p">}</span>

<span class="nt">section</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;epigraph&quot;</span><span class="o">]</span> <span class="o">&gt;</span> <span class="o">*</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">inline-block</span><span class="p">;</span>
	<span class="k">margin</span><span class="p">:</span> <span class="kc">auto</span><span class="p">;</span>
	<span class="k">margin-top</span><span class="p">:</span> <span class="mi">3</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">max-width</span><span class="p">:</span> <span class="mi">80</span><span class="kt">%</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">left</span><span class="p">;</span>
<span class="p">}</span>

<span class="p">@</span><span class="k">supports</span><span class="o">(</span><span class="nt">display</span><span class="o">:</span> <span class="nt">flex</span><span class="o">)</span><span class="p">{</span>
	<span class="nt">section</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;epigraph&quot;</span><span class="o">]</span><span class="p">{</span>
		<span class="k">align-items</span><span class="p">:</span> <span class="kc">center</span><span class="p">;</span>
		<span class="k">box-sizing</span><span class="p">:</span> <span class="kc">border-box</span><span class="p">;</span>
		<span class="k">display</span><span class="p">:</span> <span class="kc">flex</span><span class="p">;</span>
		<span class="k">flex-direction</span><span class="p">:</span> <span class="kc">column</span><span class="p">;</span>
		<span class="k">justify-content</span><span class="p">:</span> <span class="kc">center</span><span class="p">;</span>
		<span class="k">min-height</span><span class="p">:</span> <span class="nb">calc</span><span class="p">(</span><span class="mi">98</span><span class="kt">vh</span> <span class="o">-</span> <span class="mi">3</span><span class="kt">em</span><span class="p">);</span>
		<span class="k">padding-top</span><span class="p">:</span> <span class="mi">3</span><span class="kt">em</span><span class="p">;</span>
	<span class="p">}</span>

	<span class="nt">section</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;epigraph&quot;</span><span class="o">]</span> <span class="o">&gt;</span> <span class="o">*</span><span class="p">{</span>
		<span class="k">margin</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
	<span class="p">}</span>

	<span class="nt">section</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;epigraph&quot;</span><span class="o">]</span> <span class="o">&gt;</span> <span class="o">*</span> <span class="o">+</span> <span class="o">*</span><span class="p">{</span>
		<span class="k">margin-top</span><span class="p">:</span> <span class="mi">3</span><span class="kt">em</span><span class="p">;</span>
	<span class="p">}</span>
<span class="p">}</span>
<span class="c">/* End full-page epigraphs */</span></code></figure>
					</li>
					<li>Example HTML:
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;frontmatter&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;epigraph&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;epigraph&quot;</span><span class="p">&gt;</span>
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
		<section id="poetry-verse-and-songs">
			<h2>Poetry, verse, and songs</h2>
			<p>Unfortunately there’s no great way to semantically format poetry in HTML. As such, unrelated elements are conscripted for use in poetry.</p>
			<ol type="1">
				<li>A stanza is represented by a <code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code> element.
					<figure><code class="css full"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:poem&quot;</span><span class="o">]</span> <span class="nt">p</span><span class="p">{</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">left</span><span class="p">;</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:poem&quot;</span><span class="o">]</span> <span class="nt">p</span> <span class="o">+</span> <span class="nt">p</span><span class="p">{</span>
	<span class="k">margin-top</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:poem&quot;</span><span class="o">]</span> <span class="o">+</span> <span class="nt">p</span><span class="p">{</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span></code></figure>
				</li>
				<li>Each stanza contains <code class="html"><span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span></code> elements, each one representing a line in the stanza.
					<figure><code class="css full"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:poem&quot;</span><span class="o">]</span> <span class="nt">p</span> <span class="o">&gt;</span> <span class="nt">span</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">block</span><span class="p">;</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">-1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">padding-left</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span></code></figure>
				</li>
				<li>Each <code class="html"><span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span></code> line is followed by a <code class="html"><span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span></code> element, except for the last line in a stanza.
					<figure><code class="css full"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:poem&quot;</span><span class="o">]</span> <span class="nt">p</span> <span class="o">&gt;</span> <span class="nt">span</span> <span class="o">+</span> <span class="nt">br</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">none</span><span class="p">;</span>
<span class="p">}</span></code></figure>
				</li>
				<li>Indented <code class="html"><span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span></code> lines have the <code class="css"><span class="nt">i1</span></code> class. <i>Do not</i> use <code class="ws">nbsp</code> for indentation. Indenting to different levels is done by incrementing the class to <code class="css"><span class="nt">i2</span></code>, <code class="css"><span class="nt">i3</span></code>, and so on, and including the appropriate CSS.
					<figure><code class="css full"><span class="nt">p</span> <span class="nt">span</span><span class="p">.</span><span class="nc">i1</span><span class="p">{</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">-1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">padding-left</span><span class="p">:</span> <span class="mi">2</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span>

<span class="nt">p</span> <span class="nt">span</span><span class="p">.</span><span class="nc">i2</span><span class="p">{</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">-1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">padding-left</span><span class="p">:</span> <span class="mi">3</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span></code></figure>
				</li>
				<li>Poems, songs, and verse that are shorter part of a longer work, like a novel, are wrapped in a <code class="html"><span class="p">&lt;</span><span class="nt">blockquote</span><span class="p">&gt;</span></code> element.
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:poem&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">br</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>The parent element of poetry, verse, or song, has the semantic inflection of <code class="html">z3998:poem</code>, <code class="html">z3998:verse</code>, <code class="html">z3998:song</code>, or <code class="html">z3998:hymn</code>.</li>
				<li>If a poem is quoted and has one or more lines removed, the removed lines are represented with a vertical ellipses (<code class="utf">⋮</code> or u+22EE) in a <code class="html"><span class="p">&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">"elision"</span><span class="p">&gt;</span></code> element:
					<figure><code class="css full"><span class="nt">span</span><span class="p">.</span><span class="nc">elision</span><span class="p">{</span>
	<span class="k">margin</span><span class="p">:</span> <span class="mf">.5</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">margin-left</span><span class="p">:</span> <span class="mi">3</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span>

<span class="c">/* If eliding within an epigraph, include this additional style: */</span>
<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;epigraph&quot;</span><span class="o">]</span> <span class="nt">span</span><span class="p">.</span><span class="nc">elision</span><span class="p">{</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
<span class="p">}</span></code></figure>
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:verse&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>O Lady! we receive but what we give,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>And in our life alone does nature live:<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Ours is her wedding garments, ours her shroud!<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;elision&quot;</span><span class="p">&gt;</span>⋮<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;i1&quot;</span><span class="p">&gt;</span>Ah! from the soul itself must issue forth<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>A light, a glory, a fair luminous cloud,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
				</li>
			</ol>
			<section id="id1" class="no-numbering">
				<h3>Examples</h3>
				<p>Note that below we include CSS for the <code class="css"><span class="nt">i2</span></code> class, even though it’s not used in the example. It’s included to demonstrate how to adjust the CSS for indentation levels after the first.</p>
				<figure><code class="css full"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:poem&quot;</span><span class="o">]</span> <span class="nt">p</span><span class="p">{</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">left</span><span class="p">;</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:poem&quot;</span><span class="o">]</span> <span class="nt">p</span> <span class="o">&gt;</span> <span class="nt">span</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">block</span><span class="p">;</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">-1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">padding-left</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:poem&quot;</span><span class="o">]</span> <span class="nt">p</span> <span class="o">&gt;</span> <span class="nt">span</span> <span class="o">+</span> <span class="nt">br</span><span class="p">{</span>
	<span class="k">display</span><span class="p">:</span> <span class="kc">none</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:poem&quot;</span><span class="o">]</span> <span class="nt">p</span> <span class="o">+</span> <span class="nt">p</span><span class="p">{</span>
	<span class="k">margin-top</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:poem&quot;</span><span class="o">]</span> <span class="o">+</span> <span class="nt">p</span><span class="p">{</span>
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
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:poem&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>“How doth the little crocodile<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;i1&quot;</span><span class="p">&gt;</span>Improve his shining tail,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>And pour the waters of the Nile<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;i1&quot;</span><span class="p">&gt;</span>On every golden scale!<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>“How cheerfully he seems to grin,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;i1&quot;</span><span class="p">&gt;</span>How neatly spread his claws,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>And welcome little fishes in<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;i1&quot;</span><span class="p">&gt;&lt;</span><span class="nt">em</span><span class="p">&gt;</span>With gently smiling jaws!<span class="p">&lt;/</span><span class="nt">em</span><span class="p">&gt;</span>”<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
			</section>
		</section>
		<section id="plays-drama">
			<h2>Plays &amp; Drama</h2>
			<ol type="1">
				<li>Dialog in plays is structured using <code class="html"><span class="p">&lt;</span><span class="nt">table</span><span class="p">&gt;</span></code> elements.</li>
				<li>Each <code class="html"><span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span></code> is either a block of dialog or a standalone stage direction.</li>
				<li>Works that are plays or that contain sections of dramatic dialog have this core CSS:
					<figure><code class="css full"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:drama&quot;</span><span class="o">]</span><span class="p">{</span>
	<span class="k">border-collapse</span><span class="p">:</span> <span class="kc">collapse</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:drama&quot;</span><span class="o">]</span> <span class="nt">tr</span><span class="p">:</span><span class="nd">first-child</span> <span class="nt">td</span><span class="p">{</span>
	<span class="k">padding-top</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:drama&quot;</span><span class="o">]</span> <span class="nt">tr</span><span class="p">:</span><span class="nd">last-child</span> <span class="nt">td</span><span class="p">{</span>
	<span class="k">padding-bottom</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:drama&quot;</span><span class="o">]</span> <span class="nt">td</span><span class="p">{</span>
	<span class="k">vertical-align</span><span class="p">:</span> <span class="kc">top</span><span class="p">;</span>
	<span class="k">padding</span><span class="p">:</span> <span class="mf">.5</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:drama&quot;</span><span class="o">]</span> <span class="nt">td</span><span class="p">:</span><span class="nd">last-child</span><span class="p">{</span>
	<span class="k">padding-right</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:drama&quot;</span><span class="o">]</span> <span class="nt">td</span><span class="p">:</span><span class="nd">first-child</span><span class="p">{</span>
	<span class="k">padding-left</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:drama&quot;</span><span class="o">]</span> <span class="nt">td</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:persona&quot;</span><span class="o">]</span><span class="p">{</span>
	<span class="k">hyphens</span><span class="p">:</span> <span class="kc">none</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">right</span><span class="p">;</span>
	<span class="k">width</span><span class="p">:</span> <span class="mi">20</span><span class="kt">%</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:drama&quot;</span><span class="o">]</span> <span class="nt">td</span> <span class="nt">p</span><span class="p">{</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="nt">table</span><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:drama&quot;</span><span class="o">],</span>
<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:drama&quot;</span><span class="o">]</span> <span class="nt">table</span><span class="p">{</span>
	<span class="k">margin</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span> <span class="kc">auto</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:stage-direction&quot;</span><span class="o">]</span><span class="p">{</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">italic</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:stage-direction&quot;</span><span class="o">]</span><span class="p">::</span><span class="nd">before</span><span class="p">{</span>
	<span class="k">content</span><span class="p">:</span> <span class="s2">&quot;(&quot;</span><span class="p">;</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:stage-direction&quot;</span><span class="o">]</span><span class="p">::</span><span class="nd">after</span><span class="p">{</span>
	<span class="k">content</span><span class="p">:</span> <span class="s2">&quot;)&quot;</span><span class="p">;</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:persona&quot;</span><span class="o">]</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
				</li>
			</ol>
			<section id="dialog-rows">
				<h3>Dialog rows</h3>
				<ol type="1">
					<li>The first child of a row of dialog is a <code class="html"><span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span></code> element with the semantic inflection of <code class="html">z3998:persona</code>.</li>
					<li>The second child of a row of dialog is a <code class="html"><span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span></code> element containing the actual dialog. Elements that contain only one line of dialog do not have a block-level child (like <code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code>).
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:persona&quot;</span><span class="p">&gt;</span>Algernon<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>Did you hear what I was playing, Lane?<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:persona&quot;</span><span class="p">&gt;</span>Lane<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>I didn’t think it polite to listen, sir.<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>When several personas speak at once, or a group of personas ("The Actors") speaks at once, the containing <code class="html"><span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span></code> element has the <code class="html">together</code> class, and the first <code class="html"><span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span></code> child has a <code class="html">rowspan</code> attribute corresponding to the number of lines spoken together.
						<figure><code class="css full"><span class="nt">tr</span><span class="p">.</span><span class="nc">together</span> <span class="nt">td</span><span class="p">{</span>
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

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:drama&quot;</span><span class="o">]</span> <span class="p">.</span><span class="nc">together</span> <span class="nt">td</span><span class="p">:</span><span class="nd">last-child</span><span class="p">{</span>
	<span class="k">padding-left</span><span class="p">:</span> <span class="mf">.5</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">tr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;together&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span> <span class="na">rowspan</span><span class="o">=</span><span class="s">&quot;3&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:persona&quot;</span><span class="p">&gt;</span>The Actors<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>Oh, what d’you think of that?<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">tr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;together&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>Only the mantle?<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">tr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;together&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>He must be mad.<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">tr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;together&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span> <span class="na">rowspan</span><span class="o">=</span><span class="s">&quot;2&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:persona&quot;</span><span class="p">&gt;</span>Some Actresses<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>But why?<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">tr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;together&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>Mantles as well?<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
			<section id="stage-direction-rows">
				<h3>Stage direction rows</h3>
				<ol type="1">
					<li>The first child of a row of stage direction is an empty <code class="html"><span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span></code> element.</li>
					<li>The second child of a row of dialog is a <code class="html"><span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span></code> element containing the stage direction</li>
					<li>Stage direction is wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:stage-direction"</span><span class="p">&gt;</span></code> element.</li>
					<li>Personas mentioned in stage direction are wrapped in a <code class="html"><span class="p">&lt;</span><span class="nt">b</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span></code> element.</li>
				</ol>
				<section id="id2" class="no-numbering">
					<h4>Examples</h4>
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:stage-direction&quot;</span><span class="p">&gt;&lt;</span><span class="nt">b</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:persona&quot;</span><span class="p">&gt;</span>Lane<span class="p">&lt;/</span><span class="nt">b</span><span class="p">&gt;</span> is arranging afternoon tea on the table, and after the music has ceased, <span class="p">&lt;</span><span class="nt">b</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:persona&quot;</span><span class="p">&gt;</span>Algernon<span class="p">&lt;/</span><span class="nt">b</span><span class="p">&gt;</span> enters.<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span></code></figure>
				</section>
			</section>
			<section id="works-that-are-complete-plays">
				<h3>Works that are complete plays</h3>
				<ol type="1">
					<li>The top-level element (usually <code class="html"><span class="p">&lt;</span><span class="nt">body</span><span class="p">&gt;</span></code>) has the <code class="html">z3998:drama</code> semantic inflection.</li>
					<li>Acts are <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> elements containing at least one <code class="html"><span class="p">&lt;</span><span class="nt">table</span><span class="p">&gt;</span></code> for dialog, and optionally containing an act title and other top-level stage direction.</li>
					<li>Introductory or high-level stage direction is presented using <code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code> elements outside of the dialog table.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;bodymatter z3998:fiction z3998:drama&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;act-1&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;chapter z3998:scene&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>Act <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>I<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Scene: Morning-room in Algernon’s flat in Half-Moon Street. The room is luxuriously and artistically furnished. The sound of a piano is heard in the adjoining room.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">table</span><span class="p">&gt;</span>
			...
		<span class="p">&lt;/</span><span class="nt">table</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:stage-direction&quot;</span><span class="p">&gt;</span>Act Drop<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">body</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Dramatis personae are presented as a <code class="html"><span class="p">&lt;</span><span class="nt">ul</span><span class="p">&gt;</span></code> element listing the characters.
						<figure><code class="css full"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:dramatis-personae&quot;</span><span class="o">]</span><span class="p">{</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">center</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:dramatis-personae&quot;</span><span class="o">]</span> <span class="nt">ul</span><span class="p">{</span>
	<span class="k">list-style</span><span class="p">:</span> <span class="kc">none</span><span class="p">;</span>
	<span class="k">margin</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
	<span class="k">padding</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:dramatis-personae&quot;</span><span class="o">]</span> <span class="nt">ul</span> <span class="nt">li</span><span class="p">{</span>
	<span class="k">margin</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">italic</span><span class="p">;</span>
<span class="p">}</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;dramatis-personae&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:dramatis-personae&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>Dramatis Personae<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">ul</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>King Henry <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>V<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
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
		<section id="letters">
			<h2>Letters</h2>
			<p>Letters require particular attention to styling and semantic tagging. Letters may not exactly match the formatting in the source scans, but they are in visual sympathy with the source.</p>
			<ol type="1">
				<li>Letters are wrapped in a <code class="html"><span class="p">&lt;</span><span class="nt">blockquote</span><span class="p">&gt;</span></code> element with the appropriate semantic inflection, usually <code class="html">z3998:letter</code>.</li>
			</ol>
			<section id="letter-headers">
				<h3>Letter headers</h3>
				<ol type="1">
					<li>Parts of a letter prior to the body of the letter, for example the location where it is written, the date, and the salutation, are wrapped in a <code class="html"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span></code> element.</li>
					<li>If there is only a salutation and no other header content, the <code class="html"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span></code> element is omitted.</li>
					<li>The location and date of a letter have the semantic inflection of <code class="html">se:letter.dateline</code>. Dates are in a <code class="html"><span class="p">&lt;</span><span class="nt">time</span><span class="p">&gt;</span></code> element with a computer-readable date.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;se:letter.dateline&quot;</span><span class="p">&gt;</span>Blarney Castle, <span class="p">&lt;</span><span class="nt">time</span> <span class="na">datetime</span><span class="o">=</span><span class="s">&quot;1863-10-11&quot;</span><span class="p">&gt;</span>11th of October, 1863<span class="p">&lt;/</span><span class="nt">time</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">header</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>The salutation (for example, “Dear Sir” or “My dearest Jane”) has the semantic inflection of <code class="html">z3998:salutation</code>.</li>
					<li>The first line of a letter after the salutation is not indented.</li>
					<li>Salutations that are within the first line of the letter are wrapped in a <code class="html"><span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:salutation</span><span class="p">&gt;</span></code> element (or a <code class="html"><span class="p">&lt;</span><span class="nt">b</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:salutation</span><span class="p">&gt;</span></code> element if small-caps are desired).
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;&lt;</span><span class="nt">b</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:salutation&quot;</span><span class="p">&gt;</span>Dear Mother<span class="p">&lt;/</span><span class="nt">b</span><span class="p">&gt;</span>, I was so happy to hear from you.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>The name of the recipient of the letter, when set out other than within a saluation (for example a letter headed “To: John Smith Esquire”), is given the semantic inflection of <code class="html">z3998:recipient</code>. Sometimes this may occur at the end of a letter, particularly for more formal communications, in which case it is placed within a <code class="html"><span class="p">&lt;</span><span class="nt">footer</span><span class="p">&gt;</span></code> element.</li>
				</ol>
			</section>
			<section id="letter-footers">
				<h3>Letter footers</h3>
				<ol type="1">
					<li>Parts of a letter after the body of the letter, for example the signature or postscript, are wrapped in a <code class="html"><span class="p">&lt;</span><span class="nt">footer</span><span class="p">&gt;</span></code> element.</li>
					<li>The <code class="html">footer</code> element has the following CSS:
						<figure><code class="css full"><span class="nt">footer</span><span class="p">{</span>
	<span class="k">margin-top</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">right</span><span class="p">;</span>
<span class="p">}</span></code></figure>
					</li>
					<li>The valediction (for example, “Yours Truly” or “With best regards”) has the semantic inflection of <code class="html">z3998:valediction</code>.</li>
					<li>The sender’s name has semantic inflection of <code class="html">z3998:sender</code>. If the name appears to be a signature to the letter, it has the <code class="html">signature</code> class and the corresponding <code class="css"><span class="p">.</span><span class="nc">signature</span></code> CSS.
						<figure><code class="css full"><span class="p">.</span><span class="nc">signature</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">footer</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;signature z3998:sender&quot;</span><span class="p">&gt;&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;name&quot;</span><span class="p">&gt;</span>R. A.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> Johnson<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">footer</span><span class="p">&gt;</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">footer</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;z3998:sender&quot;</span><span class="p">&gt;&lt;</span><span class="nt">span</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;signature&quot;</span><span class="p">&gt;</span>John Doe<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>, President<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">footer</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Postscripts have the semantic inflection of <code class="html">z3998:postscript</code> and the following CSS:
						<figure><code class="css full"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">=~</span><span class="s2">&quot;z3998:postscript&quot;</span><span class="o">]</span><span class="p">{</span>
	<span class="k">margin-top</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">left</span><span class="p">;</span>
<span class="p">}</span></code></figure>
					</li>
				</ol>
			</section>
			<section id="id3" class="no-numbering">
				<h3>Examples</h3>
				<figure><code class="css full"><span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:letter&quot;</span><span class="o">]</span> <span class="nt">header</span><span class="p">{</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">right</span><span class="p">;</span>
<span class="p">}</span>

<span class="nt">footer</span><span class="p">{</span>
	<span class="k">margin-top</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">right</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:salutation&quot;</span><span class="o">]</span> <span class="o">+</span> <span class="nt">p</span><span class="o">,</span>
<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:letter&quot;</span><span class="o">]</span> <span class="nt">header</span> <span class="o">+</span> <span class="nt">p</span><span class="p">{</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:sender&quot;</span><span class="o">],</span>
<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:recipient&quot;</span><span class="o">],</span>
<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:salutation&quot;</span><span class="o">]</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">small-caps</span><span class="p">;</span>
<span class="p">}</span>

<span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;z3998:postscript&quot;</span><span class="o">]</span><span class="p">{</span>
	<span class="k">margin-top</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">text-indent</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">left</span><span class="p">;</span>
<span class="p">}</span>

<span class="p">.</span><span class="nc">signature</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:letter&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:salutation&quot;</span><span class="p">&gt;</span>Dearest Auntie,<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Please may we have some things for a picnic? Gerald will bring them. I would come myself, but I am a little tired. I think I have been growing rather fast.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">footer</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:valediction&quot;</span><span class="p">&gt;</span>Your loving niece,<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;signature&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:sender&quot;</span><span class="p">&gt;</span>Mabel<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:postscript&quot;</span><span class="p">&gt;&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span>P.S.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span><span class="ws">wj</span>—Lots, please, because some of us are very hungry.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">footer</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:letter&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;se:letter.dateline&quot;</span><span class="p">&gt;</span>Gracechurch-street, <span class="p">&lt;</span><span class="nt">time</span> <span class="na">datetime</span><span class="o">=</span><span class="s">&quot;08-02&quot;</span><span class="p">&gt;</span>August 2<span class="p">&lt;/</span><span class="nt">time</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">header</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:salutation&quot;</span><span class="p">&gt;</span>My dear Brother<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>, At last I am able to send you some tidings of my niece, and such as, upon the whole, I hope will give you satisfaction. Soon after you left me on Saturday, I was fortunate enough to find out in what part of London they were. The particulars, I reserve till we meet. It is enough to know they are discovered, I have seen them both⁠<span class="ws">wj</span>—<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>I shall write again as soon as anything more is determined on.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">footer</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:valediction&quot;</span><span class="p">&gt;</span>Yours, etc.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;signature&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:sender&quot;</span><span class="p">&gt;</span>Edward Gardner<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">footer</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
			</section>
		</section>
		<section id="images">
			<h2>Images</h2>
			<ol type="1">
				<li><code class="html"><span class="p">&lt;</span><span class="nt">img</span><span class="p">&gt;</span></code> elements have an <code class="html">alt</code> attribute that uses prose to describe the image in detail; this is what screen reading software will read aloud.
					<ol type="1">
						<li>The <code class="html">alt</code> attribute describes the visual image itself in words, which is not the same as writing a caption or describing its place in the book.
							<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">img</span> <span class="na">alt</span><span class="o">=</span><span class="s">&quot;The illustration for chapter 10&quot;</span> <span class="na">src</span><span class="o">=</span><span class="s">&quot;...&quot;</span> <span class="p">/&gt;</span></code></figure>
							<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">img</span> <span class="na">alt</span><span class="o">=</span><span class="s">&quot;Pierre’s fruit-filled dinner&quot;</span> <span class="na">src</span><span class="o">=</span><span class="s">&quot;...&quot;</span> <span class="p">/&gt;</span></code></figure>
							<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">img</span> <span class="na">alt</span><span class="o">=</span><span class="s">&quot;An apple and a pear inside a bowl, resting on a table.&quot;</span> <span class="na">src</span><span class="o">=</span><span class="s">&quot;...&quot;</span> <span class="p">/&gt;</span></code></figure>
						</li>
						<li>The <code class="html">alt</code> attribute is one or more complete sentences ended with periods or other appropriate punctuation. It is not composed of sentence fragments or complete sentences without ending punctuation.</li>
						<li>The <code class="html">alt</code> attribute is not necessarily the same as text in the image’s sibling <code class="html"><span class="p">&lt;</span><span class="nt">figcaption</span><span class="p">&gt;</span></code> element, if one is present.</li>
					</ol>
				</li>
				<li><code class="html"><span class="p">&lt;</span><span class="nt">img</span><span class="p">&gt;</span></code> elements have semantic inflection denoting the type of image. Common values are <code class="html">z3998:illustration</code> or <code class="html">z3998:photograph</code>.</li>
				<li><code class="html"><span class="p">&lt;</span><span class="nt">img</span><span class="p">&gt;</span></code> element whose image is black-on-white line art (i.e. exactly two colors, <strong>not</strong> grayscale!) are PNG files with a transparent background. They have the <code class="html">se:image.color-depth.black-on-transparent</code> semantic inflection.</li>
				<li><code class="html"><span class="p">&lt;</span><span class="nt">img</span><span class="p">&gt;</span></code> elements that are meant to be aligned on the block level or displayed as full-page images are contained in a parent <code class="html"><span class="p">&lt;</span><span class="nt">figure</span><span class="p">&gt;</span></code> element, with an optional <code class="html"><span class="p">&lt;</span><span class="nt">figcaption</span><span class="p">&gt;</span></code> sibling.
					<ol type="1">
						<li>When contained in a <code class="html"><span class="p">&lt;</span><span class="nt">figure</span><span class="p">&gt;</span></code> element, the <code class="html"><span class="p">&lt;</span><span class="nt">img</span><span class="p">&gt;</span></code> element does not have an <code class="html">id</code> attribute; instead the <code class="html"><span class="p">&lt;</span><span class="nt">figure</span><span class="p">&gt;</span></code> element has the <code class="html">id</code> attribute.</li>
						<li>An optional <code class="html"><span class="p">&lt;</span><span class="nt">figcaption</span><span class="p">&gt;</span></code> element containing a concise context-dependent caption may follow the <code class="html"><span class="p">&lt;</span><span class="nt">img</span><span class="p">&gt;</span></code> element within a <code class="html"><span class="p">&lt;</span><span class="nt">figure</span><span class="p">&gt;</span></code> element. This caption depends on the surrounding context, and is not necessarily (or even ideally) identical to the <code class="html"><span class="p">&lt;</span><span class="nt">img</span><span class="p">&gt;</span></code> element’s <code class="html">alt</code> attribute.</li>
						<li>All figure elements, regardless of positioning, have this CSS:
							<figure><code class="css full"><span class="nt">figure</span> <span class="nt">img</span><span class="p">{</span>
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
						<li><code class="html"><span class="p">&lt;</span><span class="nt">figure</span><span class="p">&gt;</span></code> elements that are meant to be displayed as full-page images have the <code class="html">full-page</code> class and this additional CSS:
							<figure><code class="css full"><span class="nt">figure</span><span class="p">.</span><span class="nc">full-page</span><span class="p">{</span>
	<span class="k">margin</span><span class="p">:</span> <span class="mi">0</span><span class="p">;</span>
	<span class="k">max-height</span><span class="p">:</span> <span class="mi">100</span><span class="kt">%</span><span class="p">;</span>
	<span class="k">page-break-before</span><span class="p">:</span> <span class="kc">always</span><span class="p">;</span>
	<span class="k">page-break-after</span><span class="p">:</span> <span class="kc">always</span><span class="p">;</span>
	<span class="k">page-break-inside</span><span class="p">:</span> <span class="kc">avoid</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">center</span><span class="p">;</span>
<span class="p">}</span></code></figure>
						</li>
						<li><code class="html"><span class="p">&lt;</span><span class="nt">figure</span><span class="p">&gt;</span></code> elements that meant to be aligned block-level with the text have this additional CSS:
							<figure><code class="css full"><span class="nt">figure</span><span class="p">{</span>
	<span class="k">margin</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span> <span class="kc">auto</span><span class="p">;</span>
	<span class="k">text-align</span><span class="p">:</span> <span class="kc">center</span><span class="p">;</span>
<span class="p">}</span></code></figure>
						</li>
					</ol>
				</li>
			</ol>
			<section id="id4" class="no-numbering">
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
<span class="p">&lt;</span><span class="nt">figure</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;illustration-10&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">img</span> <span class="na">alt</span><span class="o">=</span><span class="s">&quot;An apple and a pear inside a bowl, resting on a table.&quot;</span> <span class="na">src</span><span class="o">=</span><span class="s">&quot;../images/illustration-10.jpg&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:photograph&quot;</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">figcaption</span><span class="p">&gt;</span>The Monk’s Repast<span class="p">&lt;/</span><span class="nt">figcaption</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">figure</span><span class="p">&gt;</span></code></figure>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">figure</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;full-page&quot;</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;image-11&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">img</span> <span class="na">alt</span><span class="o">=</span><span class="s">&quot;A massive whale breaching the water, with a sailor floating in the water directly within the whale’s mouth.&quot;</span> <span class="na">src</span><span class="o">=</span><span class="s">&quot;../images/illustration-11.jpg&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:illustration&quot;</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">figcaption</span><span class="p">&gt;</span>The Whale eats Sailor Jim.<span class="p">&lt;/</span><span class="nt">figcaption</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">figure</span><span class="p">&gt;</span></code></figure>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He saw strange alien text that looked like this: <span class="p">&lt;</span><span class="nt">img</span> <span class="na">alt</span><span class="o">=</span><span class="s">&quot;A line of alien heiroglyphs.&quot;</span> <span class="na">src</span><span class="o">=</span><span class="s">&quot;../images/alien-text.svg&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:illustration se:color-depth.black-on-transparent&quot;</span> <span class="p">/&gt;</span>. There was nothing else amongst the ruins.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
			</section>
		</section>
		<section id="list-of-illustrations-the-loi">
			<h2>List of Illustrations (the LoI)</h2>
			<p>If an ebook has any illustrations that are <em>major structural components</em> of the work (even just one!), then the ebook includes an <code class="path">loi.xhtml</code> file at the end of the ebook. This file lists the illustrations in the ebook, along with a short caption or description.</p>
			<ol type="1">
				<li>The LoI is an XHTML file located in <code class="path">./src/epub/text/loi.xhtml</code>.</li>
				<li>The LoI file has the <code class="html">backmatter</code> semantic inflection.</li>
				<li>The LoI only contains links to images that are major structural components of the work.
					<ol type="1">
						<li>An illustration is a major structural component if, for example: it is an illustration of events in the book, like a full-page drawing or end-of-chapter decoration; it is essential to the plot, like a diagram of a murder scene or a map; or it is a component of the text, like photographs in a documentary narrative.</li>
						<li>An illustration is <em>not</em> a major structural components if, for example: it is a drawing used to represent a person’s signature, like an X mark; it is an inline drawing representing text in alien languages; it is a drawing used as a layout element to illustrate forms, tables, or diagrams.</li>
					</ol>
				</li>
				<li>The LoI file contains a single <code class="html"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"loi"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"loi"</span><span class="p">&gt;</span></code> element, which in turn contains an <code class="html"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>List of Illustrations<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code> element, followed by a <code class="html"><span class="p">&lt;</span><span class="nt">nav</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"loi"</span><span class="p">&gt;</span></code> element containing an <code class="html"><span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span></code> element, which in turn contains list items representing the images.</li>
				<li>If an image listed in the LoI has a <code class="html"><span class="p">&lt;</span><span class="nt">figcaption</span><span class="p">&gt;</span></code> element, then that caption is used in the anchor text for that LoI entry. If not, the image’s <code class="html">alt</code> attribute is used. If the <code class="html"><span class="p">&lt;</span><span class="nt">figcaption</span><span class="p">&gt;</span></code> element is too long for a concise LoI entry, the <code class="html">alt</code> attribute is used instead.</li>
				<li>Links to the images go directly to the image’s corresponding <code class="html">id</code> hashes, not just the top of the containing file.</li>
			</ol>
			<section id="id5" class="no-numbering">
				<h3>Examples</h3>
				<figure><code class="html full"><span class="cp">&lt;?xml version=&quot;1.0&quot; encoding=&quot;UTF-8&quot;?&gt;</span>
<span class="p">&lt;</span><span class="nt">html</span> <span class="na">xmlns</span><span class="o">=</span><span class="s">&quot;http://www.w3.org/1999/xhtml&quot;</span> <span class="na">xmlns:epub</span><span class="o">=</span><span class="s">&quot;http://www.idpf.org/2007/ops&quot;</span> <span class="na">epub:prefix</span><span class="o">=</span><span class="s">&quot;z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: http://standardebooks.org/vocab/1.0&quot;</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">&quot;en-GB&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">head</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>List of Illustrations<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">link</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;../css/core.css&quot;</span> <span class="na">rel</span><span class="o">=</span><span class="s">&quot;stylesheet&quot;</span> <span class="na">type</span><span class="o">=</span><span class="s">&quot;text/css&quot;</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">link</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;../css/local.css&quot;</span> <span class="na">rel</span><span class="o">=</span><span class="s">&quot;stylesheet&quot;</span> <span class="na">type</span><span class="o">=</span><span class="s">&quot;text/css&quot;</span><span class="p">/&gt;</span>
	<span class="p">&lt;/</span><span class="nt">head</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;backmatter&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;loi&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;loi&quot;</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">nav</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;loi&quot;</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>List of Illustrations<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span>
					<span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
						<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;../text/preface.xhtml#the-edge-of-the-world&quot;</span><span class="p">&gt;</span>The Edge of the World<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
					<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span>
					...
				<span class="p">&lt;/</span><span class="nt">ol</span><span class="p">&gt;</span>
			<span class="p">&lt;/</span><span class="nt">nav</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">body</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">html</span><span class="p">&gt;</span></code></figure>
			</section>
		</section>
		<section id="endnotes">
			<h2>Endnotes</h2>
			<ol type="1">
				<li>Ebooks do not have footnotes, only endnotes. Footnotes are instead converted to endnotes.</li>
				<li>“Ibid.” is a Latinism commonly used in endnotes to indicate that the source for a quotation or reference is the same as the last-mentioned source.
					<p>When the last-mentioned source is in the previous endnote, Ibid. is replaced by the full reference; otherwise Ibid. is left as-is. Since ebooks use popup endnotes, “Ibid.” becomes meaningless without context.</p>
				</li>
			</ol>
			<section id="noterefs">
				<h3>Noterefs</h3>
				<p>The noteref is the superscripted number in the body text that links to the endnote at the end of the book.</p>
				<ol type="1">
					<li>Endnotes are referenced in the text by an <code class="html"><span class="p">&lt;</span><span class="nt">a</span><span class="p">&gt;</span></code> tag with the semantic inflection <code class="html">noteref</code>.
						<ol type="1">
							<li>Noterefs point directly to the corresponding endnote <code class="html"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span></code> element in the endnotes file.</li>
							<li>Noterefs have an <code class="html">id</code> attribute like <code class="html">noteref-n</code>, where <code class="html">n</code> is identical to the endnote number.</li>
							<li>The text of the noteref is the endnote number.</li>
						</ol>
					</li>
					<li>If located at the end of a sentence, noterefs are placed after ending punctuation.</li>
					<li>If the endnote references an entire sentence in quotation marks, or the last word in a sentence in quotation marks, then the noteref is placed outside the quotation marks.</li>
				</ol>
			</section>
			<section id="the-endnotes-file">
				<h3>The endnotes file</h3>
				<ol type="1">
					<li>Endnotes are in an XHTML file located in <code class="path">./src/epub/text/endnotes.xhtml</code>.</li>
					<li>The endnotes file has the <code class="html">backmatter</code> semantic inflection.</li>
					<li>The endnotes file contains a single <code class="html"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"endnotes"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"endnotes"</span><span class="p">&gt;</span></code> element, which in turn contains an <code class="html"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>Endnotes<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code> element, followed by an <code class="html"><span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span></code> element containing list items representing the endnotes.</li>
					<li>Each endnote’s <code class="html">id</code> attribute is in sequential ascending order.</li>
				</ol>
			</section>
			<section id="individual-endnotes">
				<h3>Individual endnotes</h3>
				<ol type="1">
					<li>An endnote is an <code class="html"><span class="p">&lt;</span><span class="nt">li</span> <span class="na">id</span><span class="o">=</span><span class="s">"note-n"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"endnote"</span><span class="p">&gt;</span></code> element containing one or more block-level text elements and one backlink element.</li>
					<li>Each endnote’s contains a backlink, which has the semantic inflection <code class="html">backlink</code>, contains the text <code class="html">↩</code>, and has the <code class="html">href</code> attribute pointing to the corresponding noteref hash.
						<ol type="1">
							<li>In endnotes where the last block-level element is a <code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code> element, the backlink goes at the end of the <code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code> element, preceded by exactly one space.</li>
							<li>In endnotes where the last block-level element is verse, quotation, or otherwise not plain prose text, the backlink goes in its own <code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code> element following the last block-level element in the endnote.</li>
						</ol>
					</li>
					<li>Endnotes with ending citations have those citations are wrapped in a <code class="html"><span class="p">&lt;</span><span class="nt">cite</span><span class="p">&gt;</span></code> element, including any em-dashes. A space follows the <code class="html"><span class="p">&lt;</span><span class="nt">cite</span><span class="p">&gt;</span></code> element, before the backlink.</li>
				</ol>
			</section>
			<section id="id6" class="no-numbering">
				<h3>Examples</h3>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>... a continent that was not rent asunder by volcanic forces as was that legendary one of Atlantis in the Eastern Ocean.<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;endnotes.xhtml#note-1&quot;</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;noteref-1&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;noteref&quot;</span><span class="p">&gt;</span>1<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span> My work in Java, in Papua, ...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				<figure><code class="html full"><span class="cp">&lt;?xml version=&quot;1.0&quot; encoding=&quot;UTF-8&quot;?&gt;</span>
<span class="p">&lt;</span><span class="nt">html</span> <span class="na">xmlns</span><span class="o">=</span><span class="s">&quot;http://www.w3.org/1999/xhtml&quot;</span> <span class="na">xmlns:epub</span><span class="o">=</span><span class="s">&quot;http://www.idpf.org/2007/ops&quot;</span> <span class="na">epub:prefix</span><span class="o">=</span><span class="s">&quot;z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: http://standardebooks.org/vocab/1.0&quot;</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">&quot;en-GB&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">head</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>Endnotes<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">link</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;../css/core.css&quot;</span> <span class="na">rel</span><span class="o">=</span><span class="s">&quot;stylesheet&quot;</span> <span class="na">type</span><span class="o">=</span><span class="s">&quot;text/css&quot;</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">link</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;../css/local.css&quot;</span> <span class="na">rel</span><span class="o">=</span><span class="s">&quot;stylesheet&quot;</span> <span class="na">type</span><span class="o">=</span><span class="s">&quot;text/css&quot;</span><span class="p">/&gt;</span>
	<span class="p">&lt;/</span><span class="nt">head</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;backmatter&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;endnotes&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;endnotes&quot;</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>Endnotes<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">li</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;note-1&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;endnote&quot;</span><span class="p">&gt;</span>
					<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>For more detailed observations on these points refer to <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;name&quot;</span><span class="p">&gt;</span>G.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> Volkens, “Uber die Karolinen Insel Yap.” <span class="p">&lt;</span><span class="nt">cite</span><span class="p">&gt;</span>—<span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;name eoc&quot;</span><span class="p">&gt;</span>W. T. G.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">cite</span><span class="p">&gt;</span> <span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;chapter-2.xhtml#noteref-1&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;backlink&quot;</span><span class="p">&gt;</span>↩<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
				<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span>
			<span class="p">&lt;/</span><span class="nt">ol</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">li</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;note-2&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;endnote&quot;</span><span class="p">&gt;</span>
					<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:verse&quot;</span><span class="p">&gt;</span>
						<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
							<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>“Who never ceases still to strive,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
							<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
							<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>’Tis him we can deliver.”<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
						<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
					<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span>
					<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
						<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;chapter-4.xhtml#noteref-2&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;backlink&quot;</span><span class="p">&gt;</span>↩<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
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