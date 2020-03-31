<?
require_once('Core.php');
?><?= Template::Header(['title' => '10. Art and Images - The Standard Ebooks Manual', 'highlight' => 'contribute', 'manual' => true]) ?>
	<main class="manual"><nav><p><a href="/manual/1.0.0">The Standard Ebooks Manual of Style</a></p><ol><li><p><a href="/manual/1.0.0/1-code-style">1. XHTML, CSS, and SVG Code Style</a></p><ol><li><p><a href="/manual/1.0.0/1-code-style#1.1">1.1 XHTML formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.2">1.2 CSS formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.3">1.3 SVG Formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.4">1.4 Commits and Commit Messages</a></p></li></ol></li><li><p><a href="/manual/1.0.0/2-filesystem">2. Filesystem Layout and File Naming Conventions</a></p><ol><li><p><a href="/manual/1.0.0/2-filesystem#2.1">2.1 File locations</a></p></li><li><p><a href="/manual/1.0.0/2-filesystem#2.2">2.2 XHTML file naming conventions</a></p></li><li><p><a href="/manual/1.0.0/2-filesystem#2.3">2.3 The se-lint-ignore.xml file</a></p></li></ol></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook">3. The Structure of an Ebook</a></p><ol><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.1">3.1 Front matter</a></p></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.2">3.2 Body matter</a></p></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.3">3.3 Back matter</a></p></li></ol></li><li><p><a href="/manual/1.0.0/4-semantics">4. Semantics</a></p><ol><li><p><a href="/manual/1.0.0/4-semantics#4.1">4.1 Semantic Elements</a></p></li><li><p><a href="/manual/1.0.0/4-semantics#4.2">4.2 Semantic Inflection</a></p></li></ol></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns">5. General XHTML Patterns</a></p><ol><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.1">5.1 id attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.2">5.2 class attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.3">5.3 xml:lang attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.4">5.4 The &lt;title&gt; element</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.5">5.5 Ordered/numbered and unordered lists</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.6">5.6 Tables</a></p></li></ol></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns">6. Standard Ebooks Section Patterns</a></p><ol><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.1">6.1 The title string</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.2">6.2 The table of contents</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.3">6.3 The titlepage</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.4">6.4 The imprint</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.5">6.5 The half title page</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.6">6.6 The colophon</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.7">6.7 The Uncopyright</a></p></li></ol></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns">7. High Level Structural Patterns</a></p><ol><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.1">7.1 Sectioning</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.2">7.2 Headers</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.3">7.3 Dedications</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.4">7.4 Epigraphs</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.5">7.5 Poetry, verse, and songs</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.6">7.6 Plays and drama</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.7">7.7 Letters</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.8">7.8 Images</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.9">7.9 List of Illustrations (the LoI)</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.10">7.10 Endnotes</a></p></li></ol></li><li><p><a href="/manual/1.0.0/8-typography">8. Typography</a></p><ol><li><p><a href="/manual/1.0.0/8-typography#8.1">8.1 Section titles and ordinals</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.2">8.2 Italics</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.3">8.3 Capitalization</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.4">8.4 Indentation</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.5">8.5 Chapter headers</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.6">8.6 Ligatures</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.7">8.7 Punctuation and spacing</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.8">8.8 Numbers, measurements, and math</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.9">8.9 Latinisms</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.10">8.10 Initials and abbreviations</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.11">8.11 Times</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.12">8.12 Chemicals and compounds</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.13">8.13 Temperatures</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.14">8.14 Scansion</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.15">8.15 Legal cases and terms</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.16">8.16 Morse code</a></p></li></ol></li><li><p><a href="/manual/1.0.0/9-metadata">9. Metadata</a></p><ol><li><p><a href="/manual/1.0.0/9-metadata#9.1">9.1 General URL rules</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.2">9.2 The ebook identifier</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.3">9.3 Publication date and release identifiers</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.4">9.4 Book titles</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.5">9.5 Book subjects</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.6">9.6 Book descriptions</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.7">9.7 Book language</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.8">9.8 Book transcription and page scan sources</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.9">9.9 Additional book metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.10">9.10 General contributor rules</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.11">9.11 The author metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.12">9.12 The translator metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.13">9.13 The illustrator metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.14">9.14 The cover artist metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.15">9.15 Metadata for additional contributors</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.16">9.16 Transcriber metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.17">9.17 Producer metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.18">9.18 The ebook manifest</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.19">9.19 The ebook spine</a></p></li></ol></li><li><p><a href="/manual/1.0.0/10-art-and-images">10. Art and Images</a></p><ol><li><p><a href="/manual/1.0.0/10-art-and-images#10.1">10.1 Complete list of files</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.2">10.2 SVG patterns</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.3">10.3 The cover image</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.4">10.4 The titlepage image</a></p></li></ol></li></ol></nav>
		<article>

