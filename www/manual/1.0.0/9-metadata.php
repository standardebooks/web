<?
require_once('Core.php');
?><?= Template::Header(['title' => '9. Metadata - The Standard Ebooks Manual', 'highlight' => 'contribute', 'manual' => true]) ?>
	<main class="manual"><nav><p><a href="/manual/1.0.0">The Standard Ebooks Manual of Style</a></p><ol><li><p><a href="/manual/1.0.0/1-code-style">1. XHTML, CSS, and SVG Code Style</a></p><ol><li><p><a href="/manual/1.0.0/1-code-style#1.1">1.1 XHTML formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.2">1.2 CSS formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.3">1.3 SVG Formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.4">1.4 Commits and Commit Messages</a></p></li></ol></li><li><p><a href="/manual/1.0.0/2-filesystem">2. Filesystem Layout and File Naming Conventions</a></p><ol><li><p><a href="/manual/1.0.0/2-filesystem#2.1">2.1 File locations</a></p></li><li><p><a href="/manual/1.0.0/2-filesystem#2.2">2.2 XHTML file naming conventions</a></p></li><li><p><a href="/manual/1.0.0/2-filesystem#2.3">2.3 The se-lint-ignore.xml file</a></p></li></ol></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook">3. The Structure of an Ebook</a></p><ol><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.1">3.1 Front matter</a></p></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.2">3.2 Body matter</a></p></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.3">3.3 Back matter</a></p></li></ol></li><li><p><a href="/manual/1.0.0/4-semantics">4. Semantics</a></p><ol><li><p><a href="/manual/1.0.0/4-semantics#4.1">4.1 Semantic Tags</a></p></li><li><p><a href="/manual/1.0.0/4-semantics#4.2">4.2 Semantic Inflection</a></p></li></ol></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns">5. General XHTML Patterns</a></p><ol><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.1">5.1 id attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.2">5.2 class attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.3">5.3 xml:lang attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.4">5.4 The &lt;title&gt; element</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.5">5.5 Ordered/numbered and unordered lists</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.6">5.6 Tables</a></p></li></ol></li><li><p><a href="/manual/1.0.0/6-high-level-structural-patterns">6. High Level Structural Patterns</a></p><ol><li><p><a href="/manual/1.0.0/6-high-level-structural-patterns#6.1">6.1 Sectioning</a></p></li><li><p><a href="/manual/1.0.0/6-high-level-structural-patterns#6.2">6.2 Headers</a></p></li><li><p><a href="/manual/1.0.0/6-high-level-structural-patterns#6.3">6.3 Epigraphs</a></p></li><li><p><a href="/manual/1.0.0/6-high-level-structural-patterns#6.4">6.4 Poetry, verse, and songs</a></p></li><li><p><a href="/manual/1.0.0/6-high-level-structural-patterns#6.5">6.5 Plays &amp; Drama</a></p></li><li><p><a href="/manual/1.0.0/6-high-level-structural-patterns#6.6">6.6 Letters</a></p></li><li><p><a href="/manual/1.0.0/6-high-level-structural-patterns#6.7">6.7 Images</a></p></li><li><p><a href="/manual/1.0.0/6-high-level-structural-patterns#6.8">6.8 List of Illustrations (the LoI)</a></p></li><li><p><a href="/manual/1.0.0/6-high-level-structural-patterns#6.9">6.9 Endnotes</a></p></li></ol></li><li><p><a href="/manual/1.0.0/7-table-of-contents-patterns">7. Table of Contents Patterns</a></p><ol><li><p><a href="/manual/1.0.0/7-table-of-contents-patterns#7.1">7.1 The ToC &lt;nav&gt; element</a></p></li><li><p><a href="/manual/1.0.0/7-table-of-contents-patterns#7.2">7.2 The landmarks &lt;nav&gt; element</a></p></li></ol></li><li><p><a href="/manual/1.0.0/8-typography">8. Typography</a></p><ol><li><p><a href="/manual/1.0.0/8-typography#8.1">8.1 Section titles and ordinals</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.2">8.2 Italics</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.3">8.3 Capitalization</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.4">8.4 Indentation</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.5">8.5 Chapter headers</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.6">8.6 Ligatures</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.7">8.7 Punctuation and spacing</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.8">8.8 Numbers, measurements, and math</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.9">8.9 Latinisms</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.10">8.10 Initials and abbreviations</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.11">8.11 Times</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.12">8.12 Chemicals and compounds</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.13">8.13 Temperatures</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.14">8.14 Scansion</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.15">8.15 Legal cases and terms</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.16">8.16 Morse Code</a></p></li></ol></li><li><p><a href="/manual/1.0.0/9-metadata">9. Metadata</a></p><ol><li><p><a href="/manual/1.0.0/9-metadata#9.1">9.1 General URL rules</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.2">9.2 The ebook identifier</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.3">9.3 Publication date and release identifiers</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.4">9.4 Book titles</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.5">9.5 Book subjects</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.6">9.6 Book descriptions</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.7">9.7 Book language</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.8">9.8 Book transcription and page scan sources</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.9">9.9 Additional book metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.10">9.10 General contributor rules</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.11">9.11 The author metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.12">9.12 The translator metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.13">9.13 The illustrator metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.14">9.14 The cover artist metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.15">9.15 Metadata for additional contributors</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.16">9.16 Transcriber metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.17">9.17 Producer metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.18">9.18 The ebook manifest</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.19">9.19 The ebook spine</a></p></li></ol></li><li><p><a href="/manual/1.0.0/10-art-and-images">10. Art and Images</a></p><ol><li><p><a href="/manual/1.0.0/10-art-and-images#10.1">10.1 Complete list of files</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.2">10.2 SVG patterns</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.3">10.3 The cover image</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.4">10.4 The titlepage image</a></p></li></ol></li></ol></nav>
		<article>

