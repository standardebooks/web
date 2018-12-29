<?
require_once('Core.php');
?><?= Template::Header(['title' => 'A Basic Standard Ebooks Source Folder', 'highlight' => 'contribute', 'description' => 'All Standard Ebooks source folders have the same basic structure, described here.']) ?>
<main>
	<article id="a-basic-standard-ebooks-source-folder">
		<h1>A Basic Standard Ebooks Source Folder</h1>
		<section>
			<p>All Standard Ebooks source folders have the same basic structure. It looks a little like this:</p>
			<figure>
				<img alt="A tree view of a new Standard Ebooks draft folder" src="/images/epub-draft-tree.png">
			</figure>
			<ul>
				<li>
					<p><code class="path">dist/</code> is where our <code class="program">build</code> script will put the finished ebook files. Right now it’s empty.</p>
				</li>
				<li>
					<p><code class="path">images/</code> contains the raw image files used in an ebook. For ebooks without illustrations, you’ll have the following three files:</p>
					<ul>
						<li>
							<p><code class="path">images/cover.svg</code>, the raw cover image complete with title and author.</p>
						</li>
						<li>
							<p><code class="path">images/titlepage.svg</code>, the raw titlepage image.</p>
						</li>
						<li>
							<p><code class="path">images/cover.source.jpg</code>, not pictured; the raw, high-resolution artwork we’ve picked as the cover image background, kept here for future reference but not actually used in the final epub file.</p>
						</li>
						<li>
							<p><code class="path">images/cover.jpg</code>, not pictured; the scaled-down version of <code class="path">cover.source.jpg</code> that will be referenced by <code class="path">cover.svg</code> and included in the final epub file.</p>
						</li>
					</ul>
				</li>
				<li>
					<p><code class="path">src/</code> contains the actual source of the epub. This is the folder that’ll be zipped up as the final epub file, and where the bulk of our work will happen.</p>
					<ul>
						<li>
							<p><code class="path">src/META-INF/</code>, <code class="path">src/META-INF/container.xml</code>, and <code class="path">src/mimetype</code> are required by the epub spec; don’t edit them.</p>
						</li>
						<li>
							<p><code class="path">src/epub/css/</code> contains the CSS files used in the epub.</p>
							<ul>
								<li>
									<p><code class="path">src/epub/css/core.css</code> is the common CSS file used across all Standard Ebooks; don’t edit it.</p>
								</li>
								<li>
									<p><code class="path">src/epub/css/local.css</code> is the CSS file that contains styles for this specific ebook. This is the one you’ll be editing, <em>if necessary</em>. Not all ebooks need custom CSS—the less custom CSS, the better!</p>
								</li>
							</ul>
						</li>
						<li>
							<p><code class="path">src/epub/images/</code> contains the images used in the final epub. Right now it contains the Standard Ebooks logo file used in the colophon; don’t edit the logo. Once you’ve finished the cover and titlepage images in <code class="path">./images/</code>, the <code class="program">build-images</code> script will compile them and put them here.</p>
						</li>
						<li>
							<p><code class="path">src/epub/text/</code> is the meat and potatoes of the ebook! You’ll place the source XHTML files here, alongside the templates the draft script created for you:</p>
							<ul>
								<li>
									<p><code class="path">src/epub/text/colophon.xhtml</code> is the template for the Standard Ebooks colophon that appears at the end of every ebook. Usually you’ll edit this last, once you’ve finalized the cover page and metadata.</p>
								</li>
								<li>
									<p><code class="path">src/epub/text/titlepage.xhtml</code> is the template for the titlepage. The only thing to edit here is the title and author in the titlepage <code class="html">alt</code> attribute. Leave the rest alone.</p>
								</li>
								<li>
									<p><code class="path">src/epub/text/unlicense.xhtml</code> is the Standard Ebooks public domain dedication. Don’t edit this at all.</p>
								</li>
							</ul>
						</li>
						<li>
							<p><code class="path">src/epub/content.opf</code> is the file that contains all of the epub’s metadata. You’ll be editing this heavily.</p>
						</li>
						<li>
							<p><code class="path">src/epub/onix.xml</code> is a file containing accessibility information. Don’t edit this.</p>
						</li>
					</ul>
				</li>
			</ul>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