<section id="10"><aside class="number"><a href="#10">10</a></aside>
<h1>Art and Images</h1>
<p>When you create a new Standard Ebooks draft using the <code class="bash"><b>se</b> create-draft</code> tool, you’ll already have templates for the cover and titlepage images present in <code class="path">./images/</code>.</p>
<p>Text in these SVG files is represented as text, not paths, so you can edit them using a text editor and not an SVG editor. Then, the <code class="bash"><b>se</b> build-images</code> tool converts these text-based source images into path-based compiled images, for distribution in the final epub file. We do this so to avoid having to distribute the font files along with the epub.</p>
<p>To develop cover and titlepage images, you must have the free <a href="https://github.com/theleagueof/league-spartan">League Spartan</a> and <a href="https://github.com/theleagueof/sorts-mill-goudy">Sorts Mill Goudy</a> fonts installed on your system.</p>
<section id="10.1"><aside class="number"><a href="#10.1">10.1</a></aside>
<h2>Complete list of files</h2>
<p>A complete set of image source files consists of:</p>
<ul>
<li><p><code class="path">./images/cover.source.jpg</code>: The full source image used for the cover art, in as high a resolution as possible. Can be of any image format, but typically we end up with JPGs.</p></li>
<li><p><code class="path">./images/cover.jpg</code>: A cropped part of the source image that will serve as the actual image file we use in the cover. Must be exactly 1400w × 2100h.</p></li>
<li><p><code class="path">./images/cover.svg</code>: The SVG source file for the cover, with any text represented as actual, editable text. Must be exactly 1400w × 2100h pixels. Since the final cover image SVG has the text converted to paths, we keep this file around to make it easier to make changes to the cover in the future.</p></li>
<li><p><code class="path">./src/epub/images/cover.svg</code>: The final SVG cover image. This image should be exactly like <code class="path">./images/cover.svg</code>, but with the text converted to paths.
					</p><p>This image is generated by the <code class="bash"><b>se</b> build-images</code> tool.</p>
</li>
<li><p><code class="path">./images/titlepage.svg</code>: The SVG source file for the titlepage, with any text represented as actual, editable text. Must be exactly 1400 pixels wide, but the height must exactly match the text height plus some padding (described below).</p></li>
<li><p><code class="path">./src/epub/images/titlepage.svg</code>: The final SVG titlepage image, with text converted to paths just like the cover page.
					</p><p>This image is generated by the <code class="bash"><b>se</b> build-images</code> tool.</p>
</li>
</ul>
</section>
<section id="10.2"><aside class="number"><a href="#10.2">10.2</a></aside>
<h2>SVG patterns</h2>
<ol type="1">
<li id="10.2.1"><aside class="number"><a href="#10.2.1">10.2.1</a></aside><p>SVGs are only sized with <code class="html"><span class="na">viewBox</span></code>, not <code class="html"><span class="na">height</span></code> or <code class="html"><span class="na">width</span></code>.
					</p><ol type="1">