<section id="9"><aside class="number">9</aside>
<h1>Metadata</h1>
<p>Metadata in a Standard Ebook is stored in the <code class="path">./src/epub/content.opf</code> file. The file contains some boilerplate that an ebook producer won’t have to touch, and a lot of information that they <em>will</em> have to touch as an ebook is produced.</p>
<p>Follow the general structure of the <code class="path">content.opf</code> file generated by <code class="bash">se create-draft</code>. Don’t rearrange the order of anything in there.</p>
<section id="9.1"><aside class="number">9.1</aside>
<h2>General URL rules</h2>
<ol type="1">
<li id="9.1.1"><aside class="number">9.1.1</aside><p>URLs used in metadata are https where possible.</p></li>
<li id="9.1.2"><aside class="number">9.1.2</aside><p>URLs used in metadata do not contain query strings, or if a query string is required, only contain the minimum necessary query string to render the base resource.</p></li>
<li id="9.1.3"><aside class="number">9.1.3</aside><p>URLs used for Project Gutenberg page scans look like: <code class="path">https://www.gutenberg.org/ebooks/&lt;BOOK-ID&gt;</code>.</p></li>
<li id="9.1.4"><aside class="number">9.1.4</aside><p>URLs used for HathiTrust page scans look like: <code class="path">https://catalog.hathitrust.org/Record/&lt;RECORD-ID&gt;</code>.</p></li>
<li id="9.1.5"><aside class="number">9.1.5</aside><p>URLs used for Google Books page scans look like: <code class="path">https://books.google.com/books?id=&lt;BOOK-ID&gt;</code>.</p></li>
<li id="9.1.6"><aside class="number">9.1.6</aside><p>URLs used for Internet Archive page scans look like: <code class="path">https://archive.org/details/&lt;BOOK-ID&gt;</code>.</p></li>
</ol>
</section>
<section id="9.2"><aside class="number">9.2</aside>
<h2>The ebook identifier</h2>
<ol type="1">
<li id="9.2.1"><aside class="number">9.2.1</aside><p>The <code class="html"><span class="p">&lt;</span><span class="nt">dc:identifier</span><span class="p">&gt;</span></code> element contains the unique identifier for the ebook. The identifier is the Standard Ebooks URL for the ebook, prefaced by <code class="html">url:</code>.
					</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">dc:identifier</span> <span class="na">id</span><span class="o">=</span><span class="s">"uid"</span><span class="p">&gt;</span>url:https://standardebooks.org/ebooks/anton-chekhov/short-fiction/constance-garnett<span class="p">&lt;/</span><span class="nt">dc:identifier</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
