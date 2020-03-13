<?
require_once('Core.php');
?><?= Template::Header(['title' => '8. Typography - The Standard Ebooks Manual', 'highlight' => 'contribute', 'manual' => true]) ?>
	<main>
		<article class="manual">

	<section data-start-at="8" id="typography">
		<h1>Typography</h1>
		<section id="section-titles-and-ordinals">
			<h2>Section titles and ordinals</h2>
			<ol type="1">
				<li>Section ordinals in the body text are set in Roman numerals.</li>
				<li>Section ordinals in a file's <code class="html"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span></code> element are set in Arabic numerals.
					<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>Chapter VII<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span></code></figure>
					<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">title</span><span class="p">&gt;</span>Chapter 7<span class="p">&lt;/</span><span class="nt">title</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>Section titles are titlecased according to the output of <code class="bash">se titlecase</code>. Section titles are <em>not</em> all-caps or small-caps.</li>
				<li>Section titles do not have trailing periods.</li>
				<li>Chapter titles omit the word <code class="html">Chapter</code>, unless the word used is a stylistic choice for prose style purposes. Chapters with unique identifiers (i.e. not <code class="html">Chapter</code>, but something unique to the style of the book, like <code class="html">Book</code> or <code class="html">Stave</code>) <em>do</em> include that unique identifier in the title.
					<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>Chapter <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>II<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code></figure>
					<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title z3998:roman&quot;</span><span class="p">&gt;</span>II<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code></figure>
					<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title&quot;</span><span class="p">&gt;</span>Stave <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>III<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span></code></figure>
					<p>In special cases it may be desirable to retain <code class="html">Chapter</code> for clarity. For example, <a href="/ebooks/mary-shelley/frankenstein/">Frankenstein</a> has “Chapter” in titles to differentiate between the “Letter” sections.</p>
				</li>
			</ol>
		</section>
		<section id="italics">
			<h2>Italics</h2>
			<ol type="1">
				<li>Using both italics <em>and</em> quotes (outside of the context of quoted dialog) is usually not necessary. Either one or the other is used based on the rules above.</li>
				<li>Words and phrases that require emphasis are italicized with the <code class="html"><span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span></code> element.
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Perhaps <span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span>he<span class="p">&lt;/</span><span class="nt">em</span><span class="p">&gt;</span> was there,” Raoul said, at last.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>Strong emphasis, like shouting, may be set in small caps with the <code class="html"><span class="p">&lt;</span><span class="nt">strong</span><span class="p">&gt;</span></code> element.
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“<span class="p">&lt;</span><span class="nt">strong</span><span class="p">&gt;</span>Can’t<span class="p">&lt;/</span><span class="nt">strong</span><span class="p">&gt;</span> I?” screamed the unhappy creature to himself.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>When a short phrase within a longer clause is italicized, trailing punctuation that may belong to the containing clause is not italicized.
					<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Look at <span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span>that!<span class="p">&lt;/</span><span class="nt">em</span><span class="p">&gt;</span>” she shouted.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Look at <span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span>that<span class="p">&lt;/</span><span class="nt">em</span><span class="p">&gt;</span>!” she shouted.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>When an entire clause is italicized, trailing punctuation <em>is</em> italicized, <em>unless</em> that trailing punctuation is a comma at the end of dialog.
					<p class="html">&lt;p&gt;“&lt;em&gt;Charge!&lt;/em&gt;” she shouted.&lt;/p&gt;</p>
					<p class="html">&lt;p&gt;“&lt;em&gt;But I want to&lt;/em&gt;,” she said.&lt;/p&gt;</p>
				</li>
				<li>Words written to be read as sounds are italicized with <code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code>.
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He could hear the dog barking: <span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span>Ruff, ruff, ruff!<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
			</ol>
			<section id="italicizing-individual-letters">
				<h3>Italicizing individual letters</h3>
				<ol type="1">
					<li>Individual letters that are read as referring to letters in the alphabet are italicized with the <code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code> element.</li>
				</ol>
				<blockquote>
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He often rolled his <span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span>r<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>’s.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</blockquote>
				<ol type="1">
					<li>Individual letters that are read as referring names or the shape of the letterform are <em>not</em> italicized.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>...due to the loss of what is known in New England as the “L”: that long deep roofed adjunct usually built at right angles to the main house...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>She was learning her A B Cs.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>His trident had the shape of an E.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>The ordinal <code class="html">nth</code> is set with an italicized <code class="html">n</code>, and without a hyphen.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The <span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span>n<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>th degree.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
			<section id="italicizing-non-english-words-and-phrases">
				<h3>Italicizing non-English words and phrases</h3>
				<ol type="1">
					<li>Non-English words and phrases that are not in <a href="https://www.merriam-webster.com">Merriam-Webster</a> are italicized.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The <span class="p">&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">&quot;fr&quot;</span><span class="p">&gt;</span>corps de ballet<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span> was flung into consternation.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Foreign words that are proper names, or are in proper names, are not italicized, unless the name itself would be italicized according to the rules for italicizing or quoting names and titles. Such words are wrapped in a <code class="html"><span class="p">&lt;</span><span class="nt">span</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"LANGUAGE"</span><span class="p">&gt;</span></code> element, to assist screen readers with pronunciation.
						<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>She got off the metro at the <span class="p">&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">&quot;fr&quot;</span><span class="p">&gt;</span>Place de Clichy<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span> stop, next to the <span class="p">&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">&quot;fr&quot;</span><span class="p">&gt;</span>PLe Bon Petit Déjeuner restaurant<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“<span class="p">&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">&quot;fr&quot;</span><span class="p">&gt;</span>Où est le métro?<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>” he asked, and she pointed to <span class="p">&lt;</span><span class="nt">span</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">&quot;fr&quot;</span><span class="p">&gt;</span>Place de Clichy<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>, next to the <span class="p">&lt;</span><span class="nt">span</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">&quot;fr&quot;</span><span class="p">&gt;</span>Le Bon Petit Déjeuner<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span> restaurant.</code></figure>
					</li>
					<li>If certain foreign words are used so frequently in the text that italicizing them at each instance would be distracting to the reader, then only the first instance is italicized. Subsequent instances are wrapped in a <code class="html"><span class="p">&lt;</span><span class="nt">span</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"LANGUAGE"</span><span class="p">&gt;</span></code> element.</li>
					<li>Words and phrases that are originally non-English in origin, but that can now be found in <a href="https://www.merriam-webster.com">Merriam-Webster</a>, are not italicized.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Sir Percy’s bon mot had gone the round of the brilliant reception-rooms.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Inline-level italics are set using the <code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code> element with an <code class="html">xml:lang</code> attribute corresponding to the correct <a href="https://en.wikipedia.org/wiki/IETF_language_tag">IETF language tag</a>.</li>
					<li>Block-level italics are set using an <code class="html">xml:lang</code> attribute on the top-level block element, with the style of <code class="css"><span class="nt">font-style</span><span class="o">:</span> <span class="nt">italic</span></code>.
						<p>In this example, note the additional namespace declaration, and that we target <em>descendants</em> of the <code class="html"><span class="p">&lt;</span><span class="nt">body</span><span class="p">&gt;</span></code> element; otherwise, the entire <code class="html"><span class="p">&lt;</span><span class="nt">body</span><span class="p">&gt;</span></code> element would receive italics!</p>
						<figure><code class="css full"><span class="p">@</span><span class="k">namespace</span> <span class="nt">xml</span> <span class="s2">&quot;http://www.w3.org/XML/1998/namespace&quot;</span><span class="p">;</span>

