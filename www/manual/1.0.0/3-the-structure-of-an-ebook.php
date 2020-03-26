<?
require_once('Core.php');
?><?= Template::Header(['title' => '3. The Structure of an Ebook - The Standard Ebooks Manual', 'highlight' => 'contribute', 'manual' => true]) ?>
	<main class="manual"><nav><p><a href="/manual/1.0.0">The Standard Ebooks Manual of Style</a></p><ol><li><p><a href="/manual/1.0.0/1-code-style">1. XHTML, CSS, and SVG Code Style</a></p><ol><li><p><a href="/manual/1.0.0/1-code-style#1.1">1.1 XHTML formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.2">1.2 CSS formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.3">1.3 SVG Formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.4">1.4 Commits and Commit Messages</a></p></li></ol></li><li><p><a href="/manual/1.0.0/2-filesystem">2. Filesystem Layout and File Naming Conventions</a></p><ol><li><p><a href="/manual/1.0.0/2-filesystem#2.1">2.1 File locations</a></p></li><li><p><a href="/manual/1.0.0/2-filesystem#2.2">2.2 XHTML file naming conventions</a></p></li><li><p><a href="/manual/1.0.0/2-filesystem#2.3">2.3 The se-lint-ignore.xml file</a></p></li></ol></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook">3. The Structure of an Ebook</a></p><ol><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.1">3.1 Front matter</a></p></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.2">3.2 Body matter</a></p></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.3">3.3 Back matter</a></p></li></ol></li><li><p><a href="/manual/1.0.0/4-semantics">4. Semantics</a></p><ol><li><p><a href="/manual/1.0.0/4-semantics#4.1">4.1 Semantic Tags</a></p></li><li><p><a href="/manual/1.0.0/4-semantics#4.2">4.2 Semantic Inflection</a></p></li></ol></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns">5. General XHTML Patterns</a></p><ol><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.1">5.1 id attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.2">5.2 class attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.3">5.3 xml:lang attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.4">5.4 The &lt;title&gt; element</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.5">5.5 Ordered/numbered and unordered lists</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.6">5.6 Tables</a></p></li></ol></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns">6. Standard Ebooks Section Patterns</a></p><ol><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.1">6.1 The title string</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.2">6.2 The table of contents</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.3">6.3 The titlepage</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.4">6.4 The imprint</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.5">6.5 The half title page</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.6">6.6 The colophon</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.7">6.7 The Uncopyright</a></p></li></ol></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns">7. High Level Structural Patterns</a></p><ol><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.1">7.1 Sectioning</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.2">7.2 Headers</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.3">7.3 Dedications</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.4">7.4 Epigraphs</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.5">7.5 Poetry, verse, and songs</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.6">7.6 Plays and drama</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.7">7.7 Letters</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.8">7.8 Images</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.9">7.9 List of Illustrations (the LoI)</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.10">7.10 Endnotes</a></p></li></ol></li><li><p><a href="/manual/1.0.0/8-typography">8. Typography</a></p><ol><li><p><a href="/manual/1.0.0/8-typography#8.1">8.1 Section titles and ordinals</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.2">8.2 Italics</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.3">8.3 Capitalization</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.4">8.4 Indentation</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.5">8.5 Chapter headers</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.6">8.6 Ligatures</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.7">8.7 Punctuation and spacing</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.8">8.8 Numbers, measurements, and math</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.9">8.9 Latinisms</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.10">8.10 Initials and abbreviations</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.11">8.11 Times</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.12">8.12 Chemicals and compounds</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.13">8.13 Temperatures</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.14">8.14 Scansion</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.15">8.15 Legal cases and terms</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.16">8.16 Morse code</a></p></li></ol></li><li><p><a href="/manual/1.0.0/9-metadata">9. Metadata</a></p><ol><li><p><a href="/manual/1.0.0/9-metadata#9.1">9.1 General URL rules</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.2">9.2 The ebook identifier</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.3">9.3 Publication date and release identifiers</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.4">9.4 Book titles</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.5">9.5 Book subjects</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.6">9.6 Book descriptions</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.7">9.7 Book language</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.8">9.8 Book transcription and page scan sources</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.9">9.9 Additional book metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.10">9.10 General contributor rules</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.11">9.11 The author metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.12">9.12 The translator metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.13">9.13 The illustrator metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.14">9.14 The cover artist metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.15">9.15 Metadata for additional contributors</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.16">9.16 Transcriber metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.17">9.17 Producer metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.18">9.18 The ebook manifest</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.19">9.19 The ebook spine</a></p></li></ol></li><li><p><a href="/manual/1.0.0/10-art-and-images">10. Art and Images</a></p><ol><li><p><a href="/manual/1.0.0/10-art-and-images#10.1">10.1 Complete list of files</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.2">10.2 SVG patterns</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.3">10.3 The cover image</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.4">10.4 The titlepage image</a></p></li></ol></li></ol></nav>
		<article>