<section id="9.2.2"><aside class="number">9.2.2</aside>
<h3>Forming the SE URL</h3>
<p>The SE URL is formed by the following algorithm.</p>
<p>(Note: Strings can be made URL-safe using the <code class="bash">se make-url-safe</code> tool.)</p>
<ul>
<li><p>Start with the URL-safe author of the work, as it appears on the titlepage. If there is more than one author, continue appending subsequent URL-safe authors, separated by an underscore. Do not alpha-sort the author name.</p></li>
<li><p>Append a forward slash, then the URL-safe title of the work. Do not alpha-sort the title.</p></li>
<li><p>If the work is translated, append a forward slash, then the URL-safe translator. If there is more than one translator, continue appending subsequent URL-safe translators, separated by an underscore. Do not alpha-sort translator names.</p></li>
<li><p>If the work is illustrated, append a forward slash, then the URL-safe illustrator. If there is more than one illustrator, continue appending subsequent URL-safe illustrators, separated by an underscore. Do not alpha-sort illustrator names.</p></li>
<li><p>Finally, <em>do not</em> append a trailing forward slash.</p></li>
</ul>
</section>
</section>
<section id="9.3"><aside class="number">9.3</aside>
<h2>Publication date and release identifiers</h2>
<p>There are several elements in the metadata describing the publication date, updated date, and revision number of the ebook. Generally these are not updated by hand; instead, the <code class="bash">se prepare-release</code> tool updates them automatically.</p>
<ol type="1">
<li id="9.3.1"><aside class="number">9.3.1</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">dc:date</span><span class="p">&gt;</span></code> is a timestamp representing the first publication date of this ebook file. Once the ebook is released to the public, this value doesn’t change.</p></li>
<li id="9.3.2"><aside class="number">9.3.2</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"dcterms:modified"</span><span class="p">&gt;</span></code> is a timestamp representing the last time this ebook file was modified. This changes often.</p></li>
<li id="9.3.3"><aside class="number">9.3.3</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:revision-number"</span><span class="p">&gt;</span></code> is a special SE extension representing the revision number of this ebook file. During production, this number will be 0. When the ebook is first released to the public, the number will increment to 1. Each time <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"dcterms:modified"</span><span class="p">&gt;</span></code> changes, the revision number is incremented.</p></li>
</ol>
</section>
<section id="9.4"><aside class="number">9.4</aside>
<h2>Book titles</h2>
<section id="9.4.1"><aside class="number">9.4.1</aside>
<h3>Books without subtitles</h3>
<ol type="1">
<li id="9.4.1.1"><aside class="number">9.4.1.1</aside><p>The <code class="html"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span></code> element contains the title.</p></li>
<li id="9.4.1.2"><aside class="number">9.4.1.2</aside><p>The <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"file-as"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#title"</span><span class="p">&gt;</span></code> element contains alpha-sorted title, even if the alpha-sorted title is identical to the unsorted title.</p></li>
</ol>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>The Moon Pool<span class="p">&lt;/</span><span class="nt">dc:title</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"file-as"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#title"</span><span class="p">&gt;</span>Moon Pool, The<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>Short Fiction<span class="p">&lt;/</span><span class="nt">dc:title</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"file-as"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#title"</span><span class="p">&gt;</span>Short Fiction<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>`</code></figure>
</section>
<section id="9.4.2"><aside class="number">9.4.2</aside>
<h3>Books with subtitles</h3>
<ol type="1">
<li id="9.4.2.1"><aside class="number">9.4.2.1</aside><p>The <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"title-type"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#title"</span><span class="p">&gt;</span>main<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code> element identifies the main part of the title.</p></li>
<li id="9.4.2.2"><aside class="number">9.4.2.2</aside><p>A second <code class="html"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">"subtitle"</span><span class="p">&gt;</span></code> element contain the subtitle, and is refined with <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"title-type"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#subtitle"</span><span class="p">&gt;</span>subtitle<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code>.</p></li>
<li id="9.4.2.3"><aside class="number">9.4.2.3</aside><p>A third <code class="html"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">"fulltitle"</span><span class="p">&gt;</span></code> element contains the complete title on one line, with the main title and subtitle separated by a colon and space, and is refined with <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"title-type"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#fulltitle"</span><span class="p">&gt;</span>extended<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code>.</p></li>
<li id="9.4.2.4"><aside class="number">9.4.2.4</aside><p>All three <code class="html"><span class="p">&lt;</span><span class="nt">dc:title</span><span class="p">&gt;</span></code> elements have an accompanying <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"file-as"</span><span class="p">&gt;</span></code> element, even if the <code class="html">file-as</code> value is the same as the title.</p></li>
</ol>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>The Moon Pool<span class="p">&lt;/</span><span class="nt">dc:title</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"file-as"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#title"</span><span class="p">&gt;</span>Moon Pool, The<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>The Man Who Was Thursday<span class="p">&lt;/</span><span class="nt">dc:title</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"file-as"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#title"</span><span class="p">&gt;</span>Man Who Was Thursday, The<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"title-type"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#title"</span><span class="p">&gt;</span>main<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">"subtitle"</span><span class="p">&gt;</span>A Nightmare<span class="p">&lt;/</span><span class="nt">dc:title</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"file-as"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#subtitle"</span><span class="p">&gt;</span>Nightmare, A<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"title-type"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#subtitle"</span><span class="p">&gt;</span>subtitle<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">"fulltitle"</span><span class="p">&gt;</span>The Man Who Was Thursday: A Nightmare<span class="p">&lt;/</span><span class="nt">dc:title</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"file-as"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#fulltitle"</span><span class="p">&gt;</span>Man Who Was Thursday, The<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"title-type"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#fulltitle"</span><span class="p">&gt;</span>extended<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code></figure>
</section>
<section id="9.4.3"><aside class="number">9.4.3</aside>
<h3>Books with a more popular alternate title</h3>
<p>Some books are commonly referred to by a shorter name than their actual title. For example, <i><a href="/ebooks/mark-twain/the-adventures-of-huckleberry-finn">The Adventures of Huckleberry Finn</a></i> is often simply known as <i>Huck Finn</i>.</p>
<ol type="1">
<li id="9.4.3.1"><aside class="number">9.4.3.1</aside><p>The <code class="html"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">"title-short"</span><span class="p">&gt;</span></code> element contains the common title. It is refined with <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"title-type"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#title-short"</span><span class="p">&gt;</span>short<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code>.</p></li>
<li id="9.4.3.2"><aside class="number">9.4.3.2</aside><p>The common title does not have a corresponding <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"file-as"</span><span class="p">&gt;</span></code> element.</p></li>
</ol>
</section>
<section id="9.4.4"><aside class="number">9.4.4</aside>
<h3>Books with numbers or abbreviations in the title</h3>
<p>Books that contain numbers or abbreviations in their title may be difficult to find with a search query, because there can be different ways to search for numbers or abbreviations. For example, a reader may search for <i><a href="/ebooks/jules-verne/around-the-world-in-eighty-days/george-makepeace-towle">Around the World in Eighty Days</a></i> by searching for “80” instead of “eighty”.</p>
<ol type="1">
<li id="9.4.4.1"><aside class="number">9.4.4.1</aside><p>If a book title contains numbers or abbreviations, a <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">id</span><span class="o">=</span><span class="s">"alternative-title"</span> <span class="na">id</span><span class="o">=</span><span class="s">"se:alternative-title"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#title"</span><span class="p">&gt;</span></code> element is placed after the main title block, containing the title with expanded or alternate spelling to facilitate possible search queries.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>Around the World in Eighty Days<span class="p">&lt;/</span><span class="nt">dc:title</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"file-as"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#title"</span><span class="p">&gt;</span>Around the World in Eighty Days<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">id</span><span class="o">=</span><span class="s">"alternative-title"</span> <span class="na">id</span><span class="o">=</span><span class="s">"se:alternative-title"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#title"</span><span class="p">&gt;</span>Around the World in 80 Days<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code></figure>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>File No. 113<span class="p">&lt;/</span><span class="nt">dc:title</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"file-as"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#title"</span><span class="p">&gt;</span>File No. 113<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">id</span><span class="o">=</span><span class="s">"alternative-title"</span> <span class="na">id</span><span class="o">=</span><span class="s">"se:alternative-title"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#title"</span><span class="p">&gt;</span>File Number One Hundred and Thirteen<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code></figure>
</li>
</ol>
</section>
</section>
<section id="9.5"><aside class="number">9.5</aside>
<h2>Book subjects</h2>
<section id="9.5.1"><aside class="number">9.5.1</aside>
<h3>The <code class="html"><span class="p">&lt;</span><span class="nt">dc:subject</span><span class="p">&gt;</span></code> element</h3>
<p><code class="html"><span class="p">&lt;</span><span class="nt">dc:subject</span><span class="p">&gt;</span></code> elements describe the categories the ebook belongs to.</p>
<ol type="1">
<li id="9.5.1.1"><aside class="number">9.5.1.1</aside><p>Each <code class="html"><span class="p">&lt;</span><span class="nt">dc:subject</span><span class="p">&gt;</span></code> has the <code class="html">id</code> attribute set to <code class="html">subject-#</code>, where # is a number starting at <code class="path">1</code>, without leading zeros, that increments with each subject.</p></li>
<li id="9.5.1.2"><aside class="number">9.5.1.2</aside><p>The <code class="html"><span class="p">&lt;</span><span class="nt">dc:subject</span><span class="p">&gt;</span></code> elements are arranged sequentially in a single block.</p></li>
<li id="9.5.1.3"><aside class="number">9.5.1.3</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">dc:subject</span><span class="p">&gt;</span></code> values are sourced from <a href="http://id.loc.gov/authorities/subjects.html">Library of Congress Subject Headings</a>.</p></li>
<li id="9.5.1.4"><aside class="number">9.5.1.4</aside><p>If the transcription for the ebook comes from Project Gutenberg, the values of the <code class="html"><span class="p">&lt;</span><span class="nt">dc:subject</span><span class="p">&gt;</span></code> elements come from the Project Gutenberg “bibrec” page for the ebook. Otherwise, the values come from the <a href="https://catalog.loc.gov">Library of Congress catalog</a> listing for the book.</p></li>
<li id="9.5.1.5"><aside class="number">9.5.1.5</aside><p>After the block of <code class="html"><span class="p">&lt;</span><span class="nt">dc:subject</span><span class="p">&gt;</span></code> elements there is a block of <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"authority"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#subject-N"</span><span class="p">&gt;</span></code> and <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"term"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#subject-N"</span><span class="p">&gt;</span></code> element pairs.
						</p><ol type="1">
