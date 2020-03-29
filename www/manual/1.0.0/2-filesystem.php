<?
require_once('Core.php');
?><?= Template::Header(['title' => '2. Filesystem Layout and File Naming Conventions - The Standard Ebooks Manual', 'highlight' => 'contribute', 'manual' => true]) ?>
	<main class="manual"><nav><p><a href="/manual/1.0.0">The Standard Ebooks Manual of Style</a></p><ol><li><p><a href="/manual/1.0.0/1-code-style">1. XHTML, CSS, and SVG Code Style</a></p><ol><li><p><a href="/manual/1.0.0/1-code-style#1.1">1.1 XHTML formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.2">1.2 CSS formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.3">1.3 SVG Formatting</a></p></li><li><p><a href="/manual/1.0.0/1-code-style#1.4">1.4 Commits and Commit Messages</a></p></li></ol></li><li><p><a href="/manual/1.0.0/2-filesystem">2. Filesystem Layout and File Naming Conventions</a></p><ol><li><p><a href="/manual/1.0.0/2-filesystem#2.1">2.1 File locations</a></p></li><li><p><a href="/manual/1.0.0/2-filesystem#2.2">2.2 XHTML file naming conventions</a></p></li><li><p><a href="/manual/1.0.0/2-filesystem#2.3">2.3 The se-lint-ignore.xml file</a></p></li></ol></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook">3. The Structure of an Ebook</a></p><ol><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.1">3.1 Front matter</a></p></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.2">3.2 Body matter</a></p></li><li><p><a href="/manual/1.0.0/3-the-structure-of-an-ebook#3.3">3.3 Back matter</a></p></li></ol></li><li><p><a href="/manual/1.0.0/4-semantics">4. Semantics</a></p><ol><li><p><a href="/manual/1.0.0/4-semantics#4.1">4.1 Semantic Elements</a></p></li><li><p><a href="/manual/1.0.0/4-semantics#4.2">4.2 Semantic Inflection</a></p></li></ol></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns">5. General XHTML Patterns</a></p><ol><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.1">5.1 id attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.2">5.2 class attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.3">5.3 xml:lang attributes</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.4">5.4 The &lt;title&gt; element</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.5">5.5 Ordered/numbered and unordered lists</a></p></li><li><p><a href="/manual/1.0.0/5-general-xhtml-patterns#5.6">5.6 Tables</a></p></li></ol></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns">6. Standard Ebooks Section Patterns</a></p><ol><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.1">6.1 The title string</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.2">6.2 The table of contents</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.3">6.3 The titlepage</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.4">6.4 The imprint</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.5">6.5 The half title page</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.6">6.6 The colophon</a></p></li><li><p><a href="/manual/1.0.0/6-standard-ebooks-section-patterns#6.7">6.7 The Uncopyright</a></p></li></ol></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns">7. High Level Structural Patterns</a></p><ol><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.1">7.1 Sectioning</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.2">7.2 Headers</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.3">7.3 Dedications</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.4">7.4 Epigraphs</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.5">7.5 Poetry, verse, and songs</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.6">7.6 Plays and drama</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.7">7.7 Letters</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.8">7.8 Images</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.9">7.9 List of Illustrations (the LoI)</a></p></li><li><p><a href="/manual/1.0.0/7-high-level-structural-patterns#7.10">7.10 Endnotes</a></p></li></ol></li><li><p><a href="/manual/1.0.0/8-typography">8. Typography</a></p><ol><li><p><a href="/manual/1.0.0/8-typography#8.1">8.1 Section titles and ordinals</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.2">8.2 Italics</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.3">8.3 Capitalization</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.4">8.4 Indentation</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.5">8.5 Chapter headers</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.6">8.6 Ligatures</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.7">8.7 Punctuation and spacing</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.8">8.8 Numbers, measurements, and math</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.9">8.9 Latinisms</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.10">8.10 Initials and abbreviations</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.11">8.11 Times</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.12">8.12 Chemicals and compounds</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.13">8.13 Temperatures</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.14">8.14 Scansion</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.15">8.15 Legal cases and terms</a></p></li><li><p><a href="/manual/1.0.0/8-typography#8.16">8.16 Morse code</a></p></li></ol></li><li><p><a href="/manual/1.0.0/9-metadata">9. Metadata</a></p><ol><li><p><a href="/manual/1.0.0/9-metadata#9.1">9.1 General URL rules</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.2">9.2 The ebook identifier</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.3">9.3 Publication date and release identifiers</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.4">9.4 Book titles</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.5">9.5 Book subjects</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.6">9.6 Book descriptions</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.7">9.7 Book language</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.8">9.8 Book transcription and page scan sources</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.9">9.9 Additional book metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.10">9.10 General contributor rules</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.11">9.11 The author metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.12">9.12 The translator metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.13">9.13 The illustrator metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.14">9.14 The cover artist metadata block</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.15">9.15 Metadata for additional contributors</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.16">9.16 Transcriber metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.17">9.17 Producer metadata</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.18">9.18 The ebook manifest</a></p></li><li><p><a href="/manual/1.0.0/9-metadata#9.19">9.19 The ebook spine</a></p></li></ol></li><li><p><a href="/manual/1.0.0/10-art-and-images">10. Art and Images</a></p><ol><li><p><a href="/manual/1.0.0/10-art-and-images#10.1">10.1 Complete list of files</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.2">10.2 SVG patterns</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.3">10.3 The cover image</a></p></li><li><p><a href="/manual/1.0.0/10-art-and-images#10.4">10.4 The titlepage image</a></p></li></ol></li></ol></nav>
		<article>

