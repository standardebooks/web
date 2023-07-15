<?
$newsletterSubscriberCount = floor(Db::QueryInt('
						SELECT count(*)
						from NewsletterSubscriptions
						where IsConfirmed = true
					') / 100) * 100;

?><?= Template::Header(['title' => 'Donate', 'highlight' => 'donate', 'description' => 'Donate to Standard Ebooks.']) ?>
<main>
	<section class="donate narrow has-hero">
		<hgroup>
			<h1>Donate to Standard Ebooks</h1>
			<h2>and help bring the beauty of literature to the digital age</h2>
		</hgroup>
		<?= Template::DonationCounter(['autoHide' => false, 'showDonateButton' => false]) ?>
		<?= Template::DonationProgress(['autoHide' => false, 'showDonateButton' => false]) ?>
		<picture data-caption="The Quiet Hour. Albert Chevallier Tayler, 1925">
			<source srcset="/images/the-quiet-hour@2x.avif 2x, /images/the-quiet-hour.avif 1x" type="image/avif"/>
			<source srcset="/images/the-quiet-hour@2x.jpg 2x, /images/the-quiet-hour.jpg 1x" type="image/jpg"/>
			<img src="/images/the-quiet-hour@2x.jpg" alt="An oil painting of a women reading a book by a writing desk at twilight."/>
		</picture>
		<section id="donate">
			<p>We work hard to create beautifully-formatted ebooks that are free of cost and free of U.S. copyright restrictions. It takes a team of highly-skilled specialists working together for weeks and even months to ensure that an ebook meets our exacting aesthetic and technical standards, before it’s offered to readers for free.</p>
			<p>The cost of producing even the most basic ebook is estimated to approach almost a thousand dollars; and that doesn’t include infrastructure costs like developing and maintaining our <a href="/tools">free and open source ebook production toolset</a>, editing our <a href="/manual">free Manual of Style</a>, project management, hosting, or bandwidth. <em>This means your financial contribution is critically important for us to continue bringing beautifully-presented literature to readers in the digital age.</em></p>
			<p><a href="https://www.fracturedatlas.org/">Fractured Atlas</a>, a 501(c)(3) public charity, is our fiscal sponsor. Your donation will be processed through their website, and their name will appear on your financial statements and receipts. Donations are tax-deductible to the extent permitted by law.</p>
			<p class="button-row">
				<a href="https://fundraising.fracturedatlas.org/standard-ebooks/general_support" class="button">Make a one-time donation</a>
				<a href="https://fundraising.fracturedatlas.org/standard-ebooks/monthly_support" class="button">Start a monthly donation</a>
			</p>
		</section>
		<section id="patrons-circle">
			<h2>Join the Patrons Circle</h2>
			<p>Members of the Patrons Circle are steadfast supporters of free, unrestricted, and beautifully presented digital literature. Besides helping support the creation of free, gorgeous ebooks, they also have a direct voice in shaping the future of the Standard Ebooks catalog.</p>
			<p>Membership in the Patrons Circle is limited to individuals only. Organizations, please see <a href="#corporate-sponsors">corporate sponsorship</a> instead.</p>
			<p><i>Join the Patrons Circle with a donation of $10/month or more, or join for one year with a one-time donation of $100 or more.</i></p>
			<h3>Benefits of the Patrons Circle</h3>
			<ul>
				<li>
					<p>Your name <a href="/about#patrons-circle">listed on our masthead</a>. (You can also remain anonymous if you prefer.)</p>
				</li>
				<li>
					<p>Access to our various <a href="/feeds">ebook feeds</a>:</p>
					<ul>
						<li>
							<p>Browse and download from the entire Standard Ebooks catalog directly in your ereading app using our <a href="/feeds/opds">OPDS feed</a>.</p>
						</li>
						<li>
							<p>Get notified of new ebooks in your news client with our <a href="/feeds/atom">Atom</a> or <a href="/feeds/rss">RSS</a> feeds.</p>
						</li>
						<li>
							<p>Parse and process the feeds to use our ebooks in your personal software projects.</p>
						</li>
					</ul>
				</li>
				<li>
					<p>Access to <a href="/bulk-downloads">bulk ebook downloads</a> to easily download whole collections of ebooks at once.</p>
				</li>
				<li>
					<p>The ability to submit a book for inclusion on our <a href="/contribute/wanted-ebooks">Wanted Ebooks list</a>, once per quarter. (Submissions must conform to our <a href="/contribute/collections-policy">collections policy</a> and are subject to approval.)</p>
				</li>
				<li>
					<p>The right to periodically vote on a selection from our <a href="/contribute/wanted-ebooks">Wanted Ebooks list</a> to choose an ebook for immediate production. The resulting ebook will be a permanent addition to our <a href="/ebooks">online catalog of free digital literature</a>.</p>
				</li>
			</ul>
			<p><strong>Important:</strong> We need to know your email address to be able to log you in to the Patrons Circle. Make sure to select either “List my name publicly” or “Don’t list publicly, but reveal to project” during checkout to be able to log in to the Patrons Circle.</p>
			<p class="button-row">
				<a href="https://fundraising.fracturedatlas.org/standard-ebooks/monthly_support" class="button">Donate $10/month or more</a>
				<a href="https://fundraising.fracturedatlas.org/donor_intents/new?donation_intent=cd005756-7327-463d-bd53-a08acc5eaa4a" class="button">Donate $100 or more</a>
			</p>
		</section>
		<section id="sponsor-an-ebook">
			<h2>Sponsor an ebook of your choice</h2>
			<p>Is there a book you want to see made into a beautiful digital edition? You can give the gift of literature to readers everywhere by sponsoring an addition to the Standard Ebooks catalog.</p>
			<p>Donate at one of the levels below and a book you select will be carefully hand-produced into an ebook edition that meets our exacting standards, before becoming a permanent addition to our online catalog for the world to read, share, and reuse free of cost or U.S. copyright restrictions. Your name will appear in the ebook’s colophon and metadata, sealing your legacy as a sponsor of the literate arts.</p>
			<p><em>Before you make your donation</em>, <a href="/about#editor-in-chief">contact the S.E. Editor-in-Chief</a> to confirm that your ebook selection will be accepted at your chosen donation level. These are the general rules:</p>
			<ul>
				<li>
					<p>Your selection must conform to our <a href="/contribute/collections-policy">collections policy</a>.</p>
				</li>
				<li>
					<p>Your selection must have a usable transcription available online, for example at <a href="https://www.gutenberg.org">Project Gutenberg</a> or <a href="https://en.wikisource.org">Wikisource</a>. In general, Standard Ebooks doesn’t create new transcriptions or work with raw <a href="https://en.wikipedia.org/wiki/Optical_character_recognition">O.C.R.</a> output.</p>
				</li>
				<li>
					<p>Your selection’s word count will be calculated from the source transcription using the <a href="/tools">S.E. toolset</a>, and includes front and back matter like forewords and endnotes. <a href="/about#editor-in-chief">Contact the S.E. Editor-in-Chief</a> before donating so that we can calculate your selection’s word count for you.</p>
				</li>
				<li>
					<p>Standard Ebooks is the sole decisionmaker regarding all aspects of ebook editing and production. Trust us to create an artifact of beauty.</p>
				</li>
			</ul>
			<table>
				<tbody>
					<tr>
						<td>
							<p>Your choice of ebook of fewer than 100,000 words</p>
							<p>(Like <i><a href="/ebooks/e-m-forster/a-room-with-a-view">A Room With a View</a></i> by <a href="/ebooks/e-m-forster">E. M. Forster</a>)</p>
						</td>
						<td>
							<a href="https://fundraising.fracturedatlas.org/donor_intents/new?donation_intent=093527b1-b87d-4e25-b750-5abfc4bb6c5a" class="button">Make a $800 donation</a>
						</td>
					</tr>
					<tr>
						<td>
							<p>Your choice of ebook of 100,000–150,000 words</p>
							<p>(Like <i><a href="/ebooks/charles-dickens/a-tale-of-two-cities">A Tale of Two Cities</a></i> by <a href="/ebooks/charles-dickens">Charles Dickens</a>)</p>
						</td>

						<td>
							<a href="https://fundraising.fracturedatlas.org/donor_intents/new?donation_intent=d9d8e346-20a9-411c-84eb-cdd29af8d540" class="button">Make a $950 donation</a>
						</td>
					</tr>
					<tr>
						<td>
							<p>Your choice of ebook of 150,000–200,000 words</p>
							<p>(Like <i><a href="/ebooks/jane-austen/emma">Emma</a></i> by <a href="/ebooks/jane-austen">Jane Austen</a>)</p>
						</td>

						<td>
							<a href="https://fundraising.fracturedatlas.org/donor_intents/new?donation_intent=9eff9db9-8c89-428e-ab9b-58591bb33242" class="button">Make a $1,100 donation</a>
						</td>
					</tr>
					<tr>
						<td>
							<p>Your choice of ebook of 200,000–250,000 words</p>
							<p>(Like <i><a href="/ebooks/fyodor-dostoevsky/crime-and-punishment/constance-garnett">Crime and Punishment</a></i> by <a href="/ebooks/fyodor-dostoevsky">Fyodor Dostoevsky</a>)</p>
						</td>

						<td>
							<a href="https://fundraising.fracturedatlas.org/donor_intents/new?donation_intent=ee08936d-9078-4806-b6be-db4788a130fb" class="button">Make a $1,250 donation</a>
						</td>
					</tr>
					<tr>
						<td>
							<p>Your choice of ebook of 250,000–300,000 words</p>
							<p>(Like <i>Ulysses</i> by <a href="https://standardebooks.org/ebooks/james-joyce">James Joyce</a>)</p>
						</td>

						<td>
							<a href="https://fundraising.fracturedatlas.org/donor_intents/new?donation_intent=5ec01925-6404-40a3-81e4-d8eae07ba505" class="button">Make a $1,400 donation</a>
						</td>
					</tr>
				</tbody>
			</table>
		</section>
		<section id="corporate-sponsors">
			<h2>Corporate sponsorships</h2>
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
		<aside>
			<p>Standard Ebooks is a sponsored project of <a href="https://www.fracturedatlas.org/">Fractured Atlas</a>, a non-profit arts service organization. Contributions for the charitable purposes of Standard Ebooks must be made payable to “Fractured Atlas” only and are tax-deductible to the extent permitted by law.</p>
		</aside>
	</section>
</main>
<?= Template::Footer() ?>
