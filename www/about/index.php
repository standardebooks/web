<?
$patronsCircle = [];
$anonymousPatronCount = 0;

// Get the Patrons Circle and try to sort by last name ascending.
// See <https://mariadb.com/kb/en/pcre/#unicode-character-properties> for Unicode character properties.

$patronsCircle = Db::Query('
				SELECT if(p.AlternateName is not null, p.AlternateName, u.Name) as SortedName
				from Patrons p
				inner join Users u using(UserId)
				where p.IsAnonymous = false
				    and p.Ended is null
				order by regexp_substr(SortedName, "[\\\p{Lu}][\\\p{L}\-]*$") asc;
			');

$anonymousPatronCount = Db::QueryInt('
				SELECT sum(cnt)
				from (
				          ( select count(*) cnt
				           from Payments
				           where UserId is null
				               and IsMatchingDonation = false
				               and ( (IsRecurring = true
				                      and Amount >= 10
				                      and Created >= utc_timestamp() - interval 30 day)
				                    or (IsRecurring = false
				                        and Amount >= 100
				                        and Created >= utc_timestamp() - interval 1 year) ) )
				      union all
				          ( select count(*) as cnt
				           from Patrons
				           where IsAnonymous = true
				               and Ended is null )
				      ) x
				');

?><?= Template::Header(title: 'About Standard Ebooks', highlight: 'about', description: 'Standard Ebooks is a volunteer-driven effort to produce a collection of high quality, carefully formatted, accessible, open source, and free public domain ebooks that meet or exceed the quality of commercially produced ebooks. The text and cover art in our ebooks is already believed to be in the public domain, and Standard Ebook dedicates its own work to the public domain, thus releasing the entirety of each ebook file into the public domain.') ?>
<main>
	<article>
		<h1>About Standard Ebooks</h1>
		<?= Template::DonationCounter() ?>
		<?= Template::DonationProgress() ?>
		<p>Standard Ebooks is a volunteer-driven effort to produce a collection of high quality, carefully formatted, accessible, open source, and free public domain ebooks that meet or exceed the quality of commercially produced ebooks. The text and cover art in our ebooks are already believed to be in the U.S. public domain, and Standard Ebooks dedicates its own work to the public domain, thus releasing the entirety of each ebook file into the public domain. All the ebooks we produce are distributed free of cost and free of U.S. copyright restrictions.</p>
		<p>Standard Ebooks is organized as a “<a href="https://en.wikipedia.org/wiki/Low-profit_limited_liability_company">low-profit L.L.C.</a>,” or “L<sup>3</sup>C,” a kind of legal entity that blends the charitable focus of a traditional not-for-profit with the ease of organization and maintenance of a regular L.L.C. Our only source of income is <a href="/donate">donations from readers like you</a>.</p>
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
				<li>
					<p><a href="/about/accessibility">How we make Standard Ebooks accessible</a></p>
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
							<img src="/images/masthead/alex-cabal.jpg" alt="A portrait of Alex Cabal."/>
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
				<p><i>“Editor” is the honorary title bestowed on volunteers who, in their history of contributing to Standard Ebooks, have demonstrated exceptional capability in producing ebooks, as well as in managing and reviewing ebooks produced by other volunteers. They continue to volunteer as first-line consultants for new ebook projects.</i></p>
				<ol class="editors">
					<li>
						<picture>
							<source srcset="/images/masthead/lukas-bystricky@2x.avif 2x, /images/masthead/lukas-bystricky.avif 1x" type="image/avif"/>
							<source srcset="/images/masthead/lukas-bystricky@2x.jpg 2x, /images/masthead/lukas-bystricky.jpg 1x" type="image/jpg"/>
							<img src="/images/masthead/lukas-bystricky.jpg" alt="A portrait of Lukas Bystricky."/>
						</picture>
						<p>
							<a href="https://www.linkedin.com/in/lukasbystricky/">Lukas Bystricky</a>
							<img src="/images/masthead/lukas-bystricky-contact.svg" alt="Contact information for Lukas Bystricky." class="contact"/>
						</p>
					</li>
					<li>
						<picture>
							<source srcset="/images/masthead/weijia-cheng@2x.avif 2x, /images/masthead/weijia-cheng.avif 1x" type="image/avif"/>
							<source srcset="/images/masthead/weijia-cheng@2x.jpg 2x, /images/masthead/weijia-cheng.jpg 1x" type="image/jpg"/>
							<img src="/images/masthead/weijia-cheng.jpg" alt="A portrait of Weijia Cheng."/>
						</picture>
						<p>
							<a href="https://www.weijiarhymeswith.asia/">Weijia Cheng</a>
							<img src="/images/masthead/weijia-cheng-contact.svg" alt="Contact information for Weijia Cheng." class="contact"/>
						</p>
					</li>
					<li>
						<picture>
							<source srcset="/images/masthead/david-reimer@2x.avif 2x, /images/masthead/david-reimer.avif 1x" type="image/avif"/>
							<source srcset="/images/masthead/david-reimer@2x.jpg 2x, /images/masthead/david-reimer.jpg 1x" type="image/jpg"/>
							<img src="/images/masthead/david-reimer.jpg" alt="A portrait of David Reimer."/>
						</picture>
						<p>
							David Reimer
							<img src="/images/masthead/david-reimer-contact.svg" alt="Contact information for David Reimer." class="contact"/>
						</p>
					</li>
					<li>
						<picture>
							<source srcset="/images/masthead/vince-rice@2x.avif 2x, /images/masthead/vince-rice.avif 1x" type="image/avif"/>
							<source srcset="/images/masthead/vince-rice@2x.jpg 2x, /images/masthead/vince-rice.jpg 1x" type="image/jpg"/>
							<img src="/images/masthead/vince-rice.jpg" alt="A portrait of Vince Rice."/>
						</picture>
						<p>
							<a href="https://www.brokenandsaved.com">Vince Rice</a>
							<img src="/images/masthead/vince-rice-contact.svg" alt="Contact information for Vince Rice." class="contact"/>
						</p>
					</li>
					<li>
						<picture>
							<source srcset="/images/masthead/emma-sweeney@2x.avif 2x, /images/masthead/emma-sweeney.avif 1x" type="image/avif"/>
							<source srcset="/images/masthead/emma-sweeney@2x.jpg 2x, /images/masthead/emma-sweeney.jpg 1x" type="image/jpg"/>
							<img src="/images/masthead/emma-sweeney.jpg" alt="A portrait of Emma Sweeney."/>
						</picture>
						<p>
							<a href="https://www.linkedin.com/in/emma-sweeney-554927190/">Emma Sweeney</a>
							<img src="/images/masthead/emma-sweeney-contact.svg" alt="Contact information for Emma Sweeney." class="contact"/>
						</p>
					</li>
					<li>
						<picture>
							<source srcset="/images/masthead/robin-whittleton@2x.avif 2x, /images/masthead/robin-whittleton.avif 1x" type="image/avif"/>
							<source srcset="/images/masthead/robin-whittleton@2x.jpg 2x, /images/masthead/robin-whittleton.jpg 1x" type="image/jpg"/>
							<img src="/images/masthead/robin-whittleton.jpg" alt="A portrait of Robin Whittleton."/>
						</picture>
						<p>
							<a href="https://www.robinwhittleton.com/">Robin Whittleton</a>
							<img src="/images/masthead/robin-whittleton-contact.svg" alt="Contact information for Robin Whittleton." class="contact"/>
						</p>
					</li>
				</ol>
			</section>
			<section id="social-media">
				<h3>Social Media</h3>
				<ol class="editors">
					<li>
						<picture>
							<source srcset="/images/masthead/laura-apostol@2x.avif 2x, /images/masthead/laura-apostol.avif 1x" type="image/avif"/>
							<source srcset="/images/masthead/laura-apostol@2x.jpg 2x, /images/masthead/laura-apostol.jpg 1x" type="image/jpg"/>
							<img src="/images/masthead/laura-apostol.jpg" alt="A portrait of Laura Apostol."/>
						</picture>
						<p>
							<a href="https://www.littleeclecticstudio.com/">Laura Apostol</a>
							<br/>
							Eclectic Studio
						</p>
					</li>
				</ol>
			</section>
			<section id="corporate-sponsors">
				<h3>Corporate Sponsors</h3>
				<ol class="donors corporate">
					<li>
						<a href="https://bookshop.org">
							<picture>
								<source srcset="/images/masthead/sponsors/bookshop-org@2x.webp 2x, /images/masthead/sponsors/bookshop-org.webp 1x" type="image/webp"/>
								<img src="/images/masthead/sponsors/bookshop-org@2x.webp" class="no-border" alt="Bookshop.org"/>
							</picture>
							<p>Bookshop.org</p>
						</a>
					</li>
					<li>
						<a href="https://beat.no">
							<picture>
								<source srcset="/images/masthead/sponsors/beat-technology@2x.webp 2x, /images/masthead/sponsors/beat-technology.webp 1x" type="image/webp"/>
								<img src="/images/masthead/sponsors/beat-technology@2x.webp" class="no-border" alt="Beat Technology"/>
							</picture>
							<p>Beat Technology</p>
						</a>
					</li>
					<li>
						<a href="https://www.scribophile.com">
							<img src="/images/masthead/sponsors/scribophile.svg" alt="Scribophile writing community &amp; workshop" />
							<p>Scribophile</p>
						</a>
					</li>
				</ol>
			</section>
			<section id="patrons-circle">
				<h3>Patrons Circle</h3>
				<p><a href="/donate#patrons-circle">Join the Patrons Circle</a> to support beautiful, free, and unrestricted digital literature, and to have a direct voice in shaping the future of the Standard Ebooks catalog.</p>
				<ol class="donors patrons">
					<? foreach($patronsCircle as $patron){ ?>
						<li>
							<p><?= Formatter::EscapeHtml(str_ireplace(['\'', ' and '], ['’', ' & '], $patron->SortedName)) ?></p>
						</li>
					<? } ?>
					<? if($anonymousPatronCount > 0){ ?>
						<li>
							<p>Anonymous × <?= number_format($anonymousPatronCount) ?></p>
						</li>
					<? } ?>
				</ol>
			</section>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
