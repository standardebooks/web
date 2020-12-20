<?
require_once('Core.php');
?><?= Template::Header(['title' => 'About Standard Ebooks', 'highlight' => 'about', 'description' => 'The Standard Ebooks project is a volunteer driven, not-for-profit effort to produce a collection of high quality, carefully formatted, accessible, open source, and free public domain ebooks that meet or exceed the quality of commercially produced ebooks. The text and cover art in our ebooks is already believed to be in the public domain, and Standard Ebook dedicates its own work to the public domain, thus releasing the entirety of each ebook file into the public domain.']) ?>
<main>
	<article>
		<h1>About Standard Ebooks</h1>
		<p>The Standard Ebooks project is a volunteer driven, not-for-profit effort to produce a collection of high quality, carefully formatted, accessible, open source, and free public domain ebooks that meet or exceed the quality of commercially produced ebooks. The text and cover art in our ebooks is already believed to be in the public domain, and Standard Ebook dedicates its own work to the public domain, thus releasing the entirety of each ebook file into the public domain. All the ebooks we produce are distributed free of cost and free of U.S. copyright restrictions.</p>
		<section id="more-information">
			<h2>More information</h2>
			<ul>
				<li>
					<p><a href="/about/our-goals">Standard Ebooks’ goals</a></p>
				</li>
				<li>
					<p><a href="/about/what-makes-standard-ebooks-different">What makes Standard Ebooks different</a></p>
				</li>
				<li>
					<p><a href="/about/standard-ebooks-and-the-public-domain">Standard Ebooks and the public domain</a></p>
				</li>
			</ul>
		</section>
		<section id="masthead" class="masthead">
			<h2>Masthead</h2>
			<section id="editor-in-chief">
				<h3>Editor-in-Chief</h3>
				<ol class="editors">
					<li>
						<picture>
							<source srcset="/images/masthead/alex-cabal@2x.avif 2x, /images/masthead/alex-cabal.avif 1x" type="image/avif"/>
							<source srcset="/images/masthead/alex-cabal@2x.jpg 2x, /images/masthead/alex-cabal.jpg 1x" type="image/jpg"/>
							<img src="/images/masthead/alex-cabal.png" alt="A portrait of Alex Cabal."/>
						</picture>
						<p>
							<a href="https://alexcabal.com">Alex Cabal</a>
							<img src="/images/masthead/alex-cabal-contact.svg" alt="Contact information for Alex Cabal." class="contact"/>
						</p>
					</li>
				</ol>
			</section>
			<section id="editors">
				<h3>Editors</h3>
				<ol class="editors">
					<li>
						<picture>
							<source srcset="/images/masthead/david-grigg@2x.avif 2x, /images/masthead/david-grigg.avif 1x" type="image/avif"/>
							<source srcset="/images/masthead/david-grigg@2x.jpg 2x, /images/masthead/david-grigg.jpg 1x" type="image/jpg"/>
							<img src="/images/masthead/david-grigg.png" alt="A portrait of Robin Whittleton."/>
						</picture>
						<p>
							<a href="https://rightword.com.au/david.php">David Grigg</a>
							<img src="/images/masthead/david-grigg-contact.svg" alt="Contact information for David Grigg." class="contact"/>
						</p>
					</li>
					<li>
						<picture>
							<source srcset="/images/masthead/b-timothy-keith@2x.avif 2x, /images/masthead/b-timothy-keith.avif 1x" type="image/avif"/>
							<source srcset="/images/masthead/b-timothy-keith@2x.jpg 2x, /images/masthead/b-timothy-keith.jpg 1x" type="image/jpg"/>
							<img src="/images/masthead/b-timothy-keith.png" alt="A portrait of Robin Whittleton."/>
						</picture>
						<p>
							<a href="https://astart.ca">B. Timothy Keith</a>
							<img src="/images/masthead/b-timothy-keith-contact.svg" alt="Contact information for B. Timothy Keith." class="contact"/>
						</p>
					</li>
					<li>
						<img src="/images/masthead/portrait.svg" alt=""/>
						<p><a href="https://www.brokenandsaved.com">Vince Rice</a></p>
					</li>
					<li>
						<img src="/images/masthead/portrait.svg" alt=""/>
						<p><a href="https://www.linkedin.com/in/emma-sweeney-554927190/">Emma Sweeney</a></p>
					</li>
					<li>
						<picture>
							<source srcset="/images/masthead/robin-whittleton@2x.avif 2x, /images/masthead/robin-whittleton.avif 1x" type="image/avif"/>
							<source srcset="/images/masthead/robin-whittleton@2x.jpg 2x, /images/masthead/robin-whittleton.jpg 1x" type="image/jpg"/>
							<img src="/images/masthead/robin-whittleton.png" alt="A portrait of Robin Whittleton."/>
						</picture>
						<p>
							<a href="https://www.robinwhittleton.com/">Robin Whittleton</a>
							<img src="/images/masthead/robin-whittleton-contact.svg" alt="Contact information for Robin Whittleton." class="contact"/>
						</p>
					</li>
				</ol>
			</section>
			<section id="patrons-circle">
				<hgroup>
					<h3>Patrons Circle</h3>
					<h4>Donors contributing $15/month or $150. <a href="/donate">Join the Patrons Circle today.</a></h4>
				</hgroup>
				<ol class="donors big">
					<? if(false){ ?>
					<li>
						<picture>
							<source srcset="/images/masthead/alex-cabal@2x.avif 2x, /images/masthead/alex-cabal.avif 1x" type="image/avif"/>
							<source srcset="/images/masthead/alex-cabal@2x.jpg 2x, /images/masthead/alex-cabal.jpg 1x" type="image/jpg"/>
							<img src="/images/masthead/alex-cabal.png" alt="A portrait of Alex Cabal."/>
						</picture>
						<p>Firstname Lastname</p>
					</li>
					<li>
						<img src="/images/masthead/portrait.svg" role="presentation" alt=""/>
						<p>Anonymous × 2</p>
					</li>
					<? } ?>
				</ol>
			</section>
			<section id="friends-circle">
				<hgroup>
					<h3>Friends Circle</h3>
					<h4>Donors contributing $10/month or $100. <a href="/donate">Join the Friends Circle today.</a></h4>
				</hgroup>
				<ol class="donors small">
					<? if(false){ ?>
					<li>
						<img src="/images/masthead/portrait.svg" role="presentation" alt=""/>
						<p>Firstname Lastname</p>
					</li>
					<li>
						<img src="/images/masthead/portrait.svg" role="presentation" alt=""/>
						<p>Anonymous × 2</p>
					</li>
					<? } ?>
				</ol>
			</section>
			<section id="letters-circle">
				<hgroup>
					<h3>Letters Circle</h3>
					<h4>Donors contributing $5/month or $50. <a href="/donate">Join the Letters Circle today.</a></h4>
				</hgroup>
				<ol class="donors no-images">
					<? if(false){ ?>
					<li>
						<p>Firstname Lastname</p>
					</li>
					<li>
						<p>Anonymous × 2</p>
					</li>
					<? } ?>
				</ol>
			</section>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