<section id="3"><aside class="number">3</aside>
<h1>The Structure of an Ebook</h1>
<p>Books consist of three major partitions: Front Matter, Body Matter, and Back Matter.</p>
<p>The list below is ordered in the manner in which the items appear in a typical book. Some books may make exceptions to this ordering.</p>
<p>These terms become important when building the Table of Contents (ToC). The Landmarks section of the ToC requires items to be labeled with the appropriate partition identifier. See <a href="/manual/1.0.0/7-table-of-contents-patterns">ToC Patterns</a> for more information about the ToC.</p>
<section id="3.1"><aside class="number">3.1</aside>
<h2>Front matter</h2>
<p>Front matter is material that appears before the main content of the work. It includes such items as a dedication, an epigraph, an introduction, and so on.</p>
<section id="3.1.1"><aside class="number">3.1.1</aside>
<h3>Cover</h3>
<p>An image presenting the outer appearance of the book, usually consisting of an image, the title of the book and the author’s name. For Standard Ebooks productions, the cover is an SVG image generated from template that combines the book title and author, and a background image. The <code class="bash"><span>se</span> build-images</code> tool generates the cover image used for distribution.</p>
</section>
<section id="3.1.2"><aside class="number">3.1.2</aside>
<h3>Title page</h3>
<p>A page listing the title of the book and the author’s name. For Standard Ebooks productions, the title page contains an SVG image generated by the <code class="bash"><span>se</span> create-draft</code> tool, which is then compiled for distribution using the <code class="bash"><span>se</span> build-images</code> tool.</p>
</section>
<section id="3.1.3"><aside class="number">3.1.3</aside>
<h3>Imprint</h3>
<p>A page containing information about the publisher of the book. For Standard Ebooks productions, a template file is provided and the producer modifies it to suit the particular ebook.</p>
</section>
<section id="3.1.4"><aside class="number">3.1.4</aside>
<h3>Dedication</h3>
<p>An inscription at the start of a work, usually a tribute to some person or persons whom the author wishes to honor.</p>
</section>
<section id="3.1.5"><aside class="number">3.1.5</aside>
<h3>Epigraph</h3>
<p>A quotation or poem at the start of a book which may set the mood or inspire thoughts about the work to come.</p>
<p>If the epigraph is a poem or quotation from poetry, it must follow the standards for verse described in <a href="/manual/1.0.0/6-high-level-structural-patterns">High-Level Structural Patterns</a>.</p>
</section>
<section id="3.1.6"><aside class="number">3.1.6</aside>
<h3>Acknowledgements</h3>
<p>A list of persons or organizations whom the author wishes to thank, generally for helping with the creation of the book. The acknowledgements can also be part of the back matter of the book, depending on where the author placed them.</p>
</section>
<section id="3.1.7"><aside class="number">3.1.7</aside>
<h3>Foreword</h3>
<p>A preliminary section containing information about the book, generally written by someone other than the author.</p>
</section>
<section id="3.1.8"><aside class="number">3.1.8</aside>
<h3>Preface</h3>
<p>A preliminary section which states the subject of the book and its aims, generally written by the author of the work.</p>
</section>
<section id="3.1.9"><aside class="number">3.1.9</aside>
<h3>Introduction</h3>
<p>An introduction is typically found in non-fiction works. It is written by the book’s author and sets out the book’s main argument.</p>
</section>
<section id="3.1.10"><aside class="number">3.1.10</aside>
<h3>Half title</h3>
<p>In books which include front matter, the half title page marks the start of the body matter.</p>
<ol type="1">
<li id="3.1.10.1"><aside class="number">3.1.10.1</aside><p>The half title lists the title of the book, but not the author.</p></li>
<li id="3.1.10.2"><aside class="number">3.1.10.2</aside><p>A half title is required if there is any front matter in the book.</p></li>
</ol>
</section>
<section id="3.1.11"><aside class="number">3.1.11</aside>
<h3>Table of Contents</h3>
<p>Also known as the “ToC.” The Table of Contents lists the main headings in the book. In traditionally printed books, the table of contents is part of the front matter of the book.</p>
<ol type="1">
<li id="3.1.11.1"><aside class="number">3.1.11.1</aside><p>In Standard Ebooks productions, the table of contents is omitted from the ebook’s spine and is instead presented to the reader via their ereader’s ToC feature.</p></li>
</ol>
</section>
</section>
<section id="3.2"><aside class="number">3.2</aside>
<h2>Body matter</h2>
<p>The body matter is the main content of the book. It is typically divided into chapters, or in the case of a collection, individual stories, poems, or articles. It may be structured at the highest level into larger divisions such as volumes or parts. Besides the contents of the book itself, it may also include:</p>
<section id="3.2.1"><aside class="number">3.2.1</aside>
<h3>Prologue</h3>
<p>A prologue is generally found only in works of fiction. It may introduce characters, set up background information, or bring forward a critical part of the action to which the story leads.</p>
<p>A prologue is generally part of the body matter, unless the prologue is a fictional element of a frame narrative. In that case, it may be appropriate to place it in front of the half title and give it a different title, while keeping the <code class="html">prologue</code> semantic inflection.</p>
</section>
<section id="3.2.2"><aside class="number">3.2.2</aside>
<h3>Epilogue</h3>
<p>An epilogue is generally found only in works of fiction. It typically winds up the action or briefly tells the subsequent history of major characters. An epilogue should therefore have similar structure to the chapters of a book.</p>
</section>
</section>
<section id="3.3"><aside class="number">3.3</aside>
<h2>Back matter</h2>
<p>Back matter is material which follows the main content, but could be separated from the main content. It might include endnotes, an appendix, an afterword, a colophon, and so on.</p>
<section id="3.3.1"><aside class="number">3.3.1</aside>
<h3>Afterword</h3>
<p>A concluding section of a book, typically but not necessarily written by the author, which stands outside the main story of a work of fiction, or the main argument of a work of non-fiction. It may add additional information or comment on the book and its production.</p>
</section>
<section id="3.3.2"><aside class="number">3.3.2</aside>
<h3>List of Illustrations</h3>
<p>Also known as the “LoI,” the list of illustrations is an index to the illustrations in a book. The items are included as part of a list and linked to the points in the text where the illustration appears.</p>
</section>
<section id="3.3.3"><aside class="number">3.3.3</aside>
<h3>Endnotes</h3>
<p>A list of notes to the text. Each item is given a unique sequential number and linked to the point in the text to which the note refers. If the text originally has footnotes, they are converted to endntoes.</p>
</section>
<section id="3.3.4"><aside class="number">3.3.4</aside>
<h3>Colophon</h3>
<p>The colophon contains information about the publisher of the book, the author, the original publication date, the edition, its publication date, the cover artist and other information relevant to the particular release of a book. A Standard Ebooks colophon is standardized and follows a common pattern.</p>
</section>
<section id="3.3.5"><aside class="number">3.3.5</aside>
<h3>Copyright Page</h3>
<p>The copyright page includes information about the copyright status of the work. All Standard Ebooks are in the US Public domain, and use a standardized “copyright” page to explain this.</p>
<p>Copyright pages are usually part of the front matter of a book, but in the case of Standard Ebooks productions they are back matter, and the last item in the book.</p>
</section>
</section>
</section>
		</article>
	</main>
<?= Template::Footer() ?>
