<?
require_once('Core.php');
?><?= Template::Header(['title' => 'About Standard Ebooks', 'highlight' => 'about', 'description' => 'The Standard Ebooks project is a volunteer driven, not-for-profit effort to produce a collection of high quality, carefully formatted, accessible, open source, and free public domain ebooks that meet or exceed the quality of commercially produced ebooks. The text and cover art in our ebooks is already believed to be in the public domain, and Standard Ebook dedicates its own work to the public domain, thus releasing the entirety of each ebook file into the public domain.']) ?>
<main>
	<h1>Subscribe to the Newsletter</h1>

	<form action="https://standardebooks.us7.list-manage.com/subscribe/post?u=da307dcb73c74f6a3d597f056&amp;id=f8832654aa" method="post">

			<label class="search">Email
				<input type="email" name="EMAIL" value="" required="required" />
			</label>


	    <div id="mc_embed_signup_scroll">

	<div class="mc-field-group">
		<label for="mce-FNAME">First Name </label>
		<input type="text" value="" name="FNAME" class="" id="mce-FNAME"/>
	</div>
	<div class="mc-field-group">
		<label for="mce-LNAME">Last Name </label>
		<input type="text" value="" name="LNAME" class="" id="mce-LNAME"/>
	</div>
	<div class="mc-field-group input-group">
	    <strong>What kind of email would you like to receive? </strong>
	    <ul><li><input type="checkbox" value="1" name="group[78748][1]" id="mce-group[78748]-78748-0"/><label for="mce-group[78748]-78748-0">The occasional Standard Ebooks newsletter</label></li>
	<li><input type="checkbox" value="2" name="group[78748][2]" id="mce-group[78748]-78748-1"/><label for="mce-group[78748]-78748-1">A monthly summary of new releases</label></li>
	</ul>
	</div>
	<div class="mc-field-group input-group">
	    <strong>Email Format </strong>
	    <ul><li><input type="radio" value="html" name="EMAILTYPE" id="mce-EMAILTYPE-0" /><label for="mce-EMAILTYPE-0">html</label></li>
	<li><input type="radio" value="text" name="EMAILTYPE" id="mce-EMAILTYPE-1"/><label for="mce-EMAILTYPE-1">text</label></li>
	</ul>
	</div>
		   <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
	    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_da307dcb73c74f6a3d597f056_f8832654aa" tabindex="-1" value="" /></div>
	    <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button" /></div>
	    </div>

	</form>
</main>
<?= Template::Footer() ?>
