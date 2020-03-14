<?
require_once('Core.php');
?><?= Template::Header(['title' => '9. Metadata - The Standard Ebooks Manual', 'highlight' => 'contribute', 'manual' => true]) ?>
	<main>
		<article class="manual">

	<section data-start-at="9" id="metadata">
		<h1>Metadata</h1>
		<p>Metadata in a Standard Ebooks epub is stored in the <code class="path">./src/epub/content.opf</code> file. The file contains some boilerplate that an ebook producer won’t have to touch, and a lot of information that they <em>will</em> have to touch as an ebook is produced.</p>
		<p>Follow the general structure of the <code class="path">content.opf</code> file present in the tools <code class="path">./templates/</code> directory. Don’t rearrange the order of anything in there.</p>
		<section id="general-url-rules">
			<h2>General URL rules</h2>
			<ol type="1">
				<li>URLs used in metadata are https where possible.</li>
				<li>URLs used in metadata do not contain query strings, or if a query string is required, only contain the minimum necessary query string to render the base resource.</li>
				<li>URLs used for Project Gutenberg page scans look like: <code class="path">https://www.gutenberg.org/ebooks/&lt;BOOK-ID&gt;</code>.</li>
				<li>URLs used for HathiTrust page scans look like: <code class="path">https://catalog.hathitrust.org/Record/&lt;RECORD-ID&gt;</code>.</li>
				<li>URLs used for Google Books page scans look like: <code class="path">https://books.google.com/books?id=&lt;BOOK-ID&gt;</code>.</li>
				<li>URLs used for Internet Archive page scans look like: <code class="path">https://archive.org/details/&lt;BOOK-ID&gt;</code>.</li>
			</ol>
		</section>
		<section id="the-ebook-identifier">
			<h2>The ebook identifier</h2>
			<ol type="1">
				<li>The <code class="html"><span class="p">&lt;</span><span class="nt">dc:identifier</span><span class="p">&gt;</span></code> element contains the unique identifier for the ebook. The identifier is the Standard Ebooks URL for the ebook, prefaced by <code class="html">url:</code>.
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">dc:identifier</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;uid&quot;</span><span class="p">&gt;</span>url:https://standardebooks.org/ebooks/anton-chekhov/short-fiction/constance-garnett<span class="p">&lt;/</span><span class="nt">dc:identifier</span><span class="p">&gt;</span></code></figure>
				</li>
			</ol>
			<section id="forming-the-se-url">
				<h3>Forming the SE URL</h3>
				<p>The SE URL is formed by the following algorithm.</p>
				<p>(Note: A string can be made URL-safe using the <code class="bash">se make-url-safe</code> tool.)</p>
				<ul>
					<li>Start with the URL-safe author of the work, as it appears on the titlepage. If there is more than one author, continue appending subsequent URL-safe authors, separated by an underscore. Do not alpha-sort the author name.</li>
					<li>Append a forward slash, then the URL-safe title of the work. Do not alpha-sort the title.</li>
					<li>If the work is translated, append a forward slash, then the URL-safe translator. If there is more than one translator, continue appending subsequent URL-safe translators, separated by an underscore. Do not alpha-sort translator names.</li>
					<li>If the work is illustrated, append a forward slash, then the URL-safe illustrator. If there is more than one illustrator, continue appending subsequent URL-safe illustrators, separated by an underscore. Do not alpha-sort illustrator names.</li>
					<li>Finally, <em>do not</em> append a trailing forward slash.</li>
				</ul>
			</section>
		</section>
		<section id="publication-date-and-release-identifiers">
			<h2>Publication date and release identifiers</h2>
			<p>There are several elements in the metadata describing the publication date, updated date, and revision number of the ebook. Generally these are not updated by hand; instead, the <code class="bash">se prepare-release</code> tool updates them automatically.</p>
			<ol type="1">
				<li><code class="html"><span class="p">&lt;</span><span class="nt">dc:date</span><span class="p">&gt;</span></code> is a timestamp representing the first publication date of this ebook file. Once the ebook is released to the public, this value doesn’t change.</li>
				<li><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"dcterms:modified"</span><span class="p">&gt;</span></code> is a timestamp representing the last time this ebook file was modified. This changes often.</li>
				<li><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:revision-number"</span><span class="p">&gt;</span></code> is a special SE extension representing the revision number of this ebook file. During production, this number will be 0. When the ebook is first released to the public, the number will increment to 1. Each time <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"dcterms:modified"</span><span class="p">&gt;</span></code> changes, the revision number is incremented.</li>
			</ol>
		</section>
		<section id="book-titles">
			<h2>Book titles</h2>
			<section id="books-without-subtitles">
				<h3>Books without subtitles</h3>
				<ol type="1">
					<li>The <code class="html"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span></code> element contains the title.</li>
					<li>The <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"file-as"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#title"</span><span class="p">&gt;</span></code> element contains alpha-sorted title, even if the alpha-sorted title is identical to the unsorted title.</li>
				</ol>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>The Moon Pool<span class="p">&lt;/</span><span class="nt">dc:title</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;file-as&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#title&quot;</span><span class="p">&gt;</span>Moon Pool, The<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code></figure>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>Short Fiction<span class="p">&lt;/</span><span class="nt">dc:title</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;file-as&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#title&quot;</span><span class="p">&gt;</span>Short Fiction<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>`</code></figure>
			</section>
			<section id="books-with-subtitles">
				<h3>Books with subtitles</h3>
				<ol type="1">
					<li>The <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"title-type"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#title"</span><span class="p">&gt;</span>main<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code> element identifies the main part of the title.</li>
					<li>A second <code class="html"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">"subtitle"</span><span class="p">&gt;</span></code> element contain the subtitle, and is refined with <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"title-type"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#subtitle"</span><span class="p">&gt;</span>subtitle<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code>.</li>
					<li>A third <code class="html"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">"fulltitle"</span><span class="p">&gt;</span></code> element contains the complete title on one line, with the main title and subtitle separated by a colon and space, and is refined with <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"title-type"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#fulltitle"</span><span class="p">&gt;</span>extended<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code>.</li>
					<li>All three <code class="html"><span class="p">&lt;</span><span class="nt">dc:title</span><span class="p">&gt;</span></code> elements have an accompanying <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"file-as"</span><span class="p">&gt;</span></code> element, even if the <code class="html">file-as</code> value is the same as the title.</li>
				</ol>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>The Moon Pool<span class="p">&lt;/</span><span class="nt">dc:title</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;file-as&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#title&quot;</span><span class="p">&gt;</span>Moon Pool, The<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code></figure>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>The Man Who Was Thursday<span class="p">&lt;/</span><span class="nt">dc:title</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;file-as&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#title&quot;</span><span class="p">&gt;</span>Man Who Was Thursday, The<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;title-type&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#title&quot;</span><span class="p">&gt;</span>main<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;subtitle&quot;</span><span class="p">&gt;</span>A Nightmare<span class="p">&lt;/</span><span class="nt">dc:title</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;file-as&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#subtitle&quot;</span><span class="p">&gt;</span>Nightmare, A<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;title-type&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#subtitle&quot;</span><span class="p">&gt;</span>subtitle<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;fulltitle&quot;</span><span class="p">&gt;</span>The Man Who Was Thursday: A Nightmare<span class="p">&lt;/</span><span class="nt">dc:title</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;file-as&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#fulltitle&quot;</span><span class="p">&gt;</span>Man Who Was Thursday, The<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;title-type&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#fulltitle&quot;</span><span class="p">&gt;</span>extended<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code></figure>
			</section>
			<section id="books-with-a-more-popular-alternate-title">
				<h3>Books with a more popular alternate title</h3>
				<p>Some books are commonly referred to by a shorter name than their actual title. For example, <i><a href="/ebooks/mark-twain/the-adventures-of-huckleberry-finn">The Adventures of Huckleberry Finn</a></i> is often simply known as <i>Huck Finn</i>.</p>
				<ol type="1">
					<li>The <code class="html"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">"title-short"</span><span class="p">&gt;</span></code> element contains the common title. It is refined with <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"title-type"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#title-short"</span><span class="p">&gt;</span>short<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code>.</li>
					<li>The common title does not have a corresponding <code class="html">file-as</code> element.</li>
				</ol>
			</section>
			<section id="books-with-numbers-or-abbreviations-in-the-title">
				<h3>Books with numbers or abbreviations in the title</h3>
				<p>Books that contain numbers in their title may be difficult to find with a search query, if the query contains the Arabic number instead of the spelled-out number. For example, trying to find like <i><a href="/ebooks/jules-verne/around-the-world-in-eighty-days/george-makepeace-towle">Around the World in Eighty Days</a></i> by searching for <cite>80</cite> instead of <cite>eighty</cite>. To facilitate such queries, a special <code class="html"><span class="p">&lt;</span><span class="nt">meta</span><span class="p">&gt;</span></code> element is included.</p>
				<ol type="1">
					<li>If a book title contains numbers or abbreviations, a <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">id</span><span class="o">=</span><span class="s">"alternative-title"</span> <span class="na">id</span><span class="o">=</span><span class="s">"se:alternative-title"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#title"</span><span class="p">&gt;</span></code> element is placed after the main title block, containing the title with expanded or alternate spelling to facilitate possible search queries.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>Around the World in Eighty Days<span class="p">&lt;/</span><span class="nt">dc:title</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;file-as&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#title&quot;</span><span class="p">&gt;</span>Around the World in Eighty Days<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;alternative-title&quot;</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;se:alternative-title&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#title&quot;</span><span class="p">&gt;</span>Around the World in 80 Days<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">dc:title</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>File No. 113<span class="p">&lt;/</span><span class="nt">dc:title</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;file-as&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#title&quot;</span><span class="p">&gt;</span>File No. 113<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;alternative-title&quot;</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;se:alternative-title&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#title&quot;</span><span class="p">&gt;</span>File Number One Hundred and Thirteen<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
		</section>
		<section id="book-subjects">
			<h2>Book subjects</h2>
			<section id="library-of-congress-subjects">
				<h3>Library of Congress subjects</h3>
				<p>The <code class="html"><span class="p">&lt;</span><span class="nt">dc:subject</span><span class="p">&gt;</span></code> elements allow us to categorize the ebook. We use the Library of Congress categories assigned to the book for this purpose.</p>
				<ol type="1">
					<li>Each <code class="html"><span class="p">&lt;</span><span class="nt">dc:subject</span><span class="p">&gt;</span></code> has the <code class="html">id</code> attribute set to <code class="html">subject-#</code>, where # is a number starting at <code class="path">1</code>, without leading zeros, that increments with each subject.</li>
					<li>The <code class="html"><span class="p">&lt;</span><span class="nt">dc:subject</span><span class="p">&gt;</span></code> elements are arranged sequentially in a single block.</li>
					<li>If the transcription for the ebook comes from Project Gutenberg, the value of <code class="html"><span class="p">&lt;</span><span class="nt">dc:subject</span><span class="p">&gt;</span></code> element comes from the Project Gutenberg page for the ebook. Otherwise, the value comes from the <a href="https://catalog.loc.gov">Library of Congress catalog</a>.</li>
					<li>After the block of <code class="html"><span class="p">&lt;</span><span class="nt">dc:subject</span><span class="p">&gt;</span></code> elements there is a block of <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"meta-auth"</span><span class="p">&gt;</span></code> elements. The values of these elements represent the URLs at which each subject was found. Typically the value is the same for each element.</li>
					<li>A <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"meta-auth"</span><span class="p">&gt;</span></code> element is required for each individual <code class="html"><span class="p">&lt;</span><span class="nt">dc:subject</span><span class="p">&gt;</span></code> element, even if the <code class="html">meta-auth</code> URL is the same for all of the subjects.</li>
				</ol>
				<p>This example shows how to mark up the subjects for <i><a href="/ebooks/david-lindsay/a-voyage-to-arcturus">A Voyage to Arcturus</a></i>, by David Lindsay:</p>
				<figure><code class="html full"><span class="p">&lt;</span><span class="nt">dc:subject</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;subject-1&quot;</span><span class="p">&gt;</span>Science fiction<span class="p">&lt;/</span><span class="nt">dc:subject</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">dc:subject</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;subject-2&quot;</span><span class="p">&gt;</span>Psychological fiction<span class="p">&lt;/</span><span class="nt">dc:subject</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">dc:subject</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;subject-3&quot;</span><span class="p">&gt;</span>Quests (Expeditions) -- Fiction<span class="p">&lt;/</span><span class="nt">dc:subject</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">dc:subject</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;subject-4&quot;</span><span class="p">&gt;</span>Life on other planets -- Fiction<span class="p">&lt;/</span><span class="nt">dc:subject</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;meta-auth&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#subject-1&quot;</span><span class="p">&gt;</span>https://www.gutenberg.org/ebooks/1329<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;meta-auth&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#subject-2&quot;</span><span class="p">&gt;</span>https://www.gutenberg.org/ebooks/1329<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;meta-auth&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#subject-3&quot;</span><span class="p">&gt;</span>https://www.gutenberg.org/ebooks/1329<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;meta-auth&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#subject-4&quot;</span><span class="p">&gt;</span>https://www.gutenberg.org/ebooks/1329<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code></figure>
			</section>
			<section id="se-subjects">
				<h3>SE subjects</h3>
				<p>Along with the Library of Congress categories, we include a custom list of SE subjects in the ebook metadata. Unlike Library of Congress categories, SE subjects are purposefully broad. They’re more like the subject categories in a medium-sized bookstore, as opposed to the precise, detailed, hierarchical Library of Congress categories.</p>
				<p>It’s the producer’s task to select appropriate SE subjects for the ebook. Usually just one or two of these categories will suffice.</p>
				<section id="all-se-subjects">
					<h4>All SE subjects</h4>
					<ul>
						<li>Adventure</li>
						<li>Autobiography</li>
						<li>Biography</li>
						<li>Childrens</li>
						<li>Comedy</li>
						<li>Drama</li>
						<li>Fantasy</li>
						<li>Fiction</li>
						<li>Horror</li>
						<li>Memoir</li>
						<li>Mystery</li>
						<li>Nonfiction</li>
						<li>Philosophy</li>
						<li>Poetry</li>
						<li>Satire</li>
						<li>Science Fiction</li>
						<li>Shorts</li>
						<li>Spirituality</li>
						<li>Travel</li>
					</ul>
				</section>
				<section id="required-subjects-for-certain-kinds-of-books">
					<h4>Required subjects for certain kinds of books</h4>
					<ol type="1">
						<li>Ebooks that are collections of short stories must have the SE subject <code class="html">Shorts</code>.</li>
						<li>Ebooks that are young adult or children’s books must have the SE subject <code class="html">Childrens</code>.</li>
					</ol>
				</section>
			</section>
		</section>
		<section id="book-descriptions">
			<h2>Book descriptions</h2>
			<p>An ebook has two kinds of descriptions: a short <code class="html"><span class="p">&lt;</span><span class="nt">dc:description</span><span class="p">&gt;</span></code> element, and a much longer <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:long-description"</span><span class="p">&gt;</span></code> element.</p>
			<section id="the-short-description">
				<h3>The short description</h3>
				<p>The <code class="html"><span class="p">&lt;</span><span class="nt">dc:description</span><span class="p">&gt;</span></code> element contains a short, single-sentence summary of the ebook.</p>
				<ol type="1">
					<li>The description is a single complete sentence ending in a period, not a sentence fragment or restatement of the title.</li>
					<li>The description is typogrified, i.e. it contains Unicode curly quotes, em-dashes, and the like.</li>
				</ol>
			</section>
			<section id="the-long-description">
				<h3>The long description</h3>
				<p>The <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:long-description"</span><span class="p">&gt;</span></code> element contains a much longer description of the ebook.</p>
				<ol type="1">
					<li>The long description is a non-biased, encyclopedia-like description of the book, including any relevant publication history, backstory, or historical notes. It is as detailed as possible without giving away plot spoilers. It does not impart the producer’s opinions of the book, or include content warnings. Think along the lines of a Wikipedia-like summary of the book and its history, <em>but under no circumstances can a producer copy and paste from Wikipedia!</em></li>
					<li>The long descriptions is typogrified, i.e. it contains Unicode curly quotes, em-dashes, and the like.</li>
					<li>The long description is in <em>escaped</em> HTML, with the HTML beginning on its own line after the <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:long-description"</span><span class="p">&gt;</span></code> element.
						<aside class="tip">An easy way to escape HTML is to compose the long description in regular HTML, then insert it into the <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:long-description"</span><span class="p">&gt;</span></code> element surrounded by a <code class="html"><span class="cp">&lt;![CDATA[ ... ]]&gt;</span></code> element. Then, run the <code class="bash">se clean</code> tool, which will remove the <code class="html"><span class="cp">&lt;![CDATA[ ... ]]&gt;</span></code> element and escape the contained HTML.</aside>
					</li>
					<li>Long description HTML follows the <a href="/manual/1.0.0/code-style">code style conventions of this manual</a>.</li>
					<li>The long description element is directly followed by: <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"meta-auth"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#long-description"</span><span class="p">&gt;</span>https://standardebooks.org<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code></li>
				</ol>
			</section>
		</section>
		<section id="book-language">
			<h2>Book language</h2>
			<ol type="1">
				<li>The <code class="html"><span class="p">&lt;</span><span class="nt">dc:language</span><span class="p">&gt;</span></code> element follows the long description block. It contains the <a href="https://en.wikipedia.org/wiki/IETF_language_tag">IETF language tag</a> for the language that the work is in. Usually this is either <code class="html">en-US</code> or <code class="html">en-GB</code>.</li>
			</ol>
		</section>
		<section id="book-transcription-and-page-scan-source">
			<h2>Book transcription and page scan source</h2>
			<ol type="1">
				<li>The <code class="html"><span class="p">&lt;</span><span class="nt">dc:source</span><span class="p">&gt;</span></code> elements represent URLs to sources for the transcription the ebook is based on, and page scans of the print sources used to correct the transcriptions.</li>
				<li><code class="html"><span class="p">&lt;</span><span class="nt">dc:source</span><span class="p">&gt;</span></code> URLs are in https where possible.</li>
				<li>A book can contain more than one such element if multiple sources for page scans were used.</li>
			</ol>
		</section>
		<section id="book-production-notes">
			<h2>Book production notes</h2>
			<ol type="1">
				<li>The <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:production-notes"</span><span class="p">&gt;</span></code> element contains any of the ebook producer’s production notes. For example, the producer might note that page scans were not available, so an editorial decision was made to add commas to sentences deemed to be transcription typos; or that certain archaic spellings were retained as a matter of prose style specific to this ebook.</li>
				<li>The <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:production-notes"</span><span class="p">&gt;</span></code> element is not present if there are no production notes.</li>
			</ol>
		</section>
		<section id="readability-metadata">
			<h2>Readability metadata</h2>
			<p>These two elements are automatically computed by the <code class="bash">se prepare-release</code> tool.</p>
			<ol type="1">
				<li>The <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:word-count"</span><span class="p">&gt;</span></code> element contains an integer representing the ebooks total word count, excluding some SE files like the colophon and Uncopyright.</li>
				<li>The <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:reading-ease.flesch"</span><span class="p">&gt;</span></code> element contains a decimal representing the computed Flesch reading ease for the book.</li>
			</ol>
		</section>
		<section id="additional-book-metadata">
			<h2>Additional book metadata</h2>
			<ol type="1">
				<li><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:url.encyclopedia.wikipedia"</span><span class="p">&gt;</span></code> contains the Wikipedia URL for the book. This element is not present if there is no Wikipedia entry for the book.</li>
				<li><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:url.vcs.github"</span><span class="p">&gt;</span></code> contains the SE GitHub URL for this ebook. This is calculated by taking the string <code class="html">https://github.com/standardebooks/</code> and appending the <a href="#the-ebook-identifier">SE identifier</a>, without <code class="html">https://standardebooks.org/ebooks/</code>, and with forward slashes replaced by underscores.</li>
			</ol>
		</section>
		<section id="general-contributor-rules">
			<h2>General contributor rules</h2>
			<p>The following apply to all contributors, including the author(s), translator(s), and illustrator(s).</p>
			<ol type="1">
				<li>If there is exactly one contributor in a set (for example, only one author, or only one translator) then the <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"display-seq"</span><span class="p">&gt;</span></code> element is omitted for that contributor.</li>
				<li>If there is more than one contributor in a set (for example, multiple authors, or translators) then the <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"display-seq"</span><span class="p">&gt;</span></code> element is specified for each contributor, with a value equal to their position in the SE identifier.</li>
				<li>The epub standard specifies that in a set of contributors, if at least one has the <code class="html">display-seq</code> attribute, then other contributors in the set without the <code class="html">display-seq</code> attribute are ignored. For SE purposes, this also means they will be excluded from the SE identifier.</li>
				<li>By SE convention, contributors with <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"display-seq"</span><span class="p">&gt;</span>0<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code> are excluded from the SE identifier.</li>
			</ol>
		</section>
		<section id="the-author-metadata-block">
			<h2>The author metadata block</h2>
			<ol type="1">
				<li><code class="html"><span class="p">&lt;</span><span class="nt">dc:creator</span> <span class="na">id</span><span class="o">=</span><span class="s">"author"</span><span class="p">&gt;</span></code> contains the author’s name as it appears on the cover.</li>
				<li>If there is more than one author, the first author’s <code class="html">id</code> is <code class="html">author-1</code>, the second <code class="html">author-2</code>, and so on.</li>
				<li><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"file-as"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span><span class="p">&gt;</span></code> contains the author’s name as filed alphabetically. This element is included even if it’s identical to <code class="html"><span class="p">&lt;</span><span class="nt">dc:creator</span><span class="p">&gt;</span></code>.</li>
				<li><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:name.person.full-name"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span><span class="p">&gt;</span></code> contains the author’s full name, with any initials or middle names expanded, and including any titles. This element is not included if the value is identical to <code class="html"><span class="p">&lt;</span><span class="nt">dc:creator</span><span class="p">&gt;</span></code>.</li>
				<li><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"alternate-script"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span><span class="p">&gt;</span></code> contains the author’s name as it appears on the cover, but transliterated into their native alphabet if applicable. For example, Anton Chekhov’s name would be contained here in the Cyrillic alphabet. This element is not included if not applicable.</li>
				<li><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:url.encyclopedia.wikipedia"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span><span class="p">&gt;</span></code> contains the URL of the author’s Wikipedia page. This element is not included if there is no Wikipedia page.</li>
				<li><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:url.authority.nacoaf"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span><span class="p">&gt;</span></code> contains the URL of the author’s <a href="http://id.loc.gov/authorities/names.html">Library of Congress Names Database</a> page. It does not include the <code class="path">.html</code> file extension. This element is not included if there is no LoC Names database entry.
					<aside class="tip">
						<p>This is easily found by visiting the person’s Wikipedia page and looking at the very bottom in the “Authority Control” section, under “LCCN.”</p>
						<p>If you it’s not on Wikipedia, find it directly by visiting the <a href="http://id.loc.gov/authorities/names.html">Library of Congress Names Database</a>.</p>
					</aside>
				</li>
				<li><code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"role"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span> <span class="na">scheme</span><span class="o">=</span><span class="s">"marc:relators"</span><span class="p">&gt;</span></code> contains the <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a> for the roles the author played in creating this book.
					<p>There is always one element with the value of <code class="html">aut</code>. There may be additional elements for additional values, if applicable. For example, if the author also illustrated the book, there would be an additional <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"role"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#author"</span> <span class="na">scheme</span><span class="o">=</span><span class="s">"marc:relators"</span><span class="p">&gt;</span>ill<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code> element.</p>
				</li>
			</ol>
			<p>This example shows a complete author metadata block for <i><a href="/ebooks/anton-chekhov/short-fiction/constance-garnett">Short Fiction</a></i>, by Anton Chekhov:</p>
			<figure><code class="html full"><span class="p">&lt;</span><span class="nt">dc:creator</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;author&quot;</span><span class="p">&gt;</span>Anton Chekhov<span class="p">&lt;/</span><span class="nt">dc:creator</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;file-as&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#author&quot;</span><span class="p">&gt;</span>Chekhov, Anton<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;se:name.person.full-name&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#author&quot;</span><span class="p">&gt;</span>Anton Pavlovich Chekhov<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;alternate-script&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#author&quot;</span><span class="p">&gt;</span>Анто́н Па́влович Че́хов<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;se:url.encyclopedia.wikipedia&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#author&quot;</span><span class="p">&gt;</span>https://en.wikipedia.org/wiki/Anton_Chekhov<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;se:url.authority.nacoaf&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#author&quot;</span><span class="p">&gt;</span>http://id.loc.gov/authorities/names/n79130807<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">&quot;role&quot;</span> <span class="na">refines</span><span class="o">=</span><span class="s">&quot;#author&quot;</span> <span class="na">scheme</span><span class="o">=</span><span class="s">&quot;marc:relators&quot;</span><span class="p">&gt;</span>aut<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code></figure>
		</section>
		<section id="the-translator-metadata-block">
			<h2>The translator metadata block</h2>
			<ol type="1">
				<li>If the work is translated, the <code class="html"><span class="p">&lt;</span><span class="nt">dc:contributor</span> <span class="na">id</span><span class="o">=</span><span class="s">"translator"</span><span class="p">&gt;</span></code> metadata block follows the author metadata block.</li>
				<li>If there is more than one translator, then the first translator’s <code class="html">id</code> is <code class="html">translator-1</code>, the second <code class="html">translator-2</code>, and so on.</li>
				<li>Each block is identical to the author metadata block, but with <code class="html"><span class="p">&lt;</span><span class="nt">dc:contributor</span> <span class="na">id</span><span class="o">=</span><span class="s">"translator"</span><span class="p">&gt;</span></code> instead of <code class="html"><span class="p">&lt;</span><span class="nt">dc:creator</span> <span class="na">id</span><span class="o">=</span><span class="s">"author"</span><span class="p">&gt;</span></code>.</li>
				<li>The <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a> is <code class="html">trl</code>: <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"role"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#translator"</span> <span class="na">scheme</span><span class="o">=</span><span class="s">"marc:relators"</span><span class="p">&gt;</span>trl<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code>.</li>
				<li>Translators often annotate the work; if this is the case, the additional <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a> <code class="html">ann</code> is included in a separate <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"role"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#translator"</span> <span class="na">scheme</span><span class="o">=</span><span class="s">"marc:relators"</span><span class="p">&gt;</span></code> element.</li>
			</ol>
		</section>
		<section id="the-illustrator-metadata-block">
			<h2>The illustrator metadata block</h2>
			<ol type="1">
				<li>If the work is illustrated by a person who is not the author, the illustrator metadata block follows.</li>
				<li>If there is more than one illustrator, the first illustrator’s <code class="html">id</code> is <code class="html">illustrator-1</code>, the second <code class="html">illustrator-2</code>, and so on.</li>
				<li>Each block is identical to the author metadata block, but with <code class="html"><span class="p">&lt;</span><span class="nt">dc:contributor</span> <span class="na">id</span><span class="o">=</span><span class="s">"illustrator"</span><span class="p">&gt;</span></code> instead of <code class="html"><span class="p">&lt;</span><span class="nt">dc:creator</span> <span class="na">id</span><span class="o">=</span><span class="s">"author"</span><span class="p">&gt;</span></code>.</li>
				<li>The <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a> is <code class="html">ill</code>: <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"role"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#illustrator"</span> <span class="na">scheme</span><span class="o">=</span><span class="s">"marc:relators"</span><span class="p">&gt;</span>ill<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code>.</li>
			</ol>
		</section>
		<section id="the-cover-artist-metadata-block">
			<h2>The cover artist metadata block</h2>
			<p>The “cover artist” is the artist who painted the art the producer selected for the SE ebook cover.</p>
			<ol type="1">
				<li>The cover artist metadata block is identical to the author metadata block, but with <code class="html"><span class="p">&lt;</span><span class="nt">dc:contributor</span> <span class="na">id</span><span class="o">=</span><span class="s">"artist"</span><span class="p">&gt;</span></code> instead of <code class="html"><span class="p">&lt;</span><span class="nt">dc:creator</span> <span class="na">id</span><span class="o">=</span><span class="s">"author"</span><span class="p">&gt;</span></code>.</li>
				<li>The <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a> is <code class="html">art</code>: <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"role"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#artist"</span> <span class="na">scheme</span><span class="o">=</span><span class="s">"marc:relators"</span><span class="p">&gt;</span>art<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code>.</li>
			</ol>
		</section>
		<section id="metadata-for-additional-contributors">
			<h2>Metadata for additional contributors</h2>
			<p>Occasionally a book may have other contributors besides the author, translator, and illustrator; for example, a person who wrote a preface, an introduction, or who edited the work or added endnotes.</p>
			<ol type="1">
				<li>Additional contributor blocks are identical to the author metadata block, but with <code class="html"><span class="p">&lt;</span><span class="nt">dc:contributor</span><span class="p">&gt;</span></code> instead of <code class="html"><span class="p">&lt;</span><span class="nt">dc:creator</span><span class="p">&gt;</span></code>.</li>
				<li>The <code class="html">id</code> attribute of the <code class="html"><span class="p">&lt;</span><span class="nt">dc:contributor</span><span class="p">&gt;</span></code> is the lowercase, URL-safe, fully-spelled out version of the <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a>. For example, if the MARC relator tag is <code class="html">wpr</code>, the <code class="html">id</code> attribute would be <code class="html">writer-of-preface</code>.</li>
				<li>The <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a> is one that is appropriate for the role of the additional contributor. Common roles for ebooks are: <code class="html">wpr</code>, <code class="html">ann</code>, and <code class="html">aui</code>.</li>
			</ol>
		</section>
		<section id="transcriber-metadata">
			<h2>Transcriber metadata</h2>
			<ol type="1">
				<li>If the ebook is based on a transcription by someone else, like Project Gutenberg, then transcriber blocks follow.</li>
				<li>If there is more than one transcriber, the first transcriber is <code class="html">transcriber-1</code>, the second <code class="html">transcriber-2</code>, and so on.</li>
				<li>The <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"file-as"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#transcriber-1"</span><span class="p">&gt;</span></code> element contains an alpha-sorted representation of the transcriber’s name.</li>
				<li>The <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a> is <code class="html">trc</code>: <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"role"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#transcriber-1"</span> <span class="na">scheme</span><span class="o">=</span><span class="s">"marc:relators"</span><span class="p">&gt;</span>trc<span class="p">&lt;/</span><span class="nt">meta</span><span class="p">&gt;</span></code>.</li>
				<li>If the transcriber’s personal homepage is known, the element <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:url.homepage"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#transcriber-1"</span><span class="p">&gt;</span></code> is included, whose value is the URL of the transcriber’s homepage. The URL must link to a personal homepage only; no products, services, or other endorsements, commercial or otherwise.</li>
			</ol>
		</section>
		<section id="producer-metadata">
			<h2>Producer metadata</h2>
			<p>These elements describe the SE producer who produced the ebook for the Standard Ebooks project.</p>
			<ol type="1">
				<li>If there is more than one producer, the first producer is <code class="html">producer-1</code>, the second <code class="html">producere-2</code>, and so on.</li>
				<li>The producer metadata block is identical to the author metadata block, but with <code class="html"><span class="p">&lt;</span><span class="nt">dc:contributor</span> <span class="na">id</span><span class="o">=</span><span class="s">"producer-1"</span><span class="p">&gt;</span></code> instead of <code class="html"><span class="p">&lt;</span><span class="nt">dc:creator</span> <span class="na">id</span><span class="o">=</span><span class="s">"author"</span><span class="p">&gt;</span></code>.</li>
				<li>If the producer’s personal homepage is known, the element <code class="html"><span class="p">&lt;</span><span class="nt">meta</span> <span class="na">property</span><span class="o">=</span><span class="s">"se:url.homepage"</span> <span class="na">refines</span><span class="o">=</span><span class="s">"#producer-1"</span><span class="p">&gt;</span></code> is included, whose value is the URL of the transcriber’s homepage. The URL must link to a personal homepage only; no products, services, or other endorsements, commercial or otherwise.</li>
				<li>The <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tags</a> for the SE producer usually include all of the following:
					<ul>
						<li><code class="html">bkp</code>: The producer produced the ebook.</li>
						<li><code class="html">blw</code>: The producer wrote the blurb (the long description).</li>
						<li><code class="html">cov</code>: The producer selected the cover art.</li>
						<li><code class="html">mrk</code>: The producer wrote the HTML markup for the ebook.</li>
						<li><code class="html">pfr</code>: The producer proofread the ebook.</li>
						<li><code class="html">tyg</code>: The producer reviewed the typography of the ebook.</li>
					</ul>
				</li>
			</ol>
		</section>
		<section id="the-ebook-manifest">
			<h2>The ebook manifest</h2>
			<p>The <code class="html"><span class="p">&lt;</span><span class="nt">manifest</span><span class="p">&gt;</span></code> element is a required part of the epub spec that defines a list of files within the ebook.</p>
			<aside class="tip">The <code class="bash">se print-manifest-and-spine</code> tool generates a complete manifest that can be copied-and-pasted into the ebook’s metadata file.</aside>
			<ol type="1">
				<li>The manifest is in alphabetical order.</li>
				<li>The <code class="html">id</code> attribute is the basename of the <code class="html">href</code> attribute.</li>
				<li>Files which contain SVG images have the additional <code class="html">properties="svg"</code> property in their manifest item.</li>
				<li>The manifest item for the table of contents file has the additional <code class="html">properties="nav"</code> property.</li>
				<li>The manifest item for the cover image has the additional <code class="html">properties="cover-image"</code> property.</li>
			</ol>
		</section>
		<section id="the-ebook-spine">
			<h2>The ebook spine</h2>
			<p>The <code class="html"><span class="p">&lt;</span><span class="nt">spine</span><span class="p">&gt;</span></code> element is a required part of the epub spec that defines the reading order of the files in the ebook.</p>
			<aside class="tip">The <code class="bash">se print-manifest-and-spine</code> tool generates a draft of the spine by making some educated guesses as to the reading order. The tool’s output is never 100% correct; manual review of the output is required, and adjustments will be necessary to correct the reading order.</aside>
		</section>
	</section>
		</article>
	</main>
<?= Template::Footer() ?>