<section id="2"><aside class="number">2</aside>
<h1>Filesystem Layout and File Naming Conventions</h1>
<p>A bare Standard Ebooks directory structure looks like this:</p>
<figure>
<img alt="A directory tree representing the structure of a bare Standard Ebook." src="/images/epub-draft-tree.png"/>
</figure>
<section id="2.1"><aside class="number">2.1</aside>
<h2>File locations</h2>
<ol type="1">
<li id="2.1.1"><aside class="number">2.1.1</aside><p>XHTML files containing the actual text of the ebook are located in <code class="path">./src/epub/text/</code>. All files in this directory end in <code class="path">.xhtml</code>.</p></li>
<li id="2.1.2"><aside class="number">2.1.2</aside><p>CSS files used in the ebook are located in <code class="path">./src/epub/css/</code>. All files in this directory end in <code class="path">.css</code>. This directory contains only two CSS files:
					</p><ol type="1">
<li id="2.1.2.1"><aside class="number">2.1.2.1</aside><p><code class="path">./src/epub/css/core.css</code> is distributed with all ebooks and is not edited.</p></li>
<li id="2.1.2.2"><aside class="number">2.1.2.2</aside><p><code class="path">./src/epub/css/local.css</code> is used for custom CSS local to the particular ebook.</p></li>
</ol>
</li>
<li id="2.1.3"><aside class="number">2.1.3</aside><p>Raw source images used in the ebook, but not distributed with the ebook, are located in <code class="path">./images/</code>. These images may be, for example, very high resolution that are later converted to lower resolution for distribution, or raw bitmaps that are later converted to SVG for distribution. Every ebook contains the following images in this directory:
					</p><ol type="1">
<li id="2.1.3.1"><aside class="number">2.1.3.1</aside><p><code class="path">./images/titlepage.svg</code> is the editable titlepage file that is later compiled for distribution.</p></li>
<li id="2.1.3.2"><aside class="number">2.1.3.2</aside><p><code class="path">./images/cover.svg</code> is the editable cover file that is later compiled for distribution.</p></li>
<li id="2.1.3.3"><aside class="number">2.1.3.3</aside><p><code class="path">./images/cover.source.(jpg|png|bmp|tif)</code> is the raw cover art file that may be cropped, resized, or otherwise edited to create <code class="path">./images/cover.jpg</code>.</p></li>
<li id="2.1.3.4"><aside class="number">2.1.3.4</aside><p><code class="path">./images/cover.jpg</code> is the final edited cover art that will be compiled into <code class="path">./src/epub/images/cover.svg</code> for distribution.</p></li>
</ol>
</li>
<li id="2.1.4"><aside class="number">2.1.4</aside><p>Images compiled or derived from raw source images, that are then distributed with the ebook, are located in <code class="path">./src/epub/images/</code>.</p></li>
<li id="2.1.5"><aside class="number">2.1.5</aside><p>The table of contents is located in <code class="path">./src/epub/toc.xhtml</code>.</p></li>
<li id="2.1.6"><aside class="number">2.1.6</aside><p>The epub metadata file is located in <code class="path">./src/epub/content.opf</code>.</p></li>
<li id="2.1.7"><aside class="number">2.1.7</aside><p>The ONIX metadata file is located in <code class="path">./src/epub/onix.xml</code>. This file is identical for all ebooks.</p></li>
<li id="2.1.8"><aside class="number">2.1.8</aside><p>The ONIX metadata file is located in <code class="path">./src/epub/onix.xml</code>. This file is identical for all ebooks.</p></li>
<li id="2.1.9"><aside class="number">2.1.9</aside><p>The <code class="path">./src/META-INF/</code> and <code class="path">./src/mimetype</code> directory and files are epub structural files that are identical for all ebooks.</p></li>
<li id="2.1.10"><aside class="number">2.1.10</aside><p>The <code class="path">./LICENSE.md</code> contains the ebook license and is identical for all ebooks.</p></li>
</ol>
</section>
<section id="2.2"><aside class="number">2.2</aside>
<h2>XHTML file naming conventions</h2>
<ol type="1">
<li id="2.2.1"><aside class="number">2.2.1</aside><p>Numbers in filenames don’t include leading <code class="path">0</code>s.</p></li>
<li id="2.2.2"><aside class="number">2.2.2</aside><p>Files containing a short story, essay, or other short work in a larger collection, are named with the URL-safe title of the work, excluding any subtitles.
					</p><table>
