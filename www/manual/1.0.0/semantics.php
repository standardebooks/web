<?
require_once('Core.php');
?><?= Template::Header(['title' => '4. Semantics - The Standard Ebooks Manual', 'highlight' => 'contribute', 'manual' => true]) ?>
	<main>
		<article class="manual">

	<section data-start-at="4" id="semantics">
		<h1>Semantics</h1>
		<p>Semantics convey what an element or section <em>mean</em> or <em>are</em>, instead of merely conveying <em>how they are visually presented</em>.</p>
		<p>For example, the following snippet visually presents a paragraph, followed by a quotation from a poem:</p>
		<figure><code class="html full"><span class="p">&lt;</span><span class="nt">div</span><span class="p">&gt;</span>“All done in the tying of a cravat,” Sir Percy had declared to his clique of admirers.<span class="p">&lt;/</span><span class="nt">div</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">div</span> <span class="na">style</span><span class="o">=</span><span class="s">&quot;margin: 1em;&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">div</span><span class="p">&gt;</span>“We seek him here, we seek him there,<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	Those Frenchies seek him everywhere.<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	Is he in heaven?⁠—Is he in hell,<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	That demmed, elusive Pimpernel?”<span class="p">&lt;/</span><span class="nt">div</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">div</span><span class="p">&gt;</span></code></figure>
		<p>While that snipped might <em>visually</em> present the text as a paragraph followed by a quotation of verse, the actual HTML tells us nothing about <em>what these lines of text actually are</em>.</p>
		<p>Compare the above snippet to this next snippet, which renders almost identically but uses semantically-correct tags and epub’s semantic inflection to tell us <em>what the text is</em>:</p>
		<figure><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“All done in the tying of a cravat,” Sir Percy had declared to his clique of admirers.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:poem&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>“We seek him here, we seek him there,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Those Frenchies seek him everywhere.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Is he in heaven?⁠—Is he in hell,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
		<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>That demmed, elusive Pimpernel?”<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
		<p>By inspecting the tags above, we can see that the first line is a semantic paragraph (<code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code> stands for <strong>p</strong>aragraph, of course); the paragraph is followed by a semantic block quotation, which browsers automatically render with a margin; the quotation is a poem; the poem has one stanza; and there are four lines in the poem. (By SE convention, <code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code> elements in verse are stanzas and <code class="html"><span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span></code> elements are lines.)</p>
		<section id="semantic-tags">
			<h2>Semantic Tags</h2>
			<p>Epub allows for the use of the full range of tags in the HTML5 spec. Each tag has a semantic meaning, and each tag in an SE ebook is carefully considered before use.</p>
			<p>Below is an incomplete list of HTML5 elements and their semantic meanings. These are some of the most common elements encountered in an ebook.</p>
			<section id="block-level-tags">
				<h3>Block-level tags</h3>
				<p>Block-level tags are by default rendered with <code class="css"><span class="p">{</span> <span class="k">display</span><span class="p">:</span> <span class="kc">block</span><span class="p">;</span> <span class="p">}</span></code>. See the <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Block-level_elements">complete list of block-level tags</a>.</p>
				<ol type="1">
					<li>Sectioning block-level tags denote major structural divisions in a work.
						<ol type="1">
							<li><code class="html"><span class="p">&lt;</span><span class="nt">body</span><span class="p">&gt;</span></code>: The top-level tag in any XHTML file. Must contain a direct child that is either a <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> or <code class="html"><span class="p">&lt;</span><span class="nt">article</span><span class="p">&gt;</span></code>.</li>
							<li><code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code>: A major structural division in a work. Typically a part, volume, chapter, or subchapter. Semantically a <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> cannot stand alone, but is part of a larger work.</li>
							<li><code class="html"><span class="p">&lt;</span><span class="nt">article</span><span class="p">&gt;</span></code>: An item in a larger work that could be pulled out of the work and serialized or syndicated separately. For example, a single poem in a poetry collection, or a single short story in a short story collection; but <em>not</em> a single poem in a larger novel.</li>
						</ol>
					</li>
					<li>Other block-level tags have well-defined semantic meanings.
						<ol type="1">
							<li><code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code>: A paragraph of text.</li>
							<li><code class="html"><span class="p">&lt;</span><span class="nt">blockquote</span><span class="p">&gt;</span></code>: A quotation displayed on the block level. This may include non-speech “quotations” like business cards, headstones, telegrams, letters, and so on.</li>
							<li><code class="html"><span class="p">&lt;</span><span class="nt">figure</span><span class="p">&gt;</span></code>: Encloses a photograph, chart, or illustration, represented with an <cite>&lt;img&gt;</cite> element. Optionally includes a <code class="html"><span class="p">&lt;</span><span class="nt">figcaption</span><span class="p">&gt;</span></code> element for a context-appropriate caption.</li>
							<li><code class="html"><span class="p">&lt;</span><span class="nt">figcaption</span><span class="p">&gt;</span></code>: Only appears as a child of <code class="html"><span class="p">&lt;</span><span class="nt">figure</span><span class="p">&gt;</span></code>. Represents a context-appropriate caption for the sibling <code class="html"><span class="p">&lt;</span><span class="nt">img</span><span class="p">&gt;</span></code>. A caption <em>is not the same</em> as an <code class="html"><span class="p">&lt;</span><span class="nt">img</span><span class="p">&gt;</span></code> element’s <code class="html">alt</code> text. <code class="html">alt</code> text is strictly a textual description of the image used for screen readers, whereas <code class="html"><span class="p">&lt;</span><span class="nt">figcaption</span><span class="p">&gt;</span></code> has more freedom in its contents, depending on its context.</li>
							<li><code class="html"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span></code>: Denotes a header section applying to its direct parent. <code class="html"><span class="p">&lt;</span><span class="nt">header</span><span class="p">&gt;</span></code> is typically used for chapter headers, but can also be used in <code class="html"><span class="p">&lt;</span><span class="nt">blockquote</span><span class="p">&gt;</span></code>s or other block-level tags that require header styling.</li>
							<li><code class="html"><span class="p">&lt;</span><span class="nt">footer</span><span class="p">&gt;</span></code>: Denotes a footer section applying to its direct parent. Typically used to denote signatures in sections like prefaces, forewords, letters, telegrams, and so on.</li>
							<li><code class="html"><span class="p">&lt;</span><span class="nt">hr</span><span class="p">&gt;</span></code>: Denotes a thematic break. <code class="html"><span class="p">&lt;</span><span class="nt">hr</span><span class="p">&gt;</span></code> <em>is not used</em> any place a black border is desired; it <em>strictly denotes</em> a thematic break.</li>
							<li><code class="html"><span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span></code>: Denotes an ordered list. Ordered lists are automatically numbered by the renderer.</li>
							<li><code class="html"><span class="p">&lt;</span><span class="nt">ul</span><span class="p">&gt;</span></code>: Denotes an unordered list. Ordered lists are bulleted by the renderer.</li>
							<li><code class="html"><span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span></code>: Denotes an list item in a parent <code class="html"><span class="p">&lt;</span><span class="nt">ol</span><span class="p">&gt;</span></code> or <code class="html"><span class="p">&lt;</span><span class="nt">ul</span><span class="p">&gt;</span></code>.</li>
							<li><code class="html"><span class="p">&lt;</span><span class="nt">table</span><span class="p">&gt;</span></code>: Denotes a tabular section, for example when displaying tabular data, or reports or charts where a tabular appearance is desired.</li>
						</ol>
					</li>
					<li><code class="html"><span class="p">&lt;</span><span class="nt">div</span><span class="p">&gt;</span></code> elements are almost never appropriate, as they have no semantic meaning. However, they may in rare occasions be used to group related elements in a situation where no other semantic tag is appropriate.</li>
				</ol>
			</section>
			<section id="inline-tags">
				<h3>Inline tags</h3>
				<p>Inline tags are by default rendered with <code class="css"><span class="p">{</span> <span class="k">display</span><span class="p">:</span> <span class="kc">inline</span><span class="p">;</span> <span class="p">}</span></code>. See the <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Inline_elements">complete list of inline tags</a>.</p>
				<ol type="1">
					<li><code class="html"><span class="p">&lt;</span><span class="nt">em</span><span class="p">&gt;</span></code>: Text rendered in italics, with the semantic meaning of emphasize speech, or speech spoken in a different tone of voice; for example, a person shouting, or putting stress on a particular word.</li>
					<li><code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code>: Text rendered in italics, without any explicit semantic meaning. Because <code class="html"><span class="p">&lt;</span><span class="nt">i</span><span class="p">&gt;</span></code> lacks semantic meaning, the <code class="html">epub:type</code> attribute is added with appropriate semantic inflection to describe the contents of the tag.
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>The <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;initialism&quot;</span><span class="p">&gt;</span>HMS<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> <span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;se:name.vessel.ship&quot;</span><span class="p">&gt;</span>Bounty<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li><code class="html"><span class="p">&lt;</span><span class="nt">b</span><span class="p">&gt;</span></code>: Text rendered in small caps, without any explicit semantic meaning. Because <code class="html"><span class="p">&lt;</span><span class="nt">b</span><span class="p">&gt;</span></code> lacks semantic meaning, the <code class="html">epub:type</code> attribute is added with appropriate semantic inflection to describe the contents of the tag.</li>
					<li><code class="html"><span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span></code>: Plain inline text that requires specific styling or semantic meaning that cannot be achieved with any other semantically meaningful inline tag. Typically used in conjunction with a <code class="html">class</code> or <code class="html">epub:type</code> attribute.</li>
				</ol>
			</section>
		</section>
		<section id="semantic-inflection">
			<h2>Semantic Inflection</h2>
			<p>The epub spec allows for <a href="https://idpf.github.io/epub-vocabs/structure/">semantic inflection</a>, which is a way of adding semantic metadata to elements in the ebook document.</p>
			<p>For example, an ebook producer may want to convey that the contents of a certain <code class="html"><span class="p">&lt;</span><span class="nt">section</span><span class="p">&gt;</span></code> are part of a chapter. They would do that by using the <code class="html">epub:type</code> attribute:</p>
			<figure><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;chapter&quot;</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code></figure>
			<ol type="1">
				<li>The epub spec includes a <a href="https://idpf.github.io/epub-vocabs/structure/">vocabulary</a> that can be used in the <code class="html">epub:type</code> attribute. This vocabulary has priority when selecting a semantic keyword, even if other vocabularies contain the same one.</li>
				<li>The epub spec might not contain a keyword necessary to describe the semantics of a particular element. In that case, the <a href="http://www.daisy.org/z3998/2012/vocab/structure/">z3998 vocabulary</a> is consulted next.
					<p>Keywords using this vocabulary are preceded by the <code class="html">z3998</code> namespace.</p>
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:letter&quot;</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>If the z3998 vocabulary doesn’t have an appropriate keyword, the <a href="/vocab/1.0">Standard Ebooks vocabulary</a> is consulted next.
					<p>Keywords using this vocabulary are preceded by the <code class="html">se</code> namespace.</p>
					<p>Unlike other vocabularies, the Standard Ebooks vocabulary is organized hierarchically. A complete vocabulary entry begins with the root vocabulary entry, with subsequent children separated by <code class="html">.</code>.</p>
					<figure><code class="html full">The <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;initialism&quot;</span><span class="p">&gt;</span>HMS<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;</span> <span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;se:name.vessel.ship&quot;</span><span class="p">&gt;</span>Bounty<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>.</code></figure>
				</li>
				<li>The <code class="html">epub:type</code> attribute can have multiple keywords separated by spaces, even if the vocabularies are different.
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;chapter z3998:letter&quot;</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>Child elements inherit the semantics of their parent element.
					<p>In this example, both chapters are considered to be “non-fiction,” because they inherit it from the <code class="html"><span class="p">&lt;</span><span class="nt">body</span><span class="p">&gt;</span></code> element:</p>
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">body</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;z3998:non-fiction&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;chapter-1&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;chapter&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title z3998:roman&quot;</span><span class="p">&gt;</span>I<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
		...
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">&quot;chapter-2&quot;</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;chapter&quot;</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;title z3998:roman&quot;</span><span class="p">&gt;</span>II<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
		...
	<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">body</span><span class="p">&gt;</span></code></figure>
				</li>
			</ol>
		</section>
	</section>
		</article>
	</main>
<?= Template::Footer() ?>