<span class="nt">body</span> <span class="o">[</span><span class="nt">xml</span><span class="o">|</span><span class="nt">lang</span><span class="o">]</span><span class="p">{</span>
	<span class="k">font-style</span><span class="p">:</span> <span class="kc">italic</span><span class="p">;</span>
<span class="p">}</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:verse&quot;</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">&quot;la&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>—gelidas leto scrutata medullas,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Pulmonis rigidi stantes sine vulnere fibras<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Invenit, et vocem defuncto in corpore quaerit.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
			<section id="italicizing-or-quoting-newly-used-english-words">
				<h3>Italicizing or quoting newly-used English words</h3>
				<ol type="1">
					<li>When introducing new terms, foreign or technical terms are italicized, but terms composed of common English are set in quotation marks.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>English whalers have given this the name “ice blink.”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>

<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The soil consisted of that igneous gravel called <span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span>tuff<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>English neologisms in works where a special vocabulary is a regular part of the narrative are not italicized. For example science fiction works may necessarily contain made-up English technology words, and those are not italicized. However, “alien” language in such works <em>is</em> italicized.</li>
				</ol>
			</section>
			<section id="italics-in-names-and-titles">
				<h3>Italics in names and titles</h3>
				<ol type="1">
					<li>Place names, like pubs, bars, or buildings, are not quoted.</li>
					<li>The names of publications, music, and art that can stand alone are italicized; additionally, the names of transport vessels are italicized. These include, but are not limited to:
						<ul>
							<li>Periodicals like magazines, newspapers, and journals.</li>
							<li>Publications like books, novels, plays, and pamphlets, <em>except</em> “holy texts,” like the Bible or books within the Bible.</li>
							<li>Long poems and ballads, like the Iliad, that are book-length.</li>
							<li>Long musical compositions or audio, like operas, music albums, or radio shows.</li>
							<li>Long visual art, like films or a TV show series.</li>
							<li>Visual art, like paintings or sculptures.</li>
							<li>Transport vessels, like ships.</li>
						</ul>
					</li>
					<li>The names of short publications, music, or art, that cannot stand alone and are typically part of a larger collection or work, are quoted. These include, but are not limited to:
						<ul>
							<li>Short musical compositions or audio, like pop songs, arias, or an episode in a radio series.</li>
							<li>Short prose like novellas, shot stories, or short (i.e. not epic) poems.</li>
							<li>Chapter titles in a prose work.</li>
							<li>Essays or individual articles in a newspaper or journal.</li>
							<li>Short visual art, like short films or episodes in a TV series.</li>
						</ul>
					</li>
				</ol>
				<section id="examples" class="no-numbering">
					<h4>Examples</h4>
					<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He read “Candide” while having a pint at the “King’s Head.”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He read <span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;se:name.publication.book&quot;</span><span class="p">&gt;</span>Candide<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span> while having a pint at the King’s Head.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</section>
			</section>
			<section id="taxonomy">
				<h3>Taxonomy</h3>
				<ol type="1">
					<li>Binomial names (generic, specific, and subspecific) are italicized with a <code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code> element having the <code class="html">z3998:taxonomy</code> semantic inflection.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>A bonobo monkey is <span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:taxonomy&quot;</span><span class="p">&gt;</span>Pan paniscus<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Family, order, class, phylum or division, and kingdom names are capitalized but not italicized.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>A bonobo monkey is in the phylum Chordata, class Mammalia, order Primates.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>If a taxonomic name is the same as the common name, it is not italicized.</li>
					<li>The second part of the binomial name follows the capitalization style of the source text. Modern usage requires lowercase, but older texts may set it in uppercase.</li>
				</ol>
			</section>
		</section>
		<section id="capitalization">
			<h2>Capitalization</h2>
			<ol type="1">
				<li>In general, capitalization follows modern English style. Some very old works frequently capitalize nouns that today are no longer capitalized. These archaic capitalizations are removed, unless doing so would change the meaning of the work.</li>
				<li>Text in all caps is almost never correct typography. Instead, such text is changed to the correct case and surround with a semantically-meaningful element like <code class="html"><span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span></code> (for emphasis), <code class="html"><span class="p">&lt;</span><span class="nt">strong</span><span class="p">&gt;</span></code> (for strong emphasis, like shouting) or <code class="html"><span class="p">&lt;</span><span class="nt">b</span><span class="p">&gt;</span></code> (for unsemantic formatting required by the text). <code class="html"><span class="p">&lt;</span><span class="nt">strong</span><span class="p">&gt;</span></code> and <code class="html"><span class="p">&lt;</span><span class="nt">b</span><span class="p">&gt;</span></code> are styled in small-caps by default in SE ebooks.
					<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The sign read BOB’S RESTAURANT.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“CHARGE!” he cried.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The sign read <span class="p">&lt;</span><span class="nt">b</span><span class="p">&gt;</span>Bob’s Restaurant<span class="p">&lt;/</span><span class="nt">b</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“<span class="p">&lt;</span><span class="nt">strong</span><span class="p">&gt;</span>Charge!<span class="p">&lt;/</span><span class="nt">strong</span><span class="p">&gt;</span>” he cried.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>When something is addressed as an <cite>apostrophe &lt;https://www.merriam-webster.com/dictionary/apostrophe#dictionary-entry-2&gt;</cite>.., <code class="html">O</code> is capitalized.
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>I carried the bodies into the sea, O walker in the sea!<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>Titlecasing follows the formula used in the <code class="bash">se build-images</code> tool.</li>
			</ol>
		</section>
		<section id="indentation">
			<h2>Indentation</h2>
			<ol type="1">
				<li>Body text in a new paragraph that directly follows earlier body text is indented by 1em.</li>
				<li>The initial line of body text in a section, or any text following a visible break in text flow, like a header, a scene break, a figure, a block quotation, etc., is not indented.
					<p>For example: in a block quotation, there is a margin before the quotation and after the quotation. Thus, the first line of the quotation is not indented, and the first line of body text after the block quotation is also not indented.</p>
				</li>
			</ol>
		</section>
		<section id="chapter-headers">
			<h2>Chapter headers</h2>
			<ol type="1">
				<li>Epigraphs in chapter have the quote source set in small caps, without a leading em-dash and without a trailing period.
					<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title z3998:roman&quot;</span><span class="p">&gt;</span>II<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;epigraph&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Desire no more than to thy lot may fall. …”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">cite</span><span class="p">&gt;</span>—Chaucer.<span class="p">&lt;/</span><span class="nt">cite</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">header</span><span class="p">&gt;</span></code></figure>
					<figure class="corrected"><code class="css full"><span class="nt">header</span> <span class="o">[</span><span class="nt">epub</span><span class="o">|</span><span class="nt">type</span><span class="o">~=</span><span class="s2">&quot;epigraph&quot;</span><span class="o">]</span> <span class="nt">cite</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
					<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title z3998:roman&quot;</span><span class="p">&gt;</span>II<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;epigraph&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Desire no more than to thy lot may fall. …”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">cite</span><span class="p">&gt;</span>Chaucer<span class="p">&lt;/</span><span class="nt">cite</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">header</span><span class="p">&gt;</span></code></figure>
				</li>
			</ol>
		</section>
		<section id="ligatures">
			<h2>Ligatures</h2>
			<p>Ligatures are two or more letters that are combined into a single letter, usually for stylistic purposes. In general they are not used, and are replaced with their respective characters.</p>
			<blockquote>
				<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Œdipus Rex<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Archæology<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Oedipus Rex<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Archaeology<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
			</blockquote>
		</section>
		<section id="punctuation-and-spacing">
			<h2>Punctuation and spacing</h2>
			<ol type="1">
				<li>Sentences are single-spaced.</li>
				<li>Ampersands in names of things, like firms, are surrounded by no-break spaces.
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The firm of Hawkins<span class="ws">nbsp</span><span class="ni">&amp;amp;</span><span class="ws">nbsp</span>Harker.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>Some older works include spaces in common contrations; these spaces are removed.
					<!-- See https://english.stackexchange.com/questions/217821/space-before-apostrophe -->
					<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Would n’t it be nice to go out? It ’s such a nice day.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Wouldn’t it be nice to go out? It’s such a nice day.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
			</ol>
			<section id="quotation-marks">
				<h3>Quotation marks</h3>
				<ol type="1">
					<li>“Curly” or typographer’s quotes, both single and double, are always be used instead of straight quotes.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Don’t do it!” she shouted.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Quotation marks that are directly side-by-side are separated by a hair space (u+200A) character.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“<span class="ws">hairsp</span>‘Green?’ Is that what you said?” asked Dave.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Words with missing letters represent the missing letters with a right single quotation mark (<code class="utf">’</code> or u+2019) character to indicate elision.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He had pork ’n’ beans for dinner<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
			<section id="ellipses">
				<h3>Ellipses</h3>
				<ol type="1">
					<li>The ellipses glyph (<code class="utf">…</code> or u+2026) is used for ellipses, instead of consecutive or spaced periods.</li>
					<li>When ellipses are used as suspension points (for example, to indicate dialog that pauses or trails off), the ellipses is not preceded by a comma.
						<p>Ellipses used to indicate missing words in a quotation require keeping surrounding punctuation, including commas, as that punctuation is in the original quotation.</p>
					</li>
					<li>A hair space (u+200A) glyph is located <em>before</em> all ellipses that are not directly preceded by punctuation, or that are directly preceded by an em-dash or a two- or three-em-dash.</li>
					<li>A regular space is located <em>after</em> all ellipses that are not followed by punctuation.</li>
					<li>A hair space (u+200A) glyph is located between an ellipses and any punctuation that follows directly after the ellipses, <em>unless</em> that punctuation is a quotation mark, in which case there space at all between the ellipses and the quotation mark.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“I’m so hungry<span class="ws">hairsp</span>… What were you saying about eating<span class="ws">hairsp</span>…?”</code></figure>
					</li>
				</ol>
			</section>
			<section id="dashes">
				<h3>Dashes</h3>
				<p>There are many kinds of dashes, and the run-of-the-mill hyphen is often not the correct dash to use. In particular, hyphens are not used for things like date ranges, phone numbers, or negative numbers.</p>
				<ol type="1">
					<li>Dashes of all types do not have white space around them.</li>
					<li>Figure dashes (<code class="utf">‒</code> or u+2012) are used to indicate a dash in numbers that aren’t a range, like phone numbers.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>His number is 555‒1234.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Hyphens (<code class="utf">-</code> or u+002D) are used to join words, including double-barrel names, or to separate syllables in a word.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Pre- and post-natal.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The Smoot-Hawley act.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Minus sign glyphs (<code class="utf">−</code> or u+2212) are used to indicate negative numbers, and are used in mathematical equations instead of hyphens to represent the “subtraction” operator.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>It was −5° out yesterday!<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>5 − 2 = 3<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>En-dashes (<code class="utf">–</code> or u+2013) are used to indicate a numerical or date range; to indicate a relationships where two concepts are connected by the word “to,” for example a distance between locations or a range between numbers; or to indicate a connection in location between two places.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>We talked 2–3 days ago.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>We took the Berlin–Munich train yesterday.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>I saw the torpedo-boat in the Ems⁠–⁠Jade Canal.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
				<section id="em-dashes">
					<h4>Em-dashes</h4>
					<p>Em-dashes (<code class="utf">—</code> or u+2014) are typically used to offset parenthetical phrases.</p>
					<p># Em-dashes are preceded by the invisible word joiner glyph (u+2060).</p>
					<ol type="1">
						<li>Interruption in dialog is set by a single em-dash, not two em-dashes or a two-em-dash.
							<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“I wouldn’t go as far as that, not myself, but<span class="ws">wj</span>——”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
							<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“I wouldn’t go as far as that, not myself, but<span class="ws">wj</span>—”<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						</li>
					</ol>
				</section>
				<section id="partially-obscured-words">
					<h4>Partially-obscured words</h4>
					<ol type="1">
						<li>Em-dashes are used for partially-obscured years.
							<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>It was the year 19<span class="ws">wj</span>— in the town of Metropolis.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						</li>
						<li>A regular hyphen is used in partially obscured years where only the last number is obscured.
							<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>It was the year 192- in the town of Metropolis.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						</li>
						<li>A two-em-dash (<code class="utf">⸺</code> or u+2E3A) preceded by a word joiner (u+2060) is used in <em>partially</em> obscured word.
							<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Sally J<span class="ws">wj</span>⸺ walked through town.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						</li>
						<li>A three-em-dash (<code class="utf">⸻</code> or u+2E3B) is used for <em>completely</em> obscured words.
							<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>It was night in the town of ⸻.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						</li>
					</ol>
				</section>
			</section>
		</section>
		<section id="numbers-measurements-and-math">
			<h2>Numbers, measurements, and math</h2>
			<ol type="1">
				<li>Coordinates are set with the prime (<code class="utf">′</code> or u+2032) or double prime (<code class="utf">″</code> or u+2033) glyphs, <em>not</em> single or double quotes.
					<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span>Lat.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> 27° 0&#39; <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;compass&quot;</span><span class="p">&gt;</span>N.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>, <span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span>long.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> 20° 1&#39; <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;compass eoc&quot;</span><span class="p">&gt;</span>W.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>