<li id="9.5.1.5.1"><aside class="number">9.5.1.5.1</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"authority"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#subject-N"</span><span class="p">&gt;</span></code> contains the source for the category. For Library of Congress categories, the value is <code class="html">LCSH</code>.</p></li>
<li id="9.5.1.5.2"><aside class="number">9.5.1.5.2</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"term"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#subject-N"</span><span class="p">&gt;</span></code> contains term ID for that subject heading.</p></li>
</ol>
</li>
</ol>
<section id="examples">
<h4>Examples</h4>
<p>This example shows how to mark up the subjects for <i><a href="/ebooks/david-lindsay/a-voyage-to-arcturus">A Voyage to Arcturus</a></i>, by David Lindsay:</p>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">dc:subject</span> <span class="na">id</span><span class="o">=</span><span class="s">"subject-1"</span><span class="p">&gt;</span>Science fiction<span class="p">&lt;/</span><span class="nt">dc:subject</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">dc:subject</span> <span class="na">id</span><span class="o">=</span><span class="s">"subject-2"</span><span class="p">&gt;</span>Psychological fiction<span class="p">&lt;/</span><span class="nt">dc:subject</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">dc:subject</span> <span class="na">id</span><span class="o">=</span><span class="s">"subject-3"</span><span class="p">&gt;</span>Quests (Expeditions) -- Fiction<span class="p">&lt;/</span><span class="nt">dc:subject</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">dc:subject</span> <span class="na">id</span><span class="o">=</span><span class="s">"subject-4"</span><span class="p">&gt;</span>Life on other planets -- Fiction<span class="p">&lt;/</span><span class="nt">dc:subject</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"authority"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#subject-1"</span><span class="p">&gt;</span>LCSH<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"term"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#subject-1"</span><span class="p">&gt;</span>sh85118629<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"authority"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#subject-2"</span><span class="p">&gt;</span>LCSH<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"term"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#subject-2"</span><span class="p">&gt;</span>sh85108438<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"authority"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#subject-3"</span><span class="p">&gt;</span>LCSH<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"term"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#subject-3"</span><span class="p">&gt;</span>sh2008110314<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"authority"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#subject-4"</span><span class="p">&gt;</span>LCSH<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"term"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#subject-4"</span><span class="p">&gt;</span>sh2008106912<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code></figure>
</section>
</section>
<section id="9.5.2"><aside class="number">9.5.2</aside>
<h3>SE subjects</h3>
<p>Along with the Library of Congress categories, a set of SE subjects is included in the ebook metadata. Unlike Library of Congress categories, SE subjects are purposefully broad. They’re more like the subject categories in a small bookstore, as opposed to the precise, detailed, hierarchical Library of Congress categories.</p>
<ol type="1">
<li id="9.5.2.1"><aside class="number">9.5.2.1</aside><p>SE subjects are included with one or more <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:subject"</span><span class="p">&gt;</span></code> elements.
						</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:subject"</span><span class="p">&gt;</span>Fantasy<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:subject"</span><span class="p">&gt;</span>Philosophy<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code></figure>
