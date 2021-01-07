<?
require_once('Core.php');
?><?= Template::Header(['title' => 'Subscribe to the Standard Ebooks newsletter', 'highlight' => 'newsletter', 'description' => 'Subscribe to the Standard Ebooks newsletter to receive occasional updates about the project.']) ?>
<main>
	<article class="has-hero">
		<hgroup>
			<h1>Subscribe to the Newsletter</h1>
			<h2>to receive missives from the forefront of digital literature</h2>
		</hgroup>
		<picture>
			<source srcset="/images/the-newsletter@2x.avif 2x, /images/the-newsletter.avif 1x" type="image/avif"/>
			<source srcset="/images/the-newsletter@2x.jpg 2x, /images/the-newsletter.jpg 1x" type="image/jpg"/>
			<img src="/images/the-newsletter@2x.jpg" alt="An old man in Renaissance-era costume reading a sheet of paper."/>
		</picture>
		<p>Subscribe to receive news, updates, and more from Standard Ebooks. Your information will never be shared, and you can unsubscribe at any time.</p>
		<form action="https://standardebooks.us7.list-manage.com/subscribe/post?u=da307dcb73c74f6a3d597f056&amp;id=f8832654aa" method="post">
			<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
			<div class="anti-spam" aria-hidden="true"><input type="text" name="b_da307dcb73c74f6a3d597f056_f8832654aa" tabindex="-1" value=""/></div>
			<label class="email">Email
				<input type="email" name="EMAIL" value="" required="required"/>
			</label>
			<label class="text">First name
				<input type="text" name="FNAME" value=""/>
			</label>
			<label class="text">Last name
				<input type="text" name="LNAME" value=""/>
			</label>
			<fieldset>
				<p>What kind of email would you like to receive?</p>
				<ul>
					<li>
						<label class="checkbox"><input type="checkbox" value="1" name="group[78748][1]" checked="checked"/>The occasional Standard Ebooks newsletter</label>
					</li>
					<li>
						<label class="checkbox"><input type="checkbox" value="2" name="group[78748][2]"/>A monthly summary of new ebook releases</label>
					</li>
				</ul>
			</fieldset>
			<fieldset>
				<p>What email format do you prefer?</p>
				<ul>
					<li>
						<label class="checkbox"><input type="radio" value="html" name="EMAILTYPE" checked="checked"/>I don’t know</label>
					</li>
					<li>
						<label class="checkbox"><input type="radio" value="html" name="EMAILTYPE"/>HTML</label>
					</li>
					<li>
						<label class="checkbox"><input type="radio" value="text" name="EMAILTYPE"/>Plain text</label>
					</li>
				</ul>
			</fieldset>
			<button>Subscribe</button>
		</form>
	</article>
</main>
<?= Template::Footer() ?>
