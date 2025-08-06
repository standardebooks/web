<?= Template::Header(title: 'Producing an Ebook, Step by Step', isManual: true, highlight: 'contribute', description: 'A detailed step-by-step description of the complete process of producing an ebook for Standard Ebooks, start to finish.') ?>
<main class="manual">
	<article class="step-by-step-guide">
		<h1>Producing an Ebook, Step by Step</h1>
		<p>This guide is meant to take you step-by-step through the creation of a complete Standard Ebook. While it might seem a little long, most of the text is a description of how to use various automated scripts. It can take just an hour or two for an experienced producer to produce a draft ebook for proofreading (depending on the complexity of the ebook, of course).</p>
		<p>Our toolset is GNU/Linux-based, and producing an ebook from scratch currently requires working knowledge of the epub file format and of Unix-like systems like Mac or Linux.</p>
		<p>Our toolset doesn’t yet work natively on Windows, but there are <a href="https://www.howtogeek.com/170870/5-ways-to-run-linux-software-on-windows/">many ways to run Linux from within Windows</a>, including <a href="https://learn.microsoft.com/en-us/windows/wsl/install">one that is directly supported by Microsoft themselves</a>.</p>
		<p>If you don’t have this kind of technical expertise, you can still contribute! <a href="/contribute">Check out our contributors page for details,</a> or check out <a href="https://b-t-k.github.io/">Standard Ebooks Hints and Tricks</a>, a beginner’s guide by one of our editors.</p>
		<aside class="alert">
			<p class="warning">Before you begin</p>
			<p>We use Git to store the production and editorial history of all of our ebooks.</p>
			<p>Maintaining a clean and orderly Git history is very important to the final ebook. You should commit early and often.</p>
			<p>A single commit should only contain a single logical unit of work, like <code class="html">Typogrified text</code> or <code class="html">Fixed transcription typos</code>. Don’t cram a lot of different changes into a single commit because you forgot to commit early and often.</p>
			<p>In particular, commits that contain editorial changes to the source text, like spelling changes, must have their commit message prefaced with <code class="html">[Editorial]</code> and must not contain any non-editorial changes.</p>
			<p><strong>Do not mix editorial and non-editorial changes in a single commit.</strong> Commits are easy and free—it’s perfectly acceptable to have many very small commits, as long as each one is a single logical unit of work and doesn’t mix editorial and non-editorial changes.</p>
			<p>If you commingle editorial changes with other changes in your commits, we’ll be forced to ask you to rebase your repository to tease them out. This is very difficult and you’ll get frustrated—so please make sure to keep editorial commits separate!</p>
			<p>If your working directory contains a mix of changes and you only want to commit some of them, <code class="bash"><b>git</b> add --patch</code> is a <a href="http://git-scm.com/docs/git-add#Documentation/git-add.txt--p">useful way to only commit parts of a file</a>.</p>
		</aside>
		<details id="toc">
			<summary>Table of Contents</summary>
			<ol>
				<li><p><a href="#setup">Set up the Standard Ebooks toolset and make sure it’s up-to-date</a></p></li>
				<li><p><a href="#select">Select an ebook to produce</a></p></li>
				<li><p><a href="#locate">Locate page scans of your book online</a></p></li>
				<li><p><a href="#pitch">Contact the mailing list to pitch your production</a></p></li>
				<li><p><a href="#create">Create a Standard Ebooks epub skeleton</a></p></li>
				<li><p><a href="#rough">Do a rough cleanup of the source text and perform the first commit</a></p></li>
				<li><p><a href="#split">Split the source text at logical divisions</a></p></li>
				<li><p><a href="#clean">Clean up the source text and perform the second commit</a></p></li>
				<li><p><a href="#typogrify">Typogrify the source text and perform the corresponding commit(s)</a></p></li>
				<li><p><a href="#transcription">Check for transcription errors</a></p></li>
				<li><p><a href="#footnotes">Convert footnotes to endnotes</a></p></li>
				<li><p><a href="#illustrations">Add a list of illustrations</a></p></li>
				<li><p><a href="#quotation">Convert British quotation to American quotation</a></p></li>
				<li><p><a href="#semantics">Add semantics</a></p></li>
				<li><p><a href="#modernize">Modernize spelling and hyphenation</a></p></li>
				<li><p><a href="#diacritics">Check for consistent diacritics</a></p></li>
				<li><p><a href="#dashes">Check for consistent dashes</a></p></li>
				<li><p><a href="#titles">Set <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> elements</a></p></li>
				<li><p><a href="#manispine">Build the manifest and spine</a></p></li>
				<li><p><a href="#built-toc">Build the table of contents</a></p></li>
				<li><p><a href="#lint">Clean and lint</a></p></li>
				<li><p><a href="#proofread">Build and proofread, proofread, proofread!</a></p></li>
				<li><p><a href="#cover">Create the cover image</a></p></li>
				<li><p><a href="#content">Complete content.opf</a></p></li>
				<li><p><a href="#colophon">Complete the imprint and colophon</a></p></li>
				<li><p><a href="#checks">Final checks</a></p></li>
				<li><p><a href="#publication">Initial publication</a></p></li>
			</ol>
		</details>
		<ol>
			<li>
				<h2 id="setup">Set up the Standard Ebooks toolset and make sure it’s up-to-date</h2>
				<p>Standard Ebooks has a toolset that will help you produce an ebook. The toolset installs the <code class="bash"><b>se</b></code> command, which has various subcommands related to creating Standard Ebooks. You can <a href="/tools">read the complete installation instructions</a>, or if you already have <a href="https://pipxproject.github.io/pipx/installation/"><code class="bash"><b>pipx</b></code> installed</a>, run:</p>
				<code class="terminal"><span><b>pipx</b> install standardebooks</span></code>
				<p>The toolset changes frequently, so if you’ve installed the toolset in the past, make sure to update the toolset before you start a new ebook:</p>
				<code class="terminal"><span><b>pipx</b> upgrade standardebooks</span></code>
				<p>Once the toolset is installed, you can check which version you have with:</p>
				<code class="terminal"><span><b>se</b> --version</span></code>
			</li>
			<li>
				<h2 id="select">Select an ebook to produce</h2>
				<p>The best place to look for public domain ebooks to produce is <a href="https://www.gutenberg.org">Project Gutenberg</a>. If downloading from Project Gutenberg, be careful of the following:</p>
				<ul>
					<li>
						<p>There may be different versions of the same publication on Gutenberg, and <em>the best one might not be the one with the most downloads</em>. In particular, there could be a better translation that has fewer downloads because it was produced later, or there could be a version with better HTML markup. For example, <a href="https://www.gutenberg.org/ebooks/18857">the version of <i>Journey to the Center of the Earth</i> with the most downloads</a> is a less accurate translation than its <a href="https://www.gutenberg.org/ebooks/3748">less-frequently downloaded counterpart</a>. However, you must verify that whichever version you ultimately select is not copyrighted. For example, <a href="https://www.gutenberg.org/ebooks/2488">this modern translation of <i>Twenty Thousand Leagues Under the Seas</i></a> compares favorably to the most-downloaded <a href="https://www.gutenberg.org/ebooks/164">older translation</a>, but because the modern translation is copyrighted (see the disclaimer at the top of the HTML file) it is ineligible to be the basis of a Standard Ebooks production.</p>
					</li>
					<li>
						<p>Gutenberg usually offers both an HTML version and an epub version of the same ebook. Note that <em>one is not always exactly the same as the other!</em> A casual reader might assume that the HTML version is generated from the epub version, or the other way around; but for some reason the HTML and epub versions often differ in important ways, with the HTML version typically using fewer useless CSS classes, and including <code class="html"><span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span></code> elements that the epub version is often missing.</p>
					</li>
				</ul>
				<p>Picking either the HTML or the epub version is fine as a starting point, but make sure to pick the one that appears to be the most accurate.</p>
				<p>For this guide, we’ll use <i>The Strange Case of Dr. Jekyll and Mr. Hyde</i>, by Robert Louis Stevenson. If you search for it on Gutenberg, you’ll find that there are two versions; we will use <a href="https://www.gutenberg.org/ebooks/43">this</a> one rather than <a href="https://www.gutenberg.org/ebooks/42">this</a> one, as it is a cleaner transcription, e.g. has more modern usage of punctuation and compound words, etc.</p>
			</li>
			<li>
				<h2 id="locate">Locate page scans of your book online</h2>
				<p>As you produce your book, you’ll want to check your work against the actual page scans. Often the scans contain formatting that is missing from the source transcription. For example, older transcriptions sometimes throw away italics entirely, and you’d never know unless you looked at the page scans. So finding page scans is essential.</p>
				<p>Below are the three big resources for page scans. You should prefer them in this order:</p>
				<ul>
					<li>
						<p><a href="https://archive.org">The Internet Archive</a></p>
					</li>
					<li>
						<p><a href="https://www.hathitrust.org">The HathiTrust Digital Library</a></p>
					</li>
					<li>
						<p><a href="https://books.google.com">Google Books</a></p>
					</li>
				</ul>
				<p>Internet Archive has the widest amount of scans, with the most permissive viewing and lending policy. HathiTrust has many of the same scans as Google Books, but with a more permissive viewing policy. Google Books restricts readers based on IP address and does a poor job of implementing per-country copyright law, so people outside of the U.S. may not be able to access scans of books that are in the public domain of their country.</p>
				<p>Each of those sources allows you to filter results by publication date, so make sure you select a maximum publication date of December 31, <?= PD_YEAR ?> (in other words, everything published before <?= PD_STRING ?>) to ensure they’re in the U.S. public domain.</p>
				<p>Please keep the following important notes in mind when searching for page scans:</p>
				<ul>
					<li>
						<p>Make sure the scans you find are <em>published before <?= PD_STRING ?>.</em> You <em>must verify the copyright page in the page scans</em> before proceeding.</p>
					</li>
					<li>
						<p>Often you’ll find different editions, published at different times by different publishers, for the same book. It’s worth the effort to quickly browse through each different one to get an idea of the kinds of changes the different publishers introduced. Maybe one edition is better than another!</p>
					</li>
				</ul>
				<p>You’ll enter a link to the page scans you used in the <code class="path">content.opf</code> metadata as a <code class="html"><span class="p">&lt;</span><span class="nt">dc:source</span><span class="p">&gt;</span></code> element.</p>
			</li>
			<li>
				<h2 id="pitch">Contact the mailing list to pitch your production</h2>
				<p>If you’re looking to submit your ebook to Standard Ebooks, contact the <a href="https://groups.google.com/g/standardebooks">mailing list</a> to pitch the ebook you’ve selected, <em>before you begin production</em>. Include links to the transcription and scans you found. If you are producing this ebook for yourself, not for release at Standard Ebooks, you can skip this step.</p>
			</li>
			<li>
				<h2 id="create">Create a Standard Ebooks epub skeleton</h2>
				<p>An epub file is just a bunch of files arranged in a particular folder structure, then all zipped up. That means editing an epub file is as easy as editing a bunch of text files within a certain folder structure, then creating a zip file out of that folder.</p>
				<p>You can’t just arrange files willy-nilly, though—the epub standard expects certain files in certain places. So once you’ve picked a book to produce, create the basic epub skeleton in a working directory. <code class="bash"><b>se</b> create-draft</code> will create a basic Standard Ebooks epub folder structure, initialize a Git repository within it, and prefill a few fields in <code class="path">content.opf</code> (the file that contains the ebook’s metadata).</p>
				<ol>
					<li>
						<h3>With the <code class="bash">--pg-id</code> option</h3>
						<p>You can pass <code class="bash"><b>se</b> create-draft</code> the ID of the Project Gutenberg ebook, and it’ll try to download the ebook into <code class="path">./src/epub/text/body.xhtml</code> and prefill a lot of metadata for you:</p><code class="terminal"><span><b>se</b> create-draft --author <i>"Robert Louis Stevenson"</i> --title <i>"The Strange Case of Dr. Jekyll and Mr. Hyde"</i> --pg-id <i>43</i></span> <span><b>cd</b> <u>robert-louis-stevenson_the-strange-case-of-dr-jekyll-and-mr-hyde/</u></span></code>
						<p>If the book you’re working on was translated into English from another language, you’ll need to include the translator as well, using the <code class="bash">--translator</code> argument. (For translated books that don’t have a translator credited, you can use the name of the publisher for this argument.)</p><code class="terminal"><span><b>se</b> create-draft --author <i>"Leo Tolstoy"</i> --translator <i>"Louise Maude"</i> --title <i>"Resurrection"</i> --pg-id <i>1938</i></span> <span><b>cd</b> <u> leo-tolstoy_resurrection_louise-maude/</u></span></code>
						<p>In the unusual case that your book has <em>multiple</em> translators, you will include each one by putting each translator’s name in quotation marks after the <code class="bash">--translator</code> argument, like so:</p><code class="terminal"><span><b>se</b> create-draft --author <i>"Leo Tolstoy"</i> --translator <i>"Louise Maude"</i> <i>"Aylmer Maude"</i> --title <i>"The Power of Darkness"</i> --pg-id <i>26661</i></span> <span><b>cd</b> <u>leo-tolstoy_the-power-of-darkness_louise-maude_aylmer-maude/</u></span></code>
						<p>Because Project Gutenberg ebooks are produced in different ways by different people, <code class="bash"><b>se</b> create-draft</code> has to make some guesses and it might guess wrong. Make sure to carefully review the data it prefills into <code class="path">./src/epub/text/body.xhtml</code>, <code class="path">./src/epub/text/colophon.xhtml</code>, and <code class="path">./src/epub/content.opf</code>.</p>
						<p>In particular, make sure that the Project Gutenberg license is stripped from <code class="path">./src/epub/text/body.xhtml</code>, and that the original transcribers in <code class="path">./src/epub/text/colophon.xhtml</code> and <code class="path">./src/epub/content.opf</code> are presented correctly.</p>
					</li>
					<li>
						<h3>Without the <code class="bash">--pg-id</code> option</h3>
						<p>If you prefer to do things by hand, that’s an option too.</p><code class="terminal"><span><b>se</b> create-draft --author <i>"Robert Louis Stevenson"</i> --title <i>"The Strange Case of Dr. Jekyll and Mr. Hyde"</i></span> <span><b>cd</b> <u>robert-louis-stevenson_the-strange-case-of-dr-jekyll-and-mr-hyde/</u></span></code>
						<p>Now that we have the skeleton up, we’ll download Gutenberg’s HTML file for <i>Jekyll</i> directly into <code class="path">text/</code> folder and name it <code class="path">body.xhtml</code>.</p><code class="terminal"><span><b>wget</b> -O src/epub/text/body.xhtml <i>"https://www.gutenberg.org/files/43/43-h/43-h.htm"</i></span></code>
						<p>Many Gutenberg books were produced before UTF-8 became a standard, so we may have to convert to UTF-8 before we start work. First, check the encoding of the file we just downloaded. (Mac OS users, try <code class="bash"><b>file</b> -I</code>.)</p><code class="terminal"><span><b>file</b> -bi <u>src/epub/text/body.xhtml</u></span></code>
						<p>The output is <code class="bash">text/html; charset=iso-8859-1</code>. That’s the wrong encoding!</p>
						<p>We can convert that to UTF-8 with <code class="bash"><b>iconv</b></code>:</p><code class="terminal"><span><b>iconv</b> --from-code <i>"ISO-8859-1"</i> --to-code <i>"UTF-8"</i> &lt; <u>src/epub/text/body.xhtml</u> &gt; src/epub/text/tmp</span> <span><b>mv</b> <u>src/epub/text/tmp</u> <u>src/epub/text/body.xhtml</u></span></code>
					</li>
				</ol>
			</li>
			<li>
				<h2 id="rough">Do a rough cleanup of the source text and perform the first commit</h2>
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
						<p>Remove any footer text (e.g. “The End”), as well as any markup after the public domain text ends. This includes the Gutenberg license—but don’t worry, we’ll credit Gutenberg in the colophon and metadata later. If you invoked <code class="bash"><b>se</b> create-draft</code> with the <code class="bash">--pg-id</code> option, then it may have already stripped the license for you and included some Gutenberg metadata.</p>
					</li>
				</ul>
				<p>Now our source file looks something like this:</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">h2</span><span class="p">&gt;</span> STORY OF THE DOOR <span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span> Mr. Utterson the lawyer was a man of a rugged countenance that was never lighted by a smile; cold, scanty and embarrassed in discourse; backward in