<li id="10.2.1.1"><aside class="number"><a href="#10.2.1.1">10.2.1.1</a></aside><p>The <code class="html"><span class="na">viewBox</span></code> attribute consists of whole numbers, without fractions.</p></li>
</ol>
<aside class="tip">The <code class="html"><span class="na">viewBox</span></code> attribute is case-sensitive!</aside>
</li>
<li id="10.2.2"><aside class="number"><a href="#10.2.2">10.2.2</a></aside><p>The only attributes on the <code class="html"><span class="p">&lt;</span><span class="nt">svg</span><span class="p">&gt;</span></code> root element are: <code class="html"><span class="na">xmlns</span></code>, <code class="html"><span class="na">version</span></code>, and <code class="html"><span class="na">viewBox</span></code>.</p></li>
<li id="10.2.3"><aside class="number"><a href="#10.2.3">10.2.3</a></aside><p>The contents of the SVG’s <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element matches the <code class="html"><span class="na">alt</span></code> attribute of its <code class="html"><span class="p">&lt;</span><span class="nt">img</span><span class="p">&gt;</span></code> element in the text.</p></li>
<li id="10.2.4"><aside class="number"><a href="#10.2.4">10.2.4</a></aside><p>Grouping with <code class="html"><span class="p">&lt;</span><span class="nt">g</span><span class="p">&gt;</span></code> is avoided, unless it makes semantic sense. Groups whose sole purpose is to apply transforms should have those transforms applied to the children, and the group removed.</p></li>
<li id="10.2.5"><aside class="number"><a href="#10.2.5">10.2.5</a></aside><p>The use of fill color is avoided unless strictly necessary. Not defining a fill color allows for night mode compatibility.</p></li>
<li id="10.2.6"><aside class="number"><a href="#10.2.6">10.2.6</a></aside><p>The <code class="html"><span class="na">transform</span></code> attribute is illegal; transforms are applied to their elements directly.</p></li>
</ol>
</section>
<section id="10.3"><aside class="number"><a href="#10.3">10.3</a></aside>
<h2>The cover image</h2>
<aside class="warning">
<p>The SE Editor-in-Chief must review and approve of the cover art you select before you can commit it to your repository.</p>
<p><strong>Do not commit cover art without contacting the mailing list first!</strong></p>
</aside>
<p>The cover image is auto-generated by the <code class="bash"><b>se</b> create-draft</code> tool. The arrangement of the text is a suggestion, and may be changed by the producer in case a more visually-pleasing arrangment is desired.</p>
<p>After completing <code class="path">./images/cover.svg</code>, use the <code class="bash"><b>se</b> build-images</code> tool to build the rasterized distribution SVG in <code class="path">./src/epub/images/cover.svg</code>.</p>
<ol type="1">
<li id="10.3.1"><aside class="number"><a href="#10.3.1">10.3.1</a></aside><p>The <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element has a value of <code class="string">The cover for the Standard Ebooks edition of</code> followed by the <a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.1">title string</a>.</p></li>
</ol>
<section id="10.3.2"><aside class="number"><a href="#10.3.2">10.3.2</a></aside>
<h3>Cover image layout</h3>
<p><code class="bash"><b>se</b> create-draft</code> generates <code class="path">./images/cover.svg</code> for you with correct dimensions and layout. It’s rarely necessary to edit the cover.</p>
<ol type="1">
<li id="10.3.2.1"><aside class="number"><a href="#10.3.2.1">10.3.2.1</a></aside><p>Both the title and author are in League Spartan font with 5px letter spacing in ALL CAPS.</p></li>
<li id="10.3.2.2"><aside class="number"><a href="#10.3.2.2">10.3.2.2</a></aside><p>The left and right sides of the black title box have at least 40px padding. More padding is preferrable over cramming the title in.</p></li>
<li id="10.3.2.3"><aside class="number"><a href="#10.3.2.3">10.3.2.3</a></aside><p>Translators, illustrators, and other contributors besides the author do not appear on the cover.</p></li>
<li id="10.3.2.4"><aside class="number"><a href="#10.3.2.4">10.3.2.4</a></aside><p>The group of both the title and author lines is horizontally centered in the black title box.</p></li>
</ol>
<section id="10.3.2.5"><aside class="number"><a href="#10.3.2.5">10.3.2.5</a></aside>
<h4>Title line dimensions</h4>
<ol type="1">
<li id="10.3.2.5.1"><aside class="number"><a href="#10.3.2.5.1">10.3.2.5.1</a></aside><p>One-line titles: the line is 80px tall. Example: <i><a href="/ebooks/niccolo-machiavelli/the-prince/w-k-marriott">The Prince</a></i>, by Niccolò Machiavelli.</p></li>
<li id="10.3.2.5.2"><aside class="number"><a href="#10.3.2.5.2">10.3.2.5.2</a></aside><p>Two-line titles: each line is 80px tall, and the second title line is 20px below the first line. Example: <i><a href="/ebooks/fyodor-dostoevsky/crime-and-punishment/constance-garnett">Crime and Punishment</a></i>, by Fyodor Dostoevsky.</p></li>
<li id="10.3.2.5.3"><aside class="number"><a href="#10.3.2.5.3">10.3.2.5.3</a></aside><p>Two-line, very long titles: each line is 60px tall, and the second line is 20px below the first line. Example: <i><a href="/ebooks/selma-lagerlof/the-wonderful-adventures-of-nils/velma-swanston-howard">The Wonderful Adventures of Nils</a></i>, by Selma Lagerlöf.</p></li>
<li id="10.3.2.5.4"><aside class="number"><a href="#10.3.2.5.4">10.3.2.5.4</a></aside><p>Two-line, extremely long titles: each line is 50px tall, and the second line is 20px below the first line. Example: <i><a href="/ebooks/rudolph-erich-raspe/the-surprising-adventures-of-baron-munchausen">The Surprising Adventures of Baron Munchausen</a></i>, by Rudolph Erich Raspe.</p></li>
</ol>
</section>
<section id="10.3.2.6"><aside class="number"><a href="#10.3.2.6">10.3.2.6</a></aside>
<h4>Author line dimensions</h4>
<ol type="1">
<li id="10.3.2.6.1"><aside class="number"><a href="#10.3.2.6.1">10.3.2.6.1</a></aside><p>The first author line begins 60px below the last title line.</p></li>
<li id="10.3.2.6.2"><aside class="number"><a href="#10.3.2.6.2">10.3.2.6.2</a></aside><p>One-line authors: the line is 40px tall.</p></li>
<li id="10.3.2.6.3"><aside class="number"><a href="#10.3.2.6.3">10.3.2.6.3</a></aside><p>Two-line authors: each line is 40px tall, and the second author line is 20px below the first line.</p></li>
</ol>
</section>
</section>
<section id="10.3.3"><aside class="number"><a href="#10.3.3">10.3.3</a></aside>
<h3>Cover art</h3>
<ol type="1">
<li id="10.3.3.1"><aside class="number"><a href="#10.3.3.1">10.3.3.1</a></aside><p><code class="path">./images/cover.svg</code> links to <code class="path">./images/cover.jpg</code> as the canvas background.</p></li>
<li id="10.3.3.2"><aside class="number"><a href="#10.3.3.2">10.3.3.2</a></aside><p><code class="path">./images/cover.jpg</code> is 1400w × 2100h in pixels, and is compressed as much as possible while maintaining an acceptable image quality. An acceptable level of image quality is more important than file size.</p></li>
<li id="10.3.3.3"><aside class="number"><a href="#10.3.3.3">10.3.3.3</a></aside><p>Because <code class="path">./images/cover.jpg</code> is an image with large dimensions, it must be sourced from a high-resolution scan. It may not always be possible to locate a high-resolution scan, so a smaller source image may be upscaled <em>a small amount</em> to meet the target dimensions.</p></li>
<li id="10.3.3.4"><aside class="number"><a href="#10.3.3.4">10.3.3.4</a></aside><p>Cover art is in the “fine art oil painting” style, and in full color. Art not in this style, like modern CG art or black-and-white scans, is not acceptable.</p></li>
<li id="10.3.3.5"><aside class="number"><a href="#10.3.3.5">10.3.3.5</a></aside><p><code class="path">./images/cover.source.svg</code> is the unmodified source image used to create <code class="path">./images/cover.jpg</code>. This image is kept in case changes to the source images are to be made in the future.</p></li>
</ol>
<section id="10.3.3.6"><aside class="number"><a href="#10.3.3.6">10.3.3.6</a></aside>
<h4>US-PD clearance</h4>
<p>The paintings we use are all in the U.S. public domain (US-PD). Your task is to locate a painting suitable for the kind of book you’re producing, and then demonstrate that the painting is indeed in the U.S. public domain.</p>
<p>U.S. copyright law is complicated. Because of this, <strong>we require that you provide a link to a page scan of a 1924-or-older book that reproduces the painting you selected.</strong> <em>This is a hard requirement</em> to demonstrate that the painting you selected is in fact in the U.S. public domain. Just because a painting is very old, or Wikipedia says it’s PD, or it’s PD in a country besides the U.S., doesn’t necessarily mean it actually <em>is</em> PD in the U.S.</p>
<section id="10.3.3.6.1"><aside class="number"><a href="#10.3.3.6.1">10.3.3.6.1</a></aside>
<h5>Clearance procedure</h5>
<p>To actually demonstrate that a painting is PD, you must locate a reproduction of that painting in a 1924-or-older book.</p>
<p>This can be quite difficult. Many people find this to be the most time-consuming part of the ebook production process.</p>
<p>Because of the difficulty, finding suitable cover at is <em>all about compromise</em>. You’re unlikely to find the perfect cover image. You’ll find a lot of paintings that would be great matches, but that you can’t find reproductions of and thus we can’t use. So, be ready to compromise.</p>
<p>Note that in <code class="path">./images/cover.svg</code>, the black title and author box always goes in the lower half of the work. Thus, paintings in which some important detail would be obscured by the box cannot be used.</p>
<ul>
<li><p>Before you can go looking for a reproduction of a specific painting to prove its PD status, you have to find a suitable painting to begin with. <a href="https://www.wikiart.org/">Wikiart.org</a> is a great resource to search for paintings by keyword. Museum online collections are another good place to look for inspiration.
								</p><p>Once you find a potential candidate you can start researching its PD status.</p>
