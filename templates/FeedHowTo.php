<section id="accessing-the-feeds">
	<h2>How to access the feeds<a class="heading-permalink" href="#accessing-the-feeds" aria-label="Permalink"></a></h2>
	<? if(Session::$User?->Benefits->CanAccessFeeds){ ?>
		<p>When prompted, enter your email address and leave the password field blank to access a feed.</p>
	<? }else{ ?>
		<p>Our <a href="/feeds/atom/new-releases">New Releases RSS/Atom ebook feeds</a> are open to everyone.</p>
		<p>Access to our other feeds is a benefit of <a href="/donate#patrons-circle">membership in our Patrons Circle</a>. You can also get access in one of the following ways:</p>
		<ul>
			<li>
				<p><a href="/donate#patrons-circle">Join the Patrons Circle</a> by making a small donation in support of our mission. Patrons have full access to our ebook feeds for the duration of their gift.</p>
			</li>
			<li>
				<p><a href="/contribute">Produce an ebook</a> for Standard Ebooks to get lifetime access to our ebook feeds. (If you’ve already done that, <a href="/about#editor-in-chief">contact us</a> to enable your access.)</p>
			</li>
			<li>
				<p><a href="/donate#corporate-sponsors">Corporate sponsors</a> get access to all of our ebook feeds for the duration of their sponsorship. <a href="/about#editor-in-chief">Contact us</a> to chat about having your organization sponsor our mission.</p>
			</li>
			<li>
				<p>Open source projects can get access to our ebook feeds if they meet certain criteria. <a href="/about#editor-in-chief">Contact us</a> to discuss your open source project.</p>
			</li>
		</ul>
		<p>
			<i>If you’re a Patrons Circle member, when prompted enter your email address and leave the password field blank to access an ebook feed.</i>
		</p>
	<? } ?>
</section>