<thead>
<tr>
<th>Work</th>
<th>Filename</th>
</tr>
</thead>
<tbody>
<tr>
<td>A short story named “The Variable Man”</td>
<td><code class="path">the-variable-man.xhtml</code></td>
</tr>
<tr>
<td>A short story named “The Sayings of Limpang-Tung (The God of Mirth and of Melodious Minstrels)”</td>
<td><code class="path">the-sayings-of-limpang-tung.xhtml</code></td>
</tr>
</tbody>
</table>
</li>
<li id="2.2.3"><aside class="number">2.2.3</aside><p>Works that are divided into larger parts (sometimes called “parts,” “books,” “volumes,” “sections,” etc.) have their part divisions contained in individual files named after the type of part, followed by a number starting at <code class="path">1</code>.
					<div class="text corrected">
<p><code class="path">book-1.xhtml</code></p>
<p><code class="path">book-2.xhtml</code></p>
<p><code class="path">part-1.xhtml</code></p>
<p><code class="path">part-2.xhtml</code></p>
</div>
</p></li>
<li id="2.2.4"><aside class="number">2.2.4</aside><p>Works that are composed of chapters, short stories, essays, or other short- to medium-length sections have each of those sections in an individual file.
					</p><ol type="1">
<li id="2.2.4.1"><aside class="number">2.2.4.1</aside><p>Chapters <em>not</em> contained in separate volumes are named <code class="path">chapter-N.xhtml</code>, where <code class="path">N</code> is the chapter number starting at <code class="path">1</code>.
							</p><table>
<thead>
<tr>
<th>Section</th>
<th>Filename</th>
</tr>
</thead>
<tbody>
<tr>
<td>Chapter 1</td>
<td><code class="path">chapter-1.xhtml</code></td>
</tr>
<tr>
<td>Chapter 2</td>
<td><code class="path">chapter-2.xhtml</code></td>
</tr>
</tbody>
</table>
</li>
<li id="2.2.4.2"><aside class="number">2.2.4.2</aside><p>Chapters contained in separate volumes, where the chapter number re-starts at 1 in each volume, are named <code class="path">chapter-X-N.xhtml</code>, where <code class="path">X</code> is the part number starting at <code class="path">1</code>, and <code class="path">N</code> is the chapter number <em>within the part</em>, starting at <code class="path">1</code>.
							</p><table>
<thead>
<tr>
<th>Section</th>
<th>Filename</th>
</tr>
</thead>
<tbody>
<tr>
<td>Part 1</td>
<td><code class="path">part-1.xhtml</code></td>
</tr>
<tr>
<td>Part 1 Chapter 1</td>
<td><code class="path">chapter-1-1.xhtml</code></td>
</tr>
<tr>
<td>Part 1 Chapter 2</td>
<td><code class="path">chapter-1-2.xhtml</code></td>
</tr>
<tr>
<td>Part 1 Chapter 3</td>
<td><code class="path">chapter-1-3.xhtml</code></td>
</tr>
<tr>
<td>Part 2</td>
<td><code class="path">part-2.xhtml</code></td>
</tr>
<tr>
<td>Part 2 Chapter 1</td>
<td><code class="path">chapter-2-1.xhtml</code></td>
</tr>
<tr>
<td>Part 2 Chapter 2</td>
<td><code class="path">chapter-2-2.xhtml</code></td>
</tr>
</tbody>
</table>
</li>
<li id="2.2.4.3"><aside class="number">2.2.4.3</aside><p>Chapters contained in separate volumes, where the chapter number does not re-start at 1 in each volume, are named <code class="path">chapter-N.xhtml</code>, where <code class="path">N</code> is the chapter number, starting at <code class="path">1</code>.
							</p><table>
