<?
require_once('Core.php');
?><?= Template::Header(['title' => '7. Table of Contents Patterns - The Standard Ebooks Manual', 'highlight' => 'contribute', 'manual' => true]) ?>
	<main>
		<article class="manual">

	<section data-start-at="7" id="table-of-contents-patterns">
		<h1>Table of Contents Patterns</h1>
		<p>The table of contents (the ToC) is not viewable as a page in the ebook’s reading order. Instead, the reader’s ereading system displays the ToC as part of its reading interface.</p>
		<p>These rules outline how to structure the ToC. Typically, the output of <code class="bash">se print-toc</code> constructs ToCs according to these rules, without further changes being necessary.</p>
		<section id="the-toc-nav-element">
			<h2>The ToC <code class="html"><span class="p">&lt;</span><span class="nt">nav</span><span class="p">&gt;</span></code> element</h2>
			<ol type="1">
				<li>The first child of the Toc’s <code class="html"><span class="p">&lt;</span><span class="nt">body</span><span class="p">&gt;</span></code> tag is a <code class="html"><span class="p">&lt;</span><span class="nt">nav</span><span class="p">&gt;</span></code> element with the semantic inflection <cite>toc</cite>.</li>
				<li>The first child of the <code class="html"><span class="p">&lt;</span><span class="nt">nav</span><span class="p">&gt;</span></code> element is a <code class="html"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>Table of Contents<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code> element.</li>
				<li>The second child of the <code class="html"><span class="p">&lt;</span><span class="nt">nav</span><span class="p">&gt;</span></code> element is an <code class="html"><span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span></code> element representing the items in the Table of Contents.</li>
			</ol>
			<section id="the-top-level-ol-element">
				<h3>The top-level <code class="html"><span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span></code> element</h3>
				<p>The <code class="html"><span class="p">&lt;</span><span class="nt">nav</span><span class="p">&gt;</span></code> element’s top-level <code class="html"><span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span></code> element contains a list of items in the Table of Contents.</p>
				<ol type="1">
					<li>The first child is a link to the titlepage.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/titlepage.xhtml&quot;</span><span class="p">&gt;</span>Titlepage<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>The second child is a link to the imprint.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/imprint.xhtml&quot;</span><span class="p">&gt;</span>Imprint<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>The second-to-last child is a link to the colophon.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/colophon.xhtml&quot;</span><span class="p">&gt;</span>Colophon<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>The second-to-last child is a link to the Uncopyright.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/uncopyright.xhtml&quot;</span><span class="p">&gt;</span>Uncopyright<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>In books with half title pages, the half title page is listed in the ToC and the next sibling is an <code class="html"><span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span></code> element containing the book’s contents.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/halftitle.xhtml&quot;</span><span class="p">&gt;</span>The Moon Pool<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/chapter-1.xhtml&quot;</span><span class="p">&gt;&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>I<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>: The Thing on the Moon Path<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/chapter-2.xhtml&quot;</span><span class="p">&gt;&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>II<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>: “Dead! All Dead!”<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
			<section id="li-descendents">
				<h3><code class="html"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span></code> descendents</h3>
				<ol type="1">
					<li>Each <code class="html"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span></code> contains an <code class="html"><span class="p">&lt;</span><span class="nt">a</span><span class="p">&gt;</span></code> tag pointing to a file or hash, and optionally also contains an <code class="html"><span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span></code> element representing a nested series of ToC items.</li>
					<li>If an <code class="html"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span></code> element contains a nested <code class="html"><span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span></code> element, that <code class="html"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span></code>’s first child is an <code class="html"><span class="p">&lt;</span><span class="nt">a</span><span class="p">&gt;</span></code> element that points to the beginning of that section.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/halftitle.xhtml&quot;</span><span class="p">&gt;</span>Sybil<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/book-1.xhtml&quot;</span><span class="p">&gt;</span>Book <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>I<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
					<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/chapter-1-1.xhtml&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>I<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
				<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Roman numerals in the ToC have the semantic inflection of <code class="html">z3998:roman</code>. A <code class="html"><span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span></code> element is included if the entire contents of the <code class="html"><span class="p">&lt;</span><span class="nt">a</span><span class="p">&gt;</span></code> element are not a Roman numeral.
						<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/chapter-1.xhtml&quot;</span><span class="p">&gt;</span>I<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span></code></figure>
						<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/chapter-1.xhtml&quot;</span><span class="p">&gt;&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>I<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span></code></figure>
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/chapter-1.xhtml&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>I<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span></code></figure>
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/book-1.xhtml&quot;</span><span class="p">&gt;</span>Book <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>I<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span>
		...
	<span class="p">&lt;/</span><span class="nt">ol</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
			<section id="a-descendents">
				<h3><code class="html"><span class="p">&lt;</span><span class="nt">a</span><span class="p">&gt;</span></code> descendents</h3>
				<ol type="1">
					<li>Chapters without titles are represented by their Roman ordinal, without the word <code class="html">Chapter</code>.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">a</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title z3998:roman&quot;</span><span class="p">&gt;</span>XI<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Chapters with titles are represented by their Roman ordinal, followed by a colon and a space, followed by the chapter title.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/chapter-3.xhtml&quot;</span><span class="p">&gt;&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>III<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>: The Moon Rock<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Chapters with unique identifiers (i.e. not <code class="html">Chapter</code>, but something unique to the style of the book, like <code class="html">Book</code> or <code class="html">Stave</code>), include that unique identifier in the <code class="html"><span class="p">&lt;</span><span class="nt">a</span><span class="p">&gt;</span></code> element.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/chapter-1.xhtml&quot;</span><span class="p">&gt;</span>Stave <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>I<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>: Marley’s Ghost<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>High-level sections (like parts or divisions) without titles are represented by their identifier (like <code class="html">Book</code> or <code class="html">Part</code>), followed by their Roman ordinal.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/book-1.xhtml&quot;</span><span class="p">&gt;</span>Book <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>I<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">a</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>High-level sections (like parts or divisions) with titles include the title.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/book-10.xhtml&quot;</span><span class="p">&gt;</span>Book <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>X<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>: The Boys<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Sections that are not chapters do not include their subtitles in the ToC.
						<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/epilogue.xhtml&quot;</span><span class="p">&gt;</span>Epilogue: A Morning Call<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span></code></figure>
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/epilogue.xhtml&quot;</span><span class="p">&gt;</span>Epilogue<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>High-level sections (like parts or divisions) with titles include the title.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/book-10.xhtml&quot;</span><span class="p">&gt;</span>Book <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>X<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>: The Boys<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Entries for half title pages do not include the subtitle.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/halftitle.xhtml&quot;</span><span class="p">&gt;</span>His Last Bow<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span>
		...
	<span class="p">&lt;/</span><span class="nt">ol</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
		</section>
		<section id="the-landmarks-nav-element">
			<h2>The landmarks <code class="html"><span class="p">&lt;</span><span class="nt">nav</span><span class="p">&gt;</span></code> element</h2>
			<p>After the first <code class="html"><span class="p">&lt;</span><span class="nt">nav</span><span class="p">&gt;</span></code> element, there is a second <code class="html"><span class="p">&lt;</span><span class="nt">nav</span><span class="p">&gt;</span></code> element with the semantic inflection of <code class="html">landmarks</code>.</p>
			<ol type="1">
				<li>The first child is an <code class="html"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>Landmarks<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code> element.</li>
				<li>The second child is an <code class="html"><span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span></code> element listing the major structural divisions of the book.</li>
			</ol>
			<section id="id1">
				<h3><code class="html"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span></code> descendents</h3>
				<p>Each <code class="html"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span></code> element contains a link to one of the major structural divisions of the book. In general, a structural division is any section of the book that is not part of the body text.</p>
				<ol type="1">
					<li>Each <code class="html"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span></code> element has the computed semantic inflection of top-level <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> element in the file. The computed semantic inflection includes inherited semantic inflection from the <code class="html"><span class="p">&lt;</span><span class="nt">body</span><span class="p">&gt;</span></code> tag.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/preface.xhtml&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;frontmatter preface&quot;</span><span class="p">&gt;</span>Preface<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>The body text is represented by a link to the first file of the body text. In a prose novel, this is usually Chapter 1 or Part 1. In a collection this is usually the first item, like the first short story in a short story collection. The text is the title of the work as represented in the metadata <code class="html"><span class="p">&lt;</span><span class="nt">dc:title</span><span class="p">&gt;</span></code> element.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/book-1.xhtml&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;bodymatter z3998:fiction&quot;</span><span class="p">&gt;</span>Sybil<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/chapter-1.xhtml&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;bodymatter z3998:fiction&quot;</span><span class="p">&gt;</span>The Moon Pool<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">a</span> <span class="na">href</span><span class="o">=</span><span class="s">&quot;text/the-adventure-of-wisteria-lodge.xhtml&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;bodymatter z3998:fiction&quot;</span><span class="p">&gt;</span>His Last Bow<span class="p">&lt;/</span><span class="nt">a</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
		</section>
	</section>
		</article>
	</main>
<?= Template::Footer() ?>