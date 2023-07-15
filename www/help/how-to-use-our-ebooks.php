<?= Template::Header(['title' => 'How to Use Our Ebooks', 'highlight' => 'contribute', 'manual' => true, 'description' => 'Help, tips, and tricks for using the ebook files you download from Standard Ebooks.']) ?>
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
					<p>The <b>advanced epub</b> files are not yet suitable for general use. They use the latest technology that isn’t yet supported by most ereaders, so they’re best used as benchmarks for developers working on ereading systems, or for advanced ereaders like ones based on the <a href="https://readium.org/awesome-readium/">Readium toolkit</a>, instead of for the general reading public.</p>
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
					<p><b>Important:</b> You can’t use “Send to Kindle” to transfer our azw3 file to your Kindle. <a href="#kindle-faq">This is a bug in Amazon’s software.</a></p>
					<h4 id="kindle-method-1">Method 1—Using a USB Cable</h4>
					<ol>
						<li>
							<p>Select an ebook from Standard Ebooks and download its azw3 file to a computer.</p>
						</li>
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
					<h4 id="kindle-method-2">Method 2—Download Directly to Kindle</h4>
					<p>This method won’t add the ebook to your Kindle library, but your Kindle will remember your reading position.</p>
					<p>This method also won’t add include a cover image. If you want a cover image, use <a href="#kindle-method-1">method 1</a>.</p>
					<ol>
						<li>
							<p>From your Kindle’s home screen, tap the 3 dots in the upper-right corner.</p>
						</li>
						<li>
							<p>Select “Web Browser.”</p>
						</li>
						<li>
							<p>Navigate to the Standard Ebooks website.</p>
						</li>
						<li>
							<p>Choose an ebook, and swipe down to the downloads section for that ebook. Choose the azw3 file.</p>
						</li>
						<li>
							<p>In the download box that pops up, tap “OK.”</p>
						</li>
					</ol>
				</li>
				<li id="kindle-dx">
					<h3>Kindle DX Devices</h3>
					<p>Kindle DX devices don’t support Amazon’s own azw3 format. Instead, download the compatible epub file and use <a href="https://calibre-ebook.com">Calibre</a> to convert to mobi and transfer to your device.</p>
				</li>
				<li id="kindle-fire">
					<h3>Amazon Kindle Fire Devices</h3>
					<h4>Method 1—Direct Download</h4>
					<p>This method will not add the book to your Kindle library so it will not remember your reading position.</p>
					<ol>
						<li>
							<p>Use your device’s web browser to download the azw3 file you want to read.</p>
						</li>
						<li>
							<p>Tap the 3 dots in the upper-right hand corner, select Downloads, and the book you downloaded.</p>
						</li>
					</ol>
					<h4>Method 2—Download to a Computer</h4>
					<p>This method will add the book to your Kindle library.</p>
					<ol>
						<li>
							<p>Using a USB cable, connect your Kindle to the computer you downloaded the azw3 file to. Your Kindle will appear as a USB drive that you can browse.</p>
						</li>
						<li>
							<p>Navigate to the <code class="path">Books</code> folder on your Kindle, then drag and drop the azw3 file into the <code class="path">Books</code> folder.</p>
						</li>
					</ol>
				</li>
				<li id="kobo">
					<h3>Kobo eInk Devices</h3>
					<h4>If you’ve downloaded a kepub file</h4>
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
							<p>Eject the Kobo from your computer using your system’s “Safely remove drive” option. Your ebook should now be visible!</p>
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
				<li id="sony-prs-t2">
					<h3>Sony PRS-T2</h3>
					<ol>
						<li>
							<p>Using a USB cable, connect your Sony PRS-T2 to the computer you downloaded the epub file to.</p>
						</li>
						<li>
							<p>Download, install, and open the Sony Reader for PC software.</p>
						</li>
						<li>
							<p>Drag the epub file you downloaded into the Books tab of the Reader for PC software, and then Sync.</p>
						</li>
					</ol>
				</li>
				<li id="sony-prs-300">
					<h3>Sony PRS-300</h3>
					<ol>
						<li>
							<p>Using a USB cable, connect your Sony PRS-300 to the computer you downloaded the epub file to. Your PRS-300 will appear as a USB drive that you can browse.</p>
						</li>
						<li>
							<p>Navigate to the <code class="path">./database/media/books/</code> folder on the PRS-300 drive and drag and drop the epub file in.</p>
						</li>
						<li>
							<p>Eject the PRS-300 from your computer using your system’s “Safely remove drive” option. Your ebook should now be visible!</p>
						</li>
					</ol>
				</li>
				<li id="other">
					<h3>Other Devices</h3>
					<p>We need help collecting instructions for transferring files to other devices. If you have a non-Kindle device, <a href="/contribute">get in touch and help write these instructions</a>!</p>
				</li>
			</ul>
		</section>
		<section id="kobo-faq">
			<h2>Kobo FAQ</h2>
			<ul>
				<li>
					<p><b>Why do you offer a separate kepub file, when Kobos can open S.E.’s compatible epub files?</b></p>
					<p>It’s true that Kobos can open and read regular epub files like the compatible epubs we offer for download. But doing so triggers Kobo’s bad ebook renderer, which is based on A.D.E. Your ebooks are going to look bad because the renderer Kobo selects for plain epubs is bad.</p>
					<p>When Kobo opens kepub files—which are still epub files, but specially prepared with extra Kobo-specific markup—Kobo uses their advanced Webkit-based renderer to render the ebook. This renderer is very good, and has support for a lot of advanced ebook rendering features that our specially-prepared kepub files take advantage of. Your ebooks will look very good when you use the kepub files instead of the compatible epub files.</p>
					<p>We very much wish that Kobo would allow plain epubs to be presented to readers using their good renderer, and not A.D.E. You can <a href="https://help.kobo.com/hc/en-us/requests/new">contact Kobo</a> to let them know you’d like that, too.</p>
				</li>
			</ul>
		</section>
		<section id="kindle-faq">
			<h2>Kindle FAQ</h2>
			<ul>
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
					<p><b>I heard that Kindles support epubs now. Is that true?</b></p>
					<p>No, Kindle devices still do not natively support epub files.</p>
					<p>It’s true that Amazon’s Send to Kindle feature now <em>accepts</em> epubs. But, when you use the Send to Kindle feature to send an epub to your Kindle device, Amazon actually converts the epub file to an Amazon format before delivering it to your device. We have no control over this conversion, so chances are it’s not going to look great.</p>
					<p>It’s still not possible to use a USB cable to transfer an epub file to your Kindle device.</p>
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
