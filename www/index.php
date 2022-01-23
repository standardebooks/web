<?
require_once('Core.php');
?><?= Template::Header(['description' => 'Free and liberated ebooks, carefully produced for the true book lover. Download free ebooks with professional-quality formatting and typography, in formats compatible with your ereader.']) ?>
<main class="front-page">
	<h1>Free and liberated ebooks,<br/> carefully produced for the true book lover.</h1>
	<picture>
		<source srcset="/images/devices@2x.avif 2x, /images/devices.avif 1x" type="image/avif"/>
		<source srcset="/images/devices@2x.png 2x, /images/devices.png 1x" type="image/png"/>
		<img src="/images/devices@2x.png" alt="Ereaders with a Standard Ebook open."/>
	</picture>
	<p>Standard Ebooks is a volunteer-driven project that produces new editions of public domain ebooks that are lovingly formatted, open source, free of copyright restrictions, and free of cost.</p>
	<p>Ebook projects like <a href="https://www.gutenberg.org">Project Gutenberg</a> transcribe ebooks and make them available for the widest number of reading devices. Standard Ebooks takes ebooks from sources like Project Gutenberg, formats and typesets them using a carefully designed and professional-grade style manual, fully proofreads and corrects them, and then builds them to create a new edition that takes advantage of state-of-the-art ereader and browser technology.</p>
	<p>Standard Ebooks aren’t just a beautiful addition to your digital library—they’re a high quality standard to build your own ebooks on.</p><a class="button next" href="/ebooks">Browse our library of free ebooks</a>
	<section>
		<h2>What makes Standard Ebooks different?</h2>
		<section>
			<h3>Modern &amp; consistent typography</h3>
			<div>
				<div>
					<p>Other free ebooks don’t put much effort into professional-quality typography: they use "straight" quotes instead of “curly” quotes, they ignore details like em- and en-dashes, and they look more like early-90’s web pages instead of actual books.</p>
					<p>Standard Ebooks applies a rigorous and modern <a href="/manual">style manual</a> when developing each and every ebook to ensure they meet a professional-grade and consistent typographical standard. Our ebooks look <em>good</em>.</p>
				</div>
				<figure class="stacked">
					<picture>
						<source srcset="/images/typography-bad@2x.avif 2x, /images/typography-bad.avif 1x" type="image/avif"/>
						<source srcset="/images/typography-bad@2x.png 2x, /images/typography-bad.png 1x" type="image/png"/>
						<img src="/images/typography-bad@2x.png" class="bottom" alt="An example of bad typography." height="150" width="300"/>
					</picture>
					<picture>
						<source srcset="/images/typography-good@2x.avif 2x, /images/typography-good.avif 1x" type="image/avif"/>
						<img src="/images/typography-good@2x.png" class="top" alt="An example of Standard Ebooks typography." height="150" width="300"/>
					</picture>
					<img alt="An arrow pointing from bad typography to good typography." class="arrow" src="/images/arrow-down.png"/>
				</figure>
			</div>
		</section>
		<section>
			<h3>Full proofing with careful corrections</h3>
			<div>
				<div>
					<p>Transcriptions from other sources are often filled with typos or suffer from issues like inconsistent spelling, missing accent marks, or missing punctuation. Submitting corrections to such sources can be difficult or impossible, so errors are rarely fixed.</p>
					<p>At Standard Ebooks, we do a careful and complete readthrough of each ebook before releasing it, checking it against a scan of the original pages to fix as many typos as possible. Even if we <em>do</em> miss something, our ebooks are stored in the hugely popular Git source control system, allowing anyone to easily submit a correction.</p>
				</div>
				<figure>
					<picture>
						<source srcset="/images/proofreading@2x.avif 2x, /images/proofreading.avif 1x" type="image/avif"/>
						<source srcset="/images/proofreading@2x.png 2x, /images/proofreading.png 1x" type="image/png"/>
						<img src="/images/proofreading@2x.png" alt="A text with proofreader’s marks." height="200" width="400"/>
					</picture>
				</figure>
			</div>
		</section>
		<section>
			<h3>Rich &amp; detailed metadata</h3>
			<div>
				<div>
					<p>Our ebooks include complete, well-researched, and consistent metadata, including original, detailed book blurbs and links to encyclopedia sources. Perfect for machine processing or for extra-curious, technically-minded readers.</p>
				</div>
				<figure>
					<picture>
						<source srcset="/images/metadata@2x.avif 2x, /images/metadata.avif 1x" type="image/avif"/>
						<source srcset="/images/metadata@2x.png 2x, /images/metadata.png 1x" type="image/png"/>
						<img src="/images/metadata@2x.png" alt="Ebook metadata displayed in an ereader program." height="200" width="400"/>
					</picture>
				</figure>
			</div>
		</section>
		<section>
			<h3>State-of-the-art technology</h3>
			<div>
				<div>
					<p>Each Standard Ebook takes full advantage of the latest ereader technology, including:</p>
					<ul>
						<li>
							<p>Hyphenation support,</p>
						</li>
						<li>
							<p>Popup footnotes,</p>
						</li>
						<li>
							<p>High-resolution and scalable vector graphics,</p>
						</li>
						<li>
							<p>Ereader-compatible tables of contents,</p>
						</li>
					</ul>
					<p>and more. One of our goals is to ensure our ebooks stay up-to-date with the best reading experience technology can provide. Just because it’s a classic doesn’t mean it has to use old technology.</p>
				</div>
				<figure class="stacked">
					<picture>
						<source srcset="/images/endnote@2x.avif 2x, /images/endnote.avif 1x" type="image/avif"/>
						<source srcset="/images/endnote@2x.png 2x, /images/endnote.png 1x" type="image/png"/>
						<img src="/images/endnote@2x.png" class="bottom" alt="A screenshot of a popup endnote." height="150" width="300"/>
					</picture>
					<picture>
						<source srcset="/images/toc@2x.avif 2x, /images/toc.avif 1x" type="image/avif"/>
						<source srcset="/images/toc@2x.png 2x, /images/toc.png 1x" type="image/png"/>
						<img src="/images/toc@2x.png" class="top" alt="A screenshot of an ebook’s table of contents." height="150" width="300"/>
					</picture>
				</figure>
			</div>
		</section>
		<section>
			<h3>Quality covers</h3>
			<div>
				<div>
					<p>Everyone knows a book is judged by its cover, but most free ebooks leave it to your ereader software to generate a drab default cover.</p>
					<p>Standard Ebooks draws from a vast collection of public domain fine art to create attractive, unique, appropriate, and consistent covers for each of our ebooks.</p>
				</div>
				<figure>
					<picture>
						<source srcset="/images/covers@2x.avif 2x, /images/covers.avif 1x" type="image/avif"/>
						<source srcset="/images/covers@2x.jpg 2x, /images/covers.jpg 1x" type="image/jpg"/>
						<img src="/images/covers@2x.jpg" alt="An ebookshelf featuring Standard Ebooks covers." height="125" width="400"/>
					</picture>
				</figure>
			</div>
		</section>
		<section>
			<h3>Clean code &amp; semantic markup</h3>
			<div>
				<div>
					<p>Our strict coding standards allow technologists and ebook producers to use Standard Ebooks files as reliable, easy to read, and robust bases for their own work—not to mention as models of what well-crafted ebook files look like. Common code patterns are repeated through different ebooks, so the code never surprises you.</p>
					<p>Each ebook is also enhanced with careful standards-based semantic markup that opens the gateway for exciting new kinds of machine processing.</p>
				</div>
				<figure>
					<picture>
						<source srcset="/images/code@2x.avif 2x, /images/code.avif 1x" type="image/avif"/>
						<source srcset="/images/code@2x.png 2x, /images/code.png 1x" type="image/png"/>
						<img src="/images/code@2x.png" alt="The source code for an ebook." height="200" width="400"/>
					</picture>
				</figure>
			</div>
		</section>
		<section>
			<h3>Free, open-source, &amp; public domain</h3>
			<div>
				<div>
					<p>We use the popular Git source control system to track each and every change made to our ebooks. Anyone can easily see a history of changes, or contribute their own changes with the click of a mouse.</p>
					<p>And while all of the ebooks we feature and the cover art we draw from are <em>already</em> believed to be in the public domain in the U.S., Standard Ebooks releases all of the work we put in to each ebook into the public domain too. That makes each and every one of our ebook files not just free, but <a href="https://en.wikipedia.org/wiki/Gratis_versus_libre">libre</a> too—because the world deserves more unrestricted culture.</p>
				</div>
				<figure class="oss">
					<img alt="The Git SCM logo." src="/images/git.svg" height="150" width="150"/>
					<img alt="The no-copyright symbol." src="/images/no-copyright.svg" height="150" width="150"/>
					<img alt="The anti-DRM symbol." src="/images/no-drm.svg" height="137" width="150"/>
				</figure>
			</div>
		</section>
	</section>
</main>
<?= Template::Footer() ?>