<thead>
<tr>
<th>Section</th>
<th>Filename</th>
</tr>
</thead>
<tbody>
<tr>
<td>Part 1</td>
<td><code class="path">part-1.xhtml</code></td>
</tr>
<tr>
<td>Chapter 1</td>
<td><code class="path">chapter-1.xhtml</code></td>
</tr>
<tr>
<td>Chapter 2</td>
<td><code class="path">chapter-2.xhtml</code></td>
</tr>
<tr>
<td>Chapter 3</td>
<td><code class="path">chapter-3.xhtml</code></td>
</tr>
<tr>
<td>Part 2</td>
<td><code class="path">part-2.xhtml</code></td>
</tr>
<tr>
<td>Chapter 4</td>
<td><code class="path">chapter-4.xhtml</code></td>
</tr>
<tr>
<td>Chapter 5</td>
<td><code class="path">chapter-5.xhtml</code></td>
</tr>
</tbody>
</table>
</li>
<li id="2.2.4.4"><aside class="number">2.2.4.4</aside><p>Works that are composed of extremely short sections, like a volume of short poems, are in a single file containing all of those short sections. The filename is the URL-safe name of the work.
							</p><table>
<thead>
<tr>
<th>Section</th>
<th>Filename</th>
</tr>
</thead>
<tbody>
<tr>
<td>A book of short poems called “North of Boston”</td>
<td><code class="path">north-of-boston.xhtml</code></td>
</tr>
</tbody>
</table>
</li>
<li id="2.2.4.5"><aside class="number">2.2.4.5</aside><p>Frontmatter and backmatter sections have filenames that are named after the type of section, regardless of what the actual title of the section is.
							</p><table>
<thead>
<tr>
<th>Section</th>
<th>Filename</th>
</tr>
</thead>
<tbody>
<tr>
<td>A preface titled “Note from the author”</td>
<td><code class="path">preface.xhtml</code></td>
</tr>
</tbody>
</table>
</li>
<li id="2.2.4.6"><aside class="number">2.2.4.6</aside><p>If a work contains more than one section of the same type (for example multiple prefaces), the filename is followed by <code class="path">-N</code>, where <code class="path">N</code> is a number representing the order of the section, starting at <code class="path">1</code>.
							</p><table>
<thead>
<tr>
<th>Section</th>
<th>Filename</th>
</tr>
</thead>
<tbody>
<tr>
<td>The work’s first preface, titled “Preface to the 1850 Edition”</td>
<td><code class="path">preface-1.xhtml</code></td>
</tr>
<tr>
<td>The work’s second preface, titled “Preface to the Charles Dickens Edition”</td>
<td><code class="path">preface-2.xhtml</code></td>
</tr>
</tbody>
</table>
</li>
</ol>
</li>
</ol>
</section>
<section id="2.3"><aside class="number">2.3</aside>
<h2>The <code class="path">se-lint-ignore.xml</code> file</h2>
<p>The <code class="bash"><b>se</b> lint</code> tool makes best guesses to alert the user to potential issues in an ebook production, and it may sometimes guess wrong. An <code class="path">se-lint-ignore.xml</code> file can be placed in the ebook root to make <code class="bash"><b>se</b> lint</code> ignore specific error numbers in an ebook.</p>
<ol type="1">
<li id="2.3.1"><aside class="number">2.3.1</aside><p><code class="bash">se-lint-ignore.xml</code> is optional. If it exists, it is in the ebook root.</p></li>
<li id="2.3.2"><aside class="number">2.3.2</aside><p>An empty <code class="bash">se-lint-ignore.xml</code> file looks like this:
					</p><figure><code class="html full"><span class="cp">&lt;?xml version="1.0" encoding="utf-8"?&gt;</span>
<span class="p">&lt;</span><span class="nt">se-lint-ignore</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">se-lint-ignore</span><span class="p">&gt;</span></code></figure>
</li>
<li id="2.3.3"><aside class="number">2.3.3</aside><p>The <code class="html"><span class="p">&lt;</span><span class="nt">se-lint-ignore</span><span class="p">&gt;</span></code> root element contains one or more <code class="html"><span class="p">&lt;</span><span class="nt">file</span><span class="p">&gt;</span></code> elements.
					</p><ol type="1">
<li id="2.3.3.1"><aside class="number">2.3.3.1</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">file</span><span class="p">&gt;</span></code> elements have a <code class="html"><span class="na">path</span></code> attribute containing a filename to match in <code class="path">./src/epub/text/</code>.
							</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">file</span> <span class="na">path</span><span class="o">=</span><span class="s">"chapter-3-1-11.xhtml"</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">file</span><span class="p">&gt;</span></code></figure>