</li>
<li><p>Many museum online catalogs have a “bibliography” or “references” section for each painting in their collection. This is usually a list of books in which the painting was either mentioned or reproduced. This is a good shortcut to finding the names of books in which a painting was reproduced, and if you’re lucky, a search for the book title in Google Books will turn up scans.</p></li>
<li><p>Visit <a href="https://books.google.com/">Google Books</a>, <a href="https://www.hathitrust.org">HathiTrust</a>, and the <a href="https://archive.org">Internet Archive</a> to begin searching for books where your art is reproduced.
								</p><p>(Note that if your IP address is not in the U.S., many book archives like Google Books and HathiTrust may disable book previews.)</p>
<p>When searching for cover art, remember that artist names and painting titles may be spelled in many different ways. Often a painting went by multiple titles, or if the title was not in English, by many different translations. Your best bet is to simply search for an artist’s last name, and not the painting title.</p>
</li>
<li><p>Once you locate a book with reproductions, open the book up in thumbnail view and quickly eyeball the pages to see if the artwork is reproduced there.</p></li>
</ul>
<section id="10.3.3.6.1.1"><aside class="number"><a href="#10.3.3.6.1.1">10.3.3.6.1.1</a></aside>
<h6>Gotchas</h6>
<ul>
<li><p>In older books it was common to have <em>etchings</em> of paintings. Etchings are not strict reproductions, and so we cannot count them for PD clearance.
									</p><p>Additionally, it was common for painters to produce several different versions of the same artwork. These different versions are also not enough for PD clearance. The version you find in print must exactly match the scan you located online.</p>
