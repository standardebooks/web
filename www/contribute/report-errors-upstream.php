<?= Template::Header(['title' => 'Report Errors Upstream', 'highlight' => 'contribute', 'description' => 'Our guide to reporting errors to Gutenberg and other sources.']) ?>
<main>
	<article>
		<h1>Report Errors Upstream</h1>
		<p>If you spot an error in a Standard Ebooks ebook, the first thing to do is let us know. If you haven’t already then you can read <a href="/contribute/report-errors">how to let us know about errors</a>.</p>
		<p>But what if you want to make sure the error is also fixed in the source transcriptions? The first thing to do is to find out exactly which transcriptions the ebook was built from.</p>
		<p>To do this, find your book on the Standard Ebooks site and scroll to the “More Details” section. For example, <a href="/ebooks/thomas-hardy/far-from-the-madding-crowd#details">here’s that section for Thomas Hardy’s <i>Far From the Madding Crowd</i></a>. You’ll see that as well as links to the book’s page on Wikipedia and its repository of source code, there are links to the page scans at the Internet Archive and the original transcriptions at Project Gutenberg. These last two are the two we’re interested in.</p>
		<h2>Reporting transcription errors to Project Gutenberg</h2>
		<p>Gutenberg will happily fix problems with their transcriptions, but want errata reports formatted in a particular way, with proposed changes referenced against the line number of the plain text version of the transcription in question. Let’s look at a recent example.</p>
		<p>A single error was found while proofing <a href="/ebooks/maurice-leblanc/the-golden-triangle/alexander-teixeira-de-mattos">Maurice Leblanc’s <i>The Golden Triangle</i></a>: a chapter title had an “E” instead of an “É”. The <a href="https://www.gutenberg.org/ebooks/34795">book’s page on Gutenberg</a> has a link to <a href="https://www.gutenberg.org/files/34795/34795-0.txt">the plain text version</a>. Copying that into a text editor showed that the error (since fixed) was on line 9756. We also know from the ebook’s “<a href="/ebooks/maurice-leblanc/the-golden-triangle/alexander-teixeira-de-mattos#details">More Details</a>” section that <a href="https://hdl.handle.net/2027/hvd.hw5y1w">the source scans are available at the Hathi Trust Digital Library</a>. That meant that the following email could be sent to Gutenberg:</p>
		<blockquote>
			<p>Hi, I’ve been proofing The Golden Triangle against the source scans at https://hdl.handle.net/2027/hvd.hw5y1w and found a single error with a missing accent:</p>
			<p>Title: The Golden Triangle, by Maurice Leblanc<br/>Release Date: 30 Dec 2010 [EBook #34795]<br/>File: 34795-0.txt</p>
			<p>Line 9756:<br/>SIMEON'S LAST VICTIM<br/>SIMÉON'S LAST VICTIM</p>
		</blockquote>
		<p>The middle block contains the title, author, release date, Gutenberg ebook number and file name. This is followed by the specific problematic line numbers, along with the requested change.</p>
		<p>Gutenberg will happily take fixes for spelling, accents, and missing or surplus paragraph breaks. They generally aren’t interested in reports of additional section breaks, or changes that would require older non-Unicode books to be converted to Unicode (for example, changing the word “degrees” to the symbol “º”).</p>
		<p>The email should be sent to errata2019@pglaf.org, replacing 2019 with the current year. You should receive an autoreply within a few minutes, and generally Project Gutenberg responds in person within a few days.</p>
		<h2>Reporting transcription errors to WikiSource</h2>
		<p>WikiSource is a collaborative effort with no specific maintainers, so for errors found in their transcriptions it’s easiest to fix them yourself. First, sign up for a WikiSource account (you can use a Wikipedia account if you already have one of those).</p>
		<p>Once you’re logged in and have found the text you want to edit (for example, this <a href="https://en.wikisource.org/wiki/The_Good_Soldier/Part_I,_Chapter_I">first chapter of Ford Madox Ford’s <i>The Good Soldier</i></a>), click the “Edit” button in the header. You can then make the changes in the text editor field that appears, write a summary of the changes you’ve made in the Summary field, and finally click “Publish Changes” to save them.</p>
		<p>If you want to double-check your contribution, you can click on “View History” in the header to see the timeline of changes to the transcription. Your latest change should appear at the top along with its summary.</p>
		<h2>Reporting transcription errors to other sources</h2>
		<p>While unusual, other sources have been used for Standard Ebooks ebooks (for example <a href="https://people.umass.edu/klement/tlp/tlp.html">this transcription</a> of <a href="/ebooks/ludwig-wittgenstein/tractatus-logico-philosophicus/c-k-ogden">Ludwig Wittgenstein’s <i lang="la">Tractatus Logico-Philosophicus</i></a>). If you’ve found an error in one of these transcriptions, it would be best to <a href="https://groups.google.com/g/standardebooks">contact us via our mailing list</a> to discuss the situation.</p>
	</article>
</main>
<?= Template::Footer() ?>