</li>
<li id="2.3.3.2"><aside class="number">2.3.3.2</aside><p><code class="html"><span class="na">path</span></code> attributes accept shell-style globbing to match files.
							</p><figure><code class="html full"><span class="p">&lt;</span><span class="nt">file</span> <span class="na">path</span><span class="o">=</span><span class="s">"chapter-*.xhtml"</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">file</span><span class="p">&gt;</span></code></figure>
</li>
<li id="2.3.3.3"><aside class="number">2.3.3.3</aside><p>Each <code class="html"><span class="p">&lt;</span><span class="nt">file</span><span class="p">&gt;</span></code> element contains one or more <code class="html"><span class="p">&lt;</span><span class="nt">ignore</span><span class="p">&gt;</span></code> elements. Each <code class="html"><span class="p">&lt;</span><span class="nt">ignore</span><span class="p">&gt;</span></code> element contains one <code class="html"><span class="p">&lt;</span><span class="nt">code</span><span class="p">&gt;</span></code> element and one <code class="html"><span class="p">&lt;</span><span class="nt">reason</span><span class="p">&gt;</span></code> element.
							</p><ol type="1">
<li id="2.3.3.3.1"><aside class="number">2.3.3.3.1</aside><p>The value of <code class="html"><span class="p">&lt;</span><span class="nt">code</span><span class="p">&gt;</span></code> is the error/warning code provided by <code class="bash"><b>se</b> lint</code>. This code will be ignored for its parent file(s) when <code class="bash"><b>se</b> lint</code> is next run.</p></li>
<li id="2.3.3.3.2"><aside class="number">2.3.3.3.2</aside><p>The value of <code class="html"><span class="p">&lt;</span><span class="nt">reason</span><span class="p">&gt;</span></code> is a prose explanation about why the code was ignored. This is to aid future producers or reviewers in understanding the reasoning behind why a code was ignored.
									</p><ol type="1">
<li id="2.3.3.3.2.1"><aside class="number">2.3.3.3.2.1</aside><p><code class="html"><span class="p">&lt;</span><span class="nt">reason</span><span class="p">&gt;</span></code> is required to have a non-whitespace value.</p></li>
</ol>
</li>
</ol>
</li>
</ol>
</li>
</ol>
<section id="example">
<h3>Example</h3>
<p>The following is an example of a complete <code class="path">se-lint-ignore.xml</code> file from <a href="/ebooks/ludwig-wittgenstein/tractatus-logico-philosophicus/c-k-ogden">Tractatus Logico-Philosophicus</a>.</p>
<figure><code class="xml full"><span class="cp">&lt;?xml version="1.0" encoding="utf-8"?&gt;</span>
<span class="nt">&lt;se-lint-ignore&gt;</span>
	<span class="nt">&lt;file</span> <span class="na">path=</span><span class="s">"introduction.xhtml"</span><span class="nt">&gt;</span>
		<span class="nt">&lt;ignore&gt;</span>
			<span class="nt">&lt;code&gt;</span>t-002<span class="nt">&lt;/code&gt;</span>
			<span class="nt">&lt;reason&gt;</span>Punctuation is deliberately placed outside of quotes in this ebook to prevent confusion with mathematical symbols and formulas.<span class="nt">&lt;/reason&gt;</span>
		<span class="nt">&lt;/ignore&gt;</span>
	<span class="nt">&lt;/file&gt;</span>
	<span class="nt">&lt;file</span> <span class="na">path=</span><span class="s">"tractatus-logico-philosophicus.xhtml"</span><span class="nt">&gt;</span>
		<span class="nt">&lt;ignore&gt;</span>
			<span class="nt">&lt;code&gt;</span>s-021<span class="nt">&lt;/code&gt;</span>
			<span class="nt">&lt;reason&gt;</span>The <span class="ni">&amp;lt;</span>title<span class="ni">&amp;gt;</span> tag is accurate; the work title appears in the half title.<span class="nt">&lt;/reason&gt;</span>
		<span class="nt">&lt;/ignore&gt;</span>
		<span class="nt">&lt;ignore&gt;</span>
			<span class="nt">&lt;code&gt;</span>t-002<span class="nt">&lt;/code&gt;</span>
			<span class="nt">&lt;reason&gt;</span>Punctuation is deliberately placed outside of quotes in this ebook to prevent confusion with mathematical symbols and formulas.<span class="nt">&lt;/reason&gt;</span>
		<span class="nt">&lt;/ignore&gt;</span>
	<span class="nt">&lt;/file&gt;</span>
<span class="nt">&lt;/se-lint-ignore&gt;</span></code></figure>
</section>
</section>
</section>
		</article>
	</main>
<?= Template::Footer() ?>