<p>Before completing PD clearance, carefully compare the reproduction in the page scan with the high-resolution scan to ensure they are the same painting. Small details like the position of trees, clouds, reflections, or water are good ways to check if the painting is identical, or if you’re looking at a different version.</p>
</li>
<li><p>Sometimes the catalog record for a book has an incorrect publication year. Please verify the page scan of the copyright page to ensure the book is 1924 or older.</p></li>
</ul>
</section>
</section>
<section id="10.3.3.6.2"><aside class="number"><a href="#10.3.3.6.2">10.3.3.6.2</a></aside>
<h5>Resources for locating high resolution scans</h5>
<ul>
<li><p><a href="https://commons.wikimedia.org">Wikimedia Commons</a></p></li>
<li><p><a href="https://www.google.com/culturalinstitute/project/art-project">Google Art Project</a></p></li>
<li><p><a href="https://www.wikiart.org">WikiArt</a></p></li>
</ul>
</section>
<section id="10.3.3.6.3"><aside class="number"><a href="#10.3.3.6.3">10.3.3.6.3</a></aside>
<h5>Resources for locating print reproductions</h5>
<ul>
<li><p><a href="https://books.google.com">Google Books</a>
</p><p><a href="https://www.google.com/webhp?tbs=cdr:1,cd_min:,cd_max:1924&amp;amp;tbm=bks">Use this shortcut</a> to search for books that were published in 1924 or earlier.</p>
<p>Google Books is a convenient first stop because its thumbnail view is very fast, and it does a better job of highlighting search results than HathiTrust or Internet Archive.</p>
</li>
<li><p><a href="https://www.hathitrust.org">HathiTrust</a>
</p><p><a href="https://babel.hathitrust.org/cgi/ls?a=srchls&amp;amp;anyall1=all&amp;amp;q1=&amp;amp;field1=ocr&amp;amp;op3=AND&amp;amp;yop=before&amp;amp;pdate_end=1924">Use this shortcut</a> to search for books that were published in 1924 or earlier.</p>
</li>
<li><p><a href="https://archive.org">Internet Archive</a>
</p><p><a href="https://archive.org/search.php?query=+date%3A%5B1850-01-01+TO+1924-12-31%5D&amp;amp;sin=TXT&amp;amp;sort=-date">Use this shortcut</a> to search for books that were published in 1924 or earlier.</p>
</li>
<li><p>Our own <a href="/contribute/uncategorized-art-resources">list of uncategorized art books</a> may be helpful to browse through for inspiration and easy US-PD clearance.</p></li>
</ul>
</section>
<section id="10.3.3.6.4"><aside class="number"><a href="#10.3.3.6.4">10.3.3.6.4</a></aside>
<h5>Museums with CC0 collections</h5>
<p>Images that are explicitly marked as CC0 from these museums can be used without further research. Not all of their images are CC0; you must confirm the presence of a CC0 license on the specific image you want to use.</p>
<ul>
<li><p><a href="https://www.rijksmuseum.nl/en/search?q=&amp;f=1&amp;p=1&amp;ps=12&amp;type=painting&amp;imgonly=True&amp;st=Objects">Rijksmuseum</a> (Open the “Object Data” section and check they “Copyright” entry under the “Acquisition and right” section to confirm CC0)</p></li>
<li><p><a href="https://www.europeana.eu/portal/en/collections/art?f%5BDATA_PROVIDER%5D%5B%5D=Finnish+National+Gallery&amp;f%5BREUSABILITY%5D%5B%5D=open&amp;f%5BRIGHTS%5D%5B%5D=http%2A%3A%2F%2Fcreativecommons.org%2F%2Apublicdomain%2Fzero%2A&amp;per_page=96&amp;view=grid">Finnish National Gallery via Europeana</a> (Use the “View at” link under the “Find out more” header to confirm CC0 license at the museum’s site)</p></li>
<li><p><a href="https://www.metmuseum.org/art/collection/search#!/search?material=Paintings&amp;showOnly=withImage%7Copenaccess&amp;sortBy=Relevance&amp;sortOrder=asc&amp;perPage=20&amp;offset=0&amp;pageSize=0">Met Museum</a> (CC0 items have the CC0 logo under the image)</p></li>
<li><p><a href="https://www.nationalmuseum.se/en/samlingarna/fria-bilder">National Museum Sweden</a> (CC-PD items have the CC-PD mark in the lower left of the item’s detail view)</p></li>
<li><p><a href="https://collections.artsmia.org/">Minneapolis Institute of Art</a> (Public domain items are listed as such under “Rights”)</p></li>
<li><p><a href="https://art.thewalters.org/">The Walters Art Museum</a> (Public domain items are listed as "CC Creative Commons License" which links to a CC0 rights page)</p></li>
<li><p><a href="https://www.artic.edu/collection?q=test&amp;is_public_domain=1&amp;department_ids=European+Painting+and+Sculpture">Art Institute of Chicago</a> (CC0 items say CC0 in the lower left of the painting in the art detail page)</p></li>
<li><p><a href="http://www.clevelandart.org/art/collection/search?only-open-access=1&amp;filter-type=Painting">Cleveland Museum of Art</a> (CC0 items have the CC0 logo near the download button.)</p></li>
<li><p><a href="http://parismuseescollections.paris.fr/en/recherche/image-libre/true/avec-image/true/denominations/peinture-166168?mode=mosaique&amp;solrsort=ds_created%20desc">Paris Musées</a> (CC0 items have the CC0 logo near the download button.)</p></li>
<li><p><a href='https://www.si.edu/search/collection-images?edan_q=landscape&amp;edan_fq[0]=object_type%3A"Paintings"'>The Smithsonian</a> (CC0 items say CC0 under the Usage header in the item details.)</p></li>
<li><p><a href="http://dams.birminghammuseums.org.uk/asset-bank/action/viewDefaultHome">Birmingham Museums</a> (CC0 items say CC0 under the Usage Rights section in the item details.)</p></li>
</ul>
</section>
<section id="10.3.3.6.5"><aside class="number"><a href="#10.3.3.6.5">10.3.3.6.5</a></aside>
<h5>Clearance FAQ</h5>
<ul>
<li><p><strong>I found a great painting, and Wikipedia says it’s public domain, but I can’t find a reproduction in a book. Can I use it?</strong>
</p><p>No. You must find a reproduction of your selected painting in a book published in 1924 or earlier.</p>
</li>
<li><p><strong>I found a great painting, and it’s really old, and the author died a long time ago, but I can’t find a reproduction in a book. Can I use it?</strong>
</p><p>No. You must find a reproduction of your selected painting in a book published in 1924 or earlier.</p>
</li>
<li><p><strong>I’ve found a reproduction in a book, but the book is from 1925. Is that OK?</strong>
</p><p>No. You must find a reproduction of your selected painting in a book published in 1924 or earlier.</p>
</li>
<li><p><strong>I’ve found scan on a random museum site. Is that OK?</strong>
</p><p>No. You must find a reproduction of your selected painting in a book published in 1924 or earlier.</p>
</li>
<li><p><strong>But...</strong>
</p><p>No. You must find a reproduction of your selected painting in a book published in 1924 or earlier.</p>
</li>
</ul>
</section>
</section>
</section>
</section>
<section id="10.4"><aside class="number"><a href="#10.4">10.4</a></aside>
<h2>The titlepage image</h2>
<p>The titlepage image is auto-generated by the <code class="bash"><b>se</b> create-draft</code> tool. The arrangement of the text is a suggestion, and may be changed by the producer in case a more visually-pleasing arrangment is desired.</p>
<p>After completing <code class="path">./images/titlepage.svg</code>, use the <code class="bash"><b>se</b> build-images</code> tool to build the rasterized distribution SVG in <code class="path">./src/epub/images/titlepage.svg</code>.</p>
<ol type="1">
<li id="10.4.1"><aside class="number"><a href="#10.4.1">10.4.1</a></aside><p>The <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element has a value of <code class="string">The titlepage for the Standard Ebooks edition of</code> followed by the <a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.1">title string</a>.</p></li>
</ol>
<section id="10.4.2"><aside class="number"><a href="#10.4.2">10.4.2</a></aside>
<h3>Titlepage image layout</h3>
<ol type="1">
<li id="10.4.2.1"><aside class="number"><a href="#10.4.2.1">10.4.2.1</a></aside><p>The title, author, other contributors are in League Spartan font with 5px letter spacing in ALL CAPS.</p></li>
<li id="10.4.2.2"><aside class="number"><a href="#10.4.2.2">10.4.2.2</a></aside><p>The titlepage does not include subtitles.
						</p><p>For example, the titlepage would contain <code class="string">THE MAN WHO WAS THURSDAY</code>, but not <code class="string">THE MAN WHO WAS THURSDAY: A NIGHTMARE</code>.</p>
