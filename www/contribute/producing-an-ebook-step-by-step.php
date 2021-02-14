<?
require_once('Core.php');
?><?= Template::Header(['title' => 'Producing an Ebook, Step by Step', 'manual' => true, 'highlight' => 'contribute', 'description' => 'A detailed step-by-step description of the complete process of producing an ebook for the Standard Ebooks project, start to finish.']) ?>
<main class="manual">
	<article class="step-by-step-guide">
		<h1>Producing an Ebook, Step by Step</h1>
		<p>This guide is meant to take you step-by-step through the creation of a complete Standard Ebook. While it might seem a little long, most of the text is a description of how to use various automated scripts. It can take just an hour or two for an experienced producer to produce a draft ebook for proofreading (depending on the complexity of the ebook, of course).</p>
		<p>Our toolset is GNU/Linux-based, and producing an ebook from scratch currently requires working knowledge of the epub file format and of Unix-like systems like Mac or Linux.</p>
		<p>Our toolset doesn’t yet work natively on Windows, but there are <a href="https://www.howtogeek.com/170870/5-ways-to-run-linux-software-on-windows/">many ways to run Linux from within Windows</a>.</p>
		<p>If you don’t have this kind of technical expertise, you can still contribute! <a href="/contribute">Check out our contributors page for details.</a></p>
		<ol>
			<li>
				<h2>Set up the Standard Ebooks toolset and make sure it’s up-to-date</h2>
				<p>The Standard Ebooks project has a toolset that will help you produce an ebook. The toolset installs the <code class="bash"><b>se</b></code> command, which has various subcommands related to creating Standard Ebooks. You can <a href="/tools">read the complete installation instructions</a>, or if you already have <a href="https://pipxproject.github.io/pipx/installation/"><code class="bash"><b>pipx</b></code> installed</a>, run:</p>
				<code class="terminal"><span><b>pipx</b> install standardebooks</span></code>
				<p>The toolset changes frequently, so if you’ve installed the toolset in the past, make sure to update the toolset before you start a new ebook:</p>
				<code class="terminal"><span><b>pipx</b> upgrade standardebooks</span></code>
				<p>Once the toolset is installed, you can check which version you have with:</p>
				<code class="terminal"><span><b>se</b> --version</span></code>
			</li>
			<li>
				<h2>Select an ebook to produce</h2>
				<p>The best place to look for public domain ebooks to produce is <a href="https://www.gutenberg.org">Project Gutenberg</a>. If downloading from Gutenberg, be careful of the following:</p>
				<ul>
					<li>
						<p>There may be different versions of the same publication on Gutenberg, and <em>the best one might not be the one with the most downloads</em>. In particular, there could be a better translation that has fewer downloads because it was produced later, or there could be a version with better HTML markup. A great example of this phenomenon is the Gutenberg version of <i>20,000 Leagues Under the Seas</i>. The most-downloaded version is an <a href="https://www.gutenberg.org/ebooks/164" rel="nofollow">old translation widely criticized as being slapdash and inaccurate</a>. The less popular version is a <a href="https://www.gutenberg.org/ebooks/2488">fresh, modern translation dedicated to the public domain</a>.</p>
					</li>
					<li>
						<p>Gutenberg usually offers both an HTML version and an epub version of the same ebook. Note that <em>one is not always exactly the same as the other!</em> A casual reader might assume that the HTML version is generated from the epub version, or the other way around; but for some reason the HTML and epub versions often differ in important ways, with the HTML version typically using fewer useless CSS classes, and including <code class="html"><span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span></code> elements that the epub version is often missing.</p>
					</li>
				</ul>
				<p>Picking either the HTML or the epub version is fine as a starting point, but make sure to pick the one that appears to be the most accurate.</p>
				<p>For this guide, we’ll use <i>The Strange Case of Dr. Jekyll and Mr. Hyde</i>, by Robert Louis Stevenson. If you search for it on Gutenberg, you’ll find that there are two versions; the <a href="https://www.gutenberg.org/ebooks/42" rel="nofollow">most popular one</a> is a poor choice to produce, because the transcriber included the page numbers smack in the middle of the text! What a pain those’d be to remove. The <a href="https://www.gutenberg.org/ebooks/43">less popular one</a> is a better choice to produce, because it’s a cleaner transcription.</p>
			</li>
			<li>
				<h2>Locate page scans of your book online</h2>
				<p>As you produce your book, you’ll want to check your work against the actual page scans. Often the scans contain formatting that is missing from the source transcription. For example, older transcriptions sometimes throw away italics entirely, and you’d never know unless you looked at the page scans. So finding page scans is essential.</p>
				<p>Below are some good sources for page scans:</p>
				<ul>
					<li>
						<p><a href="https://archive.org">The Internet Archive</a></p>
					</li>
					<li>
						<p><a href="https://www.hathitrust.org">The HathiTrust Digital library</a></p>
					</li>
					<li>
						<p><a href="https://books.google.com">Google Books</a></p>
					</li>
				</ul>
				<p>Each of those sources allows you to filter results by publication date, so make sure you select <?= PD_YEAR ?> and earlier to ensure they’re in the U.S. public domain.</p>
				<p>If you can’t find scans of your book at the above sources, and you’re using a Project Gutenberg transcription as source material, there’s a good chance that PGDP (the sister project of Project Gutenberg that does the actual transcriptions) <a href="https://www.pgdp.org/ols/">has a copy of the scans they used accessible in their archives</a>. You should only use the PGDP archives as a last resort; because their scans are not searchable, verifying typos becomes extremely time-consuming.</p>
				<p>Please keep the following important notes in mind when searching for page scans:</p>
				<ul>
					<li>
						<p>Make sure the scans you find are <em>published in <?= PD_YEAR ?> or earlier.</em> You <em>must verify the copyright page in the page scans</em> before proceeding.</p>
					</li>
					<li>
						<p>Often you’ll find different editions, published at different times by different publishers, for the same book. It’s worth the effort to quickly browse through each different one to get an idea of the kinds of changes the different publishers introduced. Maybe one edition is better than another!</p>
					</li>
				</ul>
				<p>You’ll enter a link to the page scans you used in the <code class="path">content.opf</code> metadata as a <code class="html"><span class="p">&lt;</span><span class="nt">dc:source</span><span class="p">&gt;</span></code> element.</p>
			</li>
			<li>
				<h2>Create a Standard Ebooks epub skeleton</h2>
				<p>An epub file is just a bunch of files arranged in a particular folder structure, then all zipped up. That means editing an epub file is as easy as editing a bunch of text files within a certain folder structure, then creating a zip file out of that folder.</p>
				<p>You can’t just arrange files willy-nilly, though—the epub standard expects certain files in certain places. So once you’ve picked a book to produce, create the basic epub skeleton in a working directory. <code class="bash"><b>se</b> create-draft</code> will create a basic Standard Ebooks epub folder structure, initialize a Git repository within it, and prefill a few fields in <code class="path">content.opf</code> (the file that contains the ebook’s metadata).</p>
				<ol>
					<li>
						<h3>With the <code class="bash">--pg-url</code> option</h3>
						<p>You can pass <code class="bash"><b>se</b> create-draft</code> the URL for the Project Gutenberg ebook, and it’ll try to download the ebook into <code class="path">./src/epub/text/body.xhtml</code> and prefill a lot of metadata for you:</p><code class="terminal"><span><b>se</b> create-draft --author=<i>"Robert Louis Stevenson"</i> --title=<i>"The Strange Case of Dr. Jekyll and Mr. Hyde"</i> --pg-url=<i>"https://www.gutenberg.org/ebooks/43"</i></span> <span><b>cd</b> <u>robert-louis-stevenson_the-strange-case-of-dr-jekyll-and-mr-hyde/</u></span></code>
						<p>Because Project Gutenberg ebooks are produced in different ways by different people, <code class="bash"><b>se</b> create-draft</code> has to make some guesses and it might guess wrong. Make sure to carefully review the data it prefills into <code class="path">./src/epub/text/body.xhtml</code>, <code class="path">./src/epub/text/colophon.xhtml</code>, and <code class="path">./src/epub/content.opf</code>.</p>
						<p>In particular, make sure that the Project Gutenberg license is stripped from <code class="path">./src/epub/text/body.xhtml</code>, and that the original transcribers in <code class="path">./src/epub/text/colophon.xhtml</code> and <code class="path">./src/epub/content.opf</code> are presented correctly.</p>
					</li>
					<li>
						<h3>Without the <code class="bash">--pg-url</code> option</h3>
						<p>If you prefer to do things by hand, that’s an option too.</p><code class="terminal"><span><b>se</b> create-draft --author=<i>"Robert Louis Stevenson"</i> --title=<i>"The Strange Case of Dr. Jekyll and Mr. Hyde"</i></span> <span><b>cd</b> <u>robert-louis-stevenson_the-strange-case-of-dr-jekyll-and-mr-hyde/</u></span></code>
						<p>Now that we have the skeleton up, we’ll download Gutenberg’s HTML file for <i>Jekyll</i> directly into <code class="path">text/</code> folder and name it <code class="path">body.xhtml</code>.</p><code class="terminal"><span><b>wget</b> -O src/epub/text/body.xhtml <i>"https://www.gutenberg.org/files/43/43-h/43-h.htm"</i></span></code>
						<p>Many Gutenberg books were produced before UTF-8 became a standard, so we may have to convert to UTF-8 before we start work. First, check the encoding of the file we just downloaded. (Mac OS users, try <code class="bash"><b>file</b> -I</code>.)</p><code class="terminal"><span><b>file</b> -bi <u>src/epub/text/body.xhtml</u></span></code>
						<p>The output is <code class="bash">text/html; charset=iso-8859-1</code>. That’s the wrong encoding!</p>
						<p>We can convert that to UTF-8 with <code class="bash"><b>iconv</b></code>:</p><code class="terminal"><span><b>iconv</b> --from-code=<i>"ISO-8859-1"</i> --to-code=<i>"UTF-8"</i> &lt; <u>src/epub/text/body.xhtml</u> &gt; src/epub/text/tmp</span> <span><b>mv</b> <u>src/epub/text/tmp</u> <u>src/epub/text/body.xhtml</u></span></code>
					</li>
				</ol>
			</li>
			<li>
				<h2>Do a rough cleanup of the source text and perform the first commit</h2>
				<p>If you inspect the folder we just created, you’ll see it looks something like this:</p>
				<figure>
					<img alt="A tree view of a new Standard Ebooks draft folder" src="/images/epub-draft-tree.png"/>
				</figure>
				<p>You can <a href="/contribute/a-basic-standard-ebooks-source-folder">learn more about what the files in a basic Standard Ebooks source folder are all about</a> before you continue.</p>
				<p>Now that we’ve got the source text, we have to do some very broad cleanup before we perform our first commit:</p>
				<ul>
					<li>
						<p>Remove the header markup and everything, including any Gutenberg text and the work title, up to the beginning of the actual public domain text. We’ll add our own header markup to replace what we’ve removed later.</p>
						<p><i>Jekyll</i> doesn’t include front matter like an epigraph or introduction; if it did, that sort of stuff would be left in, since it’s part of the main text.</p>
					</li>
					<li>
						<p>This edition of <i>Jekyll</i> includes a table of contents; remove that too. Standard Ebooks uses the <abbr class="initialism">ToC</abbr> generated by the ereader, and doesn’t include one in the readable text.</p>
					</li>
					<li>
						<p>Remove any footer text and markup after the public domain text ends. This includes the Gutenberg license—but don’t worry, we’ll credit Gutenberg in the colophon and metadata later. If you invoked <code class="bash"><b>se</b> create-draft</code> with the <code class="bash">--pg-url</code> option, then it may have already stripped the license for you and included some Gutenberg metadata.</p>
					</li>
				</ul>
				<p>Now our source file looks something like this:</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">h2</span><span class="p">&gt;</span> STORY OF THE DOOR <span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span> Mr. Utterson the lawyer was a man of a rugged countenance that was never lighted by a smile; cold, scanty and embarrassed in discourse; backward in