<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span>Lat.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> 27° 0’ <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;compass&quot;</span><span class="p">&gt;</span>N.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>, <span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span>long.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> 20° 1’ <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;compass eoc&quot;</span><span class="p">&gt;</span>W.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span>Lat.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> 27° 0′ <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;compass&quot;</span><span class="p">&gt;</span>N.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>, <span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span>long.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> 20° 1′ <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;compass eoc&quot;</span><span class="p">&gt;</span>W.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>Ordinals for Arabic numbers are as follows: st, nd, rd, th.
					<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The 1st, 2d, 3d, 4th.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The 1st, 2nd, 3rd, 4th.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
			</ol>
			<section id="roman-numerals">
				<h3>Roman numerals</h3>
				<ol type="1">
					<li>Roman numerals are not followed by trailing periods, unless for grammatical reasons.</li>
					<li>Roman numerals are set using ASCII, not the Unicode Roman numeral glyphs.</li>
					<li>Roman numerals are not followed by ordinal indicators.
						<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Henry the <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>VIII<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>th had six wives.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Henry the <span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:roman&quot;</span><span class="p">&gt;</span>VIII<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span> had six wives.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
			<section id="fractions">
				<h3>Fractions</h3>
				<ol type="1">
					<li>Fractions are set in their appropriate Unicode glyph, if a glyph available; for example, <code class="utf">½</code>, <code class="utf">¼</code>, <code class="utf">¾</code> and u+00BC–u+00BE and u+2150–u+2189.&lt;/p&gt;
						<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>I need 1/4 cup of sugar.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>I need ¼ cup of sugar.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>If a fraction doesn't have a corresponding Unicode glyph, it is composed using the fraction slash Unicode glyph (<code class="utf">⁄</code> or u+2044) and superscript/subscript Unicode numbers. See <a href="https://en.wikipedia.org/wiki/Unicode_subscripts_and_superscripts">this Wikipedia entry for more details</a>.
						<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Roughly 6/10 of a mile.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Roughly ⁶⁄₁₀ of a mile.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
			<section id="measurements">
				<h3>Measurements</h3>
				<ol type="1">
					<li>Dimension measurements are set using the Unicode multiplication glyph (<code class="utf">×</code> or u+00D7), <em>not</em> the ASCII letter <code class="utf">x</code> or <code class="utf">X</code>.
						<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The board was 4 x 3 x 7 feet.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The board was 4 × 3 × 7 feet.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Feet and inches in shorthand are set using the prime (<code class="utf">′</code> or u+2032) or double prime (<code class="utf">″</code> or u+2033) glyphs (<em>not</em> single or double quotes), with a no-break space (u+00A0) separating consecutive feet and inch measurements.
						<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He was 6&#39;<span class="ws">nbsp</span>1&quot; in height.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>

