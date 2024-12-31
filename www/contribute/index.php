<?= Template::Header(['title' => 'Get Involved', 'highlight' => 'contribute', 'description' => 'Details on how to contribute your time and talent to the volunteer-driven Standard Ebooks project.']) ?>
<main>
	<article class="has-hero">
		<hgroup>
			<h1>Get Involved</h1>
			<p>and help create ebooks that are a pleasure to read</p>
		</hgroup>
		<?= Template::DonationCounter() ?>
		<?= Template::DonationProgress() ?>
		<picture data-caption="The Printing House of Bernardo Cennini. Tito Lessi, 1907">
			<source srcset="/images/the-printing-house@2x.avif 2x, /images/the-printing-house.avif 1x" type="image/avif"/>
			<source srcset="/images/the-printing-house@2x.jpg 2x, /images/the-printing-house.jpg 1x" type="image/jpg"/>
			<img src="/images/the-printing-house@2x.jpg" alt="An oil painting of four men in a printer’s workshop creating prints."/>
		</picture>
		<p>Standard Ebooks is a volunteer-driven project, and there’s room for people of all skill levels to contribute.</p>
		<p>At the most basic level, anyone can <a href="https://groups.google.com/g/standardebooks">contribute feedback via our mailing list</a>. Day-to-day readers can <a href="/contribute/report-errors">tell us about errors they spot in our ebooks</a>. People with an eye for typography and a talent for editing can proofread whole ebooks. Technically inclined readers can produce ebooks themselves, or <a href="https://github.com/standardebooks">contribute via GitHub</a>. You can also <a href="/donate">make a financial contribution</a> to help fund continued ebook development.</p>
		<section id="everyone">
			<h2>Everyone</h2>
			<ul>
				<li>
					<p><a href="https://groups.google.com/g/standardebooks">Join the Standard Ebooks mailing list.</a></p>
					<p>The mailing list is the best way to contact Standard Ebooks about any question, suggestion, concern, or contribution you might have. We’re just an email away!</p>
				</li>
				<li>
					<p><a href="/contribute/report-errors">Report a typo, formatting error, or typography error in any Standard Ebook.</a></p>
				</li>
				<li>
					<p>Help us catalog fine art paintings from scans of old art books. We have links to complete book scans that you can browse, and we need your help to catalog the art in each book for use as cover art in future ebooks.</p>
					<p>No technical experience is necessary. <a href="https://groups.google.com/g/standardebooks">Contact the mailing list if you want to help.</a></p>
				</li>
			</ul>
		</section>
		<section id="editors-and-readers">
			<h2>For readers</h2>
			<ul>
				<li>
					<p><a href="/contribute/report-errors">Report a typo, formatting error, or typography error in any Standard Ebook.</a></p>
				</li>
				<li>
					<p><a href="/contribute/tips-for-editors-and-proofreaders">Tips for editors and proofreaders.</a></p>
				</li>
				<li>
					<p><b><a href="/manual">The Standard Ebooks Manual of Style.</a></b></p>
				</li>
			</ul>
		</section>
		<section id="technical-contributors">
			<h2>For people wanting to produce a new ebook, and other technical contributors</h2>
			<ul>
				<li>
					<p><a href="/contribute/producers">The process of producing an ebook for Standard Ebooks.</a></p>
				</li>
				<li>
					<p><a href="/contribute/collections-policy">Our collections policy</a>, or, ebooks we do and don’t accept.</p>
				</li>
				<li>
					<p><a href="/contribute/wanted-ebooks">Our Wanted Ebooks list</a>, including suggestions for first-time productions.</p>
				</li>
				<li>
					<p><a href="/contribute/producing-an-ebook-step-by-step">A technical step-by-step guide to producing a new ebook from start to finish.</a></p>
				</li>
				<li>
					<p><b><a href="/manual">The Standard Ebooks Manual of Style.</a></b></p>
				</li>
				<li>
					<p><a href="/contribute/how-tos">How-to guides for various more complex ebook production tasks.</a></p>
				</li>
				<li>
					<p><a href="https://b-t-k.github.io/">Standard Ebooks Hints and Tricks</a>, a beginner’s guide by one of our editors.</p>
				</li>
				<li>
					<p><a href="/contribute/a-basic-standard-ebooks-source-folder">Descriptions of the files in a fresh Standard Ebooks source folder.</a></p>
				</li>
				<li>
					<p><a href="https://standardebooks.org/tools">The Standard Ebooks toolset.</a></p>
				</li>
				<li>
					<p><a href="https://github.com/standardebooks">All Standard Ebooks ebook source files.</a></p>
				</li>
				<li>
					<p><a href="/contribute/spreadsheets">A list of various research spreadsheets our volunteers created and use.</a></p>
				</li>
			</ul>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