</li>
<li id="9.5.2.2"><aside class="number">9.5.2.2</aside><p>Standard Ebooks contain at least one SE subject.</p></li>
</ol>
<section id="9.5.2.3"><aside class="number">9.5.2.3</aside>
<h4>All SE subjects</h4>
<ul>
<li><p>Adventure</p></li>
<li><p>Autobiography</p></li>
<li><p>Biography</p></li>
<li><p>Childrens</p></li>
<li><p>Comedy</p></li>
<li><p>Drama</p></li>
<li><p>Fantasy</p></li>
<li><p>Fiction</p></li>
<li><p>Horror</p></li>
<li><p>Memoir</p></li>
<li><p>Mystery</p></li>
<li><p>Nonfiction</p></li>
<li><p>Philosophy</p></li>
<li><p>Poetry</p></li>
<li><p>Satire</p></li>
<li><p>Science Fiction</p></li>
<li><p>Shorts</p></li>
<li><p>Spirituality</p></li>
<li><p>Travel</p></li>
</ul>
</section>
<section id="9.5.2.4"><aside class="number">9.5.2.4</aside>
<h4>Required SE subjects for specific types of books</h4>
<ol type="1">
<li id="9.5.2.4.1"><aside class="number">9.5.2.4.1</aside><p>Ebooks that are collections of short stories have the SE subject <code class="html">Shorts</code> as one of the SE subjects.</p></li>
<li id="9.5.2.4.2"><aside class="number">9.5.2.4.2</aside><p>Ebooks that are young adult or children’s books have the SE subject <code class="html">Childrens</code> as one of the SE subjects.</p></li>
</ol>
</section>
</section>
</section>
<section id="9.6"><aside class="number">9.6</aside>
<h2>Book descriptions</h2>
<p>An ebook has two kinds of descriptions: a short <code class="html"><span class="p">&lt;</span><span class="nt">dc:description</span><span class="p">&gt;</span></code> element, and a much longer <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:long-description"</span><span class="p">&gt;</span></code> element.</p>
<section id="9.6.1"><aside class="number">9.6.1</aside>
<h3>The short description</h3>
<p>The <code class="html"><span class="p">&lt;</span><span class="nt">dc:description</span><span class="p">&gt;</span></code> element contains a short, single-sentence summary of the ebook.</p>
<ol type="1">
<li id="9.6.1.1"><aside class="number">9.6.1.1</aside><p>The description is a single complete sentence ending in a period, not a sentence fragment or restatement of the title.</p></li>
<li id="9.6.1.2"><aside class="number">9.6.1.2</aside><p>The description is typogrified, i.e. it contains Unicode curly quotes, em-dashes, and the like.</p></li>
</ol>
</section>
<section id="9.6.2"><aside class="number">9.6.2</aside>
<h3>The long description</h3>
<p>The <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:long-description"</span><span class="p">&gt;</span></code> element contains a much longer description of the ebook.</p>
<ol type="1">
<li id="9.6.2.1"><aside class="number">9.6.2.1</aside><p>The long description is a non-biased, encyclopedia-like description of the book, including any relevant publication history, backstory, or historical notes. It is as detailed as possible without giving away plot spoilers. It does not impart the producer’s opinions of the book, or include content warnings. Think along the lines of a Wikipedia-like summary of the book and its history, <em>but under no circumstances can a producer copy and paste from Wikipedia!</em> (Wikipedia licenses articles under a CC license which is incompatible with Standard Ebooks’ CC0 public domain dedication.)</p></li>
<li id="9.6.2.2"><aside class="number">9.6.2.2</aside><p>The long descriptions is typogrified, i.e. it contains Unicode curly quotes, em-dashes, and the like.</p></li>
<li id="9.6.2.3"><aside class="number">9.6.2.3</aside><p>The long description is in <em>escaped</em> HTML, with the HTML beginning on its own line after the <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:long-description"</span><span class="p">&gt;</span></code> element.
						<aside class="tip">An easy way to escape HTML is to compose the long description in regular HTML, then insert it into the <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:long-description"</span><span class="p">&gt;</span></code> element surrounded by a <code class="html"><span class="cp">&lt;![CDATA[ ... ]]&gt;</span></code> element. Then, run the <code class="bash">se clean</code> tool, which will remove the <code class="html"><span class="cp">&lt;![CDATA[ ... ]]&gt;</span></code> element and escape the contained HTML.</aside>
