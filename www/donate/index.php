<?
$ebookCount = floor(Db::QueryInt('select count(*) from Ebooks where EbookCreatedAt is not null') / 10) * 10;
$newsletterSubscriberCount = floor(Db::QueryInt('
						select count(*)
						from NewsletterSubscriptions
						where IsConfirmed = true
					') / 100) * 100;
$donationCounter = DonationCounter::GetByIsActive();
$donateUrl = $donationCounter === null ? '/donations/new' : 'https://fundraising.fracturedatlas.org/standard-ebooks/general_support';
?><?= Template::Header(title: 'Donate', highlight: 'donate', description: 'Donate to Standard Ebooks.') ?>
<main>
	<section class="donate narrow has-hero">
		<hgroup>
			<h1>Donate to Standard Ebooks</h1>
			<p>and help bring the beauty of literature to the digital age</p>
		</hgroup>
		<?= Template::DonationDrive(autoHide: false, showDonateButton: false) ?>
		<picture data-caption="The Quiet Hour. Albert Chevallier Tayler, 1925">
			<source srcset="/images/the-quiet-hour@2x.avif 2x, /images/the-quiet-hour.avif 1x" type="image/avif"/>
			<source srcset="/images/the-quiet-hour@2x.jpg 2x, /images/the-quiet-hour.jpg 1x" type="image/jpeg"/>
			<img src="/images/the-quiet-hour@2x.jpg" alt="An oil painting of a woman reading a book by a writing desk at twilight."/>
		</picture>
		<section id="donate">
			<p>We work hard to create beautifully-formatted ebooks that are free of cost and free of U.S. copyright restrictions. It takes a team of highly-skilled specialists working together for weeks and even months to ensure that an ebook meets our exacting aesthetic and technical standards, before it’s offered to readers for free.</p>
			<p>The cost of producing even the most basic ebook is estimated to approach almost a thousand dollars; and that doesn’t include infrastructure costs like developing and maintaining our <a href="/tools">free and open source ebook production toolset</a>, editing our <a href="/manual">free Manual of Style</a>, project management, hosting, or bandwidth. <em>Your contribution is critically important for us to continue bringing beautifully-presented literature to readers in the digital age.</em></p>
			<h2>Make a donation</h2>
			<p class="button-row center<? if($donationCounter !== null){ ?> counter-active<? } ?>">
				<? if($donationCounter !== null){ ?>
					<a href="https://fundraising.fracturedatlas.org/standard-ebooks/monthly_support" class="button">Start a recurring donation</a>
					<a href="https://fundraising.fracturedatlas.org/standard-ebooks/general_support" class="button">Make a one-time donation</a>
				<? }else{ ?>
					<a href="<?= $donateUrl ?>" class="button">Donate now!</a>
				<? } ?>
				<span class="divider">or</span>
				<span class="alternatives">
					<a href="/help/donating-via-a-donor-advised-fund" class="button">Donate via a <br/>donor-advised fund</a>
					<? if($donationCounter === null){ ?><a href="https://fundraising.fracturedatlas.org/standard-ebooks/monthly_support" class="button">Make a <br/>tax-deductible donation</a><? } ?>
				</span>
			</p>
		</section>
		<section id="patrons-circle">
			<h2>Join the Patrons Circle<a class="heading-permalink" href="#patrons-circle" aria-label="Permalink"></a></h2>
			<p>Members of the Patrons Circle are steadfast supporters of free, unrestricted, and beautifully presented digital literature. Besides helping support the creation of free, gorgeous ebooks, they also have a direct voice in shaping the future of the Standard Ebooks catalog.</p>
			<p>Membership in the Patrons Circle is limited to individuals only. Organizations, please see <a href="#corporate-sponsors">corporate sponsorship</a> instead.</p>
			<div class="join-patrons-circle-callout">
				<h3>Join now</h3>
				<? if($donationCounter !== null){ ?>
					<p><i>Join the Patrons Circle for one year with a one-time donation of <?= Formatter::FormatCurrency(PATRONS_CIRCLE_YEARLY_COST, true) ?> or more.</i></p>
				<? }else{ ?>
					<p><i>Join the Patrons Circle by starting a recurring donation of <?= Formatter::FormatCurrency(PATRONS_CIRCLE_MONTHLY_COST, true) ?>/month or <?= Formatter::FormatCurrency(PATRONS_CIRCLE_YEARLY_COST, true) ?>/year or more.</i></p>
				<? } ?>

				<p class="button-row center">
					<a href="<?= $donateUrl ?>?join-patrons-circle" class="button">Join the Patrons Circle</a>
				</p>
			</div>
			<section id="patrons-benefits">
				<h3>Benefits of the Patrons Circle<a class="heading-permalink" href="#patrons-benefits" aria-label="Permalink"></a></h3>
				<ul>
					<li>
						<p>Your name <a href="/about#patrons-circle">listed on our masthead</a>. (You can also remain anonymous if you prefer.)</p>
					</li>
					<li>
						<p>Access to our various <a href="/feeds">ebook feeds</a>:</p>
						<ul>
							<li>
								<p>Browse and download from the entire Standard Ebooks catalog of over <?= number_format($ebookCount) ?> ebooks directly in your ereading app using our <a href="/feeds/opds">OPDS feed</a>.</p>
							</li>
							<li>
								<p>Get notified of new ebooks in your news client with our <a href="/feeds/atom">RSS/Atom feed</a>.</p>
							</li>
							<li>
								<p>Parse and process the feeds to use our ebooks in your personal software projects.</p>
							</li>
						</ul>
					</li>
					<li>
						<p>Access to <a href="/bulk-downloads">bulk ebook downloads</a> to easily download whole collections of ebooks in our catalog of over <?= number_format($ebookCount) ?> ebooks.</p>
					</li>
					<li>
						<p>The ability to submit a book for inclusion on our <a href="/contribute/wanted-ebooks">Wanted Ebooks list</a>, once per quarter. (Submissions must conform to our <a href="/contribute/collections-policy">collections policy</a> and are subject to approval.)</p>
					</li>
					<li>
						<p>The right to periodically vote on a selection from our <a href="/contribute/wanted-ebooks">Wanted Ebooks list</a> to choose an ebook for immediate production. The resulting ebook will be a permanent addition to our <a href="/ebooks">online catalog of free digital literature</a>.</p>
					</li>
				</ul>
			</section>
		</section>
		<section id="sponsor-an-ebook">
			<h2>Sponsor a new ebook<a class="heading-permalink" href="#sponsor-an-ebook" aria-label="Permalink"></a></h2>
			<p>Is there a book you want to see made into a beautiful digital edition? You can give the gift of literature to readers everywhere by sponsoring an addition to the Standard Ebooks catalog.</p>
			<p>Make a one-time donation based on your selection’s word count, and we’ll carefully hand-produce it into an ebook edition that meets our exacting standards. It will then become a permanent addition to our online catalog for people to read, share, and reuse free of cost or U.S. copyright restrictions. Your name will appear in the ebook’s colophon and metadata, sealing your legacy as a sponsor of the literate arts.</p>
			<p>These are the general rules:</p>
			<ul>
				<li>
					<p>Your selection must conform to our <a href="/contribute/collections-policy">collections policy</a>.</p>
				</li>
				<li>
					<p>Your selection must have a usable transcription available online, for example at <a href="https://www.gutenberg.org">Project Gutenberg</a> or <a href="https://en.wikisource.org">Wikisource</a>.</p>
				</li>
				<li>
					<p>Standard Ebooks is the sole decisionmaker regarding all aspects of ebook editing and production. Trust us to create an artifact of beauty.</p>
				</li>
			</ul>
			<p><b>Sponsoring a new ebook of your choice calls for a donation of $900 + $0.02 per word over the first 100,000.</b> Ebooks that are especially complex or that require significant advanced formatting may call for an additional donation. Your selection’s word count will be calculated using the <a href="/tools">S.E. toolset</a>, and includes front and back matter like forewords and endnotes.</p>
			<p><a href="/about#editor-in-chief">Contact the S.E. Editor-in-Chief</a> to start a sponsored ebook project.</p>
		</section>
		<section id="corporate-sponsors">
			<h2>Corporate sponsorships<a class="heading-permalink" href="#corporate-sponsors" aria-label="Permalink"></a></h2>
			<p>Sponsorships at the corporate level are a great way to show your organization’s commitment to supporting the literate arts.</p>
			<ul>
				<li>
					<p>Your organization’s logo and a link will be <a href="/about#corporate-sponsors">listed on our masthead</a>, which is prominently linked to on the Standard Ebooks website’s header and footer.</p>
				</li>
				<li>
					<p>Mentions in each of our once-monthly new releases newsletters, as well as in our occasional general newsletter.<? if($newsletterSubscriberCount > 100){ ?> These newsletters reach over <?= number_format($newsletterSubscriberCount) ?> subscribers who are devoted lovers of literature.<? } ?></p>
				</li>
				<li>
					<p>Get access to our OPDS, Atom, and RSS <a href="/feeds">ebook feeds</a> for use by your organization for the duration of your sponsorship. We can also produce different kinds of feeds to meet your needs, like <abbr class="acronym">ONIX</abbr> feeds.</p>
				</li>
				<li>
					<p>Get access to <a href="/bulk-downloads">bulk ebook downloads</a> to easily download large categories of ebooks, all at once.</p>
				</li>
			</ul>
			<p>To inquire about sponsorship options, contact the <a href="/about#editor-in-chief">Standard Ebooks Editor-in-Chief</a>.</p>
		</section>
	</section>
</main>
<?= Template::Footer() ?>