<span class="c">&lt;!--snip all the way to the end...--&gt;</span>
proceed to seal up my confession, I bring the life of that unhappy Henry Jekyll to an end. <span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>

				<p>Now that we’ve removed all the cruft from the top and bottom of the file, we’re ready for our first commit.</p>
				<p>Please use the following commit message for consistency with the rest of our ebooks:</p><code class="terminal"><span><b>git</b> add -A</span> <span><b>git</b> commit -m <i>"Initial commit"</i></span></code>
			</li>
			<li>
				<h2>Split the source text at logical divisions</h2>
				<p>The file we downloaded contains the entire work. <i>Jekyll</i> is a short work, but for longer work it quickly becomes impractical to have the entire text in one file. Not only is it a pain to edit, but ereaders often have trouble with extremely large files.</p>
				<p>The next step is to split the file at logical places; that usually means at each chapter break. For works that contain their chapters in larger “parts,” the part division should also be its own file. For example, see <i><a href="/ebooks/robert-louis-stevenson/treasure-island">Treasure Island</a></i>.</p>
				<p>To split the work, we use <code class="bash"><b>se</b> split-file</code>. <code class="bash"><b>se</b> split-file</code> takes a single file and breaks it in to a new file every time it encounters the markup <code class="html"><span class="c">&lt;!--se:split--&gt;</span></code>. <code class="bash"><b>se</b> split-file</code> automatically includes basic header and footer markup in each split file.</p>
				<p>Notice that in our source file, each chapter is marked with an <code class="html"><span class="p">&lt;</span><span class="nt">h2</span><span class="p">&gt;</span></code> tag. We can use that to our advantage and save ourselves the trouble of adding the <code class="html"><span class="c">&lt;!--se:split--&gt;</span></code> markup by hand:</p><code class="terminal"><span><b>perl</b> -pi -e <i>"s|&lt;h2|&lt;\!--se:split--&gt;&lt;h2|g"</i> <u>src/epub/text/body.xhtml</u></span></code>
				<p>(Note the slash before the ! for compatibility with some shells.)</p>
				<p>Now that we’ve added our markers, we split the file. <code class="bash"><b>se</b> split-file</code> puts the results in our current directory and conveniently names them by chapter number.</p><code class="terminal"><span><b>se</b> split-file <u>src/epub/text/body.xhtml</u></span> <span><b>mv</b> chapter<i class="glob">*</i> <u>src/epub/text/</u></span></code>
				<p>Once we’re happy that the source file has been split correctly, we can remove it.</p><code class="terminal"><span><b>rm</b> <u>src/epub/text/body.xhtml</u></span></code>
			</li>
			<li>
				<h2>Clean up the source text</h2>
				<p>If you open up any of the chapter files we now have in the <code class="path">src/epub/text/</code> folder, you’ll notice that the code isn’t very clean. Paragraphs are split over multiple lines, indentation is all wrong, and so on.</p>
				<p>If you try opening a chapter in a web browser, you’ll also likely get an error if the chapter includes any HTML entities, like <code class="html">&amp;mdash;</code>. This is because Gutenberg uses plain HTML, which allows entities, but epub uses XHTML, which doesn’t.</p>
				<p>We can fix all of this pretty quickly using <code class="bash"><b>se</b> clean</code>. <code class="bash"><b>se</b> clean</code> accepts as its argument the root of a Standard Ebook directory. We’re already in the root, so we pass it <code class="path">.</code>.</p><code class="terminal"><span><b>se</b> clean <u>.</u></span></code>
				<p>Finally, we have to do a quick runthrough of each file by hand to cut out any lingering Gutenberg markup that doesn’t belong. In <i>Jekyll</i>, notice that each chapter ends with some extra empty <code class="html"><span class="p">&lt;</span><span class="nt">div</span><span class="p">&gt;</span></code>s and <code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code>s. These were used by the original transcriber to put spaces between the chapters, and they’re not necessary anymore, so remove them before continuing.</p>
				<p>Now our chapter 1 source looks like this:</p>
				<figure><code class="html full"><span class="cp">&lt;?xml version="1.0" encoding="utf-8"?&gt;</span>