</p></li>
<li id="9.6.2.4"><aside class="number">9.6.2.4</aside><p>Long description HTML follows the <a href="/manual/1.0.0/1-code-style">general code style conventions</a>.</p></li>
<li id="9.6.2.5"><aside class="number">9.6.2.5</aside><p>The long description element is directly followed by: <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"meta-auth"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#long-description"</span><span class="p">&gt;</span>https://standardebooks.org<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code></p></li>
</ol>
</section>
</section>
<section id="9.7"><aside class="number">9.7</aside>
<h2>Book language</h2>
<ol type="1">
<li id="9.7.1"><aside class="number">9.7.1</aside><p>The <code class="html"><span class="p">&lt;</span><span class="nt">dc:language</span><span class="p">&gt;</span></code> element follows the long description block. It contains the <a href="https://en.wikipedia.org/wiki/IETF_language_tag">IETF language tag</a> for the language that the work is in. Usually this is either <code class="html">en-US</code> or <code class="html">en-GB</code>.</p></li>
</ol>
</section>
<section id="9.8"><aside class="number">9.8</aside>
<h2>Book transcription and page scan sources</h2>
<ol type="1">
<li id="9.8.1"><aside class="number">9.8.1</aside><p>The <code class="html"><span class="p">&lt;</span><span class="nt">dc:source</span><span class="p">&gt;</span></code> elements represent URLs to sources for the transcription the ebook is based on, and page scans of the print sources used to correct the transcriptions.</p></li>
<li id="9.8.2"><aside class="number">9.8.2</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">dc:source</span><span class="p">&gt;</span></code> URLs are in https where possible.</p></li>
<li id="9.8.3"><aside class="number">9.8.3</aside><p>A book can contain more than one such element if multiple sources for page scans were used.</p></li>
</ol>
</section>
<section id="9.9"><aside class="number">9.9</aside>
<h2>Additional book metadata</h2>
<ol type="1">
<li id="9.9.1"><aside class="number">9.9.1</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:url.encyclopedia.wikipedia"</span><span class="p">&gt;</span></code> contains the Wikipedia URL for the book. This element is not present if there is no Wikipedia entry for the book.</p></li>
<li id="9.9.2"><aside class="number">9.9.2</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:url.vcs.github"</span><span class="p">&gt;</span></code> contains the SE GitHub URL for this ebook. This is calculated by taking the string <code class="html">https://github.com/standardebooks/</code> and appending the <a href="/manual/1.0.0/9-metadata#9.2">SE identifier</a>, without <code class="html">https://standardebooks.org/ebooks/</code>, and with forward slashes replaced by underscores.</p></li>
</ol>
<section id="9.9.3"><aside class="number">9.9.3</aside>
<h3>Book production notes</h3>
<ol type="1">
<li id="9.9.3.1"><aside class="number">9.9.3.1</aside><p>The <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:production-notes"</span><span class="p">&gt;</span></code> element contains any of the ebook producer’s production notes. For example, the producer might note that page scans were not available, so an editorial decision was made to add commas to sentences deemed to be transcription typos; or that certain archaic spellings were retained as a matter of prose style specific to this ebook.</p></li>
<li id="9.9.3.2"><aside class="number">9.9.3.2</aside><p>The <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:production-notes"</span><span class="p">&gt;</span></code> element is not present if there are no production notes.</p></li>
</ol>
</section>
<section id="9.9.4"><aside class="number">9.9.4</aside>
<h3>Readability metadata</h3>
<p>These two elements are automatically computed by the <code class="bash">se prepare-release</code> tool.</p>
<ol type="1">
<li id="9.9.4.1"><aside class="number">9.9.4.1</aside><p>The <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:word-count"</span><span class="p">&gt;</span></code> element contains an integer representing the ebooks total word count, excluding some SE files like the colophon and Uncopyright.</p></li>
<li id="9.9.4.2"><aside class="number">9.9.4.2</aside><p>The <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:reading-ease.flesch"</span><span class="p">&gt;</span></code> element contains a decimal representing the computed Flesch reading ease for the book.</p></li>
</ol>
</section>
</section>
<section id="9.10"><aside class="number">9.10</aside>
<h2>General contributor rules</h2>
<p>The following apply to all contributors, including the author(s), translator(s), and illustrator(s).</p>
<ol type="1">
<li id="9.10.1"><aside class="number">9.10.1</aside><p>If there is exactly one contributor in a set (for example, only one author, or only one translator) then the <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"display-seq"</span><span class="p">&gt;</span></code> element is omitted for that contributor.</p></li>
<li id="9.10.2"><aside class="number">9.10.2</aside><p>If there is more than one contributor in a set (for example, multiple authors, or translators) then the <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"display-seq"</span><span class="p">&gt;</span></code> element is specified for each contributor, with a value equal to their position in the SE identifier.</p></li>
<li id="9.10.3"><aside class="number">9.10.3</aside><p>The epub standard specifies that in a set of contributors, if at least one has the <code class="html">display-seq</code> attribute, then other contributors in the set without the <code class="html">display-seq</code> attribute are ignored. For SE purposes, this also means they will be excluded from the SE identifier.</p></li>
<li id="9.10.4"><aside class="number">9.10.4</aside><p>By SE convention, contributors with <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"display-seq"</span><span class="p">&gt;</span>0<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code> are excluded from the SE identifier.</p></li>
</ol>
</section>
<section id="9.11"><aside class="number">9.11</aside>
<h2>The author metadata block</h2>
<ol type="1">
<li id="9.11.1"><aside class="number">9.11.1</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">dc:creator</span> <span class="na">id</span><span class="o">=</span><span class="s">"author"</span><span class="p">&gt;</span></code> contains the author’s name as it appears on the cover.</p></li>
<li id="9.11.2"><aside class="number">9.11.2</aside><p>If there is more than one author, the first author’s <code class="html">id</code> is <code class="html">author-1</code>, the second <code class="html">author-2</code>, and so on.</p></li>
<li id="9.11.3"><aside class="number">9.11.3</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"file-as"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span><span class="p">&gt;</span></code> contains the author’s name as filed alphabetically. This element is included even if it’s identical to <code class="html"><span class="p">&lt;</span><span class="nt">dc:creator</span><span class="p">&gt;</span></code>.</p></li>
<li id="9.11.4"><aside class="number">9.11.4</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:name.person.full-name"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span><span class="p">&gt;</span></code> contains the author’s full name, with any initials or middle names expanded, and including any titles. This element is not included if the value is identical to <code class="html"><span class="p">&lt;</span><span class="nt">dc:creator</span><span class="p">&gt;</span></code>.</p></li>
<li id="9.11.5"><aside class="number">9.11.5</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"alternate-script"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span><span class="p">&gt;</span></code> contains the author’s name as it appears on the cover, but transliterated into their native alphabet if applicable. For example, Anton Chekhov’s name would be contained here in the Cyrillic alphabet. This element is not included if not applicable.</p></li>
<li id="9.11.6"><aside class="number">9.11.6</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:url.encyclopedia.wikipedia"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span><span class="p">&gt;</span></code> contains the URL of the author’s Wikipedia page. This element is not included if there is no Wikipedia page.</p></li>
<li id="9.11.7"><aside class="number">9.11.7</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:url.authority.nacoaf"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span><span class="p">&gt;</span></code> contains the URL of the author’s <a href="http://id.loc.gov/authorities/names.html">Library of Congress Names Database</a> page. It does not include the <code class="path">.html</code> file extension. This element is not included if there is no LoC Names database entry.
					<aside class="tip">
<p>This is easily found by visiting the person’s Wikipedia page and looking at the very bottom in the “Authority Control” section, under “LCCN.”</p>
<p>If you it’s not on Wikipedia, find it directly by visiting the <a href="http://id.loc.gov/authorities/names.html">Library of Congress Names Database</a>.</p>
</aside>
</p></li>
<li id="9.11.8"><aside class="number">9.11.8</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"role"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span> <span class="na">scheme</span><span class="o">=</span><span class="s">"marc:relators"</span><span class="p">&gt;</span></code> contains the <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a> for the roles the author played in creating this book.
					</p><p>There is always one element with the value of <code class="html">aut</code>. There may be additional elements for additional values, if applicable. For example, if the author also illustrated the book, there would be an additional <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"role"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span> <span class="na">scheme</span><span class="o">=</span><span class="s">"marc:relators"</span><span class="p">&gt;</span>ill<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code> element.</p>
