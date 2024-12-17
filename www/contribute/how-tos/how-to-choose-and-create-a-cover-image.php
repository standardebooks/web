<?= Template::Header(['title' => 'How to Choose and Create a Cover Image', 'manual' => true, 'highlight' => 'contribute', 'description' => 'A guide to choosing, clearing, and formatting your cover image.']) ?>
<main class="manual">
		<article class="step-by-step-guide">
			<h1>How to Choose and Create a Cover Image</h1>
			<p>A book’s cover is an important part of the Standard Ebooks mission, to create books that meet or exceed the quality of commercially produced books. This guide will help you choose a cover that meets Standard Ebooks’ technical, legal, and editorial standards.</p>
			<aside class="alert">
				<p class="warning">Before you read</p>
				<p>
					<strong>Make sure to read the <a href="https://standardebooks.org/manual/latest">Standard Ebooks Manual of Style</a> before consulting this guide, and DO NOT add any artwork to your project until it has been approved or you may need to rebase your project.</strong>
				</p>
			</aside>
			<details id="toc">
				<summary>Table of Contents</summary>
				<ol>
					<li>
						<p>
							<a href="#before-you-begin">Before You Begin</a>
						</p>
					</li>
					<li>
						<p>
							<a href="#where-to-find">Where to Find Public Domain Cover Art</a>
						</p>
					</li>
					<ol>
						<li>
							<p>
								<a href="#where-to-find-se-database">The Standard Ebooks cover art database</a>
							</p>
						</li>
						<li>
							<p>
								<a href="#where-to-find-museums-with-cc0">Museums with CC0 Copyright Disclaimers</a>
							</p>
						</li>
						<li>
							<p>
								<a href="#where-to-find-published-art">Art Published in a Public Domain Book</a>
							</p>
						</li>
						<li>
							<p>
								<a href="#where-to-find-faq">Copyright Clearance FAQ</a>
							</p>
						</li>
					</ol>
					<li>
						<p>
							<a href="#suitable">Choosing Suitable Art</a>
						</p>
					</li>
					<li>
						<p>
							<a href="#approval">Getting Artwork Approved</a>
						</p>
					</li>
					<li>
						<p>
							<a href="#resources">Resources</a>
						</p>
					</li>
					<ol>
						<li>
							<p>
								<a href="#resources-se-database">The Standard Ebooks cover art database</a>
							</p>
						</li>
						<li>
							<p>
								<a href="#resources-cc0">Museums with CC0 Collections</a>
							</p>
						</li>
						<li>
							<p>
								<a href="#resources-images">Sources for high definition image downloads</a>
							</p>
						</li>
						<li>
							<p>
								<a href="#resources-pages">Sources for page proofs</a>
							</p>
						</li>
					</ol>
					<li>
						<p>
							<a href="#i-still-have-questions">I Still Have Questions</a>
						</p>
					</li>
				</ol>
			</details>
			<ol>
				<li id="before-you-begin">
					<h2>Before You Begin</h2>
					<p>Every cover image must be approved by either the Editor in Chief or the manager assigned to your project. Until your chosen image has been approved, <strong>do not</strong> add it to your repository. If you commit early and your artwork is not approved, you may need to rebase your repository to remove all traces of the image.</p>
				</li>
				<li id="where-to-find">
					<h2>Where to Find Public Domain Cover Art</h2>
					<p>Your cover image must be provably in the public domain in the United States. Standard Ebooks has strict guidelines for proving that your cover art is in the public domain. There are three ways to demonstrate public domain status:</p>
					<ul>
						<li><p>Choose art from the <a href="/artworks">Standard Ebooks Artwork Database</a>;</p></li>
						<li><p>Download art from a museum collection under a CC0 license; or</p></li>
						<li><p>Find art printed in a public domain book.</p></li>
					</ul>
					<p>Because U.S. copyright law is complicated, one of these three methods is <i>required</i> in order to demonstrate that the painting you selected is in fact in the U.S. public domain, with no exceptions. Just because a painting is very old, or Wikipedia says it’s PD, or it’s PD in a country besides the U.S., doesn’t necessarily mean it actually is PD in the U.S.</p>
					<ol>
						<li id="where-to-find-se-database">
							<h3>The Standard Ebooks Cover Database</h3>
							<p>Standard Ebooks maintains a <a href="/artworks">database of US-public-domain art</a>. The art included in this database has been reviewed by at least two S.E. volunteers and includes links to the image and the proof of copyright.</p>
							<p>Choosing pre-approved art from the database is as simple as searching by artist, title, or keyword.</p>
							<p>Please remember that even art from the database needs to be approved on the list before it is added to your repository.</p>
						</li>
						<li id="where-to-find-museums-with-cc0">
							<h3>Museums with CC0 Copyright Licenses</h3>
							<p>If there is nothing in the database that fits, you can also find public domain art in online museum collections.</p>
							<p>You can use a painting from an online museum collection without further proof <strong>only if</strong> the museum that holds the original work of art has released it via the CC0 license. The CC0 deed is a legal document by which the museum waives any and all copyright in the image and the underlying work itself. S.E. <strong>does not</strong> accept other public domain declarations (not even other Creative Commons declarations such as CC-PDM).</p>
							<p>Below you will find an <a href="#resources-cc0">extensive list of museums with CC0 collections</a>. Please be sure to check the individual license; not every artwork at every listed museum will have the same license. Only works marked as CC0 will be approved.</p>
						</li>
						<li id="where-to-find-published-art">
							<h3>Art Published in a Public Domain Book</h3>
							<p>The final way to clear a painting for use as a cover image is to locate a reproduction of that painting in a book published before <?= PD_STRING ?>. This option gives you the most freedom in finding a suitable image, but it can be difficult; proving an artwork’s public domain status this way can sometimes be the most time-consuming part of the ebook production process.</p>
							<p>This process involves: (1) identifying a work of art that you think will be a suitable cover, then (2) finding book page scans with a reproduction of that artwork in a book published before <?= PD_STRING ?> at an online book archive like the Internet Archive, Google Books, or HathiTrust. (Note that if your IP address is not in the U.S., some book archives may disable book previews.)</p>
							<section>
								<h4>Public Domain Proof Tips</h4>
								<ul>
									<li><p>Many museum online catalogs have a “bibliography” or “references” section for each painting in their collection, which is a list of books in which the painting was either mentioned or reproduced. This is a good shortcut to finding page scans of a book with reproductions, and if you’re lucky, a search for the book title in Google Books will turn up scans.</p></li>
									<li><p>Remember that paintings often go by many different titles in different languages; your best bet is to search for an artist’s last name instead.</p></li>
									<li><p>Artists often had various spellings of their names, especially ones in non-Latin alphabets (like <a href="/artworks/ilya-repin">Ilya Repin</a>). Make sure to try several spelling variants to ensure you find them in art books.</p></li>
								</ul>
							</section>
							<section>
								<h4>Public Domain Proof Pitfalls</h4>
								<p>There are some things you need to be especially careful of when using this method:</p>
								<ul>
									<li><p>In older books it was common to have etchings of paintings. Etchings are not strict reproductions, and so we cannot count them for PD clearance. Etchings can sometimes be identified by: (1) having more clearly defined lines, or shading with more contrast; (2) having shading done with a stipple effect; (3) differences in small amorphous details like the shape of clouds, trees, or fabric compared to the original painting. Etchings mostly fell out of use by around 1900.</p></li>
									<li><p>Painters often produced several different versions of the same artwork. For PD clearance, your scan must be of the exact version you will be using. Carefully compare the two. Check for differences in small details, like the position of trees, clouds, reflections, or water. Any difference and the proof will be rejected.</p></li>
									<li><p>Do not rely on the date given in the catalog entry at HathiTrust or the Internet Archive; these can be wrong. Please verify the page scan of the copyright page to ensure the book was published before <?= PD_STRING ?>.</p></li>
								</ul>
							</section>
						</li>
						<li id="where-to-find-faq">
							<h3>Copyright Clearance FAQs</h3>
							<ul>
								<li>
									<p>
										<b>I found a great painting, and Wikipedia says it’s public domain, but I can’t find a reproduction in a book. Can I use it?</b>
									</p>
									<p>No. You must find a reproduction of your selected painting in a book published before <?= PD_STRING ?>.</p>
								</li>
								<li>
									<p>
										<b>I found a great painting, and it’s really old, and the author died a long time ago, but I can’t find a reproduction in a book. Can I use it?</b>
									</p>
									<p>No. You must find a reproduction of your selected painting in a book published before <?= PD_STRING ?>.</p>
								</li>
								<li>
									<p>
										<b>I’ve found a reproduction in a book, but the book was published after <?= PD_STRING ?>. Is that OK?</b>
									</p>
									<p>No. You must find a reproduction of your selected painting in a book published before <?= PD_STRING ?>.</p>
								</li>
								<li>
									<p>
										<b>I’ve found a painting on a museum site and it says they think it’s in the public domain, but there’s no other license information. Is that OK?</b>
									</p>
									<p>No. You must find a reproduction of your selected painting in a book published before <?= PD_STRING ?>.</p>
								</li>
								<li>
									<p>
										<b>I’ve found a painting on a museum site, and it has a CC license other than CC0. Is that OK?</b>
									</p>
									<p>No. You must find a reproduction of your selected painting in a book published before <?= PD_STRING ?>.</p>
								</li>
								<li>
									<p>
										<b>But...</b>
									</p>
									<p>No. You must find a reproduction of your selected painting in a book published before <?= PD_STRING ?>.</p>
								</li>
							</ul>
						</li>
					</ol>
				</li>
				<li id="suitable">
					<h2>Choosing Suitable Art</h2>
					<p>When choosing cover art, you also need to make sure it is suitable for Standard Ebooks and for your book in particular. The main considerations are:</p>
					<ol>
						<li>
							<p><b>House Style.</b> The Manual of Style states that:</p>
							<blockquote>
								<p>Cover art is in the “fine art oil painting” style, and in full color. Artworks not in this style, like ink drawings, woodcuts, medieval-style “flat” paintings, pencil sketches, modern CG art, or black-and-white scans, are not acceptable. Generally, watercolors are not acceptable, though some watercolors *may* be accepted if their appearance is oil-painting-like.</p>
								<p>Cover art is not a very famous or easily-recognizable painting, like Da Vinci’s <i>Mona Lisa</i> or Van Gogh’s <i>Starry Night</i>.</p>
								<p>Cover art is not AI-generated.</p>
							</blockquote>
							<p>Beyond those basic requirements, there are a few more specific considerations:</p>
							<ul>
								<li>
									<p><b>Science fiction.</b> For works of twentieth century science fiction, more abstract or modernist cover art is a good bet, as for obvious reasons there are few fine art oil paintings of science fiction subjects. Starting around the 1910s dust jackets of fantasy and science fiction books began featuring oil paintings, which can be used as the S.E. cover for their respective books.</p>
								</li>
								<li><p><b>Compilations.</b> It can be difficult to find a single image that sums up a collection of short stories, poems, or essays. A portrait of the author can be a good choice here, if available.</p></li>
							</ul>
						</li>
						<li>
							<p><b>Orientation and Crop.</b> Remember that your final cover art will have to be taller than it is wide, with a ratio of 3:2 height to width. Most art is wider than this and will need to be cropped to fit. If you start with a wide landscape painting, you may only be able to use a small slice of it.</p>
							<p>Also, remember that our tools add a title and author box to the bottom of the cover. If there is important detail in that area then the artwork can’t be used. Sometimes creative cropping can help avoid this problem.</p>
							<p>You need to use image editing software to create a final cover image exactly 1400 pixels wide and 2100 pixels tall. If you don’t already have one, <a href="https://www.gimp.org">Gimp</a> is a free, open source image editor that is available for Mac, Windows and Linux.</p>
						</li>
						<li>
							<p><b>Duplication.</b> Make sure your image hasn’t already been used as a Standard Ebooks cover image. You can <a href="/artworks">search the S.E. Cover Art Database</a> by artist name to check if your cover is already in use.</p>
						</li>
						<li>
							<p><b>Resolution.</b> The final cover image must be exactly 2100 pixels high and 1400 pixels wide. If you upscale a low-resolution image, the result can be muddy and unflattering. Therefore, finding a reproduction of a suitable resolution is important.</p>
							<p>It is possible to upscale an image that is smaller than 2100 × 1400. If it only needs to be upscaled a small amount, most image editors like <a href="https://www.gimp.org">Gimp</a> will give good results. New AI image editing tools like <a href="https://www.topazlabs.com/gigapixel">Gigapixel</a> can also get good results from very low resolution source images. If you’re not experienced with these tools yourself, ask on the mailing list; someone there should be able to help you.</p>
						</li>
						<li>
							<p><b>Compromise.</b> Every producer wants to find the perfect match for their project, but remember, you also need to find a cover that is high enough resolution and, most importantly, public domain. So, be ready to compromise.</p>
						</li>
					</ol>
				</li>
				<li id="approval">
					<h2>Getting Artwork Approved</h2>
					<p>Before you add any cover image to your project, you need to have it approved on the S.E. list by the editor assigned to your project, or by the Editor-in-Chief. Just post in your project thread, and include:</p>
					<ul>
						<li><p>A link to your proof of public domain status. This may be a link to the S.E. cover art database, to a museum listing with a CC0 declaration, or to the page images of a print appearance.</p></li>
						<li><p>A mockup of your cover, including the Standard Ebooks title block.</p></li>
					</ul>
					<p>Creating a mockup is simple. All you need to do is:</p>
					<ul>
						<li>
							<p>Make a copy of the cover.svg file from your project’s <code class="path">./images</code> folder (<strong>not</strong> from <code class="path">./src/epub/images</code>) and move it somewhere outside of your project.</p>
						</li>
						<li>
							<p>Rename your edited cover image to <code class="path">cover.jpg</code> and copy it into the same folder.</p>
						</li>
						<li>
							<p>Open your copy of <code class="path">cover.svg</code> with any application that can read SVG files. Because <code class="path">cover.svg</code> includes a link to <code class="path">cover.jpg</code>, you should see a complete cover image as long as both files are in the same folder.</p>
						</li>
						<li>
							<p>Export the cover image as a PNG file to post on the mailing list.</p>
						</li>
					</ul>
				</li>
				<li id="resources">
					<h2>Resources</h2>
					<ol>
						<li id="resources-se-database">
							<h3>The Standard Ebooks Art Database</h3>
							<p>Our <a href="/artworks">searchable database of public domain art</a>.</p>
						</li>
						<li id="resources-cc0">
							<h3>Museums with CC0 Collections</h3>
							<p>Only images from these collections that are explicitly marked as CC0 can be used without further research. Not every painting at every listed museum is CC0; you must confirm the presence of a CC0 deed for the specific image you want to use.</p>
							<p>Where available, these links go directly so search results for public domain paintings.</p>
							<section>
								<dl>
									<dt><a href="https://www.metmuseum.org/art/collection/search?showOnly=withImage%7CopenAccess&amp;material=Paintings">Met Museum</a></dt>
									<dd>CC0 items have an “OA Public Domain” icon under the picture, which leads to the Met’s Open Access Initiative page that clarifies a CC0 license.</dd>
									<dt><a href="https://www.si.edu/search/collection-images?edan_q=landscape&amp;edan_fq%5B0%5D=object_type%3A%22Paintings%22">The Smithsonian</a></dt>
									<dd>CC0 items say CC0 under Restrictions &amp; Rights in the item details.</dd>
									<dt><a href="https://www.nga.gov/collection/collection-search.html">National Gallery of Art</a></dt>
									<dd>CC0 items have a “0 Public Domain” icon under the picture, which leads to an Open Access policy mentioning a CC0 license.</dd>
									<dt><a href="https://www.getty.edu/art/collection">Getty Museum Collection</a></dt>
									<dd>CC0 items have a “0 public domain” icon that links to a CC0 license. <strong>Beware, some items say “no copyright” which is not the same as CC0 and cannot be used!</strong></dd>
									<dt><a href="https://collections.britishart.yale.edu/?utf8=%E2%9C%93&amp;f%5Bcollection_ss%5D%5B%5D=Paintings+and+Sculpture&amp;range%5BearliestDate_is%5D%5Bbegin%5D=1614&amp;range%5BearliestDate_is%5D%5Bend%5D=1900&amp;search_field=all_fields&amp;q=">Yale Center for British Art</a></dt>
									<dd>CC0 items have a “0 Public Domain” icon under the picture, which links to the CC0 license.</dd>
									<dt><a href="https://artgallery.yale.edu/">Yale University Art Gallery</a></dt>
									<dd>CC0 items say “No Copyright - United States” under the “Object copyright” section, which links to a CC0 license.</dd>
									<dt><a href="https://www.artic.edu/collection?q=test&amp;is_public_domain=1&amp;department_ids=European+Painting+and+Sculpture">Art Institute of Chicago</a></dt>
									<dd>CC0 items say CC0 in the lower left of the painting in the art detail page.</dd>
									<dt><a href="https://art.thewalters.org/">The Walters Art Museum</a></dt>
									<dd>CC0 items are listed as "CC Creative Commons License" which links to a CC0 rights page.</dd>
									<dt><a href="https://www.grpmcollections.org/Browse/Collections">Grand Rapids Public Museum</a></dt>
									<dd>CC0 items have a CC0 logo and link near the download button.</dd>
									<dt><a href="https://collections.artsmia.org/">Minneapolis Institute of Art</a></dt>
									<dd>Public domain items are listed as such under “Rights.”</dd>
									<dt><a href="http://www.clevelandart.org/art/collection/search?only-open-access=1&amp;filter-type=Painting">Cleveland Museum of Art</a></dt>
									<dd>CC0 items have the CC0 logo in the search results and near the download button.</dd>
									<dt><a href="https://risdmuseum.org/art-design/collection">RISD Museum</a></dt>
									<dd>CC0 items have a link to the CC0 license in the “Use” section.</dd>
									<dt><a href="https://www.rijksmuseum.nl/en/search?q=&amp;f=1&amp;p=1&amp;ps=12&amp;type=painting&amp;imgonly=True&amp;st=Objects">Rijksmuseum</a></dt>
									<dd>Open the “Object Data” section and check the “Copyright” entry under the “Acquisition and right” section. If it says "Public domain," click through to confirm CC0.</dd>
									<dt><a href="https://www.museabrugge.be/en/collections/browse">Musea Brugge</a></dt>
									<dd>CC0 items indicate the images are published under the CC0 license in the “Copyright” line.</dd>
									<dt>
										<a href="https://open.smk.dk/en/">National Gallery of Denmark</a>
									</dt>
									<dd>CC0 items have “No copyright” icon and a “Free to use” notice, and the About page states that such images are released via CC0.</dd>
									<dt><a href="https://nivaagaard.dk/en/the-collection/">Nivaagaards Malerisamling</a></dt>
									<dd>CC0 items say “Public Domain” by the picture, which leads to a license details page, which links to a CC0 license.</dd>
									<dt><a href="https://kataloget.thorvaldsensmuseum.dk/en/results?q=&amp;level%5B%5D=B">Thorvaldsens Museum</a></dt>
									<dd>CC0 items have a “0” icon under the picture, which links to the “Copyright” page, which links to the CC0 license.</dd>
									<dt>
										<a href="https://www.kansallisgalleria.fi/en/search?category=artwork&amp;classification=painting&amp;copyrightFree=true&amp;hasImage=true">Finnish National Gallery</a>
									</dt>
									<dd>CC0 items say “Copyright Free,” and the general rights statement in the original Finnish indicates that such images are CC0 licensed.</dd>
									<dt><a href="http://parismuseescollections.paris.fr/en/recherche/image-libre/true/avec-image/true/denominations/peinture-166168?mode=mosaique&amp;solrsort=ds_created%20desc">Paris Musées</a></dt>
									<dd>CC0 items have the CC0 logo near the download button.</dd>
									<dt><a href="https://www.lenbachhaus.de/en/discover/collection-online">Lenbachhaus</a></dt>
									<dd>CC0 items say CC0 below the picture with a link to the CC0 license.</dd>
									<dt><a href="https://www.kunsthalle-karlsruhe.de/en/collection/">Staatliche Kunsthalle Karlsruhe</a></dt>
									<dd>CC0 items have a CC0 icon below the picture.</dd>
									<dt><a href="https://kmska.be/en/overview/the-collection">Royal Museum of Fine Arts Antwerp</a></dt>
									<dd>CC0 items say “This image may be downloaded for free” in the “Copyright and legal” section, which has a link to a disclaimer stating that KMSKA releases the photo under a CC0 license.</dd>
									<dt><a href="https://digitaltmuseum.no/search/?aq=owner%3F%3A%22LKM%22+license%3F%3A%22zero%22+type%3F%3A%22Fineart%22+media%3F%3A%22image%22">Lillehammer Kunstmuseum</a></dt>
									<dd>CC0 items say “License: CC CC0 1.0” under the “License information” section. In the art metadata, “Owner of collection” <strong>must</strong> be “Lillehammer Kunstmuseum.” Any other art on <a href="https://www.digitaltmuseum.no">digitaltmuseum.no</a> or <a href="https://www.digitaltmuseum.se">digitaltmuseum.se</a> must be cleared separately.</dd>
									<dt><a href="https://zbiory.mnk.pl/">National Museum in Krakow</a></dt>
									<dd>CC0 items say "CC0 - Public Domain" under the Copyright section.</dd>
									<dt><a href="https://www.nationalmuseum.se/en/explore-art-and-design/images/free-images">National Museum in Stockholm</a></dt>
									<dd>CC-PD items have the CC-PD mark in the lower left of the item’s detail view.</dd>
									<dt><a href="https://dams.birminghammuseums.org.uk/asset-bank/action/viewDefaultHome">Birmingham Museums</a></dt>
									<dd>CC0 items say CC0 under the Usage Rights section in the item details.</dd>
									<dt><a href="https://collections.brightonmuseums.org.uk/?q=&amp;departments=Fine%20Art">Brighton &amp; Hove Museums</a></dt>
									<dd>CC0 items have the URL of the CC0 license in the “License” field.</dd>
									<dt><a href="https://emuseum.aberdeencity.gov.uk/collections/102307/open-access-images--fine-art">Aberdeen Archives, Gallery &amp; Museums</a></dt>
									<dd>CC0 items say “Out of copyright - CC0” on the copyright line.</dd>
								</dl>
							</section>
						</li>
						<li id="resources-images">
							<h3>Sources for high definition image downloads</h3>
							<p>Remember that for art from these sources you will also need to find separate copyright proof. Online museum collections are also a great place to find high definition images.</p>
							<ul>
								<li>
									<a href="https://commons.wikimedia.org">Wikimedia Commons</a>
								</li>
								<li>
									<a href="https://www.google.com/culturalinstitute/project/art-project">Google Art Project</a>
								</li>
								<li>
									<a href="https://www.wikiart.org">WikiArt</a>
								</li>
								<li>
									<a href="https://artvee.com/">Artvee</a>
								</li>
							</ul>
						</li>
						<li id="resources-pages">
							<h3>Sources for page proofs</h3>
							<ul>
								<li>
									<p>
										<a href="https://books.google.com">Google Books</a>
									</p>
									<p><a href="https://www.google.com/webhp?tbm=bks&amp;tbs=cdr:1,cd_max:12/31/<?= PD_YEAR ?>">Use this shortcut</a> to search for books that were published before <?= PD_STRING ?>.</p>
									<p>Google Books is a good first stop because its thumbnail view is very fast, and it does a better job of highlighting search results than HathiTrust or Internet Archive.</p>
								</li>
								<li>
									<p>
										<a href="https://www.hathitrust.org">HathiTrust</a>
									</p>
									<p><a href="https://babel.hathitrust.org/cgi/ls?lmt=ft&amp;a=srchls&amp;adv=1&amp;q1=Art&amp;field1=ocr&amp;anyall1=all&amp;op1=AND&amp;yop=before&amp;pdate_end=<?= PD_YEAR ?>">Use this shortcut</a> to search for books that were published before <?= PD_STRING ?>.</p>
								</li>
								<li>
									<p>
										<a href="https://archive.org">Internet Archive</a>
									</p>
									<p><a href="https://archive.org/search.php?query=+date%3A%5B1850-01-01+TO+<?= PD_YEAR ?>-12-31%5D&amp;sin=TXT&amp;sort=-date">Use this shortcut</a> to search for books that were published before <?= PD_STRING ?>.</p>
								</li>
								<li>
									<p>Our own <a href="/contribute/uncategorized-art-resources">list of uncategorized art books</a> that have not yet been processed for the art database may be helpful to browse through for inspiration and easy US-PD clearance.</p>
								</li>
							</ul>
						</li>
					</ol>
				</li>
				<li id="i-still-have-questions">
					<h2>I still have questions!</h2>
					<p>If you’re unsure about anything, or have a question that isn’t answered here, please ask on our <a href="https://groups.google.com/g/standardebooks">mailing list.</a> The experienced producers there can answer any question you might have.</p>
				</li>
			</ol>
		</article>


</main>
<?= Template::Footer() ?>