<span class="c">&lt;!--snip all the way to the end...--&gt;</span>
proceed to seal up my confession, I bring the life of that unhappy Henry Jekyll to an end. <span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>

				<p>Now that we’ve removed all the cruft from the top and bottom of the file, we’re ready for our first commit.</p>
				<p>Each commit has an accompanying message describing the changes we are making. Please use the commit messages as they are written here in this guide as the editors rely on these messages when they review the work.</p>
				<p>Also, try to make one commit per type of change, for example: “fixing typos in chapters 1-18” or “worked on letter formatting.”</p>
				<p>For this first commit:</p><code class="terminal"><span><b>git</b> add -A</span> <span><b>git</b> commit -m <i>"Initial commit"</i></span></code>
			</li>
			<li>
				<h2 id="split">Split the source text at logical divisions</h2>
				<p>The file we downloaded contains the entire work. <i>Jekyll</i> is a short work, but for longer work it quickly becomes impractical to have the entire text in one file. Not only is it a pain to edit, but ereaders often have trouble with extremely large files.</p>
				<p>The next step is to split the file at logical places; that usually means at each chapter break. For works that contain their chapters in larger “parts,” the part division should also be its own file. For example, see <i><a href="/ebooks/robert-louis-stevenson/treasure-island">Treasure Island</a></i>.</p>
				<p>To split the work, we use <code class="bash"><b>se</b> split-file</code>. <code class="bash"><b>se</b> split-file</code> takes a single file and breaks it in to a new file every time it encounters the markup <code class="html"><span class="c">&lt;!--se:split--&gt;</span></code>. <code class="bash"><b>se</b> split-file</code> automatically includes basic header and footer markup in each split file.</p>
				<p>Notice that in our source file, each chapter is marked with an <code class="html"><span class="p">&lt;</span><span class="nt">h2</span><span class="p">&gt;</span></code> element. We can use that to our advantage and save ourselves the trouble of adding the <code class="html"><span class="c">&lt;!--se:split--&gt;</span></code> markup by hand:</p><code class="terminal"><span><b>perl</b> -pi -e <!--Single quote to prevent ! from becoming history expansion--><i>'s|&lt;h2|&lt;!--se:split--&gt;&lt;h2|g'</i> <u>src/epub/text/body.xhtml</u></span></code>
				<p>Now that we’ve added our markers, we split the file. <code class="bash"><b>se</b> split-file</code> puts the results in our current directory and conveniently names them by chapter number.</p><code class="terminal"><span><b>se</b> split-file <u>src/epub/text/body.xhtml</u></span> <span><b>mv</b> chapter<i class="glob">*</i> <u>src/epub/text/</u></span></code>
				<p>Once we’re happy that the source file has been split correctly, we can remove it.</p><code class="terminal"><span><b>rm</b> <u>src/epub/text/body.xhtml</u></span></code>
			</li>
			<li>
				<h2 id="clean">Clean up the source text and perform the second commit</h2>
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
				<p>If you look carefully, you’ll notice that the <code class="html"><span class="p">&lt;</span><span class="nt">html</span><span class="p">&gt;</span></code> element has the <code class="html"><span class="na">xml:lang</span><span class="o">=</span><span class="s">"en-US"</span></code> attribute, even though our source text uses British spelling! We have to change the <code class="html"><span class="na">xml:lang</span></code> attribute for the source files to match the actual language, which in this case is en-GB. Let’s do that now:</p><code class="terminal"><span><b>perl</b> -pi -e <i>"s|en-US|en-GB|g"</i> src/epub/text/chapter<i class="glob">*</i></span></code>
				<p>Note that we <em>don’t</em> change the language for the metadata or boilerplate files, like <code class="path">colophon.xhtml</code>, <code class="path">imprint.xhtml</code>, <code class="path">toc.xhtml</code>, or <code class="path">titlepage.xhtml</code>. Those must always be in American spelling, so they’ll always have the en-US language tag.</p>
				<p>Once the file split and cleanup is complete, you can perform your second commit.</p><code class="terminal"><span><b>git</b> add -A</span> <span><b>git</b> commit -m <i>"Split files and clean"</i></span></code>
			</li>
			<li>
				<h2 id="typogrify">Typogrify the source text and perform the corresponding commit(s)</h2>
				<p>Now that we have a clean starting point, we can start getting the <em>real</em> work done. <code class="bash"><b>se</b> typogrify</code> can do a lot of the heavy lifting necessary to bring an ebook up to Standard Ebooks typography standards.</p>
				<p>Like <code class="bash"><b>se</b> clean</code>, <code class="bash"><b>se</b> typogrify</code> accepts as its argument the root of a Standard Ebook directory.</p><code class="terminal"><span><b>se</b> typogrify <u>.</u></span></code>
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
				<p>While <code class="bash"><b>se</b> typogrify</code> does a lot of work for you, each ebook is totally different so there’s almost always more work to do that can only be done by hand. However, you will do a third commit first, to put the automated changes in a separate commit from any manual changes.</p><code class="terminal"><span><b>git</b> add -A</span> <span><b>git</b> commit -m <i>"Typogrify"</i></span></code>
				<p>As an example of manual changes that might be needed, in <i>Jekyll</i>, you’ll notice that the chapter titles are in all caps. The S.E. standard requires chapter titles to be in title case, and <code class="bash"><b>se</b> titlecase</code> can do that for us. <code class="bash"><b>se</b> titlecase</code> accepts a string as its argument, and outputs the string in title case.</p>
				<aside class="tip">
					<p>Many text editors allow you to configure external macros—perfect for creating a keyboard shortcut to run <code class="bash"><b>se</b> titlecase</code> on selected text.</p>
					<p>If you do that, you might find its <code class="bash">--no-newline</code> flag helpful to prevent an extra newline from being inserted into your document.</p>
				</aside>
				<h3>Typography checklist</h3>
				<p>There are many things that <code class="bash"><b>se</b> typogrify</code> isn’t well suited to do automatically. Check the <a href="/manual/latest/8-typography">Typography section of the <abbr class="acronym">SEMoS</abbr></a> to see exactly how to format the work. Below is a brief, but incomplete, list of common issues that arise in ebooks:</p>
				<aside class="tip">
				<p><code class="bash"><b>se</b> interactive-replace</code> is a useful tool for quickly performing a regular expression search-and-replace, while reviewing each replacement. To use it, pass it a regex and a replacement. Then for each replacement, press <code>y</code> to accept the replacement or <code>n</code> to reject it.</p>
				</aside>
				<ul>
					<li>
						<p><a href="/manual/latest/8-typography#8.7.5.3">Elision</a>. <code class="html">’</code> (i.e., <code class="html">&amp;rsquo;</code>) is used for elided letters in a word. <code class="bash"><b>se</b> typogrify</code> often gets this wrong, and you need to review your ebook by hand to ensure it didn’t insert <code class="html">‘</code> (<code class="html">&amp;lsquo;</code>) instead.</p>
						<code class="terminal"><span><b>se</b> interactive-replace --ignore-case <i>"(\s)‘([a-z])"</i> <i>"\1’\2"</i> src/epub/text/<i class="glob">*</i></span></code>
					</li>
					<li>
						<p><a href="/manual/latest/8-typography#8.8.1">Coordinates</a>. Use the prime and double prime glyphs for coordinates.</p>
						<code class="terminal"><span><b>se</b> interactive-replace <i>"([0-9]+)’"</i> <i>"\1′"</i> src/epub/text/<i class="glob">*</i></span></code>
						<code class="terminal"><span><b>se</b> interactive-replace <i>"([0-9]+)”"</i> <i>"\1″"</i> src/epub/text/<i class="glob">*</i></span></code>
					</li>
					<li>
						<p><a href="/manual/latest/8-typography#8.3.3">Text in all caps</a>. Text in all caps is almost never correct, and should either be converted to lowercase with the <code class="html"><span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span></code> element (for spoken emphasis), <code class="html"><span class="p">&lt;</span><span class="nt">strong</span><span class="p">&gt;</span></code> (for extreme spoken emphasis), or <code class="html"><span class="p">&lt;</span><span class="nt">b</span><span class="p">&gt;</span></code> (for unsemantic small caps, like in storefront signs).</p>
						<p>Use the following command to check for instances of all caps:</p>
						<code class="terminal"><b>se</b> xpath <i>"//p//text()[re:test(., '[A-Z]{2,}') and not(contains(., 'OK') or contains(., 'SOS')) and not(parent::abbr or parent::var or parent::a or parent::*[contains(@epub:type, 'z3998:roman')])]"</i> <u>src/epub/text/<i class="glob">*</i>.xhtml</u></code>
					</li>
					<li>
						<p>Sometimes <code class="bash"><b>se</b> typogrify</code> doesn’t close quotation marks near em-dashes correctly.</p>
						<p>Use this regex to find incorrectly closed quotation marks near em-dashes: <code class="regex">—[’”][^<span class="p">&lt;</span>\s]</code></p>
					</li>
					<li>
						<p><a href="/manual/latest/8-typography#8.7.7.9">Two-em dashes should be used for partially-obscured words</a>.</p>
					</li>
					<li>
						<p>Commas and periods should generally be inside quotation marks, not outside. Use this command to find and replace them:</p>
						<code class="terminal"><span><b>se</b> interactive-replace <i>"([’”])([,.])" "\2\1"</i> src/epub/text/<i class="glob">*</i></span></code>
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
					<li>
						<p>
							<a href="/manual/latest/single-page#8.7.7.6">Non-breaking hyphens are used when a word is stretched out for effect.</a>
						</p>
						<p>Although it will produce a lot of false positives, this regex can help you find stretched words: <code class="regex">(?i)([a-z])-\1</code></p>
					</li>
				</ul>
				<h3>The fourth commit</h3>
				<p>Once you’ve searched the work for the common issues above, if any manual changes were necessary, you should perform the fourth commit.</p><code class="terminal"><span><b>git</b> add -A</span> <span><b>git</b> commit -m <i>"Manual typography changes"</i></span></code>
			</li>
			<li>
				<h2 id="transcription">Check for transcription errors</h2>
				<p>Transcriptions often have errors, because the O.C.R. software might confuse letters for other, more unusual characters, or because the ebook’s character set got mangled somewhere along the way from the source to your repository. You’ll find most transcription errors when you proofread the text, but right now you use the <code class="bash"><b>se</b> find-unusual-characters</code> tool to see a list of any unusual characters in the transcription. If the tool outputs any, check the source to make sure those characters aren’t errors.</p><code class="terminal"><span><b>se</b> find-unusual-characters <u>.</u></span></code>
				<p>If any errors had to be corrected, a commit is needed as well.</p><code class="terminal"><span><b>git</b> add -A</span> <span><b>git</b> commit -m <i>"Correct transcription errors"</i></span></code>
			</li>
			<li>
				<h2 id="footnotes">Convert footnotes to endnotes</h2>
				<p>Works often include footnotes, either added by an annotator or as part of the work itself. Since ebooks don’t have a concept of a “page,” there’s no place for footnotes to go. Instead, we convert footnotes to a single endnotes file, which will provide popup references in the final epub.</p>
				<p>The endnotes file and the format for endnote links are <a href="/manual/latest/7-high-level-structural-patterns#7.10">standardized in the <abbr class="acronym">SEMoS</abbr></a>.</p>
				<p>You can add a template of an endnotes file using:</p>
				<code class="terminal"><span><b>se</b> add-file endnotes <u>.</u></span></code>
				<p>If you find that you accidentally mis-ordered an endnote, never fear! <code class="bash"><b>se</b> shift-endnotes</code> will allow you to quickly rearrange endnotes in your ebook.</p>
				<p>If any footnotes were present and moved to endnotes, do another commit.</p><code class="terminal"><span><b>git</b> add -A</span> <span><b>git</b> commit -m <i>"Move footnotes to endnotes"</i></span></code>
				<p><i>Jekyll</i> doesn’t have any footnotes or endnotes, so we skip this step.</p>
			</li>
			<li>
				<h2 id="illustrations">Add a list of illustrations</h2>
				<p>If a work has illustrations besides the cover and title pages, we include a “list of illustrations” at the end of the book, after the endnotes but before the colophon. The <abbr class="initialism">LoI</abbr> file <a href="/manual/latest/7-high-level-structural-patterns#7.9">is also standardized</a>.</p>
				<p>If an LoI is created, do a corresponding commit.</p><code class="terminal"><span><b>git</b> add -A</span> <span><b>git</b> commit -m <i>"Add LoI"</i></span></code>
				<p><i>Jekyll</i> doesn’t have any illustrations, so we skip this step.</p>
			</li>
			<li>
				<h2 id="quotation">Convert British quotation to American quotation</h2>
				<p>If the work you’re producing uses <a href="http://www.thepunctuationguide.com/british-versus-american-style.html">British quotation style</a> (single quotes for dialog and other outer quotes versus double quotes in American), we have to convert it to American style. We use American style in part because it’s easier to programmatically convert from American to British than it is to convert the other way around. <em>Skip this step if your work is already in American style.</em></p>
				<p><code class="bash"><b>se</b> british2american</code> attempts to automate the conversion. Your work must already be typogrified (one of the previous steps in this guide) for the script to work.</p><code class="terminal"><span><b>se</b> british2american <u>.</u></span></code>
				<p>While <code class="bash"><b>se</b> british2american</code> tries its best, thanks to the quirkiness of English punctuation rules it’ll invariably mess some stuff up. Proofreading is required after running the conversion.</p>
				<aside class="tip">
					<p>This regex is useful for spotting incorrectly converted quotes next to em dashes: <code class="regex">“[^”‘]+’⁠—</code></p>
				</aside>
				<p>After you’ve run the conversion, do another commit.</p><code class="terminal"><span><b>git</b> commit -am <i>"Convert from British-style quotation to American style"</i></span></code>
			</li>
			<li>
				<h2 id="semantics">Add semantics</h2>
				<p>Part of producing a book for Standard Ebooks is adding meaningful semantics wherever possible in the text. <code class="bash"><b>se</b> semanticate</code> does a little of that for us—for example, for some common abbreviations—but much of it has to be done by hand.</p>
				<p>Adding semantics means two things:</p>
				<ol>
					<li>
						<p>Using meaningful elements to mark up the work: <code class="html"><span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span></code> when conveying emphatic speech instead of <code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code>, <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span></code> to wrap abbreviations, <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> to mark structural divisions, using the <code class="html"><span class="na">xml:lang</span></code> attribute to specify the language of a word or passage, and so on.</p>
					</li>
					<li>
						<p>Using the <a href="http://www.idpf.org/epub/30/spec/epub30-contentdocs.html#sec-xhtml-semantic-inflection">epub3 semantic inflection language</a> to add deeper meaning to elements.</p>
						<p>Currently we use a mix of <a href="http://www.idpf.org/epub/vocab/structure/">epub3 structural semantics</a>, <a href="http://www.daisy.org/z3998/2012/vocab/structure/">z3998 structural semantics</a> for when the epub3 vocabulary isn’t enough, and our own <a href="/vocab/1.0">S.E. semantics</a> for when z3998 isn’t enough.</p>
					</li>
				</ol>
				<p>Use <code class="bash"><b>se</b> semanticate</code> to do some common cases for you:</p><code class="terminal"><span><b>se</b> semanticate <u>.</u></span></code>
				<p><code class="bash"><b>se</b> semanticate</code> tries its best to correctly add semantics, but sometimes it’s wrong. For that reason you should review the changes it made before accepting them:</p><code class="terminal"><span><b>git</b> difftool</span></code>
				<p>As we did with <code class="bash">typogrify</code>, we want the automated portion of adding semantics to be in its own commit. After running <code class="bash">semanticate</code>, do another commit.</p><code class="terminal"><span><b>git</b> commit -am <i>"Semanticate"</i></span></code>
				<p>Beyond that, adding semantics is mostly a by-hand process. See the <a href="/manual"><abbr class="acronym">SEMoS</abbr></a> for a detailed list of the kinds of semantics we expect in a Standard Ebook.</p>
				<p>Here’s a short list of some of the more common semantic issues you’ll encounter:</p>
				<ul>
					<li>
						<p>Semantics for italics: <code class="html"><span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span></code> should be used for when a passage is emphasized, as in when dialog is shouted or whispered. <code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code> is used for all other italics, <a href="/manual/latest/4-semantics#4.2">with the appropriate semantic inflection</a>. Older transcriptions usually use just <code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code> for both, so you must change them manually if necessary.</p>
						<p>Sometimes, transcriptions from Project Gutenberg may use ALL CAPS instead of italics. To replace these, you can use:</p>
						<code class="terminal"><span><b>perl</b> -pi -e <i>"use utf8;s|([A-Z’]{2,})|&lt;em&gt;\L\1&lt;/em&gt;|g"</i> src/epub/text/<i class="glob">*</i></span></code>
						<p>This will unfortunately replace language tags like <code>en-US</code>, so fix those up with this:</p>
						<code class="terminal"><span><b>perl</b> -pi -e <i>"use utf8;s|en-&lt;em&gt;([a-z]+)&lt;/em&gt;|en-\U\1|g"</i> src/epub/text/<i class="glob">*</i></span></code>
						<p>These replacements don’t take Title Caps or roman numerals into account, so use <code class="bash"><b>git</b> diff</code> to review the changes and fix errors before committing.</p>
					</li>
					<li>
						<p><a href="/manual/latest/8-typography#8.1">Semantics rules for chapter titles</a>.</p>
					</li>
					<li>
						<p><a href="/manual/latest/8-typography#8.10">Semantics rules for abbreviations</a>. Abbreviations should always be wrapped in the <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span></code> element and with the correct <code class="html"><span class="na">epub:type</span></code> attribute.</p>
						<p>Specifically, see the <a href="/manual/latest/8-typography#8.10.4">typography rules for initials</a>. Wrap people’s initials in <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:given-name"</span><span class="p">&gt;</span></code>. This command helps wrap initials:</p>
						<code class="terminal"><span><b>se</b> interactive-replace <!--Single quote to prevent ! from becoming history expansion--><i>'(?&lt;!&lt;abbr[^&lt;]*?&gt;)([A-Z]\.(\s?[A-Z]\.)*)(?!&lt;/abbr&gt;|”)'</i> <i>'&lt;abbr epub:type="z3998:given-name"&gt;\1&lt;/abbr&gt;'</i> src/epub/text/<i class="glob">*</i></span></code>
					</li>
					<li>
						<p><a href="/manual/latest/8-typography#8.11">Typography rules for times</a>. Wrap a.m. and p.m. in <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span></code> and add a no-break space between digits and a.m. or p.m.</p>
					</li>
					<li>
						<p>Words or phrases in foreign languages should always be marked up with <code class="html"><span class="p">&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"TAG"</span><span class="p">&gt;</span></code>, where TAG is an <a href="https://en.wikipedia.org/wiki/IETF_language_tag">IETF language tag</a>. <a href="https://r12a.github.io/app-subtags/">This website can help you look them up</a>. If the text uses fictional or unspecific languages, use the <code class="html">x-</code> prefix and make up a subtag yourself.</p>
					</li>
					<li>
						<p>Semantics for poetry, verse, and song: Many Gutenberg productions use the <code class="html"><span class="p">&lt;</span><span class="nt">pre</span><span class="p">&gt;</span></code> element to format poetry, verse, and song. This is, of course, semantically incorrect. <a href="/manual/latest/7-high-level-structural-patterns#7.5">See the Poetry section of the <abbr class="acronym">SEMoS</abbr></a> for templates on how to semantically format poetry, verse, and song.</p>
					</li>
				</ul>
				<p>After you’ve added semantics according to the <a href="/manual"><abbr class="acronym">SEMoS</abbr></a>, do another commit.</p><code class="terminal"><span><b>git</b> commit -am <i>"Manually add additional semantics"</i></span></code>
			</li>
			<li>
				<h2 id="modernize">Modernize spelling and hyphenation</h2>
				<p>Many older works use outdated spelling and hyphenation that would distract a modern reader. (For example, <code class="html">to-night</code> instead of <code class="html">tonight</code>). <code class="bash"><b>se</b> modernize-spelling</code> automatically removes hyphens from words that used to be compounded, but aren’t anymore in modern English spelling.</p>
				<p><em>Do</em> run this tool on prose. <em>Don’t</em> run this tool on poetry.</p>
				<code class="terminal"><span><b>se</b> modernize-spelling <u>.</u></span></code>
				<p>After you run the tool, <em>you must check what the tool did to confirm that each removed hyphen is correct</em>. Sometimes the tool will remove a hyphen that needs to be included for clarity, or one that changes the meaning of the word, or it may result in a word that just doesn’t seem right. Re-introducing a hyphen is OK in these cases.</p>
				<p>Here’s a real-world example of where <code class="bash"><b>se</b> modernize-spelling</code> made the wrong choice: In <i><a href="/ebooks/oscar-wilde/the-picture-of-dorian-gray">The Picture of Dorian Gray</a></i> <a href="/ebooks/oscar-wilde/the-picture-of-dorian-gray/text/chapter-11">chapter 11</a>, Oscar Wilde writes:</p>
				<blockquote>
					<p>He possessed a gorgeous cope of crimson silk and gold-thread damask…</p>
				</blockquote>
				<p><code class="bash"><b>se</b> modernize-spelling</code> would replace the dash in <code class="html">gold-thread</code> so that it reads <code class="html">goldthread</code>. Well <code class="html">goldthread</code> is an actual word, which is why it’s in our dictionary, and why the script makes a replacement—but it’s the name of a type of flower, <em>not</em> a golden fabric thread! In this case, <code class="bash"><b>se</b> modernize-spelling</code> made an incorrect replacement, and we have to change it back.</p>
				<aside class="tip">
					<p><code class="bash"><b>git</b></code> compares changes line-by-line, but since lines in an ebook can be very long, a line-level comparison can make spotting small changes difficult. Instead of just doing <code class="bash"><b>git</b> diff</code>, try the following command to highlight changes at the character level:</p>
					<code class="terminal"><span><b>git</b> -c color.ui=always diff -U0 --word-diff-regex=.</span></code>
					<p>You can also <a href="https://stackoverflow.com/questions/10998792/how-to-color-the-git-console">enable color in your <code class="bash"><b>git</b></code> output globally</a>, or assign this command to a shortcut in your <code class="bash"><b>git</b></code> configuration.</p>
					<p>Alternatively, you can use an external diff GUI to review changes in closer detail:</p>
					<code class="terminal"><span><b>git</b> difftool</span></code>
				</aside>
				<h3>Modernize spacing in select words</h3>
				<p>Over time, spelling of certain common two-word phrases has evolved into a single word. For example, <code class="html">someone</code> used to be the two-word phrase <code class="html">some one</code>, which would read awkwardly to modern readers. This is our chance to modernize such phrases.</p>
				<p>Note that we use <code class="bash"><b>se</b> interactive-replace</code> to perform an interactive search and replace, instead of doing a global, non-interactive search and replace. This is because some phrases caught by the regular expression should not be changed, depending on context. For example, <code class="html">some one</code> in the following snippet from <a href="/ebooks/anton-chekhov/short-fiction/constance-garnett">Anton Chekhov’s short fiction</a> <em>should not</em> be corrected:</p>
				<blockquote>
					<p>He wanted to think of some one part of nature as yet untouched...</p>
				</blockquote>
				<p>Use each of the following commands to correct a certain set of such phrases:</p>
				<ul class="changes">
					<li>
						<h3>some one ➔ someone</h3>
						<table>
							<tbody>
								<tr>
									<td>Correct change:</td>
									<td>
										<p><code class="html">She asked some one on the street.</code> ➔</p>
										<p><code class="html">She asked someone on the street.</code></p>
									</td>
								</tr>
								<tr>
									<td>Incorrect change:</td>
									<td>
										<p><code class="html">But every clever crime is founded ultimately on some one quite simple fact.</code> ➔</p>
										<p><code class="html"><span class="wrong">But every clever crime is founded ultimately on someone quite simple fact.</span></code></p>
									</td>
								</tr>
							</tbody>
						</table>
						<code class="terminal"><span><b>se</b> interactive-replace <i>"\b([Ss])ome one" "\1omeone"</i> src/epub/text/<i class="glob">*</i></span></code>
					</li>
					<li>
						<h3>any one ➔ anyone</h3>
						<table>
							<tbody>
								<tr>
									<td>Correct change:</td>
									<td>
										<p><code class="html">“Any one else on this floor?” he asked.</code> ➔</p>
										<p><code class="html">“Anyone else on this floor?” he asked.</code></p>
									</td>
								</tr>
								<tr>
									<td>Incorrect change:</td>
									<td>
										<p><code class="html">It is not easy to restore lost property to any one of them.</code> ➔</p>
										<p><code class="html"><span class="wrong">It is not easy to restore lost property to anyone of them.</span></code></p>
									</td>
								</tr>
							</tbody>
						</table>
						<code class="terminal"><span><b>se</b> interactive-replace <i>"\b([Aa])ny one" "\1nyone"</i> src/epub/text/<i class="glob">*</i></span></code>
					</li>
					<li>
						<h3>every one ➔ everyone</h3>
						<table>
							<tbody>
								<tr>
									<td>Correct change:</td>
									<td>
										<p><code class="html">He was furious<span class="ws">wj</span>—furious with himself, furious with every one.</code> ➔</p>
										<p><code class="html">He was furious<span class="ws">wj</span>—furious with himself, furious with everyone.</code></p>
									</td>
								</tr>
								<tr>
									<td>Incorrect change:</td>
									<td>
										<p><code class="html">I’m sure we missed ten for every one we saw.</code> ➔</p>
										<p><code class="html"><span class="wrong">I’m sure we missed ten for everyone we saw.</span></code></p>
									</td>
								</tr>
							</tbody>
						</table>
						<code class="terminal"><span><b>se</b> interactive-replace <!--Single quote to prevent ! from becoming history expansion--><i>'(?&lt;![Ee]ach and )([Ee])very one(?!\s+of)' "\1veryone"</i> src/epub/text/<i class="glob">*</i></span></code>
					</li>
					<li>
						<h3>any way ➔ anyway</h3>
						<table>
							<tbody>
								<tr>
									<td>Correct change:</td>
									<td>
										<p><code class="html">Not all, of course, but any way it is much better than the life here.</code> ➔</p>
										<p><code class="html">Not all, of course, but anyway it is much better than the life here.</code></p>
									</td>
								</tr>
								<tr>
									<td>Incorrect change:</td>
									<td>
										<p><code class="html">And I’m not at fault in any way, and there’s no need for me to suffer.</code> ➔</p>
										<p><code class="html"><span class="wrong">And I’m not at fault in anyway, and there’s no need for me to suffer.</span></code></p>
									</td>
								</tr>
							</tbody>
						</table>
						<code class="terminal"><span><b>se</b> interactive-replace <!--Single quote to prevent ! from becoming history expansion--><i>'(?&lt;!in\s+)\b([Aa])ny way(?!\s+(?:of|to))' "\1nyway"</i> src/epub/text/<i class="glob">*</i></span></code>
					</li>
					<li>
						<h3>with out ➔ without</h3>
						<table>
							<tbody>
								<tr>
									<td>Correct change:</td>
									<td>
										<p><code class="html">Send with out delay warrant of arrest to Bombay.</code> ➔</p>
										<p><code class="html">Send without delay warrant of arrest to Bombay.</code></p>
									</td>
								</tr>
								<tr>
									<td>Incorrect change:</td>
									<td>
										<p><code class="html">“Who on earth are you talking with out there?” called the querulous voice.</code> ➔</p>
										<p><code class="html"><span class="wrong">“Who on earth are you talking without there?” called the querulous voice.</span></code></p>
									</td>
								</tr>
							</tbody>
						</table>
						<code class="terminal"><span><b>se</b> interactive-replace <i>"\b([Ww])ith out\b([^-])" "\1ithout\2"</i> src/epub/text/<i class="glob">*</i></span></code>
					</li>
				</ul>
				<p>After you’ve reviewed the changes, create an <code class="html">[Editorial]</code> commit. This type of commit is important, because it gives purists an avenue to reverse these changes back to the original text.</p>
				<aside class="tip">
					<p>Editorial changes are those where we make an editorial decision to alter the original text, for example modernizing spelling or fixing a probable printer’s typo.</p>
					<p>Fixing a transcriber’s typo—i.e. where the transcriber made a mistake when converting the page scans to digital text—is <strong>not</strong> an editorial change.</p>
				</aside>
				<aside class="tip">
					<p><code class="html">[Editorial]</code> commits are an important part of the ebook’s history. They make it easier for reviewers to confirm your work, and they make it easy for readers to see how we may have changed the text.</p>
					<p>You <strong>must</strong> use commits prefaced with <code class="html">[Editorial]</code> when you’re making editorial changes to the text. These commits may <strong>only</strong> contain editorial changes and <strong>no other work on the ebook</strong>.</p>
					<p>Commits are easy and free. Don’t worry about making many small commits, if it means that editorial commits are clean and isolated. If you commingle editorial changes with other changes, we’ll have to ask you to rebase your repository to tease them out. This is very difficult—so please make sure to keep editorial commits separate!</p>
				</aside>
				<code class="terminal"><span><b>git</b> commit -am <i>"[Editorial] Modernize hyphenation and spelling"</i></span></code>
				<h3>Manual spelling changes</h3>
				<p>You can and should update spelling of other words that you come across during proofreading, with the following caveats:</p>
				<ul>
					<li>
						<p>We modernize <strong>sound-alike spelling</strong>, not grammar or word usage. Thus, updating <code class="html">mak</code> to <code class="html">make</code> is OK, but changing <code class="html">maketh</code> to <code class="html">makes</code> is not.</p>
					</li>
					<li>
						<p>We usually don’t bother with adding or removing dashes or spaces from compound words, other than what <code class="bash"><b>se</b> modernize-spelling</code> does. For example <code class="html">dining-room</code> might read more modern with a space, but dashes and spaces are inconsistent over a large number of words and it’s too much work to keep a master list.</p>
					</li>
					<li>
						<p>Don’t change spelling from en-US to en-GB or vice-versa, even to align with a book’s “language.” Having mixed spelling in a book was common and not something we standardize. <a href="https://en.wikipedia.org/wiki/American_and_British_English_spelling_differences">This article</a> provides a good overview of the differences between British and U.S. English.</p>
					</li>
					<li>
						<p>If, after having run <code class="bash"><b>se</b> modernize-spelling</code>, you find a hyphenated compound word that appears in Merriam-Webster's basic online search results without a hyphen, then you can make an Editorial change to update it. Please also let us know so that we can update <code class="bash"><b>se</b> modernize-spelling</code>.</p>
					</li>
					<li>
						<p>If you find an archaic word that you think should be modernized, a good way to check is with a <a href="https://books.google.com/ngrams/">Google Ngram search</a>. Remember to select either American English or British English!</p>
					</li>
				</ul>
				<p>Any manual spelling changes made must be in an <code class="html">[Editorial]</code> commit, e.g.</p>
				<code class="terminal"><b>git</b> commit -m <i>"[Editorial] mak -> make"</i></code>
			</li>
			<li>
				<h2 id="diacritics">Check for consistent diacritics</h2>
				<p>Sometimes during transcription or even printing, instances of some words might have diacritics while others don’t. For example, a word in one chapter might be spelled <code class="html">châlet</code>, but in the next chapter it might be spelled <code class="html">chalet</code>.</p>
				<p><code class="bash"><b>se</b> find-mismatched-diacritics</code> lists these instances for you to review. Spelling should be normalized across the work so that all instances of the same word are spelled in the same way. Keep the following in mind as you review these instances:</p>
				<ul>
					<li>
						<p>In modern English spelling, many diacritics are removed (like <code class="html">chalet</code>). If in doubt, ask your assigned project manager.</p>
					</li>
					<li>
						<p>Even though diacritics might be removed in English spelling, they may be preserved in non-English text, or in proper names.</p>
						<blockquote>
							<p>He visited the hotel called the Châlet du Nord.</p>
						</blockquote>
					</li>
				</ul>
				<code class="terminal"><span><b>se</b> find-mismatched-diacritics <u>.</u></span></code>
				<p>If any changes had to be made, a corresponding editorial commit should be done as well.</p><code class="terminal"><span><b>git</b> commit -am <i>"[Editorial] Correct mismatched diacritics"</i></span></code>
			</li>
			<li>
				<h2 id="dashes">Check for consistent dashes</h2>
				<p>Similar to <code class="bash"><b>se</b> find-mismatched-diacritics</code>, <code class="bash"><b>se</b> find-mismatched-dashes</code> lists instances where a compound word is spelled both with and without a dash. Dashes in words should be normalized to one or the other style.</p>
				<code class="terminal"><span><b>se</b> find-mismatched-dashes <u>.</u></span></code>
				<p>If corrections were made, another commit is needed.</p><code class="terminal"><span><b>git</b> commit -am <i>"[Editorial] Correct mismatched dashes"</i></span></code>
			</li>
			<li>
				<h2 id="titles">Set <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> elements</h2>
				<p>After you’ve added semantics and correctly marked up <a href="/manual/latest/7-high-level-structural-patterns#7.2">section headers</a>, it’s time to update the <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> elements in each chapter to match <a href="/manual/latest/5-general-xhtml-and-css-patterns#5.4">their expected values</a>.</p>
				<p>The <code class="bash"><b>se</b> build-title</code> tool takes a well-marked-up section header from a file, and updates the file’s <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element to match:</p>
				<code class="terminal"><span><b>se</b> build-title <u>.</u></span></code>
				<p>Once you’ve verified the titles look good, commit:</p>
				<code class="terminal"><span><b>git</b> commit -am <i>"Add titles"</i></span></code>
			</li>
			<li>
				<h2 id="manispine">Build the manifest and spine</h2>
				<p>In <code class="path">content.opf</code>, the manifest is a list of all of the files in the ebook. The spine is the reading order of the various XHTML files.</p>
				<p><code class="bash"><b>se</b> build-manifest</code> and <code class="bash"><b>se</b> build-spine</code> will create these for you. Run these on our source directory and they’ll update the <code class="html"><span class="p">&lt;</span><span class="nt">manifest</span><span class="p">&gt;</span></code> and <code class="html"><span class="p">&lt;</span><span class="nt">spine</span><span class="p">&gt;</span></code> elements in <code class="path">content.opf</code>.</p>
				<aside class="tip">
					<p>If you’re using a Mac and its badly-behaved Finder program, you may find that it has carelessly polluted your work directory with <code class="path">.DS_Store</code> files. Before continuing, you should <a href="https://duckduckgo.com/?q=mac+alternative+file+manager">find a better file manager program</a>, then delete all of that litter. Otherwise, <code class="bash"><b>se</b> build-manifest</code> and <code class="bash"><b>se</b> build-spine</code> will include that litter in their output, and your epub won’t be valid.</p>
					<p>Use this command to delete all of them in one go:</p>
					<code class="terminal"><span><b>find</b> <u>.</u> -name <i>".DS_Store"</i> -type f -delete</span></code>
				</aside>
				<p>Since this is the first time we’re editing <code class="path">content.opf</code>, we’re OK with replacing both the manifest and spine elements with a guess at the correct contents.</p>
				<code class="terminal">
					<span><b>se</b> build-manifest <u>.</u></span>
					<span><b>se</b> build-spine <u>.</u></span>
				</code>
				<p>The manifest is already in the correct order and doesn’t need to be edited. The spine, however, will have to be reordered to be in the correct reading order. Once you’ve done that, commit!</p><code class="terminal"><span><b>git</b> commit -am <i>"Add manifest and spine"</i></span></code>
			</li>
			<li>
				<h2 id="build-toc">Build the table of contents</h2>
				<p>With the spine in the right order, we can now build the table of contents.</p>
				<p>The table of contents is a structured document that lets the reader easily navigate the book. In a Standard Ebook, it’s stored outside of the readable text directory with the assumption that the reading system will parse it and display a navigable representation for the user.</p>
				<p>Use <code class="bash"><b>se</b> build-toc</code> to generate a table of contents for this ebook.</p>
				<code class="terminal raw"><span><b>se</b> build-toc <u>.</u></span></code>
				<p>Review the generated ToC in <code class="path">./src/epub/toc.xhtml</code> to make sure <code class="bash"><b>se</b> build-toc</code> did the right thing. <code class="bash"><b>se</b> build-toc</code> is a valuable tool to discover structural problems in your ebook. If an entry is arranged in a way you weren’t expecting, perhaps the problem isn’t with <code class="bash"><b>se</b> build-toc</code>, but with your XHTML code—be careful!</p>
				<p>It’s very rare that <code class="bash"><b>se</b> build-toc</code> makes an error given a correct ebook structure, but if it does, you may have to make changes to the table of contents by hand.</p>
				<p>Once you’re done, commit:</p>
				<code class="terminal"><span><b>git</b> commit -am <i>"Add ToC"</i></span></code>
			</li>
			<li>
				<h2 id="lint">Clean and lint</h2>
				<p>Before you build the ebook for proofreading, it’s a good idea to check the ebook for some common problems you might have run into during production.</p>
				<p>First, run <code class="bash"><b>se</b> clean</code> one more time to both clean up the source files, and to alert you if there are XHTML parsing errors. Even though we ran <code class="bash"><b>se</b> clean</code> before, it’s likely that in the course of production the ebook got into less-than-perfect markup formatting. Remember you can run <code class="bash"><b>se</b> clean</code> as many times as you want—it should always produce the same output.</p>
				<code class="terminal"><span><b>se</b> clean <u>.</u></span></code>
				<p>Now, run <code class="bash"><b>se</b> lint</code>. If your ebook has any problems, you’ll see some output listing them. We’re expecting some errors, because we haven’t added a cover or completed the colophon or metadata. You can ignore those errors for now, because we’ll fix them in a later step. But, you <em>do</em> want to correct any fixable errors related to your previous work.</p>
				<code class="terminal"><span><b>se</b> lint <u>.</u></span></code>
				<p>If there are no errors, <code class="bash"><b>se</b> lint</code> will complete silently—but again, at this stage we’re expecting to see some errors because our ebook isn’t done yet.</p>
			</li>
			<li>
				<h2 id="proofread">Build and proofread, proofread, proofread!</h2>
				<p>At this point, our ebook is still missing some important things—a cover, the colophon, and some metadata—but the actual book is in a state where we can start proofreading. We complete a cover-to-cover proofread now, even though there’s still work to be done on the ebook, because once you’ve actually read the book, you’ll have a better idea of what kind of cover to select and what to write in the metadata description.</p>
				<p><code class="bash"><b>se</b> build</code> will create a usable epub file for transfer to your ereader. We’ll run it with the <code class="bash">--kindle</code> and <code class="bash">--kobo</code> flag to build a file for Kindles and Kobos too. If you won’t be using a Kindle or Kobo, you can omit those flags.</p>
				<code class="terminal"><span><b>se</b> build --output-dir=$HOME/dist/ --kindle --kobo <u>.</u></span></code>
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
				<p>Now, transfer the ebook to your ereader and start a cover-to-cover proofread.</p>
				<h3>What do we mean by “proofreading”?</h3>
				<p>“Proofreading” means a close reading of the text to try to spot any transcription errors or issues which the <abbr class="acronym">SEMoS</abbr> says we must update. It’s typically <em>not</em> a line-by-line comparison to the page scans—that work was already done by the initial transcriber. Rather, proofreading is reading the book as you would any other book, but with careful attention to possible problems in the transcription or in your production. For some general tips on what to look out for when proofreading see the guide <a href="/contribute/how-tos/things-to-look-out-for-when-proofreading">here</a>.</p>
			</li>
			<li>
				<h2 id="cover">Create the cover image</h2>
				<aside class="alert">
					<p class="warning">STOP</p>
					<p><strong>Do not commit cover art to your repository’s history until you have <a href="https://groups.google.com/g/standardebooks">cleared your selection with the S.E. Editor-in-Chief or your assigned project manager.</a></strong></p>
					<p>If you commit non-public-domain cover art, you’ll have to rebase your repository to remove the art from its history. This is complicated, dangerous, and annoying, and you’ll be tempted to give up.</p>
					<p><a href="https://groups.google.com/g/standardebooks">Contact us first</a> with page scans verifying your cover art’s public domain status before you commit your cover art!</p>
				</aside>
				<p>Now that you’ve read the ebook, you’re ready to find a cover image.</p>
				<p>Cover images for Standard Ebooks books have a standardized layout. The bulk of the work you’ll be doing is locating a suitable public domain painting to use. See the <a href="/manual/latest/10-art-and-images">Art and Images section of the <abbr class="acronym">SEMoS</abbr></a> for details on assembling a cover image.</p>
				<p>As you search for an image, keep the following in mind:</p>
				<ul>
					<li>
						<p>Cover images must be in the public domain. Thanks to quirks in U.S. copyright law, this is harder to decide for paintings than it is for published writing. You must provide proof of the public domain status of your selection to the S.E. in the form of a page scan of the painting from a book published before <?= PD_STRING ?>, and your project manager must approve your selection before you can commit it to your repository. Our <a href="/artworks">Artwork Database</a> contains preapproved cover art options that you can browse by subject.</p>
					</li>
					<li>
						<p>Find the largest possible cover image you can. Since the final image is 1400 × 2100, having to resize a small image will greatly reduce the quality of the final cover.</p>
					</li>
					<li>
						<p>Your selection must in the style of a “fine art” oil painting, so that all Standard Ebooks have a consistent cover style.</p>
					</li>
					<li>
						<p>The Standard Ebooks Editor-in-Chief has the final say on the cover image you pick, and it may be rejected for, among other things, poor public domain status research, being too low resolution, not fitting in with the “fine art” style, or being otherwise inappropriate for your ebook.</p>
					</li>
				</ul>
				<aside class="tip">
					<p>Make sure to read our detailed guide on <a href="/contribute/how-tos/how-to-choose-and-create-a-cover-image">how to choose and create a cover image</a>, including tips and tricks, gotchas, and resources for finding suitable cover art.</p>
				</aside>
				<aside class="tip">
					<p>You can use the <a href="/artworks">Standard Ebooks Artwork Database</a> to browse preapproved cover art by subject.</p>
				</aside>
				<p>What can we use for <i>Jekyll</i>? In 1863 Hans von Marées painted an <a href="https://commons.wikimedia.org/wiki/File:Hans_von_Mar%C3%A9es_-_Double_Portrait_of_Mar%C3%A9es_and_Lenbach_-_WGA14059.jpg">eerie self-portrait with a friend</a>. The sallow, enigmatic look of the man on the left suggests the menacing personality of Hyde hiding just behind the sober Jekyll. It was <a href="https://books.google.com/books?id=etcwAQAAMAAJ&amp;pg=PA336">reproduced in a book published in 1910</a>.</p>
				<p>You usually won’t have to edit the actual cover SVG file, <code class="path">./images/cover.svg</code>, because it’s automatically generated. All you have to do is resize and crop your cover art to 1400 × 2100 and move it to <code class="path">./images/cover.jpg</code>.</p>
				<p>After you’re done with the cover, you’ll have four files in <code class="path">./images/</code>:</p>
				<ul>
					<li>
						<p><code class="path">cover.source.jpg</code> is the raw image file we used for the cover. We keep it in case we want to make adjustments later. For <i>Jekyll</i>, this would be the raw portrait downloaded from Wikimedia.</p>
					</li>
					<li>
						<p><code class="path">cover.jpg</code> is the scaled cover image that <code class="path">cover.svg</code> links to. This file is exactly 1400 × 2100. For <i>Jekyll</i>, this is a crop of <code class="path">cover.source.jpg</code>, and resized up to our target resolution.</p>
					</li>
					<li>
						<p><code class="path">cover.svg</code> is the full cover image including the title and author. This is auto-generated by <code class="bash"><b>se</b> create-draft</code>.</p>
					</li>
					<li>
						<p><code class="path">titlepage.svg</code> is the titlepage image auto-generated by <code class="bash"><b>se</b> create-draft</code>.</p>
					</li>
				</ul>
				<p><code class="bash"><b>se</b> build-images</code> takes both <code class="path">./images/cover.svg</code> and <code class="path">./images/titlepage.svg</code>, converts text to paths, and embeds the cover artwork. The output is placed in <code class="path">./src/epub/images/</code>, where you’ll commit it to your repo’s history.</p>
				<p>Once we built the images successfully, perform a commit.</p><code class="terminal"><span><b>git</b> add -A</span> <span><b>git</b> commit -m <i>"Add cover image"</i></span></code>
			</li>
			<li>
				<h2 id="content">Complete content.opf</h2>
				<aside class="alert">
					<p class="warning">STOP</p>
					<p><strong>Do not use AI tools to write or edit any part of the metadata,</strong> including the descriptions.</p>
					<p>Using AI tools in any part of the step-by-step guide is strictly prohibited.</p>
				</aside>
				<p><code class="path">content.opf</code> is the file that contains the ebook metadata like author, title, description, and reading order. Most of it will be filling in that basic information, and including links to various resources related to the text. We already completed the manifest and spine in an earlier step.</p>
				<p><code class="path">content.opf</code> is standardized. See the <a href="/manual/latest/9-metadata">Metadata section of the <abbr class="acronym">SEMoS</abbr></a> for details on how to fill it out.</p>
				<p>The last details to fill out here will be the short and long descriptions, verifying any Wikipedia links that <code class="bash"><b>se</b> create-draft</code> automatically found, adding cover artist metadata, filling out any missing author or contributor metadata, and adding your own metadata as the ebook producer.</p>
				<aside class="tip">
					<p>The long description must be <em>escaped</em> HTML, which can be difficult to write by hand. It’s much easier to write the long description in regular HTML, and then run <code class="bash"><b>se</b> clean</code>, which will escape the long description for you.</p>
				</aside>
				<p>Once you’re done, commit:</p>
				<code class="terminal"><span><b>git</b> commit -am <i>"Complete content.opf"</i></span></code>
			</li>
			<li>
				<h2 id="colophon">Complete the imprint and colophon</h2>
				<p><code class="bash"><b>se</b> create-draft</code> put a skeleton <code class="path">imprint.xhtml</code> file in the <code class="path">./src/epub/text/</code> folder. Fill out the links to the transcription and page scans.</p>
				<p>There’s also a skeleton <code class="path">colophon.xhtml</code> file. Now that we have the cover image and artist, we can fill out the various fields there. Make sure to credit the original transcribers of the text (generally we assume them to be whoever’s name is on the file we download from Project Gutenberg) and to include a link back to the Gutenberg text we used, along with a link to any scans we used (from the Internet Archive or HathiTrust, for example).</p>
				<p>You can also include your own name as the producer of this Standard Ebooks edition. Besides that, the colophon is standardized; don’t get too creative with it.</p>
				<p>Leave the release date unchanged, as <code class="bash"><b>se</b> prepare-release</code> will fill it in for you in a later step.</p>
				<p>Once you’re done, commit:</p>
				<code class="terminal"><span><b>git</b> commit -am <i>"Complete the imprint and colophon"</i></span></code>
			</li>
			<li>
				<h2 id="checks">Final checks</h2>
				<p>It’s a good idea to run <code class="bash"><b>se</b> typogrify</code> and <code class="bash"><b>se</b> clean</code> one more time before running these final checks. Make sure to review the changes with <code class="bash"><b>git</b> difftool</code> before accepting them—<code class="bash"><b>se</b> typogrify</code> is usually right, but not always!</p>
				<p>Now that our ebook is complete, let’s verify that there are no errors at the <abbr>S.E.</abbr> style level:</p>
				<code class="terminal"><span><b>se</b> lint <u>.</u></span></code>
				<p>Once <code class="bash"><b>se</b> lint</code> completes without errors, we’re ready to confirm that there are no errors at the epub level. We do this by invoking <code class="bash"><b>se</b> build</code> with the <code class="bash">--check-only</code> flag, which will run <code class="bash"><b>epubcheck</b></code> to verify that our final epub has no errors, but won’t output ebook files, since we don’t need them right now.</p>
				<code class="terminal"><span><b>se</b> build --check-only <u>.</u></span></code>
				<p>Once that completes without errors, we’re ready to move on to the final step!</p>
			</li>
			<li>
				<h2 id="publication">Initial publication</h2>
				<p>You’re ready to publish!</p>
				<ul>
					<li>
						<p><b>If you’re submitting your ebook to Standard Ebooks:</b></p>
						<p>Contact the mailing list with a link to your GitHub repository to let them know you’re finished. A reviewer will review your production and work with you to fix any issues. They’ll then release the ebook for you.</p>
						<p><em>Don’t run <code class="bash"><b>se</b> prepare-release</code> on an ebook you’re submitting for review!</em></p>
					</li>
					<li>
						<p><b>If you’re producing this ebook for yourself, not for release at Standard Ebooks:</b></p>
						<p>Complete the initial publication by adding a release date, modification date, and final word count to <code class="path">content.opf</code> and <code class="path">colophon.xhtml</code>. <code class="bash"><b>se</b> prepare-release</code> does all of that for us.</p>
						<code class="terminal"><span><b>se</b> prepare-release <u>.</u></span></code>
						<p>With that done, we commit again using a commit message of <code class="html">Initial publication</code> to signify that we’re all done with production, and now expect only proofreading corrections to be committed. (This may not actually be the case in reality, but it’s still a nice milestone to have.)</p>
						<code class="terminal">
							<span><b>git</b> add -A</span>
							<span><b>git</b> commit -m <i>"Initial publication"</i></span>
						</code>
						<p>Finally, build everything again.</p>
						<code class="terminal">
							<span><b>se</b> build --output-dir=$HOME/dist/ --kindle --kobo --check <u>.</u></span>
						</code>
					</li>
				</ul>
				<p>Congratulations! You’ve just finished producing a Standard Ebook!</p>
			</li>
		</ol>
	</article>
</main>
<?= Template::Footer() ?>