</li>
</ol>
<p>This example shows a complete author metadata block for <i><a href="/ebooks/anton-chekhov/short-fiction/constance-garnett">Short Fiction</a></i>, by Anton Chekhov:</p>
<figure><code class="html full"><span class="p">&lt;</span><span class="nt">dc:creator</span> <span class="na">id</span><span class="o">=</span><span class="s">"author"</span><span class="p">&gt;</span>Anton Chekhov<span class="p">&lt;/</span><span class="nt">dc:creator</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"file-as"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span><span class="p">&gt;</span>Chekhov, Anton<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:name.person.full-name"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span><span class="p">&gt;</span>Anton Pavlovich Chekhov<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"alternate-script"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span><span class="p">&gt;</span>Анто́н Па́влович Че́хов<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:url.encyclopedia.wikipedia"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span><span class="p">&gt;</span>https://en.wikipedia.org/wiki/Anton_Chekhov<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:url.authority.nacoaf"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span><span class="p">&gt;</span>http://id.loc.gov/authorities/names/n79130807<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"role"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span> <span class="na">scheme</span><span class="o">=</span><span class="s">"marc:relators"</span><span class="p">&gt;</span>aut<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code></figure>
</section>
<section id="9.12"><aside class="number">9.12</aside>
<h2>The translator metadata block</h2>
<ol type="1">
<li id="9.12.1"><aside class="number">9.12.1</aside><p>If the work is translated, the <code class="html"><span class="p">&lt;</span><span class="nt">dc:contributor</span> <span class="na">id</span><span class="o">=</span><span class="s">"translator"</span><span class="p">&gt;</span></code> metadata block follows the author metadata block.</p></li>
<li id="9.12.2"><aside class="number">9.12.2</aside><p>If there is more than one translator, then the first translator’s <code class="html">id</code> is <code class="html">translator-1</code>, the second <code class="html">translator-2</code>, and so on.</p></li>
<li id="9.12.3"><aside class="number">9.12.3</aside><p>Each block is identical to the author metadata block, but with <code class="html"><span class="p">&lt;</span><span class="nt">dc:contributor</span> <span class="na">id</span><span class="o">=</span><span class="s">"translator"</span><span class="p">&gt;</span></code> instead of <code class="html"><span class="p">&lt;</span><span class="nt">dc:creator</span> <span class="na">id</span><span class="o">=</span><span class="s">"author"</span><span class="p">&gt;</span></code>.</p></li>
<li id="9.12.4"><aside class="number">9.12.4</aside><p>The <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a> is <code class="html">trl</code>: <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"role"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#translator"</span> <span class="na">scheme</span><span class="o">=</span><span class="s">"marc:relators"</span><span class="p">&gt;</span>trl<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code>.</p></li>
<li id="9.12.5"><aside class="number">9.12.5</aside><p>Translators often annotate the work; if this is the case, the additional <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a> <code class="html">ann</code> is included in a separate <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"role"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#translator"</span> <span class="na">scheme</span><span class="o">=</span><span class="s">"marc:relators"</span><span class="p">&gt;</span></code> element.</p></li>
</ol>
</section>
<section id="9.13"><aside class="number">9.13</aside>
<h2>The illustrator metadata block</h2>
<ol type="1">
<li id="9.13.1"><aside class="number">9.13.1</aside><p>If the work is illustrated by a person who is not the author, the illustrator metadata block follows.</p></li>
<li id="9.13.2"><aside class="number">9.13.2</aside><p>If there is more than one illustrator, the first illustrator’s <code class="html">id</code> is <code class="html">illustrator-1</code>, the second <code class="html">illustrator-2</code>, and so on.</p></li>
<li id="9.13.3"><aside class="number">9.13.3</aside><p>Each block is identical to the author metadata block, but with <code class="html"><span class="p">&lt;</span><span class="nt">dc:contributor</span> <span class="na">id</span><span class="o">=</span><span class="s">"illustrator"</span><span class="p">&gt;</span></code> instead of <code class="html"><span class="p">&lt;</span><span class="nt">dc:creator</span> <span class="na">id</span><span class="o">=</span><span class="s">"author"</span><span class="p">&gt;</span></code>.</p></li>
<li id="9.13.4"><aside class="number">9.13.4</aside><p>The <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a> is <code class="html">ill</code>: <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"role"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#illustrator"</span> <span class="na">scheme</span><span class="o">=</span><span class="s">"marc:relators"</span><span class="p">&gt;</span>ill<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code>.</p></li>
</ol>
</section>
<section id="9.14"><aside class="number">9.14</aside>
<h2>The cover artist metadata block</h2>
<p>The “cover artist” is the artist who painted the art the producer selected for the Standard Ebook cover.</p>
<ol type="1">
<li id="9.14.1"><aside class="number">9.14.1</aside><p>The cover artist metadata block is identical to the author metadata block, but with <code class="html"><span class="p">&lt;</span><span class="nt">dc:contributor</span> <span class="na">id</span><span class="o">=</span><span class="s">"artist"</span><span class="p">&gt;</span></code> instead of <code class="html"><span class="p">&lt;</span><span class="nt">dc:creator</span> <span class="na">id</span><span class="o">=</span><span class="s">"author"</span><span class="p">&gt;</span></code>.</p></li>
<li id="9.14.2"><aside class="number">9.14.2</aside><p>The <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a> is <code class="html">art</code>: <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"role"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#artist"</span> <span class="na">scheme</span><span class="o">=</span><span class="s">"marc:relators"</span><span class="p">&gt;</span>art<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code>.</p></li>
</ol>
</section>
<section id="9.15"><aside class="number">9.15</aside>
<h2>Metadata for additional contributors</h2>
<p>Occasionally a book may have other contributors besides the author, translator, and illustrator; for example, a person who wrote a preface, an introduction, or who edited the work or added endnotes.</p>
<ol type="1">
<li id="9.15.1"><aside class="number">9.15.1</aside><p>Additional contributor blocks are identical to the author metadata block, but with <code class="html"><span class="p">&lt;</span><span class="nt">dc:contributor</span><span class="p">&gt;</span></code> instead of <code class="html"><span class="p">&lt;</span><span class="nt">dc:creator</span><span class="p">&gt;</span></code>.</p></li>
<li id="9.15.2"><aside class="number">9.15.2</aside><p>The <code class="html">id</code> attribute of the <code class="html"><span class="p">&lt;</span><span class="nt">dc:contributor</span><span class="p">&gt;</span></code> is the lowercase, URL-safe, fully-spelled out version of the <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a>. For example, if the MARC relator tag is <code class="html">wpr</code>, the <code class="html">id</code> attribute would be <code class="html">writer-of-preface</code>.</p></li>
<li id="9.15.3"><aside class="number">9.15.3</aside><p>The <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a> is one that is appropriate for the role of the additional contributor. Common roles for ebooks are: <code class="html">wpr</code>, <code class="html">ann</code>, and <code class="html">aui</code>.</p></li>
</ol>
</section>
<section id="9.16"><aside class="number">9.16</aside>
<h2>Transcriber metadata</h2>
<ol type="1">
<li id="9.16.1"><aside class="number">9.16.1</aside><p>If the ebook is based on a transcription by someone else, like Project Gutenberg, then transcriber blocks follow the general contributor metadata blocks.</p></li>
<li id="9.16.2"><aside class="number">9.16.2</aside><p>If the transcriber is anonymous, the value for the producer’s <code class="html"><span class="p">&lt;</span><span class="nt">dc:contributor</span><span class="p">&gt;</span></code> element is <code class="html">An Anonymous Volunteer</code>.</p></li>
<li id="9.16.3"><aside class="number">9.16.3</aside><p>If there is more than one transcriber, the first transcriber is <code class="html">transcriber-1</code>, the second <code class="html">transcriber-2</code>, and so on.</p></li>
<li id="9.16.4"><aside class="number">9.16.4</aside><p>The <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"file-as"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#transcriber-1"</span><span class="p">&gt;</span></code> element contains an alpha-sorted representation of the transcriber’s name.</p></li>
<li id="9.16.5"><aside class="number">9.16.5</aside><p>The <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a> is <code class="html">trc</code>: <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"role"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#transcriber-1"</span> <span class="na">scheme</span><span class="o">=</span><span class="s">"marc:relators"</span><span class="p">&gt;</span>trc<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code>.</p></li>
<li id="9.16.6"><aside class="number">9.16.6</aside><p>If the transcriber’s personal homepage is known, the element <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:url.homepage"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#transcriber-1"</span><span class="p">&gt;</span></code> is included, whose value is the URL of the transcriber’s homepage. The URL must link to a personal homepage only; no products, services, or other endorsements, commercial or otherwise.</p></li>
</ol>
</section>
<section id="9.17"><aside class="number">9.17</aside>
<h2>Producer metadata</h2>
<p>These elements describe the SE producer who produced the ebook for the Standard Ebooks project.</p>
<ol type="1">
<li id="9.17.1"><aside class="number">9.17.1</aside><p>If there is more than one producer, the first producer is <code class="html">producer-1</code>, the second <code class="html">producere-2</code>, and so on.</p></li>
<li id="9.17.2"><aside class="number">9.17.2</aside><p>The producer metadata block is identical to the author metadata block, but with <code class="html"><span class="p">&lt;</span><span class="nt">dc:contributor</span> <span class="na">id</span><span class="o">=</span><span class="s">"producer-1"</span><span class="p">&gt;</span></code> instead of <code class="html"><span class="p">&lt;</span><span class="nt">dc:creator</span> <span class="na">id</span><span class="o">=</span><span class="s">"author"</span><span class="p">&gt;</span></code>.</p></li>
<li id="9.17.3"><aside class="number">9.17.3</aside><p>If a producer is anonymous, the value for the producer’s <code class="html"><span class="p">&lt;</span><span class="nt">dc:contributor</span><span class="p">&gt;</span></code> element is <code class="html">Anonymous</code>.</p></li>
<li id="9.17.4"><aside class="number">9.17.4</aside><p>If the producer’s personal homepage is known, the element <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:url.homepage"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#producer-1"</span><span class="p">&gt;</span></code> is included, whose value is the URL of the transcriber’s homepage. The URL must link to a personal homepage only; no products, services, or other endorsements, commercial or otherwise.</p></li>
<li id="9.17.5"><aside class="number">9.17.5</aside><p>The <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tags</a> for the SE producer usually include all of the following:
					</p><ul>
