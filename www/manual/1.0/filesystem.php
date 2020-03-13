<?
require_once('Core.php');
?><?= Template::Header(['title' => '2. Filesystem Layout and File Naming Conventions - The Standard Ebooks Manual', 'highlight' => 'contribute', 'manual' => true]) ?>
	<main>
		<article class="manual">

	<section data-start-at="2" id="filesystem-layout-and-file-naming-conventions">
		<h1>Filesystem Layout and File Naming Conventions</h1>
		<!-- An introduction is required for the data-start-at class to take effect. -->
		<p>Filenames aim to be clear, common-sense descriptions of their contents.</p>
		<section id="file-locations">
			<h2>File locations</h2>
			<ol type="1">
				<li>XHTML files containing the actual text of the ebook are located in <code class="path">./src/epub/text/</code>. All files in this directory end in <code class="path">.xhtml</code>.</li>
				<li>CSS files used in the ebook are located in <code class="path">./src/epub/css/</code>. All files in this directory end in <code class="path">.css</code>. This directory contains only two CSS files:
					<ol type="1">
						<li><code class="path">./src/epub/css/core.css</code> is distributed with all ebooks and is not meant to be edited.</li>
						<li><code class="path">./src/epub/css/local.css</code> is used for custom CSS local to the particular ebook.</li>
					</ol>
				</li>
				<li>Raw source images used in the ebook, but not distributed with the ebook, are located in <code class="path">./src/images/</code>. These images may be, for example, very high resolution that are later converted to lower resolution for distribution, or raw bitmaps that are later converted to SVG for distribution. Every ebook contains the following images in this directory:
					<ol type="1">
						<li><code class="path">./src/images/titlepage.svg</code> is the editable titlepage file that is later compiled for distribution.</li>
						<li><code class="path">./src/images/cover.svg</code> is the editable cover file that is later compiled for distribution.</li>
						<li><code class="path">./src/images/cover.source.(jpg|png|bmp|tiff)</code> is the raw cover art file that may be cropped, resized, or otherwise edited to create <code class="path">./src/images/cover.jpg</code>.</li>
						<li><code class="path">./src/images/cover.jpg</code> is the final edited cover art that will be compiled in to <code class="path">./src/epub/images/cover.svg</code> for distribution.</li>
					</ol>
				</li>
				<li>Images compiled or derived from raw source images, that are then distributed with the ebook, are located in <code class="path">./src/epub/images/</code>.</li>
				<li>The table of contents is located in <code class="path">./src/epub/toc.xhtml</code>.</li>
				<li>The epub metadata file is located in <code class="path">./src/epub/content.opf</code>.</li>
				<li>The ONIX metadata file is located in <code class="path">./src/epub/onix.xml</code>. This file is identical for all ebooks.</li>
				<li>The ONIX metadata file is located in <code class="path">./src/epub/onix.xml</code>. This file is identical for all ebooks.</li>
				<li>The <code class="path">./src/META-INF/</code> and <code class="path">./src/mimetype</code> directory and files are epub structural files that are identical for all ebooks.</li>
				<li>The <code class="path">./LICENSE.md</code> contains th ebook license and is identical for all ebooks.</li>
			</ol>
		</section>
		<section id="xhtml-file-naming-conventions">
			<h2>XHTML file naming conventions</h2>
			<ol type="1">
				<li>Numbers in filenames don’t include leading <code class="path">0</code>s.</li>
				<li>Files containing a short story, essay, or other short work in a larger collection, are named with the URL-safe title of the work, excluding any subtitles.
					<table>
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
				<li>Works that are divided into larger parts (sometimes called “parts,” “books,” “volumes,” “sections,” etc.) have their part divisions contained in individual files named after the type of part, followed by a number starting at <code class="path">1</code>.
					<div class="text corrected">
						<p><code class="path">book-1.xhtml</code></p>
						<p><code class="path">book-2.xhtml</code></p>
						<p><code class="path">part-1.xhtml</code></p>
						<p><code class="path">part-2.xhtml</code></p>
					</div>
				</li>
				<li>Works that are composed of chapters, short stories, essays, or other short- to medium-length sections have each of those sections in an individual file.
					<ol type="1">
						<li>Chapters <em>not</em> contained in separate volumes are named <code class="path">chapter-N.xhtml</code>, where <code class="path">N</code> is the chapter number starting at <code class="path">1</code>.
							<table>
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
						<li>Chapters contained in separate volumes, where the chapter number re-starts at 1 in each volume, are named <code class="path">chapter-X-N.xhtml</code>, where <code class="path">X</code> is the part number starting at <code class="path">1</code>, and <code class="path">N</code> is the chapter number <em>within the part</em>, starting at <code class="path">1</code>.
							<table>
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
						<li>Chapters contained in separate volumes, where the chapter number does not re-start at 1 in each volume, are named <code class="path">chapter-N.xhtml</code>, where <code class="path">N</code> is the chapter number, starting at <code class="path">1</code>.
							<table>
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
						<li>Works that are composed of extremely short sections, like a volume of short poems, are in a single file containing all of those short sections. The filename is the URL-safe name of the work.
							<table>
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
						<li>Frontmatter and backmatter sections have filenames that are named after the type of section, regardless of what the actual title of the section is.
							<table>
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
						<li>If a work contains more than one section of the same type (for example multiple prefaces), the filename is followed by <code class="path">-N</code>, where <code class="path">N</code> is a number representing the order of the section, starting at <code class="path">1</code>.
							<table>
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
	</section>
		</article>
	</main>
<?= Template::Footer() ?>
