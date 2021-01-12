<?
require_once('Core.php');
?><?= Template::Header(['title' => 'How to Use Our Ebooks', 'highlight' => 'contribute', 'description' => 'Help, tips, and tricks for using the ebook files you download from Standard Ebooks.']) ?>
<main>
	<article>
		<h1>How to Use Our Ebooks</h1>
		<section id="which-file-to-download">
			<h2>Which File Should I Download?</h2>
			<p>We offer four different kinds of ebook file for you to download. Which one you pick depends on which ereading device and ereading software you’re using.</p>
			<ul>
				<li>
					<p><b>Amazon Kindle devices and software</b> (but not the Kindle application for iOS): download the <b>azw3</b> file. You can optionally download the Kindle cover thumbnail if you’d like the ebook cover to appear in your library. (Thanks to a long-standing bug in the Kindle software, side-loaded ebooks don’t display cover images automatically. <a href="https://www.amazon.com/help/">Complain to Amazon.</a>)</p>
				</li>
				<li>
					<p><b>Kobo devices and software</b>: download the <b>kepub</b> file. This file is specially prepared for the best reading experience on Kobos.</p>
				</li>
				<li>
					<p><b>Other ereaders</b>: download the <b>compatible epub</b> file. This compatible file has been prepared to work in all ereaders, with the exception of Kindles and extremely old ereader devices. Note that while this file will also work on Kobo devices and software, it will lack some functionality and look worse than our specially-prepared kepub file.</p>
				</li>
				<li>
					<p>For advanced ereaders like <a href="https://readium.org/about/applications.html/">Readium</a>, you can download the <b>advanced epub</b> file. The advanced epub file uses the latest technology that isn’t yet supported by most ereaders, so only download this file if you know what you’re doing.</p>
				</li>
			</ul>
		</section>
		<section id="transferring-to-your-ereader">
			<h2>Transferring Ebooks to Your Ereader</h2>
			<p>Once you’ve downloaded the file most appropriate for your ereader, you’ll need to transfer that file to your device.</p>
			<p>Kindle users should also see our <a href="#kindle-faq">Kindle FAQ</a> at the bottom of this page.</p>
			<ul>
				<li id="kindle">
					<h3>Amazon Kindle eInk Devices (Paperwhite, Voyage, Oasis, etc. <em>except</em> DX)</h3>
					<p><b>Important:</b> You can’t use “Send to Kindle” to transfer our azw3 file. <a href="#kindle-faq">This is a bug in Amazon’s software.</a> You <em>must</em> transfer our azw3 ebooks to Kindles with a USB cable.</p>
					<ol>
						<li>
							<p>Using a USB cable, connect your Kindle to the computer you downloaded the azw3 file to. Your Kindle will appear as a USB drive that you can browse.</p>
						</li>
						<li>
							<p>Navigate to the <code class="path">documents</code> folder on your Kindle, then drag and drop the azw3 file into the <code class="path">documents</code> or <code class="path">ebooks</code> folder.</p>
						</li>
						<li>
							<p>If you downloaded the Kindle cover thumbnail, now navigate to your Kindle’s <code class="path">system</code> folder.</p>
							<p><i>If you don’t see a <code class="path">system</code> folder when your Kindle is plugged in, you may have to tell your computer to show hidden system files. If you’re using Windows, you may also have to show protected operating system files. To do that, see <a href="https://www.howtogeek.com/howto/windows-vista/show-hidden-files-and-folders-in-windows-vista/">these instructions for Windows</a> and <a href="https://www.lifewire.com/display-hidden-files-in-os-x-153332">these instructions for Mac</a>.</i></p>
							<p>Once you’re in the <code class="path">system</code> folder, find the <code class="path">thumbnails</code> folder and drag and drop the thumbnail into there.</p>
							<p><em>Make sure to copy the thumbnail <strong>after</strong> copying the azw3 ebook file.</em> Due to a bug in the Kindle software, your Kindle will use a default cover for your ebook if you don’t copy the thumbnail file over. <a href="https://www.amazon.com/help/">Complain to Amazon.</a></p>
						</li>
						<li>
							<p>Eject the Kindle from your computer using your system’s “Safely remove drive” option. Your ebook should now be visible!</p>
						</li>
					</ol>
				</li>
				<li id="kindle-dx">
					<h3>Kindle DX Devices</h3>
					<p>Kindle DX devices don’t support Amazon’s own azw3 format. Instead, download the compatible epub file and use <a href="https://calibre-ebook.com">Calibre</a> to convert to mobi and transfer to your device.</p>
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
					<p><strong>Important:</strong> <em>Don’t use Calibre to transfer the kepub file!</em> Calibre will apply its own conversion <em>on top of</em> our own conversion, making for strange results.</p>
					<ol>
						<li>
							<p>Using a USB cable, connect your Kobo to the computer you downloaded the kepub file to. Your Kobo will appear as a USB drive that you can browse using your computer’s file manager.</p>
						</li>
						<li>
							<p>Navigate to the Kobo drive and drag and drop the kepub file in. <em>Don’t change the filename of the ebook!</em> Kobo requires that the filename end in <code class="path">.kepub.epub</code>.</p>
						</li>
						<li>
							<p>Eject the Kobo from your computer using your system’s “Safely remove drive” option. Your ebook should now be visible!</p>
						</li>
					</ol>
					<h4>If you’ve downloaded a regular epub file</h4>
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
							<p>Eject the Kindle from your computer using your system’s “Safely remove drive” option. Your ebook should now be visible!</p>
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
					<p>We need help collecting instructions for transferring files to other devices. If you have a non-Kindle device, <a href="/contribute">get in touch and help write these instructions</a>!</p>
				</li>
			</ul>
		</section>
		<section id="kindle-faq">
			<h2>Kindle FAQ</h2>
			<ul>
				<li>
					<p><b>How do I transfer ebooks to my Kindle?</b></p>
					<p>Scroll up to see!</p>
				</li>
				<li>
					<p><b>Why don’t you provide mobi files <em>instead of</em> azw3 files?</b></p>
					<p>Mobi ebooks are an older ebook file format, and they don’t support many basic features you’d expect from a beautiful ebook, like hyphenation. Since the file format is so old—dating back to 2007—its support for the latest ebook technology is poor, making it much harder for us to produce well-formatted ebooks in that file format.</p>
					<p>There’s a newer version of the mobi ebook file format that <em>can</em> contain advanced formatting. But, if you use Kindle’s “Send to Kindle” feature, it silently removes that advanced formatting, turning the file you sent into an older mobi version that looks really bad. When that happens, <em>we</em> get the blame for a bad ebook, even though it was Kindle’s fault for quietly butchering our carefully produced file.</p>
					<p>In 2011 Amazon released a new ebook format, azw3, that they created, and that is only compatible with Kindle devices. This format allows for fairly good formatting, including hyphenation and kerning, so we chose to use that format to bring you the best reading experience possible.</p>
				</li>
				<li>
					<p><b>Why don’t you provide mobi files <em>in addition to</em> azw3 files?</b></p>
					<p>Standard Ebooks is a small, volunteer-led project, and we don’t have the time or resources to support a second proprietary file type just because Amazon can’t get its act together. We have time for one or the other, and azw3 is the technically superior format that provides the better reading experience.</p>
				</li>
				<li>
					<p><b>Why can’t I use “Send to Kindle” to send an azw3 file to my Kindle?</b></p>
					<p>We don’t know! You’d think that Amazon would allow you to send the very file format it invented to its own devices. But Amazon hasn’t made it possible to send azw3 files via “Send to Kindle,” even though they surely could. You should <a href="https://www.amazon.com/help/">complain to Amazon</a>, or vote with your wallet and buy a better ereader.</p>
				</li>
				<li>
					<p><b>I just can’t deal with azw3 files, I need a mobi file!</b></p>
					<p>You can use the excellent <a href="https://calibre-ebook.com">Calibre ebook management software</a> to convert from azw3 to mobi. But note that when you do that, all bets are off as to how your ebook is going to look. Calibre usually does a good job, but don’t blame us if your ebook doesn’t look great after an automatic file format conversion.</p>
				</li>
			</ul>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