<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He was 6’<span class="ws">nbsp</span>1” in height.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He was 6′<span class="ws">nbsp</span>1″ in height.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>When forming a compound of a number and unit of measurement in which the measurement is abbreviated, the number and unit of measurement are separated with a no-break space (u+0A00), <em>not</em> a dash.
						<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>A 12-mm pistol.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>A 12<span class="ws">nbsp</span>mm pistol.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
			<section id="math">
				<h3>Math</h3>
				<ol type="1">
					<li>In works that are not math-oriented or that dont't have a significant amount of mathematical equations, equations are set using regular HTML and Unicode.
						<ol type="1">
							<li>Operators and operands in mathematical equations are separated by a space.
								<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>6−2+2=6<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
								<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>6 − 2 + 2 = 6<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
							</li>
							<li>Operators like subtraction (<code class="utf">−</code> or u+2212), multiplication (<code class="utf">×</code> or u+00D7), and equivalence (<code class="utf">≡</code> or u+2261) are set using their corresponding Unicode glyphs, <em>not</em> a hyphen or <code class="utf">x</code>. Almost all mathematical operators have a corresponding special Unicode glyph.
								<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>6 -  2 x 2 == 2<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
								<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>6 − 2 × 2 ≡ 2<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
							</li>
						</ol>
					</li>
					<li>In works that are math-oriented or that have a significant amount of math, <em>all</em> variables, equations, and other mathematical objects are set using MathML.
						<ol type="1">
							<li>When MathML is used in a file, the <code class="html">m</code> namespace is declared at the top of the file and used for all subsequent MathML code, as follows:
								<figure><code class="html full">xmlns:m=&quot;http://www.w3.org/1998/Math/MathML&quot;</code></figure>
								<p>This namespace is declared and used even if there is just a single MathML equation in a file.</p>
								<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">html</span> <span class="na">xmlns</span><span class="o">=</span><span class="s">&quot;http://www.w3.org/1999/xhtml&quot;</span> <span class="na">xmlns:epub</span><span class="o">=</span><span class="s">&quot;http://www.idpf.org/2007/ops&quot;</span> <span class="na">ub:prefix</span><span class="o">=</span><span class="s">&quot;z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: https://standardebooks.org/vocab/1.0&quot;</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">&quot;en-GB&quot;</span><span class="p">&gt;</span>
