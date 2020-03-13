<?
require_once('Core.php');
?><?= Template::Header(['title' => '1. XHTML, CSS, and SVG Code Style - The Standard Ebooks Manual', 'highlight' => 'contribute', 'manual' => true]) ?>
	<main>
		<article class="manual">
	<section data-start-at="1" id="xhtml-css-and-svg-code-style">
		<h1>XHTML, CSS, and SVG Code Style</h1>
		<p>The <code class="bash">se clean</code> tool in the <a href="https://github.com/standardebooks/tools">Standard Ebooks toolset</a> formats XHTML code according to our style guidelines. The vast majority of the time its output is correct and no further modifications to code style are necessary.</p>
		<section id="xhtml-formatting">
			<h2>XHTML formatting</h2>
			<ol type="1">
				<li>The first line of all XHTML files is:
					<figure><code class="html full"><span class="cp">&lt;?xml version=&quot;1.0&quot; encoding=&quot;utf-8&quot;?&gt;</span></code></figure>
				</li>
				<li>The second line of all XHTML files is (replace <code class="html">xml:lang="en-US"</code> with the <a href="https://en.wikipedia.org/wiki/IETF_language_tag">appropriate language tag</a> for the file):
					<figure><code class="html full"><span class="p">&lt;</span><span class="nt">html</span> <span class="na">xmlns</span><span class="o">=</span><span class="s">&quot;http://www.w3.org/1999/xhtml&quot;</span> <span class="na">xmlns:epub</span><span class="o">=</span><span class="s">&quot;http://www.idpf.org/2007/ops&quot;</span> <span class="na">epub:prefix</span><span class="o">=</span><span class="s">&quot;z3998: http://www.daisy.org/z3998/2012/vocab/structure/, se: https://standardebooks.org/vocab/1.0&quot;</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">&quot;en-US&quot;</span><span class="p">&gt;</span></code></figure>
				</li>
				<li>Tabs are used for indentation.</li>
				<li>Tag names are lowercase.</li>
				<li>Tags whose content is <a href="https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/Content_categories#Phrasing_content">phrasing content</a> are on a single line, with no whitespace between the opening and closing tags and the content.
					<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
	It was a dark and stormy night...
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>It was a dark and stormy night...<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
				</li>
			</ol>
			<section id="br-elements">
				<h3><code class="html"><span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span></code> elements</h3>
				<ol type="1">
					<li><code class="html"><span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span></code> elements within <a href="https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/Content_categories#Phrasing_content">phrasing content</a> are on the same line as the precediong phrasing content, and are followed by a newline.
						<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Pray for the soul of the
<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
Demoiselle Jeanne D’Ys.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Pray for the soul of the
<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>Demoiselle Jeanne D’Ys.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Pray for the soul of the<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
Demoiselle Jeanne D’Ys.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>The next indentation level after a <code class="html"><span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span></code> element is the same as the previous indentation level.
						<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Pray for the soul of the<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	Demoiselle Jeanne D’Ys,<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	who died<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	in her youth for love of<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	Philip, a Stranger.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Pray for the soul of the<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
Demoiselle Jeanne D’Ys,<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
who died<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
in her youth for love of<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
Philip, a Stranger.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>The closing tag of the phrasing content broken by a <code class="html"><span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span></code> element is on the same line as the last line of the phrasing content.
						<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Pray for the soul of the<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
Demoiselle Jeanne D’Ys.
<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Pray for the soul of the<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
Demoiselle Jeanne D’Ys.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
					<li><code class="html"><span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span></code> elements have phrasing content both before and after; they don’t appear with phrasing content only before, or only after.
						<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Pray for the soul of the<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
