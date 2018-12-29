<?
require_once('Core.php');
?><?= Template::Header(['title' => 'How to Use Our Ebooks', 'highlight' => 'contribute', 'description' => 'Help, tips, and tricks for using the ebook files you download from Standard Ebooks.']) ?>
<main>
	<article>
		<h1>How to Use Our Ebooks</h1>
		<section id="which-file-to-download">
			<h2>Which File Do I Download?</h2>
			<p>We offer four different kinds of ebook file for you to download. Which one you pick depends on which ereading device and ereading software you’re using.</p>
			<ul>
				<li>
					<p>For <b>most ereaders</b>, <em>except</em> Amazon Kindle and Kobo devices and software: download the <b>epub</b> file. This epub2-compatible file will work in all ereaders except Kindles. While this file will work on Kobo, it’ll look pretty bad; please download our special Kobo-compatible epub file for Kobo devices and software.</p>
				</li>
				<li>
					<p>For <b>Amazon Kindle ereading software or devices</b>: download the <b>azw3</b> file. You can optionally download the Kindle cover thumbnail if you’d like the ebook cover to appear in your library. (Thanks to a long-standing bug in the Kindle software, side-loaded ebooks don’t display cover images automatically. <a href="https://www.amazon.com/help/">Complain to Amazon.</a>)</p>
				</li>
				<li>
					<p>For <b>Kobo ereading software or devices</b>: download the <b>kepub</b> file. This file is specially prepared to present the best reading experience for Kobos.</p>
				</li>
				<li>
					<p>For advanced ereaders like <a href="https://chrome.google.com/webstore/detail/readium/fepbnnnkkadjhjahcafoaglimekefifl">Readium</a>, and for readers who like bleeding-edge technology: download the <b>pure epub3</b> file. Pure epub3 isn’t compatible with most ereaders yet, so only download this file if you know what you’re doing.</p>
				</li>
			</ul>
		</section>
		<section id="transferring-to-your-ereader">
			<h2>Transferring Ebooks to Your Ereader</h2>
			<p>Once you’ve downloaded the file most appropriate for your ereader, you’ll need to transfer that file to your device.</p>
			<ul>
				<li id="kindle">
					<h3>Amazon Kindle eInk Devices (Paperwhite, Voyage, Oasis, etc. <em>except DX</em>)</h3>
					<ol>
						<li>
							<p>Using a USB cable, connect your Kindle to the computer you downloaded the azw3 file to. Your Kindle will appear as a USB drive that you can browse.</p>
						</li>
						<li>
							<p>Navigate to the <code class="path">documents</code> folder on your Kindle, then drag and drop the azw3 file into the <code class="path">documents</code> or <code class="path">ebooks</code> folder.</p>
						</li>
						<li>
							<p>If you downloaded the Kindle cover thumbnail, now navigate to your Kindle’s <code class="path">system</code> folder.</p>
							<p><i><strong>If you don’t see a <code class="path">system</code> folder when your Kindle is plugged in</strong>, you may have to tell your computer to show hidden system files. For instructions on how to do that, <a href="http://windows.microsoft.com/en-us/windows/show-hidden-files">see here for Windows</a> or <a href="https://www.lifewire.com/display-hidden-files-in-os-x-153332">see here for Mac</a>.</i></p>
							<p>Once you’re in the <code class="path">system</code> folder, find the <code class="path">thumbnails</code> folder and drag and drop the thumbnail into there. <em>Make sure to copy the thumbnail <strong>after</strong> copying the azw3 ebook file.</em> Due to a bug in the Kindle software, your Kindle will use a default cover for your ebook if you don’t copy the thumbnail file over. <a href="https://www.amazon.com/help/">Complain to Amazon.</a></p>
						</li>
						<li>
							<p>Eject the Kindle from your computer using your system’s “Safely remove drive” option. Your ebook should now be visible!</p>
						</li>
					</ol>
				</li>
				<li id="kindle-dx">
					<h3>Kindle DX Devices</h3>
					<p>Kindle DX devices don’t support Amazon’s latest azw3 format. Instead, download the epub file and use Calibre to convert to mobi and transfer to your device.</p>
				</li>
				<li id="kindle-fire">
					<h3>Amazon Kindle Fire Devices</h3>
					<ol>
						<li>
							<p>Use your device’s web browser to download the azw3 file you want to read.</p>
						</li>
						<li>
							<p>Tap the 3 dots in the upper-right hand corner, select Downloads, and the book you downloaded.</p>
						</li>
					</ol>
				</li>
				<li id="kobo">
					<h3>Kobo eInk Devices</h3>
					<h4>If you’ve download a kepub file</h4>
					<p><strong><em>Don’t use Calibre to transfer the .kepub.epub file!</em></strong></p>
					<ol>
						<li>
							<p>Using a USB cable, connect your Kobo to the computer you downloaded the kepub file to. Your Kobo will appear as a USB drive that you can browse using your computer’s file manager.</p>
						</li>
						<li>
							<p>Navigate to the Kobo drive and drag and drop the kepub file in. <em>Don’t change the filename of the ebook!</em> Kobo requires that the filename end in “.kepub.epub”.</p>
						</li>
						<li>
							<p>Eject the Kobo from your computer using your system’s “Safely remove drive” option. Your ebook should now be visible!</p>
						</li>
					</ol>
					<h4>If you’ve download a regular epub file</h4>
					<p><em>These steps aren’t necessary if you’ve downloaded our special kepub file.</em></p>
					<p>The best way to transfer our epubs to your Kobo device is to download the kepub file instead of the epub file. If you prefer our epub file, then your best bet is to use the <a href="https://calibre-ebook.com">Calibre ebook management program</a> with a special plugin. Calibre knows how to talk to your Kobo device to ensure our epubs look and function optimally.</p>
					<ol>
						<li>
							<p>Download and install <a href="https://calibre-ebook.com">Calibre</a>.</p>
						</li>
						<li>
							<p>Open Calibre, and install the KoboTouchExtended plugin:</p>
							<ol>
								<li>
									<p>Click the “Preferences” button on the toolbar.</p>
								</li>
								<li>
									<p>Under the “Advanced” heading, click the “Plugins” button.</p>
								</li>
								<li>
									<p>Click the “Get new plugins” button.</p>
								</li>
								<li>
									<p>In the “Filter by name” field, enter “KoboTouchExtended” and double-click the result to install it.</p>
								</li>
								<li>
									<p>Restart Calibre.</p>
								</li>
							</ol>
						</li>
						<li>
							<p>Add the epub file you downloaded to your Calibre library by clicking the “Add books” button on the toolbar.</p>
						</li>
						<li>
							<p>Using a USB cable, connect your Kobo device to the computer you downloaded the epub file to.</p>
						</li>
						<li>
							<p>Highlight the ebook in your library and click the “Send to device” button on the toolbar.</p>
						</li>
						<li>
							<p>Eject your Kobo and you’re ready to read!</p>
						</li>
					</ol>
				</li>
				<li id="ibooks">
					<h3>iPad/iBooks</h3>
					<ol>
						<li>
							<p>Open the download page for the ebook you’d like to read and tap on the epub download link.</p>
						</li>
						<li>
							<p>On the download screen that appears, tap “open in iBooks”.</p>
						</li>
					</ol>
				</li>
				<li id="nook">
					<h3>Barnes &amp; Noble Nook eInk Devices (Nook Glowlight, Nook Glowlight Plus, Nook Simple Touch, etc.)</h3>
					<ol>
						<li>
							<p>Using a USB cable, connect your Nook to the computer you downloaded the epub file to. Your Nook will appear as a USB drive that you can browse.</p>
						</li>
						<li>
							<p>Navigate to the Nook drive and drag and drop the epub file in.</p>
						</li>
						<li>
							<p>Eject the Nook from your computer using your system’s “Safely remove drive” option. Your ebook should now be visible!</p>
						</li>
					</ol>
				</li>
				<li id="sony">
					<h3>Sony PRS-T2</h3>
					<ol>
						<li>
							<p>Using a USB cable, connect your Sony PRS-T2 to the computer you downloaded the epub file to.</p>
						</li>
						<li>
							<p>Download, install, and open the <a href="http://www.sony.com.au/support/download/469196">Sony Reader for PC</a> software.</p>
						</li>
						<li>
							<p>Drag the epub file you downloaded into the Books tab of the Reader for PC software, and then Sync.</p>
						</li>
					</ol>
				</li>
				<li id="other">
					<h3>Other Devices</h3>
					<p>We need help collecting instructions for transferring files to other devices. If you have a non-Kindle device, <a href="/contribute/">get in touch and help write these instructions</a>!</p>
				</li>
			</ul>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