...
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">math</span> <span class="na">xmlns</span><span class="o">=</span><span class="s">&quot;http://www.w3.org/1998/Math/MathML&quot;</span> <span class="na">alttext</span><span class="o">=</span><span class="s">&quot;x&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">ci</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">ci</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
								<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">html</span> <span class="na">xmlns</span><span class="o">=</span><span class="s">&quot;http://www.w3.org/1999/xhtml&quot;</span> <span class="na">xmlns:epub</span><span class="o">=</span><span class="s">&quot;http://www.idpf.org/2007/ops&quot;</span> <span class="na">xmlns:m</span><span class="o">=</span><span class="s">&quot;http://www.w3.org/1998/Math/MathML&quot;</span> <span class="na">epub:prefix</span><span class="o">=</span><span class="s">&quot;z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: https://standardebooks.org/vocab/1.0&quot;</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">&quot;en-GB&quot;</span><span class="p">&gt;</span>
...
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">&quot;x&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:ci</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:ci</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
							</li>
							<li>When possible, Content MathML is used over Presentational MathML. (This may not always be possible depending on the complexity of the work.)
								<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">&quot;x + 1 = y&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:apply</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:equals</span><span class="p">/&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:apply</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">m:plus</span><span class="p">/&gt;</span>
				<span class="p">&lt;</span><span class="nt">m:ci</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:ci</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">m:cn</span><span class="p">&gt;</span>1<span class="p">&lt;/</span><span class="nt">m:cn</span><span class="p">&gt;</span>
			<span class="p">&lt;/</span><span class="nt">m:apply</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:ci</span><span class="p">&gt;</span>y<span class="p">&lt;/</span><span class="nt">m:ci</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">m:apply</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
							</li>
							<li>Each <code class="html"><span class="p">&lt;</span><span class="nt">m:math</span><span class="p">&gt;</span></code> element has an <code class="html">alttext</code> attribute.
								<ol type="1">
									<li>The <code class="html">alttext</code> attribute describes the contents in the element in plain-text Unicode according to the rules in <a href="https://www.unicode.org/notes/tn28/UTN28-PlainTextMath.pdf">this specification</a>.</li>
									<li>Operators in the <code class="html">alttext</code> attribute are surrounded by a single space.
										<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">&quot;x+1=y&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:apply</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:equals</span><span class="p">/&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:apply</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">m:plus</span><span class="p">/&gt;</span>
				<span class="p">&lt;</span><span class="nt">m:ci</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:ci</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">m:cn</span><span class="p">&gt;</span>1<span class="p">&lt;/</span><span class="nt">m:cn</span><span class="p">&gt;</span>
			<span class="p">&lt;/</span><span class="nt">m:apply</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:ci</span><span class="p">&gt;</span>y<span class="p">&lt;/</span><span class="nt">m:ci</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">m:apply</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
										<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">&quot;x + 1 = y&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:apply</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:equals</span><span class="p">/&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:apply</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">m:plus</span><span class="p">/&gt;</span>
				<span class="p">&lt;</span><span class="nt">m:ci</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:ci</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">m:cn</span><span class="p">&gt;</span>1<span class="p">&lt;/</span><span class="nt">m:cn</span><span class="p">&gt;</span>
			<span class="p">&lt;/</span><span class="nt">m:apply</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:ci</span><span class="p">&gt;</span>y<span class="p">&lt;/</span><span class="nt">m:ci</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">m:apply</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
									</li>
								</ol>
							</li>
							<li>When using Presentational MathML, <code class="html"><span class="p">&lt;</span><span class="nt">m:mrow</span><span class="p">&gt;</span></code> is used to group subexpressions, but only when necessary. Many elements in MathML, like <code class="html"><span class="p">&lt;</span><span class="nt">m:math</span><span class="p">&gt;</span></code> and <code class="html"><span class="p">&lt;</span><span class="nt">m:mtd</span><span class="p">&gt;</span></code>, <em>imply</em> <code class="html"><span class="p">&lt;</span><span class="nt">m:mrow</span><span class="p">&gt;</span></code> and redundant elements are not desirable. See <a href="https://www.w3.org/Math/draft-spec/mathml.html#chapter3_presm.reqarg">this section of the MathML spec</a> for more details.
								<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">&quot;x&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mrow</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">m:mrow</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
								<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">&quot;x&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
							</li>
							<li>If a Presentational MathML expression contains a function, the invisible Unicode function application glyph (u+2061) is used as an operator between the function name and its operand. This element looks exactly like the following, including the comment for readability: <code class="html"><span class="p">&lt;</span><span class="nt">m:mo</span><span class="p">&gt;</span>⁡<span class="c">&lt;!--hidden u+2061 function application--&gt;</span><span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span></code>. (Note that the preceding element contains an <em>invisible</em> Unicode character! It can be revealed with the <code class="bash">se unicode-names</code> tool.)
								<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">&quot;f(x)&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>f<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:row</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mo</span> <span class="na">fence</span><span class="o">=</span><span class="s">&quot;true&quot;</span><span class="p">&gt;</span>(<span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mo</span> <span class="na">fence</span><span class="o">=</span><span class="s">&quot;true&quot;</span><span class="p">&gt;</span>)<span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">m:row</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
								<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">&quot;f(x)&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>f<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mo</span><span class="p">&gt;</span>⁡<span class="utf">u+2061</span><span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span><span class="c">&lt;!--hidden u+2061 function application--&gt;</span><span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:row</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mo</span> <span class="na">fence</span><span class="o">=</span><span class="s">&quot;true&quot;</span><span class="p">&gt;</span>(<span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mo</span> <span class="na">fence</span><span class="o">=</span><span class="s">&quot;true&quot;</span><span class="p">&gt;</span>)<span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">m:row</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
							</li>
							<li>Expressions grouped by parenthesis or brackets are wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">m:row</span><span class="p">&gt;</span></code> element, and fence characters are set using the <code class="html"><span class="p">&lt;</span><span class="nt">m:mo</span> <span class="na">fence</span><span class="o">=</span><span class="s">"true"</span><span class="p">&gt;</span></code> element. Separators are set using the <code class="html"><span class="p">&lt;</span><span class="nt">m:mo</span> <span class="na">separator</span><span class="o">=</span><span class="s">"true"</span><span class="p">&gt;</span></code> element. <code class="html"><span class="p">&lt;</span><span class="nt">m:mfenced</span><span class="p">&gt;</span></code>, which used to imply both fences and separators, is deprecated and thus is not used.
								<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">&quot;f(x,y)&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>f<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mo</span><span class="p">&gt;</span>⁡<span class="utf">u+2061</span><span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span><span class="c">&lt;!--hidden u+2061 function application--&gt;</span><span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:fenced</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>y<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">m:fenced</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
								<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">&quot;f(x,y)&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>f<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mo</span><span class="p">&gt;</span>⁡<span class="utf">u+2061</span><span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span><span class="c">&lt;!--hidden u+2061 function application--&gt;</span><span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:row</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mo</span> <span class="na">fence</span><span class="o">=</span><span class="s">&quot;true&quot;</span><span class="p">&gt;</span>(<span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mo</span> <span class="na">separator</span><span class="o">=</span><span class="s">&quot;true&quot;</span><span class="p">&gt;</span>,<span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mo</span> <span class="na">fence</span><span class="o">=</span><span class="s">&quot;true&quot;</span><span class="p">&gt;</span>)<span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">m:row</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
							</li>
							<li>If a MathML variable includes an overline, it is set by combining the variable's normal Unicode glyph and the Unicode overline glyph (<code class="utf">‾</code> or u+203E) in a <code class="html"><span class="p">&lt;</span><span class="nt">m:mover</span><span class="p">&gt;</span></code> element. However in the <code class="html">alttext</code> attribute, the Unicode overline combining mark (u+0305) is used to represent the overline in Unicode.
								<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">m:math</span> <span class="na">alttext</span><span class="o">=</span><span class="s">&quot;x̅&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">m:mover</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mi</span><span class="p">&gt;</span>x<span class="p">&lt;/</span><span class="nt">m:mi</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">m:mo</span><span class="p">&gt;</span>‾<span class="p">&lt;/</span><span class="nt">m:mo</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">m:mover</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">m:math</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
							</li>
						</ol>
					</li>
				</ol>
			</section>
		</section>
		<section id="latinisms">
			<h2>Latinisms</h2>
			<ul>
				<li><a href="/manual/1.0/typography#8.11">See here for times</a>.</li>
			</ul>
			<ol type="1">
				<li>Latinisms (except sic) that can be found in a modern dictionary are not italicized. Examples include e.g., i.e., ad hoc, viz., ibid., etc.. The exception is sic, which is always italicized.</li>
				<li>Whole passages of Latin language and Latinisms that aren’t found in a modern dictionary are italicized.</li>
				<li>“&amp;c;” is not used, and is replaced with “etc.”</li>
				<li>For “Ibid.,” <a href="/manual/1.0/high-level-structural-patterns#6.9">see Endnotes</a>.</li>
				<li>Latinisms that are abbreviations are set in lowercase with periods between words and no spaces between them, except BC, AD, BCE, and CE, which are set without periods, in small caps, and wrapped with <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"era"</span><span class="p">&gt;</span></code>:
					<figure><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">era</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Julius Caesar was born around 100 <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;era&quot;</span><span class="p">&gt;</span>BC<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
			</ol>
		</section>
		<section id="initials-and-abbreviations">
			<h2>Initials and abbreviations</h2>
			<ul>
				<li><a href="/manual/1.0/typography#8.13">See here for temperatures</a>.</li>
				<li><a href="/manual/1.0/typography#8.11">See here for times</a>.</li>
				<li><a href="/manual/1.0/typography#8.9">See here for Latinisms including BC and AD</a>.</li>
			</ul>
			<ol type="1">
				<li>“OK” is set without periods or spaces. It is not an abbreviation.</li>
				<li>When an abbreviation contains a terminal period, its <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span></code> tag has the additional <code class="html">eoc</code> class (End of Clause) if the terminal period is also the last period in clause. Such sentences do not have two consecutive periods.
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>She loved Italian food like pizza, pasta, <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;eoc&quot;</span><span class="p">&gt;</span>etc.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He lists his name alphabetically as Johnson, <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;name eoc&quot;</span><span class="p">&gt;</span>R. A.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>His favorite hobby was <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;acronym&quot;</span><span class="p">&gt;</span>SCUBA<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>Initialisms, postal codes, and abbreviated US states are set in all caps, without periods or spaces.</li>
				<li>Acronyms (terms made up of initials and pronounced as one word, like NASA, SCUBA, or NATO) are set in small caps, without periods, and are wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"acronym"</span><span class="p">&gt;</span></code> element.
					<figure><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">acronym</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He was hired by <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;acronym&quot;</span><span class="p">&gt;</span>NASA<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> last week.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>Initialisms (terms made up of initials in which each initial is pronounced separately, like ABC, HTML, or CSS) are set with without periods and are wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"initialism"</span><span class="p">&gt;</span></code> element.
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He was hired by the <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;initialism&quot;</span><span class="p">&gt;</span>FBI<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> last week.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>Initials of people’s names are each separated by periods and spaces. The group of initials is wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"name"</span><span class="p">&gt;</span></code> element.
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;name&quot;</span><span class="p">&gt;</span>H. P.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> Lovecraft described himself as an aged antiquarian.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>Academic degrees, except ones that include a lowercase letter (like PhD) are wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"degree"</span><span class="p">&gt;</span></code> element. For example: BA, BD, BFA, BM, BS, DB, DD, DDS, DO, DVM, JD, LHD, LLB, LLD, LLM, MA, MBA, MD, MFA, MS, MSN.
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Judith Douglas, <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;degree&quot;</span><span class="p">&gt;</span>DDS<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>State names and postal codes are wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"postal"</span><span class="p">&gt;</span></code> element.
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Washington <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;postal&quot;</span><span class="p">&gt;</span>DC<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>Abbreviations that are abbreviations of a single word, and that are not acronyms or initialisms (like Mr., Mrs., or lbs.) are set with <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span></code>.
					<ol type="1">
						<li>Abbreviations ending in a lowercase letter are set without spaces and followed by a period.</li>
						<li>Abbreviations without lowercase letters are set without spaces and without a trailing period.</li>
						<li>Abbreviations that describes the next word, like Mr., Mrs., Mt., and St., are set with a no-break space between the abbreviation and its target.
							<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He called on <span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span>Mrs.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span><span class="ws">nbsp</span>Jones yesterday.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						</li>
					</ol>
				</li>
				<li>Compass points are separated by periods and spaces. The group of points are wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"compass"</span><span class="p">&gt;</span></code> element.
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He traveled <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;compass&quot;</span><span class="p">&gt;</span>S.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>, <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;compass&quot;</span><span class="p">&gt;</span>N. W.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>, then <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;compass eoc&quot;</span><span class="p">&gt;</span>E. S. E.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
			</ol>
		</section>
		<section id="times">
			<h2>Times</h2>
			<ol type="1">
				<li>Times in a.m. and p.m. format are set in lowercase, with periods, and without spaces.</li>
				<li>“a.m.” and “p.m.” are wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"time"</span><span class="p">&gt;</span></code> element.</li>
			</ol>
			<section id="times-as-digits">
				<h3>Times as digits</h3>
				<ol type="1">
					<li>Digits in times are separated by a colon, not a period or comma.</li>
					<li>Times written in digits followed by “a.m.” or “p.m.” are set with a no-break space between the digit and “a.m.” or “p.m.”.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He called at 6:40<span class="ws">nbsp</span><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;time eoc&quot;</span><span class="p">&gt;</span>a.m.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
			<section id="times-as-words">
				<h3>Times as words</h3>
				<ol type="1">
					<li>Words in a spelled-out time are separated by spaces, unless they appear before a noun, where they are separated by a hyphen.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He arrived at five thirty.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>They took the twelve-thirty train.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Times written in words followed by “a.m.” or “p.m.” are set with a regular space between the time and “a.m.” or “p.m.”.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>She wasn’t up till seven <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;time eoc&quot;</span><span class="p">&gt;</span>a.m.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>Military times that are spelled out (for example, in dialog) are set with dashes. Leading zeros are spelled out as “oh”.
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He arrived at oh-nine-hundred.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
		</section>
		<section id="chemicals-and-compounds">
			<h2>Chemicals and compounds</h2>
			<ol type="1">
				<li>Molecular compounds are set in Roman, without spaces, and wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"compound"</span><span class="p">&gt;</span></code> element.
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He put extra <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;compound&quot;</span><span class="p">&gt;</span>NaCl<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> on his dinner.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>Elements in a molecular compound are capitalized according to their listing in the periodic table.</li>
				<li>Amounts of an element in a molecular compound are set in subscript with a <code class="html"><span class="p">&lt;</span><span class="nt">sub</span><span class="p">&gt;</span></code> element.
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>She drank eight glasses of <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;compound&quot;</span><span class="p">&gt;</span>H<span class="p">&lt;</span><span class="nt">sub</span><span class="p">&gt;</span>2<span class="p">&lt;/</span><span class="nt">sub</span><span class="p">&gt;</span>O<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> a day.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
			</ol>
		</section>
		<section id="temperatures">
			<h2>Temperatures</h2>
			<ol type="1">
				<li>The minus sign glyph (<code class="utf">−</code> or u+2212), not the hyphen glyph, is used to indicate negative numbers.</li>
				<li>Either the degree glyph (<code class="utf">°</code> or u+00B0) or the word “degrees” is acceptable. Works that use both are normalize to use the dominant method.</li>
			</ol>
			<section id="abbreviated-units-of-temperature">
				<h3>Abbreviated units of temperature</h3>
				<p>Units of temperature measurement, like Farenheit or Celcius, may be abbreviated to “F” or “C”.</p>
				<ol type="1">
					<li>Units of temperature measurement do not have trailing periods.</li>
					<li>If an <em>abbreviate</em> unit of temperature measurement is preceded by a number, the unit of measurement is first preceded by a hair space (u+200A).</li>
					<li>Abbreviated units of measurement are set in small caps.</li>
					<li>Abbreviated units of measurement are wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"temperature"</span><span class="p">&gt;</span></code> element.
						<figure><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">temperature</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
						<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>It was −23.33° Celsius (or −10°<span class="ws">hairsp</span><span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;temperature&quot;</span><span class="p">&gt;</span>F<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span>) last night.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
		</section>
		<section id="scansion">
			<h2>Scansion</h2>
			<p>Scansion is the representation of the metrical stresses in lines of verse.</p>
			<ol type="1">
				<li><code class="utf">×</code> (u+00d7) indicates an unstressed sylllable and <code class="utf">/</code> (u+002f) indicates a stressed syllable. They are separated from each other with no-break spaces.</li>
			</ol>
		</section>
		<section id="legal-cases-and-terms">
			<h2>Legal cases and terms</h2>
			<ol type="1">
				<li>Legal cases are set in italics.</li>
				<li>Either “versus” or “v.” are acceptable in the name of a legal case; if using “v.”, a period follows the “v.”, and it is wrapped in an <code class="html"><span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span></code> element.
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>He prosecuted <span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;se:name.legal-case&quot;</span><span class="p">&gt;</span>Johnson <span class="p">&lt;</span><span class="nt">abbr</span><span class="p">&gt;</span>v.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> Smith<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
			</ol>
		</section>
		<section id="morse-code">
			<h2>Morse Code</h2>
			<p>Any Morse code that appears in a book will have to be changed to fit SE's format.</p>
			<section id="american-morse-code">
				<h3>American Morse Code</h3>
				<ol type="1">
					<li>The middle dot glyph (U+00B7) is used for the short mark or dot.</li>
					<li>The en dash (U+2013) is used for the longer mark or short dash.</li>
					<li>The em dash (U+2014) is used for the long dash (the letter L).</li>
					<li>If two en dashes are placed next to each other, a hair space (U+200A) must be placed in between them to keep the glyphs from merging into a longer dash.</li>
					<li>Only in American Morse Code, there are internal gaps used between glyphs in the letters C,O,R, or Z. Use a no-break space (U+00A0) for this gap.</li>
					<li>Use en spaces (U+2002) between letters.</li>
					<li>Use em spaces (U+2003) between words.
						<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>--  .. ..   __  ..  - -  __  .   . ..  __  -..   .. .  .- -<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>My little old cat.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>– – ·· ·· — ·· – – — · · · — –·· ·· · ·– –<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>My little old cat.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
		</section>
	</section>
		</article>
	</main>
<?= Template::Footer() ?>