Demoiselle Jeanne D’Ys<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“Pray for the soul of the<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
Demoiselle Jeanne D’Ys<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code></figure>
					</li>
				</ol>
			</section>
			<section id="attributes">
				<h3>Attributes</h3>
				<ol type="1">
					<li>Attributes are in alphabetical order.</li>
					<li>Attributes, their namespaces, and their values are lowercase, except for values which are IETF language tags. In IETF language tags, the language subtag is capitalized.
						<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">EPUB:TYPE</span><span class="o">=</span><span class="s">&quot;CHAPTER&quot;</span> <span class="na">XML:LANG</span><span class="o">=</span><span class="s">&quot;EN-US&quot;</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code></figure>
						<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">&quot;chapter&quot;</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">&quot;en-US&quot;</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code></figure>
					</li>
					<li>The string <code class="html">utf-8</code> is lowercase.</li>
				</ol>
				<section id="classes">
					<h4>Classes</h4>
					<ol type="1">
						<li>Classes are not used as one-time style hooks. There is almost always a clever selector that can be constructed instead of taking the shortcut of adding a class to an element.</li>
						<li>Classes are named semantically, describing <em>what they are styling</em> instead of the <em>resulting visual style</em>.
							<figure class="wrong"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>There was one great tomb more lordly than all the rest; huge it was, and nobly proportioned. On it was but one word<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;small-caps&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Dracula.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
							<figure class="corrected"><code class="html full"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>There was one great tomb more lordly than all the rest; huge it was, and nobly proportioned. On it was but one word<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">class</span><span class="o">=</span><span class="s">&quot;tomb&quot;</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Dracula.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span></code></figure>
						</li>
					</ol>
				</section>
			</section>
		</section>
		<section id="css-formatting">
			<h2>CSS formatting</h2>
			<ol type="1">
				<li>The first two lines of all CSS files are:
					<figure><code class="css full"><span class="p">@</span><span class="k">charset</span> <span class="s2">&quot;utf-8&quot;</span><span class="p">;</span>
<span class="p">@</span><span class="k">namespace</span> <span class="nt">epub</span> <span class="s2">&quot;http://www.idpf.org/2007/ops&quot;</span><span class="p">;</span></code></figure>
				</li>
				<li>Tabs are used for indentation.</li>
				<li>Selectors, properties, and values are lowercase.</li>
			</ol>
			<section id="selectors">
				<h3>Selectors</h3>
				<ol type="1">
					<li>Selectors are each on their own line, directly followed by a comma or a brace with no whitespace inbetween.
						<figure class="wrong"><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">era</span><span class="o">,</span> <span class="p">.</span><span class="nc">signature</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
						<figure class="corrected"><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">era</span><span class="o">,</span>
<span class="p">.</span><span class="nc">signature</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
					</li>
					<li>Complete selectors are separated by exactly one blank line.
						<figure class="wrong"><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">era</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span>


<span class="nt">strong</span><span class="p">{</span>
	<span class="k">font-weight</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
						<figure class="corrected"><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">era</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span>

<span class="nt">strong</span><span class="p">{</span>
	<span class="k">font-weight</span><span class="p">:</span> <span class="kc">normal</span><span class="p">;</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
					</li>
					<li>Closing braces are on their own line.</li>
				</ol>
			</section>
			<section id="properties">
				<h3>Properties</h3>
				<ol type="1">
					<li>Properties are each on their own line (even if the selector only has one property) and indented with a single tab.
						<figure class="wrong"><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">era</span><span class="p">{</span> <span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span> <span class="p">}</span></code></figure>
						<figure class="corrected"><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">era</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
					</li>
					<li><em>Where possible</em>, properties are in alphabetical order.
						<p>This isn’t always possible if a property is attempting to override a previous property in the same selector, and in some other cases.</p>
					</li>
					<li>Properties are directly followed by a colon, then a single space, then the property value.
						<figure class="wrong"><code class="css full"><span class="nt">blockquote</span><span class="p">{</span>
	<span class="k">margin-left</span><span class="p">:</span>	<span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">margin-right</span><span class="p">:</span>   <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">border</span><span class="p">:</span><span class="kc">none</span><span class="p">;</span>
<span class="p">}</span></code></figure>
						<figure class="corrected"><code class="css full"><span class="nt">blockquote</span><span class="p">{</span>
	<span class="k">margin-left</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">margin-right</span><span class="p">:</span> <span class="mi">1</span><span class="kt">em</span><span class="p">;</span>
	<span class="k">border</span><span class="p">:</span> <span class="kc">none</span><span class="p">;</span>
<span class="p">}</span></code></figure>
					</li>
					<li>Property values are directly followed by a semicolon, even if it’s the last value in a selector.
						<figure class="wrong"><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">era</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span>
<span class="p">}</span></code></figure>
						<figure class="corrected"><code class="css full"><span class="nt">abbr</span><span class="p">.</span><span class="nc">era</span><span class="p">{</span>
	<span class="k">font-variant</span><span class="p">:</span> <span class="kc">all-small-caps</span><span class="p">;</span>
<span class="p">}</span></code></figure>
					</li>
				</ol>
			</section>
		</section>
		<section id="svg-formatting">
			<h2>SVG Formatting</h2>
			<ol type="1">
				<li>SVG formatting follows the same directives as <a href="/manual/1.0/code-style#1.1">XHTML formatting</a>.</li>
			</ol>
		</section>
	</section>
		</article>
	</main>
<?= Template::Footer() ?>