<li><p><code class="html">bkp</code>: The producer produced the ebook.</p></li>
<li><p><code class="html">blw</code>: The producer wrote the blurb (the long description).</p></li>
<li><p><code class="html">cov</code>: The producer selected the cover art.</p></li>
<li><p><code class="html">mrk</code>: The producer wrote the HTML markup for the ebook.</p></li>
<li><p><code class="html">pfr</code>: The producer proofread the ebook.</p></li>
<li><p><code class="html">tyg</code>: The producer reviewed the typography of the ebook.</p></li>
</ul>
</li>
</ol>
</section>
<section id="9.18"><aside class="number">9.18</aside>
<h2>The ebook manifest</h2>
<p>The <code class="html"><span class="p">&lt;</span><span class="nt">manifest</span><span class="p">&gt;</span></code> element is a required part of the epub spec that defines a list of files within the ebook.</p>
<aside class="tip">The <code class="bash">se print-manifest-and-spine</code> tool generates a complete manifest that can be copied-and-pasted into the ebook’s metadata file.</aside>
<ol type="1">
<li id="9.18.1"><aside class="number">9.18.1</aside><p>The manifest is in alphabetical order.</p></li>
<li id="9.18.2"><aside class="number">9.18.2</aside><p>The <code class="html">id</code> attribute is the basename of the <code class="html">href</code> attribute.</p></li>
<li id="9.18.3"><aside class="number">9.18.3</aside><p>Files which contain SVG images have the additional <code class="html">properties="svg"</code> property in their manifest item.</p></li>
<li id="9.18.4"><aside class="number">9.18.4</aside><p>The manifest item for the table of contents file has the additional <code class="html">properties="nav"</code> property.</p></li>
<li id="9.18.5"><aside class="number">9.18.5</aside><p>The manifest item for the cover image has the additional <code class="html">properties="cover-image"</code> property.</p></li>
</ol>
</section>
<section id="9.19"><aside class="number">9.19</aside>
<h2>The ebook spine</h2>
<p>The <code class="html"><span class="p">&lt;</span><span class="nt">spine</span><span class="p">&gt;</span></code> element is a required part of the epub spec that defines the reading order of the files in the ebook.</p>
<aside class="tip">The <code class="bash">se print-manifest-and-spine</code> tool generates a draft of the spine by making some educated guesses as to the reading order. The tool’s output is never 100% correct; manual review of the output is required, and adjustments may be necessary to correct the reading order.</aside>
</section>
</section>
		</article>
	</main>
<?= Template::Footer() ?>
