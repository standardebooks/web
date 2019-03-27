<?
require_once('Core.php');
?><?= Template::Header(['Metadata Manual' => 'title', 'js' => true, 'highlight' => 'contribute', 'description' => 'The Standard Ebooks Metadata Manual, containing requirements and directives for the epub metadata file.']) ?>
<main>
	<article>
		<h1>Metadata Manual</h1>
		<aside class="alert">
			<p>Standard Ebooks is a brand-new project&mdash;this manual is a pre-alpha, and much of it is incomplete. If you have a question, need clarification, or run in to an issue not yet covered here, please <a href="https://groups.google.com/forum/#!forum/standardebooks">contact us</a> so we can update this manual.</p>
		</aside>
		<h2>General principles</h2>
		<p>Metadata in a Standard Ebooks epub is stored in the <code class="path">./src/epub/content.opf</code> file. The file contains some boilerplate that you won’t have to touch, and a lot of information that you <em>will</em> have to touch as you produce your ebook.</p>
		<p>You should follow the general structure of the <code class="path">content.opf</code> file present in the tools <code class="path">./templates/</code> directory. Don't rearrange the order of anything in there and you should be fine.</p>
		<h2>The <code class="html">&lt;dc:identifier&gt;</code> element</h2>
		<p>The <code class="html">&lt;dc:identifier&gt;</code> element contains the unique identifier for this ebook. That identifier is always the Standard Ebooks URL for that ebook, prefaced by <code class="html">url:</code>.</p>
		<figure>
			<code class="html">&lt;dc:identifier id="uid"&gt;url:https://standardebooks.org/ebooks/anton-chekhov/short-fiction/constance-garnett&lt;/dc:identifier&gt;</code>
		</figure>
		<h3>Forming the SE identifier</h3>
		<p>The SE identifier is formed by the following algorithm. A string can be made URL-safe using the <code class="program">make-url-safe</code> tool.</p>
		<ol>
			<li>
				<p>Start with the URL-safe author of the work, as it appears on the titlepage. If there is more than one author, continue appending subsequent URL-safe authors, separated by an underscore. Do not alpha-sort the author name.</p>
			</li>
			<li>
				<p>Append a forward slash, then the URL-safe title of the work. Again, do not alpha-sort the title.</p>
			</li>
			<li>
				<p>If the work is translated, append a forward slash, then the URL-safe translator. If there is more than one translator, continue appending subsequent URL-safe translators, separated by an underscore. Do not alpha-sort translator names.</p>
			</li>
			<li>
				<p>If the work is illustrated, append a foreward slash, then the URL-safe illustrator. If there is more than one illustrator, continue appending subsequent URL-safe illustrators, separated by an underscore. Do not alpha-sort illustrator names.</p>
			</li>
			<li>
				<p>Finally, <em>do not</em> append a trailing forward slash.</p>
			</li>
		</ol>
		<h2>The <code class="html">&lt;dc:date&gt;</code>, <code class="html">&lt;meta property="dcterms:modified"&gt;</code>, and <code class="html">&lt;meta property="se:revision-number"&gt;</code> elements</h2>
		<p>There are several elements in the metadata describing the publication date, updated date, and revision number of the ebook. Generally you don’t have to update these by hand; instead, use the <code class="program">prepare-release</code> tool to update them automatically both in <code class="path">content.opf</code> and in <code class="path">colophon.xhtml</code>.</p>
		<ul>
			<li>
				<p><code class="html">&lt;dc:date&gt;</code> is a timestamp representing the first publication date of this ebook file. Once the ebook is released to the public, this value doesn’t change.</p>
			</li>
			<li>
				<p><code class="html">&lt;meta property="dcterms:modified"&gt;</code> is a timestamp representing the last time this ebook file was modified. This changes often.</p>
			</li>
			<li>
				<p><code class="html">&lt;meta property="se:revision-number"&gt;</code> is a special SE extension representing the revision number of this ebook file. During production, this number will be 0. When the ebook is first released to the public, the number will increment to 1. Each time <code class="html">&lt;meta property="dcterms:modified"&gt;</code> changes, the revision number must be incremented.</p>
			</li>
		</ul>
		<h2>The ebook title</h2>
		<p>Usually titles are fairly easy to represent with the <code class="html">&lt;dc:title&gt;</code> element.</p>
		<h3>Books without subtitles</h3>
		<ul>
			<li>
				<p>Add a <code class="html">&lt;dc:title&gt;</code> element to contain the title.</p>
			</li>
			<li>
				<p>Add an accompanying <code class="html">file-as</code> element with the title alpha-sorted, even if the <code class="html">file-as</code> value is the same as the title.</p>
			</li>
		</ul>
		<p>These examples shows how to mark up a simple title like <i><a href="/ebooks/abraham-merritt/the-moon-pool">The Moon Pool</a></i> or <i><a href="/ebooks/anton-chekhov/short-fiction/constance-garnett">Chekhov’s Short Fiction</a></i>.</p><code class="html full">&lt;dc:title id="title"&gt;The Moon Pool&lt;/dc:title&gt; &lt;meta property="file-as" refines="#title"&gt;Moon Pool, The&lt;/meta&gt;</code> <code class="html full">&lt;dc:title id="title"&gt;Short Fiction&lt;/dc:title&gt; &lt;meta property="file-as" refines="#title"&gt;Short Fiction&lt;/meta&gt;</code>
		<h3>Books with subtitles</h3>
		<ul>
			<li>
				<p>Add a <code class="html">&lt;meta property="title-type"&gt;</code> element with the value of <code class="html">main</code> to identify the main part of the title.</p>
			</li>
			<li>
				<p>Add a second <code class="html">&lt;dc:title&gt;</code> element to contain the subtitle, and refine it with <code class="html">&lt;meta property="title-type"&gt;</code> set to <code class="html">subtitle</code>.</p>
			</li>
			<li>
				<p>Add a third <code class="html">&lt;dc:title&gt;</code> element to contain the complete title on one line, with the main title and subtitle separated by a colon and space. Refine it with <code class="html">&lt;meta property="title-type"&gt;</code> set to <code class="html">extended</code>.</p>
			</li>
			<li>
				<p>All three of these <code class="html">&lt;dc:title&gt;</code> elements must have an accompanying <code class="html">file-as</code> element, even if the <code class="html">file-as</code> value is the same as the title.</p>
			</li>
		</ul>
		<p>This example shows how to mark up <i><a href="/ebooks/g-k-chesterton/the-man-who-was-thursday">The Man Who Was Thursday: A Nightmare</a></i>.</p><code class="html full">&lt;dc:title id="title"&gt;The Man Who Was Thursday&lt;/dc:title&gt; &lt;meta property="file-as" refines="#title"&gt;Man Who Was Thursday, The&lt;/meta&gt; &lt;meta property="title-type" refines="#title"&gt;main&lt;/meta&gt; &lt;dc:title id="subtitle"&gt;A Nightmare&lt;/dc:title&gt; &lt;meta property="file-as" refines="#subtitle"&gt;Nightmare, A&lt;/meta&gt; &lt;meta property="title-type" refines="#subtitle"&gt;subtitle&lt;/meta&gt; &lt;dc:title id="fulltitle"&gt;The Man Who Was Thursday: A Nightmare&lt;/dc:title&gt; &lt;meta property="file-as" refines="#fulltitle"&gt;Man Who Was Thursday, The&lt;/meta&gt; &lt;meta property="title-type" refines="#fulltitle"&gt;extended&lt;/meta&gt;</code>
		<h3>Books with a more popularly-known alternative title</h3>
		<p>Some books are commonly referred to by a shorter name than their actual title. For example, <i><a href="/ebooks/mark-twain/the-adventures-of-huckleberry-finn">The Adventures of Huckleberry Finn</a></i> is often simply known as <i>Huck Finn</i>.</p>
		<ul>
			<li>
				<p>Add an additional <code class="html">&lt;dc:title&gt;</code> element to contain the common title, and refine it with <code class="html">&lt;meta property="title-type"&gt;</code> set to <code class="html">short</code>.</p>
			</li>
			<li>
				<p>Do not include a <code class="html">file-as</code> element for the short title.</p>
			</li>
		</ul><code class="html full">&lt;dc:title id="title-short"&gt;Huck Finn&lt;/dc:title&gt; &lt;meta property="title-type" refines="#title-short"&gt;short&lt;/meta&gt;</code>
		<h2>Ebook subjects</h2>
		<p>The <code class="html">&lt;dc:subject&gt;</code> elements allow us to categorize the ebook. We use the Library of Congress categories assigned to the book for this purpose.</p>
		<p>If you’re working on a book that has a Project Gutenberg transcription, you can almost always find these categorizations in the ebook’s “bibrec” tab, saving you effort.</p>
		<p>If your ebook doesn’t have a Project Gutenberg page, then you can search the <a href="https://catalog.loc.gov">Library of Congress catalog</a> to find the categories for your ebook.</p>
		<ul>
			<li>
				<p>Each <code class="html">&lt;dc:subject&gt;</code> has the <code class="html">id</code> attribute set to <code class="html">subject-#</code>, where # is a number starting at 1, without leading zeros, that increments with each subject.</p>
			</li>
			<li>
				<p>Arrange the <code class="html">&lt;dc:subject&gt;</code> elements sequentially in a block.</p>
			</li>
			<li>
				<p>After the block of <code class="html">&lt;dc:subject&gt;</code> elements, include a block of <code class="html">&lt;meta property="meta-auth"&gt;</code> elements with values representing the URL at which you found the categorizations for each individual subject.</p>
				<p>For example, if we found the Library of Congress categories for an ebook at the Project Gutenberg ebook’s “bibrec” tab, then you would use the URL of the Project Gutenberg ebook.</p>
			</li>
			<li>
				<p>The <code class="html">&lt;meta property="meta-auth"&gt;</code> element must refine each individual <code class="html">&lt;dc:subject&gt;</code> element, even if the URL is the same for all of them.</p>
			</li>
		</ul>
		<p>This example shows how to mark up the subjects for <i><a href="/ebooks/david-lindsay/a-voyage-to-arcturus">A Voyage to Arcturus</a></i>:</p><code class="html full">&lt;dc:subject id="subject-1"&gt;Science fiction&lt;/dc:subject&gt; &lt;dc:subject id="subject-2"&gt;Psychological fiction&lt;/dc:subject&gt; &lt;dc:subject id="subject-3"&gt;Quests (Expeditions) -- Fiction&lt;/dc:subject&gt; &lt;dc:subject id="subject-4"&gt;Life on other planets -- Fiction&lt;/dc:subject&gt; &lt;meta property="meta-auth" refines="#subject-1"&gt;https://www.gutenberg.org/ebooks/1329&lt;/meta&gt; &lt;meta property="meta-auth" refines="#subject-2"&gt;https://www.gutenberg.org/ebooks/1329&lt;/meta&gt; &lt;meta property="meta-auth" refines="#subject-3"&gt;https://www.gutenberg.org/ebooks/1329&lt;/meta&gt; &lt;meta property="meta-auth" refines="#subject-4"&gt;https://www.gutenberg.org/ebooks/1329&lt;/meta&gt;</code>
		<h2>SE subjects</h2>
		<p>Along the Library of Congress categories, we include a custom list of SE subjects in the ebook metadata. Unlike Library of Congress categories, SE subjects are purposefully broad. They’re more like subject categories you’d find a medium-sized bookstore, as opposed to the precise, detailed, heirarchical Library of Congress categories.</p>
		<p>It’s your task to select appropriate SE subjects for your ebook. Usually just one or two of these categories will suffice.</p>
		<p>If you strongly feel like your book deserves a new category, please contact us to discuss it.</p>
		<p>Below is a list of all of the recognized SE subjects:</p>
		<ul>
			<li>
				<p><code class="tag">Adventure</code></p>
			</li>
			<li>
				<p><code class="tag">Autobiography</code></p>
			</li>
			<li>
				<p><code class="tag">Childrens</code></p>
			</li>
			<li>
				<p><code class="tag">Comedy</code></p>
			</li>
			<li>
				<p><code class="tag">Drama</code></p>
			</li>
			<li>
				<p><code class="tag">Fantasy</code></p>
			</li>
			<li>
				<p><code class="tag">Fiction</code></p>
			</li>
			<li>
				<p><code class="tag">Horror</code></p>
			</li>
			<li>
				<p><code class="tag">Memoir</code></p>
			</li>
			<li>
				<p><code class="tag">Mystery</code></p>
			</li>
			<li>
				<p><code class="tag">Nonfiction</code></p>
			</li>
			<li>
				<p><code class="tag">Philosophy</code></p>
			</li>
			<li>
				<p><code class="tag">Poetry</code></p>
			</li>
			<li>
				<p><code class="tag">Satire</code></p>
			</li>
			<li>
				<p><code class="tag">Science Fiction</code></p>
			</li>
			<li>
				<p><code class="tag">Shorts</code></p>
			</li>
			<li>
				<p><code class="tag">Spirituality</code></p>
			</li>
			<li>
				<p><code class="tag">Travel</code></p>
			</li>
		</ul>
		<h3>Required subjects for specific kinds of ebooks</h3>
		<ul>
			<li>
				<p>Ebooks that are collections of short stories must have the SE subject <code class="tag">Shorts</code>.</p>
			</li>
			<li>
				<p>Ebooks that are young adult or children’s books must have the SE subject <code class="tag">Childrens</code>.</p>
			</li>
		</ul>
		<h2>Ebook descriptions</h2>
		<p>An ebook has two kinds of descriptions: a short <code class="html">&lt;dc:description&gt;</code> element, and a much longer <code class="html">&lt;meta property="se:long-description"&gt;</code> element.</p>
		<h3>The short description</h3>
		<p>The <code class="html">&lt;dc:description&gt;</code> element contains a short, single-sentence summary of the ebook.</p>
		<ul>
			<li>
				<p>The description must be a single complete sentence ending in a period, not a sentence fragment or restatment of the title. Do not use more than one sentence.</p>
			</li>
			<li>
				<p>The description must be typogrified, i.e. it must contain Unicode curly quotes, em-dashes, and the like.</p>
			</li>
		</ul>
		<h3>The long description</h3>
		<p>The <code class="html">&lt;meta property="se:long-description"&gt;</code> element contains a much longer description of the ebook.</p>
		<ul>
			<li>
				<p>The long description must be a non-biased, encyclopedia-like description of the ebook, including any relevant publication history, backstory, or historical notes. It must be as detailed as possible, without giving away plot spoilers. It must not impart the producer’s opinions of the book. Think along the lines of a Wikipedia-like summary of the book and its history, <em>but under no circumstances can you copy and paste from Wikipedia!</em></p>
			</li>
			<li>
				<p>The long descriptions must be typogrified, i.e. it must contain Unicode curly quotes, em-dashes, and the like.</p>
			</li>
			<li>
				<p>The long description must be in <em>escaped</em> HTML, with the HTML beginning on its own line after the <code class="html">&lt;meta property="se:long-description"&gt;</code> element.</p>
				<p>An easy way to escape your HTML is to compose your long description in regular HTML, then insert it into the <code class="html">&lt;meta property="se:long-description"&gt;</code> element surrounded by a <code class="html">&lt;![CDATA[ ... ]]&gt;</code> element. Once you’re done, run the <code class="path">clean</code> tool, which will remove the <code class="html">&lt;![CDATA[ ... ]]&gt;</code> element and escape the contained HTML for you.</p>
			</li>
			<li>
				<p>Include the following element directly after the long description: <code class="html">&lt;meta property="meta-auth" refines="#long-description"&gt;https://standardebooks.org&lt;/meta&gt;</code></p>
			</li>
		</ul>
		<h2>The <code class="html">&lt;dc:language&gt;</code> element</h2>
		<p>The <code class="html">&lt;dc:language&gt;</code> element follows the long description block. It contains the IETF language tag for the language that the work is in. Usually this is either <code class="ietf">en-US</code> or <code class="ietf">en-GB</code>.</p>
		<h2>The <code class="html">&lt;dc:source&gt;</code> elements</h2>
		<p>The <code class="html">&lt;dc:source&gt;</code> elements represent URLs to sources for both the transcription we based this ebook off of, and page scans of the print sources used to correct or work on the transcriptions.</p>
		<ul>
			<li>
				<p>Where possible, these URLs should use https.</p>
			</li>
			<li>
				<p>A Standard Ebooks ebook typically contains more than one <code class="html">&lt;dc:source&gt;</code> element, and can contain more than one such element if multiple sources for page scans were used.</p>
			</li>
		</ul>
		<h2>The <code class="html">&lt;meta property="se:production-notes"&gt;</code> elements</h2>
		<p>This element can be used by the ebook producers to convey production notes relevant to the production process. For example, a producer might note that page scans were not available, so an editorial decision was made to add commas to sentences deemed to be transcription typos.</p>
		<p>If there are no production notes, remove this element.</p>
		<h2>The <code class="html">&lt;meta property="se:word-count"&gt;</code> and <code class="html">&lt;meta property="se:reading-ease.flesch"&gt;</code> elements</h2>
		<p>These elements are automatically computed by the <code class="program">prepare-release</code> tool. Don’t compute them by hand.</p>
		<h2>SE-specific metadata for the ebook</h2>
		<p>Next, Standard Ebooks also includes two additional custom metadata items about the ebook:</p>
		<ol>
			<li>
				<p><code class="html">&lt;meta property="se:url.encyclopedia.wikipedia"&gt;</code> contains the Wikipedia URL for this ebook. If there isn’t one, remove this element.</p>
			</li>
			<li>
				<p><code class="html">&lt;meta property="se:url.vcs.github"&gt;</code> contains the GitHub URL for this ebook. This is calculated by taking the string “https://github.com/standardebooks/” and appending the ebook identifier (calculated above), without “https://standardebooks.org/ebooks/”, and with forward slashes replaced by underscores.</p>
			</li>
		</ol>
		<h2>Author metadata</h2>
		<p>Next, we include the author metadata block.</p>
		<p>The author metadata block always has the ID of “author”. If there is more than one author, the first author is “author-1”, the second “author-2”, and so on. Each block of the following, in this order:</p>
		<ol>
			<li>
				<p><code class="html">&lt;dc:creator id="author"&gt;</code>: the author’s name as it appears on the cover.</p>
			</li>
			<li>
				<p><code class="html">&lt;meta property="file-as" refines="#author"&gt;</code>: the author’s name as filed alphabetically. Include this even if it’s identical to <code class="html">&lt;dc:creator&gt;</code>.</p>
			</li>
			<li>
				<p><code class="html">&lt;meta property="se:name.person.full-name" refines="#author"&gt;</code>: the author’s full name, with any initials or middle names expanded. If this is identical to <code class="html">&lt;dc:creator&gt;</code>, remove this element.</p>
			</li>
			<li>
				<p><code class="html">&lt;meta property="alternate-script" refines="#author"&gt;</code>: the author’s name as it appears on the cover, but transliterated into their native alphabet if applicable. For example, Anton Chekhov’s name would be contained here in the Cyrillic alphabet. Remove this element if not applicable.</p>
			</li>
			<li>
				<p><code class="html">&lt;meta property="se:url.encyclopedia.wikipedia" refines="#author"&gt;</code>: the URL of the author’s Wikipedia page. Remove this element if not applicable.</p>
			</li>
			<li>
				<p><code class="html">&lt;meta property="se:url.authority.nacoaf" refines="#author"&gt;</code>: the URL of the author’s Library of Congress Names Database page.</p>
				<ul>
					<li>
						<p>This is easily found by visiting the person’s Wikipedia page and looking at the very bottom in the “Authority Control” section, under “LCCN”.</p>
					</li>
					<li>
						<p>If you can’t find it in Wikipedia, you can find it directly by visiting <a href="http://id.loc.gov/authorities/names.html">http://id.loc.gov/authorities/names.html</a>.</p>
					</li>
					<li>
						<p>Note that the canonical URLs <em>do not</em> include a trailing <code class="path">.html</code> (the LoC site performs a silent redirect when you load it to append <code class="path">.html</code> to the URL it considers canonical). Remove this element if not applicable.</p>
					</li>
				</ul>
			</li>
			<li>
				<p><code class="html">&lt;meta property="role" refines="#author" scheme="marc:relators"&gt;</code>: the <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a> for the roles the author played in creating this book. You will always have one element with the value of <code class="marc">aut</code>. You can have additional elements for additional values, if applicable. For example, if the author also illustrated the book, you would include an additional element with the value of <code class="marc">ill</code>.</p>
			</li>
		</ol>
		<h3>An example of a complete author metadata block</h3><code class="html full">&lt;dc:creator id="author"&gt;Anton Chekhov&lt;/dc:creator&gt; &lt;meta property="file-as" refines="#author"&gt;Chekhov, Anton&lt;/meta&gt; &lt;meta property="se:name.person.full-name" refines="#author"&gt;Anton Pavlovich Chekhov&lt;/meta&gt; &lt;meta property="alternate-script" refines="#author"&gt;Анто́н Па́влович Че́хов&lt;/meta&gt; &lt;meta property="se:url.encyclopedia.wikipedia" refines="#author"&gt;https://en.wikipedia.org/wiki/Anton_Chekhov&lt;/meta&gt; &lt;meta property="se:url.authority.nacoaf" refines="#author"&gt;http://id.loc.gov/authorities/names/n79130807&lt;/meta&gt; &lt;meta property="role" refines="#author" scheme="marc:relators"&gt;aut&lt;/meta&gt;</code>
		<h2>Translator metadata</h2>
		<p>If the work is translated, the translator metadata block follows.</p>
		<p>The translator metadata block always has the ID of “translator”. If there is more than one translator, the first translator is “translator-1”, the second “translator-2”, and so on. Each block is identical to the author metadata block, but using <code class="html">&lt;dc:contributor id="translator"&gt;</code> instead of <code class="html">&lt;dc:creator&gt;</code>. The <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a> will be <code class="marc">trl</code>. Translators often annotate the work; if this is the case, also include the <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a> <code class="marc">ann</code>.</p>
		<h2>Illustrator metadata</h2>
		<p>If the work is illustrated by a person who is not the author, the illustrator metadata block follows.</p>
		<p>The illustrator metadata block always has the ID of “illustrator”. If there is more than one author, the first illustrator is “illustrator-1”, the second “illustrator-2”, and so on. Each block is identical to the author metadata block, but using <code class="html">&lt;dc:contributor id="illustrator"&gt;</code> instead of <code class="html">&lt;dc:creator&gt;</code>. The <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a> will be <code class="marc">ill</code>.</p>
		<h2>Cover artist metadata</h2>
		<p>The cover artist metadata block follows.</p>
		<p>The cover artist metadata block always has the ID of “artist”. Each block is identical to the author metadata block, but using <code class="html">&lt;dc:contributor id="artist"&gt;</code> instead of <code class="html">&lt;dc:creator&gt;</code>. The <a href="http://www.loc.gov/marc/relators/relacode.html">MARC relator tag</a> will be <code class="marc">art</code>.</p>
		<h2>Transcriber metadata</h2>
		<p>If you based this ebook on a transcription by someone else, like Project Gutenberg, then transcriber blocks follow. The first transcriber is “transcriber-1”, the second “transcriber-2”, and so on. Usually, trancribers only have the following two elements:</p>
		<ol>
			<li>
				<p><code class="html">&lt;meta property="file-as" refines="#transcriber-1"&gt;</code></p>
			</li>
			<li>
				<p><code class="html">&lt;meta property="role" refines="#transcriber-1" scheme="marc:relators"&gt;</code> with the value of <code class="marc">trc</code>.</p>
			</li>
		</ol>
		<h2>Producer metadata</h2>
		<p>This block is for information about you, the producer of this Standard Ebook. It contains the same type of elements as the author block, but with <code class="html">&lt;dc:contributor id="producer-1"&gt;</code>.</p>
		<ol>
			<li>
				<p>You can include the <code class="html">&lt;meta property="se:url.homepage" refines="#producer-1"&gt;</code> element with a link to your personal homepage. This must be a link to a personal homepage only; no products, services, or other endorsements, commercial or otherwise.</p>
			</li>
			<li>
				<p>Your MARC relator roles will usually be the following:</p>
				<ul>
					<li>
						<p><code class="marc">bkp</code>: you are the producer of this ebook.</p>
					</li>
					<li>
						<p><code class="marc">blw</code>: you wrote the blurb (the long description).</p>
					</li>
					<li>
						<p><code class="marc">cov</code>: you selected the cover art.</p>
					</li>
					<li>
						<p><code class="marc">mrk</code>: you wrote HTML markup for this ebook.</p>
					</li>
					<li>
						<p><code class="marc">pfr</code>: you proofread the ebook.</p>
					</li>
					<li>
						<p><code class="marc">tyg</code>: you reviewed the typography of the ebook.</p>
					</li>
				</ul>
			</li>
		</ol>
		<h2>The <code class="html">&lt;manifest&gt;</code> element</h2>
		<p>The <code class="html">&lt;manifest&gt;</code> element is a required part of the epub spec. This should usually be generated by the <code class="program">print-manifest-and-spine</code> tool and copy-and-pasted into the <code class="path">content.opf</code> file. It must be in alphabetical order, which is handled for you by the <code class="program">print-manifest-and-spine</code> tool.</p>
		<h2>The <code class="html">&lt;spine&gt;</code> element</h2>
		<p>The <code class="html">&lt;spine&gt;</code> element is a required part of the epub spec that defines the reading order of the files in the ebook. You can use the <code class="program">print-manifest-and-spine</code> tool to generate a draft of the spine. The tool makes a best guess as to the spine order, but it cannot be 100% correct; please review the output and adjust the reading order accordingly.</p>
	</article>
</main>
<?= Template::Footer() ?>