</li>
<li id="10.4.2.3"><aside class="number"><a href="#10.4.2.3">10.4.2.3</a></aside><p>Names of contributors besides the author are preceded by <code class="string">translated by</code> or <code class="string">illustrated by</code>. <code class="string">translated by</code> and <code class="string">illustrated by</code> are set in lowercase Sorts Mill Goudy Italic font.</p></li>
<li id="10.4.2.4"><aside class="number"><a href="#10.4.2.4">10.4.2.4</a></aside><p>Only the author, translator, and illustrator are on the titlepage. Other contributors like writers of introductions or annotators are not included.</p></li>
<li id="10.4.2.5"><aside class="number"><a href="#10.4.2.5">10.4.2.5</a></aside><p>The canvas has a padding area of 50px vertically and 100px horizontally in which text must not enter.</p></li>
<li id="10.4.2.6"><aside class="number"><a href="#10.4.2.6">10.4.2.6</a></aside><p>The viewbox width is exactly 1400px wide.</p></li>
<li id="10.4.2.7"><aside class="number"><a href="#10.4.2.7">10.4.2.7</a></aside><p>The viewbox height must <em>precisely fit the titlepage contents, plus 50px padding</em>.</p></li>
</ol>
<section id="10.4.2.8"><aside class="number"><a href="#10.4.2.8">10.4.2.8</a></aside>
<h4>Title line dimensions</h4>
<ol type="1">
<li id="10.4.2.8.1"><aside class="number"><a href="#10.4.2.8.1">10.4.2.8.1</a></aside><p>Each title line is 80px tall.</p></li>
<li id="10.4.2.8.2"><aside class="number"><a href="#10.4.2.8.2">10.4.2.8.2</a></aside><p>The title is split into as many lines as necessary to fit.</p></li>
<li id="10.4.2.8.3"><aside class="number"><a href="#10.4.2.8.3">10.4.2.8.3</a></aside><p>Title lines are separated by a 20px margin between each line.</p></li>
</ol>
</section>
<section id="10.4.2.9"><aside class="number"><a href="#10.4.2.9">10.4.2.9</a></aside>
<h4>Author line dimensions</h4>
<ol type="1">
<li id="10.4.2.9.1"><aside class="number"><a href="#10.4.2.9.1">10.4.2.9.1</a></aside><p>The first author line begins 100px below the last title line.</p></li>
<li id="10.4.2.9.2"><aside class="number"><a href="#10.4.2.9.2">10.4.2.9.2</a></aside><p>Each author line is 60px tall.</p></li>
<li id="10.4.2.9.3"><aside class="number"><a href="#10.4.2.9.3">10.4.2.9.3</a></aside><p>If an author line must be split, the next line begins 20px below the previous one.</p></li>
<li id="10.4.2.9.4"><aside class="number"><a href="#10.4.2.9.4">10.4.2.9.4</a></aside><p>For works with multiple authors, subsequent author lines begin 20px below the last author line.</p></li>
</ol>
</section>
<section id="10.4.2.10"><aside class="number"><a href="#10.4.2.10">10.4.2.10</a></aside>
<h4>Contributor lines dimensions</h4>
<ol type="1">
<li id="10.4.2.10.1"><aside class="number"><a href="#10.4.2.10.1">10.4.2.10.1</a></aside><p>“Contributors” are a “contributor descriptor,” like <code class="string">translated by</code>, followed by the contributor name on a new line.</p></li>
<li id="10.4.2.10.2"><aside class="number"><a href="#10.4.2.10.2">10.4.2.10.2</a></aside><p>The first contributor descriptor line begins 150px below the last author line.</p></li>
<li id="10.4.2.10.3"><aside class="number"><a href="#10.4.2.10.3">10.4.2.10.3</a></aside><p>Contributor descriptor lines are 40px tall, all lowercase, in the Sorts Mill Goudy Italic font.</p></li>
<li id="10.4.2.10.4"><aside class="number"><a href="#10.4.2.10.4">10.4.2.10.4</a></aside><p>The contributor name begins 20px below the contributor descriptor line.</p></li>
<li id="10.4.2.10.5"><aside class="number"><a href="#10.4.2.10.5">10.4.2.10.5</a></aside><p>The contributor name is 40px tall, ALL CAPS, in the League Spartan font.</p></li>
<li id="10.4.2.10.6"><aside class="number"><a href="#10.4.2.10.6">10.4.2.10.6</a></aside><p>If there is more than one contributor of the same type (like multiple translators), they are listed on one line. If there are two, separate them with <code class="string">AND</code>. If there are more than two, separate them with commas, and <code class="string">AND</code> after the final comma. Example: <i><a href="/ebooks/hermann-hesse/siddhartha/gunther-olesch_anke-dreher_amy-coulter_stefan-langer_semyon-chaichenets">Siddhartha</a></i>, by Hermann Hesse.</p></li>
<li id="10.4.2.10.7"><aside class="number"><a href="#10.4.2.10.7">10.4.2.10.7</a></aside><p>If there is more than one contributor type (like both a translator and an illustrator), the next contributor descriptor begins 80px after the last contributor name.</p></li>
</ol>
</section>
</section>
</section>
</section>
		</article>
	</main>
<?= Template::Footer() ?>
