<?
require_once('Core.php');
?><?= Template::Header(['title' => '5. General XHTML Patterns - The Standard Ebooks Manual', 'highlight' => 'contribute', 'manual' => true]) ?>
	<main>
		<article class="manual">

	<section data-start-at="5" id="general-xhtml-patterns">
		<h1>General XHTML Patterns</h1>
		<p>Intro.</p>
		<section id="the-id-attribute">
			<h2>The <code class="html">id</code> attribute</h2>
			<section id="id-attributes-of-section-elements">
				<h3><code class="html">id</code> attributes of <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> elements</h3>
				<ol type="1">
					<li>Each <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> has an <code class="html">id</code> attribute identical to the filename containing that <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code>, without the trailing extension.</li>
					<li>In files containing multiple <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> elements, each <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> has an <code class="html">id</code> attribute identical to what the filename <em>would</em> be if the section was in an individual file, without the trailing extension.
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;bodymatter z3998:fiction&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">article</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;the-fox-and-the-grapes&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;se:short-story&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>The Fox and the Grapes<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">article</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">article</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;the-goose-that-laid-the-golden-eggs&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;se:short-story&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>The Goose That Laid the Golden Eggs<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">article</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">body</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
			<section id="id-attributes-of-other-elements">
				<h3><code class="html">id</code> attributes of other elements</h3>
				<ol type="1">
					<li>Elements that are not <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> elements do not have an <code class="html">id</code> attribute, unless a part of the ebook, like an endnote, refers to a specific point in the book, and a direct link is desirable.</li>
					<li><code class="html">id</code> attributes are generally used to identify parts of the document that a reader may wish to navigate to using a hash in the URL. That generally means major structural divisions.</li>
					<li><code class="html">id</code> attributes are not used as hooks for CSS styling.</li>
					<li>If an element that is not a <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> requires an <code class="html">id</code> attribute, the attribute’s value is the name of the tag followed by <code class="path">-N</code>, where <code class="path">N</code> is the sequence number of the tag start at <code class="path">1</code>.
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>See <span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;#p-4&quot;</span><span class="p">&gt;</span>this paragraph<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span> for more details.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;p-4&quot;</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
		</section>
		<section id="the-class-attribute">
			<h2>The <code class="html">class</code> attribute</h2>
			<p>Classes denote a group of elements sharing a similar style.</p>
			<ol type="1">
				<li>Classes are <em>not</em> used as single-use style hooks. There is almost always a way to compose a CSS selector to select a single element without the use of a one-off class.
					<figure class="wrong"><code class="css full"><span class="p">.</span><span class="nc">business-card</span><span class="p">{</span>
	<span class="k">border</span><span class="p">:</span> <span class="mi">1</span><span class="kt">px</span> <span class="kc">solid</span><span class="p">;</span>
	<span class="k">padding</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span></code></figure>
					<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;bodymatter z3998:fiction&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;chapter&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;business-card&quot;</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>John Doe, <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;eoc&quot;</span><span class="p">&gt;</span>Esq.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">body</span><span class="p">&gt;</span></code></figure>
					<figure class="corrected"><code class="css full"><span class="p">#</span><span class="nn">chapter-3</span> <span class="nt">blockquote</span><span class="p">{</span>
	<span class="k">border</span><span class="p">:</span> <span class="mi">1</span><span class="kt">px</span> <span class="kc">solid</span><span class="p">;</span>
	<span class="k">padding</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span></code></figure>
					<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;bodymatter z3998:fiction&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;chapter-3&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;chapter&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">blockquote</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>John Doe, <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;eoc&quot;</span><span class="p">&gt;</span>Esq.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">body</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>Classes are used to style a recurring <em>class</em> of elements, i.e. a class of element that appears more than once in an ebook.
					<figure class="corrected"><code class="css full"><span class="p">.</span><span class="nc">business-card</span><span class="p">{</span>
	<span class="k">border</span><span class="p">:</span> <span class="mi">1</span><span class="kt">px</span> <span class="kc">solid</span><span class="p">;</span>
	<span class="k">padding</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
<span class="p">}</span></code></figure>
					<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;bodymatter z3998:fiction&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;chapter-3&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;chapter&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;business-card&quot;</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Jane Doe, <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;eoc&quot;</span><span class="p">&gt;</span>Esq.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;business-card&quot;</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>John Doe, <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;eoc&quot;</span><span class="p">&gt;</span>Esq.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">body</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>Class names describe <em>what</em> they are styling semantically, <em>not</em> the actual style the class is applying.
					<figure class="wrong"><code class="css full"><span class="p">.</span><span class="nc">black-border</span><span class="p">{</span>
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
		<section id="the-title-element">
			<h2>The <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element</h2>
			<ol type="1">
				<li>The <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element contains an appropriate description of the local file only. It does not contain the book title.</li>
			</ol>
			<section id="titles-of-files-that-are-an-individual-chapter-or-part-division">
				<h3>Titles of files that are an individual chapter or part division</h3>
				<ol type="1">
					<li>Convert chapter or part numbers that are in Roman numerals to decimal numbers. Do not convert other Roman numerals that may be in the chapter title.
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>Chapter 10<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>If a chapter or part is only an ordinal and has no title or subtitle, the <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element is <code class="html">Chapter</code> followed by the chapter number.
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>Chapter 4<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span>
...
<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title z3998:roman&quot;</span><span class="p">&gt;</span>IV<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
...
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The chapter body...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>If a chapter or part has a title or subtitle, the <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element is <code class="html">Chapter</code>, followed by the chapter number in decimal, followed by a colon and a single space, followed by the title or subtitle.
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>Chapter 6: The Reign of Louis XVI<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span>
...
<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title z3998:roman&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>VI<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>&gt;
	<span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;subtitle&quot;</span><span class="p">&gt;</span>The Reign of Louis <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>XVI<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
...
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The chapter body...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
			<section id="titles-of-files-that-are-not-chapter-or-part-divisions">
				<h3>Titles of files that are not chapter or part divisions</h3>
				<ol type="1">
					<li>Files that are not a chapter or a part division, like a preface, introduction, or epigraph, have a <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element that contains the complete title of the section.
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>Preface<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>If a file contains a section with a title or subtitle, the <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element contains the title, followed by a colon and a single space, followed by the title or subtitle.
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>Quevedo and His Works: With an Essay on the Picaresque Novel<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
		</section>
		<section id="ordered-numbered-and-unordered-lists">
			<h2>Ordered/numbered and unordered lists</h2>
			<ol type="1">
				<li>All <code class="html"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span></code> children of <code class="html"><span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span></code> and <code class="html"><span class="p">&lt;</span><span class="nt">ul</span><span class="p">&gt;</span></code> elements have at least one direct child block-level tag. This is usually a <code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code> element, but not necessarily; for example, a <code class="html"><span class="p">&lt;</span><span class="nt">blockquote</span><span class="p">&gt;</span></code> element might also be appropriate.
					<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">ul</span><span class="p">&gt;</span>
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
		<section id="tables">
			<h2>Tables</h2>
			<ol type="1">
				<li><code class="html"><span class="p">&lt;</span><span class="nt">table</span><span class="p">&gt;</span></code> elements have a direct child <code class="html"><span class="p">&lt;</span><span class="nt">tbody</span><span class="p">&gt;</span></code> element.
					<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">table</span><span class="p">&gt;</span>
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
				<li><code class="html"><span class="p">&lt;</span><span class="nt">table</span><span class="p">&gt;</span></code> elements may have an optional direct child <code class="html"><span class="p">&lt;</span><span class="nt">thead</span><span class="p">&gt;</span></code> element, if a table heading is desired.</li>
				<li><code class="html"><span class="p">&lt;</span><span class="nt">table</span><span class="p">&gt;</span></code> elements that are used to display tabular numerical data, for example columns of sums, have CSS styling for tabular numbers: <code class="css"><span class="p">{</span> <span class="k">font-variant-numeric</span><span class="p">:</span> <span class="n">tabular-nums</span><span class="p">;</span> <span class="p">}</span></code>.
					<figure class="corrected"><code class="css full"><span class="nt">table</span> <span class="nt">td</span><span class="p">:</span><span class="nd">last-child</span><span class="p">{</span>
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
		<section id="xml-lang-attributes">
			<h2><code class="html">xml:lang</code> attributes</h2>
			<ol type="1">
				<li>When words are required to be pronounced in a language other than English, the <code class="html">xml:lang</code> attribute is used to indicate the IETF language tag in use.
					<ol type="1">
						<li>The <code class="html">xml:lang</code> attribute is used even if a word is not required to be italicized. This allows screen readers to understand that a particular word or phrase should be pronounced in a certain way.</li>
						<li>The <code class="html">xml:lang</code> attribute is included in <em>any</em> word that requires special pronunciation, including names of places and titles of books.</li>
					</ol>
					<figure class="corrected"><code class="html full">She opened the book titled <span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;se:name.publication.book&quot;</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">&quot;la&quot;</span><span class="p">&gt;</span>Mortis Imago<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>.</code></figure>
					<ol type="1">
						<li>The <code class="html">xml:lang</code> attribute is applied to the highest-level tag possible. If italics are required and moving the <code class="html">xml:lang</code> attribute would also remove an <code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code> element, the parent element can be styled with <code class="css"><span class="nt">body</span> <span class="o">[</span><span class="nt">xml</span><span class="o">|</span><span class="nt">lang</span><span class="o">]</span><span class="p">{</span> <span class="k">font-style</span><span class="p">:</span> <span class="kc">italic</span><span class="p">;</span> <span class="p">}</span></code>.</li>
					</ol>
					<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">blockquote</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">&quot;es&quot;</span><span class="p">&gt;</span>“Como estas?” el preguntó.<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">&quot;es&quot;</span><span class="p">&gt;</span>“Bien, gracias,” dijo ella.<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
					<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">&quot;es&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Como estas?” el preguntó.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Bien, gracias,” dijo ella.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
				</li>
			</ol>
		</section>
	</section>
		</article>
	</main>
<?= Template::Footer() ?>