<span class="p">&lt;</span><span class="nt">html</span> <span class="na">xmlns</span><span class="o">=</span><span class="s">"http://www.w3.org/1999/xhtml"</span> <span class="na">xmlns:epub</span><span class="o">=</span><span class="s">"http://www.idpf.org/2007/ops"</span> <span class="na">epub:prefix</span><span class="o">=</span><span class="s">"z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: https://standardebooks.org/vocab/1.0"</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"en-US"</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">head</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>Chapter 1<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">link</span> <span class="na">href</span><span class="o">=</span><span class="s">"../css/core.css"</span> <span class="na">rel</span><span class="o">=</span><span class="s">"stylesheet"</span> <span class="na">type</span><span class="o">=</span><span class="s">"text/css"</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">link</span> <span class="na">href</span><span class="o">=</span><span class="s">"../css/local.css"</span> <span class="na">rel</span><span class="o">=</span><span class="s">"stylesheet"</span> <span class="na">type</span><span class="o">=</span><span class="s">"text/css"</span><span class="p">/&gt;</span>
<span class="p">&lt;/</span><span class="nt">head</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"bodymatter z3998:fiction"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"chapter-1"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"chapter"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">h2</span><span class="p">&gt;</span>STORY OF THE DOOR<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Mr. Utterson the lawyer was a man of a rugged countenance...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="c">&lt;!--snip all the way to the end...--&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>"With all my heart," said the lawyer. "I shake hands on that, Richard."<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">body</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">html</span><span class="p">&gt;</span></code></figure>
				<p>If you look carefully, you’ll notice that the <code class="html"><span class="p">&lt;</span><span class="nt">html</span><span class="p">&gt;</span></code> tag has the <code class="html"><span class="na">xml:lang</span><span class="o">=</span><span class="s">"en-US"</span></code> attribute, even though our source text uses British spelling! We have to change the <code class="html"><span class="na">xml:lang</span></code> attribute for the source files to match the actual language, which in this case is en-GB. Let’s do that now:</p><code class="terminal"><span><b>perl</b> -pi -e <i>"s|en-US|en-GB|g"</i> src/epub/text/chapter<i class="glob">*</i></span></code>
				<p>Note that we <em>don’t</em> change the language for the metadata or front/back matter files, like <code class="path">content.opf</code>, <code class="path">titlepage.xhtml</code>, or <code class="path">colophon.xhtml</code>. Those must always be in American spelling, so they’ll always have the en-US language tag.</p>
			</li>
			<li>
				<h2>Typogrify the source text and perform the second commit</h2>
				<p>Now that we have a clean starting point, we can start getting the <em>real</em> work done. <code class="bash"><b>se</b> typogrify</code> can do a lot of the heavy lifting necessary to bring an ebook up to Standard Ebooks typography standards.</p>
				<p>Like <code class="bash"><b>se</b> clean</code>, <code class="bash"><b>se</b> typogrify</code> accepts as its argument the root of a Standard Ebook directory.</p><code class="terminal"><span><b>se</b> typogrify .</span></code>
				<p>Among other things, <code class="bash"><b>se</b> typogrify</code> does the following:</p>
				<ul>
					<li>
						<p>Converts straight quotes to curly quotes;</p>
					</li>
					<li>
						<p>Adds no-break spaces where appropriate for some common abbreviations;</p>
					</li>
					<li>
						<p>Normalizes ellipses;</p>
					</li>
					<li>
						<p>Normalizes spacing in em-, en-, and double-em-dashes, as well as between nested quotation marks, and adds word joiners.</p>
					</li>
				</ul>
				<p>While <code class="bash"><b>se</b> typogrify</code> does a lot of work for you, each ebook is totally different so there’s almost always more work to do that can only be done by hand. In <i>Jekyll</i>, you’ll notice that the chapter titles are in all caps. The S.E. standard requires chapter titles to be in title case, and <code class="bash"><b>se</b> titlecase</code> can do that for us.</p>
				<p><code class="bash"><b>se</b> titlecase</code> accepts a string as its argument, and outputs the string in title case. Many text editors allow you to configure external macros—perfect for creating a keyboard shortcut to run <code class="bash"><b>se</b> titlecase</code> on selected text.</p>
				<h3>Typography checklist</h3>
				<p>There are many things that <code class="bash"><b>se</b> typogrify</code> isn’t well suited to do automatically. Check <a href="/manual/latest/8-typography">our complete typography manual</a> to see exactly how to format the work. Below is a brief, but incomplete, list of common issues that arise in ebooks:</p>
				<ul>
					<li>
						<p><a href="/manual/latest/8-typography#8.7.5.3">Elision</a>. <code class="html">’</code> (i.e., <code class="html">&amp;rsquo;</code>) is used for elided letters in a word. <code class="bash"><b>se</b> typogrify</code> often gets this wrong, and you need to review your ebook by hand to ensure it didn't insert <code class="html">‘</code> (<code class="html">&amp;lsquo;</code>) instead.</p>
						<p>Use this regex to examine potential candidates for correction: <code class="regex">\s‘[a-z]</code></p>
					</li>
					<li>
						<p><a href="/manual/latest/8-typography#8.8.1">Coordinates</a>. Use the prime and double prime glyphs for coordinates. These regexes helps match and replace coordinates:</p>
						<code class="terminal"><span><b>sed</b> --regexp-extended --in-place <i>"s|([0-9])+’|\1′|g"</i> src/epub/text/<i class="glob">*</i></span></code>
						<code class="terminal"><span><b>sed</b> --regexp-extended --in-place <i>"s|([0-9])+”|\1″|g"</i> src/epub/text/<i class="glob">*</i></span></code>
					</li>
					<li>
						<p><a href="/manual/latest/8-typography#8.3.3">Text in all caps</a>. Text in all caps is almost never correct, and should either be converted to lowercase with the <code class="html"><span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span></code> tag (for spoken emphasis), <code class="html"><span class="p">&lt;</span><span class="nt">strong</span><span class="p">&gt;</span></code> (for extreme spoken emphasis), or <code class="html"><span class="p">&lt;</span><span class="nt">b</span><span class="p">&gt;</span></code> (for unsemantic small caps, like in storefront signs). This case-sensitive regex helps find candidates: <code class="regex">(?<span class="p">&lt;</span>!en-)(?<span class="p">&lt;</span>!z3998:roman">)(?<span class="p">&lt;</span>![A-Z])[A-Z]{2,}(?!")</code></p>
					</li>
					<li>
						<p>Sometimes <code class="bash"><b>se</b> typogrify</code> doesn’t close quotation marks near em-dashes correctly. Try to find such instances with this regex: <code class="regex">—[’”][^<span class="p">&lt;</span>\s]</code></p>
					</li>
					<li>
						<p><a href="/manual/latest/8-typography#8.7.7">Two-em dashes should be used for elision</a>.</p>
					</li>
					<li>
						<p>Commas and periods should generally be inside quotation marks, not outside. This command helps find and replace them:</p>
						<code class="terminal"><span><b>se</b> interactive-sr <i>"/\v([’”])([,.])/\2\1/"</i> src/epub/text/*</span></code>
						<p>When using this command, be careful to distinguish between the use of <code class="html">’</code> as a quotation mark and its use in elision or as part of a plural possessive (i.e. <code class="html">s’</code>).</p>
						<table>
							<tbody>
								<tr>
									<td><b>Correct change:</b></td>
									<td>
										<p><code class="html">“Let’s have a game of ‘noses’, lads!”</code> ➔</p>
										<p><code class="html">“Let’s have a game of ‘noses,’ lads!”</code></p>
									</td>
								</tr>
								<tr>
									<td><b>Incorrect change:</b></td>
									<td>
										<p><code class="html">This wood now is not mine, but the peasants’.</code> ➔</p>
										<p><code class="html wrong">This wood now is not mine, but the peasants.’</code></p>
									</td>
								</tr>
							</tbody>
						</table>
					</li>
				</ul>
				<h3>The second commit</h3>
				<p>Once you’ve run <code class="bash"><b>se</b> typogrify</code> and you’ve searched the work for the common issues above, you can perform your second commit.</p><code class="terminal"><span><b>git</b> add -A</span> <span><b>git</b> commit -m <i>"Typogrify"</i></span></code>
			</li>
			<li>
				<h2>Convert footnotes to endnotes and add a list of illustrations</h2>
				<p>Works often include footnotes, either added by an annotator or as part of the work itself. Since ebooks don’t have a concept of a “page,” there’s no place for footnotes to go. Instead, we convert footnotes to a single endnotes file, which will provide popup references in the final epub.</p>
				<p>The endnotes file and the format for endnote links are <a href="/manual/latest/7-high-level-structural-patterns#7.10">standardized in the semantics manual</a>.</p>
				<p>If you find that you accidentally mis-ordered an endnote, never fear! <code class="bash"><b>se</b> reorder-endnotes</code> will allow you to quickly rearrange endnotes in your ebook.</p>
				<p>If a work has illustrations besides the cover and title pages, we include a “list of illustrations” at the end of the book, after the endnotes but before the colophon. The <abbr class="initialism">LoI</abbr> file is also <a href="/manual/latest/7-high-level-structural-patterns#7.9">standardized in the semantics manual</a>.</p>
				<p><i>Jekyll</i> doesn’t have any footnotes, endnotes, or illustrations, so we skip this step.</p>
			</li>
			<li>
				<h2>Converting British quotation to American quotation</h2>
				<p>If the work you’re producing uses <a href="http://www.thepunctuationguide.com/british-versus-american-style.html">British quotation style</a> (single quotes for dialog versus double quotes in American), we have to convert it to American style. We use American style in part because it’s easier to programmatically convert from American to British than it is to convert the other way around. <em>Skip this step if your work is already in American style.</em></p>
				<p><code class="bash"><b>se</b> british2american</code> attempts to automate the conversion. Your work must already be typogrified (the previous step in this guide) for the script to work.</p><code class="terminal"><span><b>se</b> british2american <u>.</u></span></code>
				<p>While <code class="bash"><b>se</b> british2american</code> tries its best, thanks to the quirkiness of English punctuation rules it’ll invariably mess some stuff up. Proofreading is required after running the conversion.</p>
				<p>After you’ve run the conversion, do another commit.</p><code class="terminal"><span><b>git</b> add -A</span> <span><b>git</b> commit -m <i>"Convert from British-style quotation to American style"</i></span></code>
				<p>This regex is useful for spotting incorrectly converted quotes next to em dashes: <code class="regex">“[^”‘]+’⁠—(?=[^”]*?&lt;/p&gt;;)</code></p>
			</li>
			<li>
				<h2>Add semantics</h2>
				<p>Part of the Standard Ebooks project is adding meaningful semantics wherever possible in the text. <code class="bash"><b>se</b> semanticate</code> does a little of that for us—for example, for some common abbreviations—but much of it has to be done by hand.</p>
				<p>Adding semantics means two things:</p>
				<ol>
					<li>
						<p>Using meaningful tags to mark up the work: <code class="html"><span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span></code> when conveying emphatic speech instead of <code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code>, <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span></code> to wrap abbreviations, <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> to mark structural divisions, using the <code class="html"><span class="na">xml:lang</span></code> attribute to specify the language of a word or passage, and so on.</p>
					</li>
					<li>
						<p>Using the <a href="http://www.idpf.org/epub/30/spec/epub30-contentdocs.html#sec-xhtml-semantic-inflection">epub3 semantic inflection language</a> to add deeper meaning to tags.</p>
						<p>Currently we use a mix of <a href="http://www.idpf.org/epub/vocab/structure/">epub3 structural semantics</a>, <a href="http://www.daisy.org/z3998/2012/vocab/structure/">z3998 structural semantics</a> for when the epub3 vocabulary isn’t enough, and our own <a href="/vocab/1.0">S.E. semantics</a> for when z3998 isn’t enough.</p>
					</li>
				</ol>
				<p>Use <code class="bash"><b>se</b> semanticate</code> to do some common cases for you:</p><code class="terminal"><span><b>se</b> semanticate <u>.</u></span></code>
				<p><code class="bash"><b>se</b> semanticate</code> tries its best to correctly add semantics, but sometimes it’s wrong. For that reason you should review the changes it made before accepting them:</p><code class="terminal"><span><b>git</b> difftool</span></code>
				<p>Beyond that, adding semantics is mostly a by-hand process. See the <a href="/manual">Standard Ebooks Manual of Style</a> for a detailed list of the kinds of semantics we expect in a Standard Ebook.</p>
				<p>Here’s a short list of some of the more common semantic issues you’ll encounter:</p>
				<ul>
					<li>
						<p>Semantics for italics: <code class="html"><span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span></code> should be used for when a passage is emphasized, as in when dialog is shouted or whispered. <code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code> is used for all other italics, <a href="/manual/latest/4-semantics#4.2">with the appropriate semantic inflection</a>. Older transcriptions usually use just <code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code> for both, so you must change them manually if necessary.</p>
						<p>Sometimes, transcriptions from Project Gutenberg may use ALL CAPS instead of italics. To replace these, you can use <code class="bash"><b>sed</b></code>:</p>
						<code class="terminal"><span><b>sed</b> --regexp-extended --in-place <i>"s|[A-Z’]{2,}|&lt;em&gt;\L\1&lt;/em&gt;|g"</i> src/epub/text/<i class="glob">*</i></span></code>
						<p>This will unfortunately replace language tags like <code>en-US</code>, so fix those up with this:</p>
						<code class="terminal"><span><b>sed</b> --regexp-extended --in-place <i>"s|en-&lt;em&gt;([a-z]+)&lt;/em&gt;|en-\U\1|g"</i> src/epub/text/<i class="glob">*</i></span></code>
						<p>These replacments don’t take Title Caps into account, so use <code class="bash"><b>git</b> diff</code> to review the changes and fix errors before committing.</p>
					</li>
					<li>
						<p><a href="/manual/latest/8-typography#8.1">Semantics rules for chapter titles</a>.</p>
					</li>
					<li>
						<p><a href="/manual/latest/8-typography#8.10">Semantics rules for abbreviations</a>. Abbreviations should always be wrapped in the <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span></code> tag and with the correct <code class="html"><span class="na">class</span></code> attribute.</p>
						<p>Specifically, see the <a href="/manual/latest/8-typography#8.10.6">typography rules for initials</a>. Wrap people’s initials in <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"name"</span><span class="p">&gt;</span></code>. This regex helps match initials: <code class="regex">[A-Z]\.\s*([A-Z]\.\s*)+</code></p>
					</li>
					<li>
						<p><a href="/manual/latest/8-typography#8.11">Typography rules for times</a>. Wrap a.m. and p.m. in <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"time"</span><span class="p">&gt;</span></code> and add a no-break space between digits and a.m. or p.m.</p>
					</li>
					<li>
						<p>Words or phrases in foreign languages should always be marked up with <code class="html"><span class="p">&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"TAG"</span><span class="p">&gt;</span></code>, where TAG is an <a href="https://en.wikipedia.org/wiki/IETF_language_tag">IETF language tag</a>. <a href="https://r12a.github.io/app-subtags/">This app can help you look them up</a>. If the text uses fictional or unspecific languages, use the “x-” prefix and make up a subtag yourself.</p>
					</li>
					<li>
						<p>Semantics for poetry, verse, and song: Many Gutenberg productions use the <code class="html"><span class="p">&lt;</span><span class="nt">pre</span><span class="p">&gt;</span></code> tag to format poetry, verse, and song. This is, of course, semantically incorrect. <a href="/manual/latest/7-high-level-structural-patterns#7.5">See the Poetry section of the <abbr class="acronym">SEMOS</abbr></a> for templates on how to semantically format poetry, verse, and song.</p>
					</li>
				</ul>
				<p>After you’ve added semantics according to the <a href="/manual">Standard Ebooks Manual of Style</a>, do another commit.</p><code class="terminal"><span><b>git</b> add -A</span> <span><b>git</b> commit -m <i>"Semanticate"</i></span></code>
			</li>
			<li>
				<h2>Set <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> elements</h2>
				<p>After you’ve added semantics and correctly marked up <a href="/manual/latest/7-high-level-structural-patterns#7.2">section headers</a>, it’s time to update the <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> elements in each chapter to match <a href="/manual/latest/5-general-xhtml-and-css-patterns#5.4">their expected values</a>.</p>
				<p>The <code class="bash"><b>se</b> print-title</code> tool takes a well-marked-up section header from a file, and prints the expected value for the <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element to the terminal. It also has the <code class="bash">--in-place</code> option, which will allow us to update all the chapters at once:</p>
				<code class="terminal"><span><b>se</b> print-title --in-place src/epub/text/<i class="glob">*</i></span></code>
				<p>Once you’ve verified the titles look good, commit:</p>
				<code class="terminal"><span><b>git</b> add -A</span> <span><b>git</b> commit -m <i>"Add titles"</i></span></code>
			</li>
			<li>
				<h2>Modernize spelling and hyphenation</h2>
				<p>Many older works use outdated spelling and hyphenation that would distract a modern reader. (For example, “to-night” instead of “tonight”). <code class="bash"><b>se</b> modernize-spelling</code> automatically removes hyphens from words that used to be compounded, but aren’t anymore in modern English spelling.</p>
				<p><em>Do</em> run this tool on prose. <em>Don’t</em> run this tool on poetry.</p>
				<code class="terminal"><span><b>se</b> modernize-spelling <u>.</u></span></code>
				<p>After you run the tool, <em>you must check what the tool did to confirm that each removed hyphen is correct</em>. Sometimes the tool will remove a hyphen that needs to be included for clarity, or one that changes the meaning of the word, or it may result in a word that just doesn’t seem right. Re-introducing a hyphen is OK in these cases.</p>
				<p>Here’s a real-world example of where <code class="bash"><b>se</b> modernize-spelling</code> made the wrong choice: In <i><a href="/ebooks/oscar-wilde/the-picture-of-dorian-gray">The Picture of Dorian Gray</a></i> chapter 11, Oscar Wilde writes:</p>
				<blockquote>
					<p>He possessed a gorgeous cope of crimson silk and gold-thread damask…</p>
				</blockquote>
				<p><code class="bash"><b>se</b> modernize-spelling</code> would replace the dash in <code class="html">gold-thread</code> so that it reads <code class="html">goldthread</code>. Well <code class="html">goldthread</code> is an actual word, which is why it’s in our dictionary, and why the script makes a replacement—but it’s the name of a type of flower, <em>not</em> a golden fabric thread! In this case, <code class="bash"><b>se</b> modernize-spelling</code> made an incorrect replacement, and we have to change it back.</p>
				<p><code class="bash"><b>git</b></code> usually compares changes line-by-line, but since lines an ebook can be very long, a line-level comparison can make spotting small changes difficult. Intead of just doing <code class="bash"><b>git</b> diff</code>, try the following command to highlight changes at the character level:</p>
				<code class="terminal"><span><b>git</b> diff -U0 --word-diff-regex=.</span></code>
				<p>You can also enable color in your <code class="bash"><b>git</b></code> output to make the output of that command more readable, and even assign it to a shortcut in your <code class="bash"><b>git</b></code> configuration.</p>
				<p>Alternatively, you can use an external diff GUI to review changes:</p>
				<code class="terminal"><span><b>git</b> difftool</span></code>
				<p>After you’ve reviewed the changes that the tool made, create an “[Editorial]” commit. This commit is important, because it gives purists an avenue to revert modernizing changes to the original text.</p>
				<p>Note how we preface this commit with “[Editorial]”. Any change you make to the source text that can be considered a modernization or editorial change should be prefaced like this, so that the <code class="bash"><b>git</b></code> history can be easily searched by people looking to revert changes.</p><code class="terminal"><span><b>git</b> commit -am <i>"[Editorial] Modernize hyphenation and spelling"</i></span></code>
			</li>
			<li>
				<h2>Check for consistent diacritics</h2>
				<p>Sometimes during transcription or even printing, instances of some words might have diacritics while others don’t. For example, a word in one chapter might be spelled <code class="html">châlet</code>, but in the next chapter it might be spelled <code class="html">chalet</code>.</p>
				<p><code class="bash"><b>se</b> find-mismatched-diacritics</code> lists these instances for you to review. Spelling should be normalized across the work so that all instances of the same word are spelled in the same way. Keep the following in mind as you review these instances:</p>
				<ul>
					<li>
						<p>In modern English spelling, many diacritics are removed (like <code class="html">chalet</code>). If in doubt, ask the S.E. Editor-in-Chief.</p>
					</li>
					<li>
						<p>Even though diacritics might be removed in English spelling, they may be preserved in non-English text, or in proper names.</p>
						<blockquote>
							<p>He visited the hotel called the Châlet du Nord.</p>
						</blockquote>
					</li>
				</ul>
			</li>
			<li>
				<h2>Modernize spacing in select words</h2>
				<p>Over time, spelling of certain common two-word phrases has evolved into a single word. For example, “someone” used to be the two-word phrase “some one,” which would read awkwardly to modern readers. This is our chance to modernize such phrases.</p>
				<p>Note that we use <code class="bash"><b>se</b> interactive-sr</code> to perform an interactive search and replace, instead of doing a global, non-interactive search and replace. This is because some phrases caught by the regular expression should not be changed, depending on context. For example, "some one" in the following snippet from <a href="/ebooks/anton-chekhov/short-fiction/constance-garnett/">Anton Chekhov’s short fiction</a> <em>should not</em> be corrected:</p>
				<blockquote>
					<p>He wanted to think of some one part of nature as yet untouched...</p>
				</blockquote>
				<p>When running <code class="bash"><b>se</b> interactive-sr</code>, press <code>y</code> to accept a replacement and <code>n</code> to reject a replacement.</p>
				<p>Use the following regular expression invocations to correct a certain set of such phrases:</p>

				<ul class="changes">
					<li>
						<table>
							<tbody>
								<tr>
									<td>Correct change:</td>
									<td>
										<p>She asked some one on the street. ➔</p>
										<p>She asked someone on the street.</p>
									</td>
								</tr>
								<tr>
									<td>Incorrect change:</td>
									<td>
										<p>But every clever crime is founded ultimately on some one quite simple fact⁠. ➔</p>
										<p><span class="wrong">But every clever crime is founded ultimately on someone quite simple fact⁠.</span></p>
									</td>
								</tr>
							</tbody>
						</table>
						<code class="terminal"><span><b>se</b> interactive-sr <i>"/\v([Ss])ome one/\1omeone/"</i> src/epub/text/<i class="glob">*</i></span> <span><b>git</b> commit -am <i>"[Editorial] some one -&gt; someone"</i></span></code>
					</li>
					<li>
						<table>
							<tbody>
								<tr>
									<td>Correct change:</td>
									<td>
										<p>“Any one else on this floor?” he asked. ➔</p>
										<p>“Anyone else on this floor?” he asked.</p>
									</td>
								</tr>
								<tr>
									<td>Incorrect change:</td>
									<td>
										<p>It is not easy to restore lost property to any one of them. ➔</p>
										<p><span class="wrong">It is not easy to restore lost property to anyone of them.</span></p>
									</td>
								</tr>
							</tbody>
						</table>
						<code class="terminal"><span><b>se</b> interactive-sr <i>"/\v(&lt;[Aa])ny one/\1nyone/"</i> src/epub/text/<i class="glob">*</i></span> <span><b>git</b> commit -am <i>"[Editorial] any one -&gt; anyone"</i></span></code>
					</li>
					<li>
						<table>
							<tbody>
								<tr>
									<td>Correct change:</td>
									<td>
										<p>He was furious⁠—furious with himself, furious with every one. ➔</p>
										<p>He was furious⁠—furious with himself, furious with everyone.</p>
									</td>
								</tr>
								<tr>
									<td>Incorrect change:</td>
									<td>
										<p>I’m sure we missed ten for every one we saw. ➔</p>
										<p><span class="wrong">I’m sure we missed ten for everyone we saw.</span></p>
									</td>
								</tr>
							</tbody>
						</table>
						<code class="terminal"><span><b>se</b> interactive-sr <i>"/\v([Ee]ach and )@&lt;\!([Ee])very one(\s+of)@\!/\2veryone/"</i> src/epub/text/<i class="glob">*</i></span> <span><b>git</b> commit -am <i>"[Editorial] every one -&gt; everyone"</i></span></code>
					</li>
					<li>
						<table>
							<tbody>
								<tr>
									<td>Correct change:</td>
									<td>
										<p>Equip a ship with every thing apt for naval battle. ➔</p>
										<p>Equip a ship with everything apt for naval battle.</p>
									</td>
								</tr>
								<tr>
									<td>Incorrect change:</td>
									<td>
										<p>For there was no one more clever than he to do a hand’s turn at any and every thing. ➔</p>
										<p><span class="wrong">For there was no one more clever than he to do a hand’s turn at any and everything.</span></p>
									</td>
								</tr>
							</tbody>
						</table>
						<code class="terminal"><span><b>se</b> interactive-sr <i>"/\v([Ee])very thing/\1verything/"</i> src/epub/text/<i class="glob">*</i></span> <span><b>git</b> commit -am <i>"[Editorial] every thing -&gt; everything"</i></span></code>
					</li>
					<li>
						<table>
							<tbody>
								<tr>
									<td>Correct change:</td>
									<td>
										<p>If you have any thing to say to us say it quickly. ➔</p>
										<p>If you have anything to say to us say it quickly.</p>
									</td>
								</tr>
								<tr>
									<td>Incorrect change:</td>
									<td>
										<p>Any man or any thing who faces me during these games, dies. ➔</p>
										<p><span class="wrong">Any man or anything who faces me during these games, dies.</span></p>
									</td>
								</tr>
							</tbody>
						</table>
						<code class="terminal"><span><b>se</b> interactive-sr <i>"/\v(&lt;[Aa])ny thing/\1nything/"</i> src/epub/text/<i class="glob">*</i></span> <span><b>git</b> commit -am <i>"[Editorial] any thing -&gt; anything"</i></span></code>
					</li>
					<li>
						<table>
							<tbody>
								<tr>
									<td>Correct change:</td>
									<td>
										<p>No; perhaps you will love her for ever. ➔</p>
										<p>No; perhaps you will love her forever.</p>
									</td>
								</tr>
								<tr>
									<td>Incorrect change:</td>
									<td>
										<p>I have been struggling on for ever so long without doing anything. ➔</p>
										<p><span class="wrong">I have been struggling on forever so long without doing anything.</span></p>
									</td>
								</tr>
							</tbody>
						</table>
						<code class="terminal"><span><b>se</b> interactive-sr <i>"/\v([Ff])or ever(&gt;)/\1orever\2/"</i> src/epub/text/<i class="glob">*</i></span> <span><b>git</b> commit -am <i>"[Editorial] for ever -&gt; forever"</i></span></code>
					</li>
					<li>
						<table>
							<tbody>
								<tr>
									<td>Correct change:</td>
									<td>
										<p>Not all, of course, but any way it is much better than the life here. ➔</p>
										<p>Not all, of course, but anyway it is much better than the life here.</p>
									</td>
								</tr>
								<tr>
									<td>Incorrect change:</td>
									<td>
										<p>And I’m not at fault in any way, and there’s no need for me to suffer. ➔</p>
										<p><span class="wrong">And I’m not at fault in anyway, and there’s no need for me to suffer.</span></p>
									</td>
								</tr>
							</tbody>
						</table>
						<code class="terminal"><span><b>se</b> interactive-sr <i>"/\v(in\s+)@&lt;\!(&lt;[Aa])ny way(\s+(of|to))@\!/\2nyway/"</i> src/epub/text/<i class="glob">*</i></span> <span><b>git</b> commit -am <i>"[Editorial] any way -&gt; anyway"</i></span></code>
					</li>
					<li>
						<table>
							<tbody>
								<tr>
									<td>Correct change:</td>
									<td>
										<p>And in the mean time, he’ll also keep on being a laughing stock? ➔</p>
										<p>And in the meantime, he’ll also keep on being a laughing stock?</p>
									</td>
								</tr>
								<tr>
									<td>Incorrect change:</td>
									<td>
										<p>You’ve had an awful mean time, Ethan Frome. ➔</p>
										<p><span class="wrong">You’ve had an awful meantime, Ethan Frome.</span></p>
									</td>
								</tr>
							</tbody>
						</table>
						<code class="terminal"><span><b>se</b> interactive-sr <i>"/\v([Mm])ean time/\1eantime/"</i> src/epub/text/<i class="glob">*</i></span> <span><b>git</b> commit -am <i>"[Editorial] mean time -&gt; meantime"</i></span></code>
					</li>
				</ul>
			</li>
			<li>
				<h2>Create the cover image</h2>
				<aside class="alert">
					<p class="warning">!!! STOP !!!</p>
					<p><strong>Do not commit cover art to your repository’s history until you have <a href="https://groups.google.com/forum/#!forum/standardebooks">cleared your selection with the S.E. Editor-in-Chief.</a></strong></p>
					<p>If you commit non-public-domain cover art, you’ll have to rebase your repository to remove the art from its history. This is complicated, dangerous, and annoying, and you’ll be tempted to give up.</p>
					<p><a href="https://groups.google.com/forum/#!forum/standardebooks">Contact us first</a> with page scans verifying your cover art’s public domain status before you commit your cover art!</p>
				</aside>
				<p>Cover images for Standard Ebooks books have a standardized layout. The bulk of the work you’ll be doing is locating a suitable public domain painting to use. See the <a href="/manual/latest/10-art-and-images">Art and Images section of the Standard Ebooks Manual of Style</a> for details on assembling a cover image.</p>
				<p>As you search for an image, keep the following in mind:</p>
				<ul>
					<li>
						<p>Cover images must be in the public domain. Thanks to quirks in copyright law, this is harder to decide for paintings than it is for published writing. In general, Wikipedia is a good starting point for deciding if a work is in the public domain, but very careful research is required to confirm that status.</p>
					</li>
					<li>
						<p>Find the largest possible cover image you can. Since the final image is 1400 × 2100, having to resize a small image will greatly reduce the quality of the final cover.</p>
					</li>
					<li>
						<p>The image you pick should be a “fine art” oil painting so that all Standard Ebooks have a consistent cover style. This is actually easier than you think, because it turns out most public domain artwork is from the era of fine art.</p>
					</li>
					<li>
						<p>You must provide proof of public domain status to the S.E. Editor-in-Chief in the form of a page scan of the painting from a <?= PD_YEAR ?>-or-older book, and the Editor-in-Chief must approve your selection before you can commit it to your repository.</p>
					</li>
					<li>
						<p>The Standard Ebooks Editor-in-Chief has the final say on the cover image you pick, and it may be rejected for, among other things, poor public domain status research, being too low resolution, not fitting in with the “fine art” style, or being otherwise inappropriate for your ebook.</p>
					</li>
				</ul>
				<p>What can we use for <i>Jekyll</i>? In 1885 Albert Edelfelt painted a <a href="https://en.wikipedia.org/wiki/File:Albert_Edelfelt_-_Louis_Pasteur_-_1885.jpg">portrait of Louis Pasteur</a> in a laboratory. A crop of the lab equipment would be a good way to represent Dr. Jekyll’s lab.</p>
				<p>The cover file itself, <code class="path">cover.svg</code>, is easy to edit. It automatically links to <code class="path">cover.jpg</code>. All you have to do is open <code class="path">cover.svg</code> with a text editor and edit the title and author. Make sure you have the League Spartan font installed on your system!</p>
				<p>After we’re done with the cover, we’ll have four files in <code class="path">./images/</code>:</p>
				<ul>
					<li>
						<p><code class="path">cover.source.jpg</code> is the raw image file we used for the cover. We keep it in case we want to make adjustments later. For <i>Jekyll</i>, this would be the raw Pasteur portrait downloaded from Wikipedia.</p>
					</li>
					<li>
						<p><code class="path">cover.jpg</code> is the scaled cover image that <code class="path">cover.svg</code> links to. This file is exactly 1400 × 2100. For <i>Jekyll</i>, this is a crop of <code class="path">cover.source.jpg</code> that includes just the lab equipment, and resized up to our target resolution.</p>
					</li>
					<li>
						<p><code class="path">cover.svg</code> is the completed cover image with the title and author. <code class="bash"><b>se</b> build-images</code> will take <code class="path">cover.svg</code>, embed <code class="path">cover.jpg</code>, convert the text to paths, and place the result in <code class="path">./src/epub/images/</code> for inclusion in the final epub.</p>
					</li>
					<li>
						<p><code class="path">titlepage.svg</code> is the completed titlepage image that <code class="bash"><b>se</b> create-draft</code> created for you.</p>
					</li>
				</ul>
			</li>
			<li>
				<h2>Create the titlepage image, build both the cover and titlepage, and commit</h2>
				<p>Titlepage images for Standard Ebooks books are also standardized. See our the <a href="/manual/latest/10-art-and-images">Art and Images section of the Standard Ebooks Manual of Style</a> for details.</p>
				<p><code class="bash"><b>se</b> create-draft</code> already created a completed titlepage for you. If the way it arranged the lines doesn’t look great, you can always edit the titlepage to make the arrangement of words on each line more aesthetically pleasing. Don’t use a vector editing program like Inkscape to edit it. Instead, open it up in your favorite text editor and type the values in directly.</p>
				<p>The source images for both the cover and the titlepage are kept in <code class="path">./images/</code>. Since the source images refer to installed fonts, and since we can’t include those fonts in our final ebook without having to include a license, we have to convert that text to paths for final distribution. <code class="bash"><b>se</b> build-images</code> does just that.</p><code class="terminal"><span><b>se</b> build-images <u>.</u></span></code>
				<p><code class="bash"><b>se</b> build-images</code> takes both <code class="path">./images/cover.svg</code> and <code class="path">./images/titlepage.svg</code>, converts text to paths, and embeds the cover jpg. The output goes to <code class="path">./src/epub/images/</code>.</p>
				<p>Once we built the images successfully, perform a commit.</p><code class="terminal"><span><b>git</b> add -A</span> <span><b>git</b> commit -m <i>"Add cover and titlepage images"</i></span></code>
			</li>
			<li>
				<h2>Complete content.opf</h2>
				<p><code class="path">content.opf</code> is the file that contains the ebook metadata like author, title, description, and reading order. Most of it will be filling in that basic information, and including links to various resources related to the text.</p>
				<p>The <code class="path">content.opf</code> is standardized. See the <a href="/manual/latest/9-metadata">Metadata section of the Standard Ebooks Manual of Style</a> for details on how to fill out <code class="path">content.opf</code>.</p>
				<p>As you complete the metadata, you’ll have to order the spine and the manifest in this file. Fortunately, Standard Ebooks has tools for that too: <code class="bash"><b>se</b> print-manifest</code> and <code class="bash"><b>se</b> print-spine</code>. Run these on our source directory and, as you can guess, they’ll print out the <code class="html"><span class="p">&lt;</span><span class="nt">manifest</span><span class="p">&gt;</span></code> and <code class="html"><span class="p">&lt;</span><span class="nt">spine</span><span class="p">&gt;</span></code> elements for this work.</p>
				<p>If you’re using a Mac, and thus the badly-behaved Finder program, you may find that it has carelessly polluted your work directory with useless <code class="path">.DS_Store</code> files. Before continuing, you should <a href="https://duckduckgo.com/?q=mac+alternative+file+manager">find a better file manager program</a>, then delete all of that litter with the following command. Otherwise, <code class="bash"><b>se</b> print-manifest</code> and <code class="bash"><b>se</b> print-spine</code> will include that litter in its output and your epub won’t be valid.</p>
				<code class="terminal"><span><b>find</b> <u>.</u> -name <i>".DS_Store"</i> -type f -delete</span></code>
				<p>Since this is the first time we’re editing <code class="path">content.opf</code>, we’re OK with replacing both the manifest and spine elements with a guess at the correct contents. We can do this using the <code class="bash">--in-place</code> option. If we have to update the manifest or spine later, we can omit the option to print to standard output instead of altering <code class="path">content.opf</code> directly.</p>
				<code class="terminal">
					<span><b>se</b> print-manifest --in-place <u>.</u></span>
					<span><b>se</b> print-spine --in-place <u>.</u></span>
				</code>
				<p>The manifest is already in the correct order and doesn’t need to be edited. The spine, however, will have to be reordered to be in the correct reading order. Once you’ve done that, commit!</p><code class="terminal"><span><b>git</b> add -A</span> <span><b>git</b> commit -m <i>"Complete content.opf"</i></span></code>
			</li>
			<li>
				<h2>Complete the table of contents</h2>
				<p>The table of contents is a structured document that should let the reader easily navigate the book. In a Standard Ebook, it’s stored outside of the readable text directory with the assumption that the reading system will parse it and display a navigable representation for the user.</p>
				<p>Once you’ve completed the <code class="html"><span class="p">&lt;</span><span class="nt">spine</span><span class="p">&gt;</span></code> element in <code class="path">content.opf</code>, you can use <code class="bash"><b>se</b> print-toc</code> to generate a table of contents for this ebook. Since this is the first time we’re generating a ToC for this ebook, use the <code class="bash">--in-place</code> flag to replace the template ToC file with the generated ToC.</p>
				<code class="terminal raw"><span><b>se</b> print-toc --in-place <u>.</u></span></code>
				<p>Review the generated ToC in <code class="path">./src/epub/toc.xhtml</code> to make sure <code class="bash"><b>se</b> print-toc</code> did the right thing. <code class="bash"><b>se</b> print-toc</code> is valuable tool to discover structural problems in your ebook. If an entry is arranged in a way you weren’t expecting, perhaps the problem isn’t with <code class="bash"><b>se</b> print-toc</code>, but with your HTML code—be careful! You may have to make changes by hand for complex or unusual books.</p>
				<p>Once you’re done, commit:</p>
				<code class="terminal"><span><b>git</b> add -A</span> <span><b>git</b> commit -m <i>"Add ToC"</i></span></code>
			</li>
			<li>
				<h2>Complete the colophon</h2>
				<p><code class="bash"><b>se</b> create-draft</code> put a skeleton <code class="path">colophon.xhtml</code> file in the <code class="path">./src/epub/text/</code> folder. Now that we have the cover image and artist, we can fill out the various fields there. Make sure to credit the original transcribers of the text (generally we assume them to be whoever’s name is on the file we download from Gutenberg) and to include a link back to the Gutenberg text we used, along with a link to any scans we used (from archive.org or hathitrust.org, for example).</p>
				<p>You can also include your own name as the producer of this Standard Ebooks edition. Besides that, the colophon is standardized; don’t get too creative with it.</p>
				<p>The release and updated dates should be the same for the first release, and they should match the dates in <code class="path">content.opf</code>. For now, leave them unchanged, as <code class="bash"><b>se</b> prepare-release</code> will automatically fill them in for you as we’ll describe later in this guide.</p><code class="terminal"><span><b>git</b> add -A</span> <span><b>git</b> commit -m <i>"Complete the colophon"</i></span></code>
			</li>
			<li>
				<h2>Complete the imprint</h2>
				<p>There’s also a skeleton <code class="path">imprint.xhtml</code> file in the <code class="path">./src/epub/text/</code> folder. All you’ll have to change here is the links to the transcription and page scans you used.</p>
			</li>
			<li>
				<h2>Clean and lint before building</h2>
				<p>Before you build the final ebook for you to proofread, it’s a good idea to check the ebook for some common problems you might run in to during production.</p>
				<p>First, run <code class="bash"><b>se</b> clean</code> one more time to both clean up the source files, and to alert you if there are XHTML parsing errors. Even though we ran <code class="bash"><b>se</b> clean</code> before, it’s likely that in the course of production the ebook got in to less-than-perfect markup formatting. Remember you can run <code class="bash"><b>se</b> clean</code> as many times as you want—it should always produce the same output.</p>
				<code class="terminal"><span><b>se</b> clean <u>.</u></span></code>
				<p>Now, run <code class="bash"><b>se</b> lint</code>. If your ebook has any problems, you’ll see some output listing them. If everything’s OK, then <code class="bash"><b>se</b> lint</code> will complete silently.</p>
				<code class="terminal"><span><b>se</b> lint <u>.</u></span></code>
			</li>
			<li>
				<h2>Build and proofread, proofread, proofread!</h2>
				<p>At this point we’re just about ready to build our proofreading draft! <code class="bash"><b>se</b> build</code> does this for us. We’ll run it with the <code class="bash">--check</code> flag to make sure the epub we produced is valid, and with the <code class="bash">--kindle</code> and <code class="bash">--kobo</code> flag to build a file for Kindles and Kobos too. If you won’t be using a Kindle or Kobo, you can omit those flags.</p>
				<code class="terminal"><span><b>se</b> build --output-dir=$HOME/dist/ --kindle --kobo --check <u>.</u></span></code>
				<p>If there are no errors, we’ll see five files in the brand-new <code class="path">~/dist/</code> folder in our home directory:</p>
				<ul>
					<li>
						<p><code class="path">the-strange-case-of-dr-jekyll-and-mr-hyde_advanced.epub</code> is the zipped up version of our source. Unfortunately most ebook readers don’t fully support all of epub3’s capabilities yet, or the advanced CSS selectors and XHTML structure we use, so we’re more interested in…</p>
					</li>
					<li>
						<p><code class="path">the-strange-case-of-dr-jekyll-and-mr-hyde.epub</code>, the compatible epub version of our ebook. This file is the raw source, plus various compatiblity fixes applied during our build process. If you don’t have a Kindle, this is the file you’ll be using to proofread.</p>
					</li>
					<li>
						<p><code class="path">the-strange-case-of-dr-jekyll-and-mr-hyde.kepub.epub</code> is the Kobo version of our ebook. You can copy this to a Kobo using a USB cable.</p>
					</li>
					<li>
						<p><code class="path">the-strange-case-of-dr-jekyll-and-mr-hyde.azw3</code> is the Kindle version of our ebook. You can copy this to a Kindle using a USB cable.</p>
					</li>
					<li>
						<p><code class="path">thumbnail_xxxx_EBOK_portrait.jpg</code> is a thumbnail file you can copy to your Kindle to have the cover art appear in your reader. A bug in Amazon’s software prevents the Kindle from reading cover images in side-loaded files; contact Amazon to complain.</p>
					</li>
				</ul>
				<p>This is the step where you read the ebook and make adjustments to the text so that it conforms to our <a href="/manual/latest/8-typography">typography manual</a>.</p>
				<p>All Standard Ebooks productions must be proofread at this stage to confirm that there are no typos, formatting errors, or typography errors. It’s extremely common for transcriptions sourced from Gutenberg to have various typos and formatting errors (like missing italics), and it’s also not uncommon for one of Standard Ebook’s tools to make the wrong guess about things like a closing quotation mark somewhere. As you proofread, it’s extremely handy to have a print copy of the book with you. For famous books that might just be a trip to your local library. For rarer books, or for those without a library nearby, there are several sites that provide free digital scans of public domain writing:</p>
				<ul>
					<li>
						<p><a href="http://www.hathitrust.org/">The HathiTrust Digital Library</a> is a comprehensive collection of Google’s book scanning project. They have a vast catalog and a feature-rich search and reading interface.</p>
					</li>
					<li>
						<p><a href="https://archive.org/">The Internet Archive</a> is another collection of scans of public domain books.</p>
					</li>
				</ul>
				<p>If you end up using scans from one of these sources, you <em>must</em> mention it in the ebook’s colophon and as a <code class="html"><span class="p">&lt;</span><span class="nt">dc:source</span><span class="p">&gt;</span></code> item in <code class="path">content.opf</code>.</p>
				<p>If you’re using a transcription from Project Gutenberg as the base for this ebook, you may wish to report typos you’ve found to them, so that they can correct their copy. <a href="/contribute/report-errors-upstream">Instructions for how to do so are here.</a></p>
			</li>
			<li>
				<h2>Initial publication</h2>
				<p>Now that we’ve proofread the work and corrected any errors we’ve found, we’re ready to release the finished ebook!</p>
				<p>It’s a good idea to run <code class="bash"><b>se</b> typogrify</code> and <code class="bash"><b>se</b> clean</code> one more time before releasing. Make sure to review the changes with <code class="bash"><b>git</b> difftool</code> before accepting them—<code class="bash"><b>se</b> typogrify</code> is usually right, but not always!</p>
				<ul>
					<li>
						<p><b>If you’re submitting your ebook to Standard Ebooks for review:</b></p>
						<p><em>Don’t run <code class="bash"><b>se</b> prepare-release</code> on an ebook you’re submitting for review!</em></p>
						<p>Contact the mailing list with a link to your GitHub repository to let them know you’re finished. A reviewer will review your production and work with you to fix any issues. They’ll then release the ebook for you.</p>
					</li>
					<li>
						<p><b>If you’re producing this ebook for yourself, not for release at Standard Ebooks:</b></p>
						<p>Complete the initial publication by adding a release date, modification date, and final word count to <code class="path">content.opf</code> and <code class="path">colophon.xhtml</code>. <code class="bash"><b>se</b> prepare-release</code> does all of that for us.</p>
						<code class="terminal"><span><b>se</b> prepare-release <u>.</u></span></code>
						<p>With that done, we commit again using a commit message of “Initial publication” to signify that we’re all done with production, and now expect only proofreading corrections to be committed. (This may not actually be the case in reality, but it’s still a nice milestone to have.)</p>
						<code class="terminal">
							<span><b>git</b> add -A</span>
							<span><b>git</b> commit -m <i>"Initial publication"</i></span>
						</code>
					</li>
				</ul>
				<p>Finally, build everything again.</p>
				<code class="terminal">
					<span><b>se</b> build --output-dir=$HOME/dist/ --kindle --kobo --check <u>.</u></span>
				</code>
				<p>If the build completed successfully, congratulations! You’ve just finished producing a Standard Ebook!</p>
			</li>
		</ol>
	</article>
</main>
<?= Template::Footer() ?>
