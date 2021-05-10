<?
require_once('Core.php');
?><?= Template::Header(['title' => 'How to Produce a Shakespeare Play', 'manual' => true, 'highlight' => 'contribute', 'description' => 'A guide to producing any play by William Shakespeare.']) ?>
<main class="manual">
	<article class="step-by-step-guide">
		<h1>How to create figures for music scores</h1>
		<p>Standard Ebooks handles instances of music notation in books by recreating them in a modern score editor and embedding them as SVG files much as any other illustration. To explain the process, consider the example of <a href="https://standardebooks.org/ebooks/george-bernard-shaw/man-and-superman">Man and Superman</a>, which has three instances of music notation.</p>
		<ol>
			<li>
				<h2>Finding sources</h2>
				<p>In <a href="https://www.gutenberg.org/files/3328/3328-h/3328-h.htm">the original Project Gutenberg transcription</a>, the music is simply referenced as (a staff of music is supplied here). You therefore have to go find the relevant music in the scans of <a href="https://archive.org/details/manandsupermana06shawgoog/page/n132/mode/1up">the source</a>.</p>
				<img src="images/music-notation-1-scan.png" alt="The music notation as it appears in page scans">
				<p>This image is not clear enough that it is possible to simply follow the illustration tracing procedure, and even if it were could, the goal is not to duplicate the source as exactly as possible but instead to produce a clean, modern, legible version of it.</p>
			</li>
			<li>
				<h2>Recreating with a scorewriter</h2>
				<p>A number of modern scorewriters exist, including <a href="https://en.wikipedia.org/wiki/Comparison_of_scorewriters">several free options</a>. While any of these that can output MusicXML and SVG files can be made to work, <a href="https://musescore.org/">MuseScore</a>, a powerful, open source option, is the SE default.</p>
				<p>The score should be recreated as closely as possible while still being readable, but at this stage it does not need to be perfect as it can be further edited by an SVG editor. At this point, the priority is for it to be human-readable, not for the scorewriter to produce the best possible playback.</p>
				<img src="images/music-notation-2-MuseScore.png" alt="The music notatation after initial recreation with MuseScore.">
				<p>MuseScore cannot fully replicate the original, so final changes will be made in a vector editing program. Once you are ready to progress to the next step, export the music into two formats: MusicXML and SVG.</p>
					<div class="tip">
					<div class="title">
					<p>Tip</p>
					</div>
					<p>If you are not using MuseScore, you may need to <a href="https://www.musicxml.com/">install a plugin</a> to be able to export as MusicXML.</p>
					</div>
					<p>MusicXML files should be named <code class="interpreted-text" role="string">illustration-n.xml</code> and saved in <code class="interpreted-text" role="string">./images/</code>. Ensure that they have a .xml filename. SVG files should be saved as <code class="interpreted-text" role="string">illustration-n.svg</code> in <code class="interpreted-text" role="string">./src/epub/images/</code>.</p>
					<div class="tip">
					<div class="title">
					<p>Tip</p>
					</div>
					<p>While you may need to have extra bars at the end of a phrase to decrease the layout stretch and compress the notation visually for SVG export, you should remove these extra bars, as well as any unused titles or other elements before saving as MusicXML.</p>
					</div>
			</li>
			<li>
				<h2>Edit in a vector graphics editor</h2>
				<p>There are <a href="https://en.wikipedia.org/wiki/Comparison_of_vector_graphics_editors">a number of vector graphics editors</a> available and you are free to use whichever you are most familiar with to make visual changes to the score. Regardless of what you use to make visual edits, <a href="https://inkscape.org/">Inkscape</a> and specific plugins available for it are are recommended to clean up the SVG code.</p>
				<img src="images/music-notation-3-SVG.png" alt="The music notation after it has been modified with a vector graphics editor.">
				<p>At this stage, final visual changes are made. The final rests and bars at the end are cropped out, clef lines behind the instrument text have been removed, and elements have been slightly rearranged to reduce the amount of space the key and time signatures take up. The SVG is made black against a transparent background, and cropped so there is no blank space around the edges of the illustration.</p>
			</li>
			<li>
				<h2>Prepare the SVG file</h2>
				<p>As initially prepared by Inkscape or your vector graphics editor of choice, the SVG is unlikely to be in good shape. Any transformations need to be applied, all colour needs to be removed to allow for readers with an inverted color scheme, and the header needs to be corrected. For the complete SE guidance on SVG files, see <a href="https://standardebooks.org/manual/1.5.0/10-art-and-images#10.2">section 10.2 of the manual</a>.</p>
				<h3>Applying transformations</h3>
				<p>As generated by MuseScore, SVG files are likely to include transform elements throughout, which need to be removed. There are several ways to do this, but the the following two are recommended.</p>
				<ul>
					<li>Install the extension <a href="https://github.com/Klowner/inkscape-applytransforms">Inkscape Apply Transforms</a> and use this.</li>
					<li>Use the command line tool <a href="https://github.com/svg/svgo">SVGO (SVG Optimizer)</a>. An <a href="https://jakearchibald.github.io/svgomg/">online version of the tool</a> also exists, and can be a good starting point. The default settings are generally good except for the following: markup should be prettified, XML instructions should not be removed, viewBox should be preferred to width and height and not removed, paths should not be rounded or rewritten, and "title" and "description" should not be removed.</li>
				</ul>
					<div class="wrong">
					<div class="sourceCode" id="cb1"><pre class="sourceCode html"><code class="sourceCode html"><span id="cb1-1"><a href="#cb1-1" aria-hidden="true" tabindex="-1"></a><span class="kw">&lt;g</span> <span class="er">transform</span><span class="ot">=</span><span class="st">&quot;matrix(0.860979,0,0,1,-289.804,-511.548)&quot;</span><span class="kw">&gt;</span></span>
					<span id="cb1-2"><a href="#cb1-2" aria-hidden="true" tabindex="-1"></a>  <span class="kw">&lt;path</span> <span class="er">d</span><span class="ot">=</span><span class="st">&quot;M336.598,634.198L2775.68,634.198&quot;</span> <span class="er">style</span><span class="ot">=</span><span class="st">&quot;fill:none;fill-rule:nonzero;stroke:black;stroke-width:2.73px;&quot;</span><span class="kw">/&gt;</span></span>
					<span id="cb1-3"><a href="#cb1-3" aria-hidden="true" tabindex="-1"></a><span class="kw">&lt;/g&gt;</span></span>
					<span id="cb1-4"><a href="#cb1-4" aria-hidden="true" tabindex="-1"></a><span class="kw">&lt;g</span> <span class="er">transform</span><span class="ot">=</span><span class="st">&quot;matrix(1,0,0,1,-415.857,-511.548)&quot;</span><span class="kw">&gt;</span></span>
					<span id="cb1-5"><a href="#cb1-5" aria-hidden="true" tabindex="-1"></a>  <span class="kw">&lt;path</span> <span class="er">d</span><span class="ot">=</span><span class="st">&quot;M1671.05,698.502C1700.14,732.525 1765.87,740.853 1802.53,715.157C1765,747.742 1699.26,739.414 1671.05,698.502&quot;</span> <span class="er">style</span><span class="ot">=</span><span class="st">&quot;stroke:black;stroke-width:1.74px;stroke-linecap:round;stroke-linejoin:round;&quot;</span><span class="kw">/&gt;</span></span>
					<span id="cb1-6"><a href="#cb1-6" aria-hidden="true" tabindex="-1"></a><span class="kw">&lt;/g&gt;</span></span></code></pre></div>
					</div>
					<div class="corrected">
					<div class="sourceCode" id="cb2"><pre class="sourceCode html"><code class="sourceCode html"><span id="cb2-1"><a href="#cb2-1" aria-hidden="true" tabindex="-1"></a><span class="kw">&lt;path</span> <span class="er">d</span><span class="ot">=</span><span class="st">&quot;M 0.005868 122.65 L 2100.02 122.65&quot;</span> <span class="er">fill</span><span class="ot">=</span><span class="st">&quot;none&quot;</span> <span class="er">stroke</span><span class="ot">=</span><span class="st">&quot;#000&quot;</span> <span class="er">stroke-width</span><span class="ot">=</span><span class="st">&quot;2.73px&quot;</span><span class="kw">/&gt;</span></span>
					<span id="cb2-2"><a href="#cb2-2" aria-hidden="true" tabindex="-1"></a><span class="kw">&lt;path</span> <span class="er">d</span><span class="ot">=</span><span class="st">&quot;M 1255.14 186.95 C 1284.23 220.973 1349.96 229.301 1386.62 203.605 C 1349.09 236.19 1283.35 227.862 1255.14 186.95&quot;</span> <span class="er">stroke</span><span class="ot">=</span><span class="st">&quot;#000&quot;</span> <span class="er">stroke-linecap</span><span class="ot">=</span><span class="st">&quot;round&quot;</span> <span class="er">stroke-linejoin</span><span class="ot">=</span><span class="st">&quot;round&quot;</span> <span class="er">stroke-width</span><span class="ot">=</span><span class="st">&quot;1.74px&quot;</span><span class="kw">/&gt;</span></span></code></pre></div>
					</div>
				<h3>Correcting the header</h3>
				<p>The header of the SVG should include minimal basic information, and a title. In the case of named pieces of music, the title will be that name. In all other cases, it should be the short description that will be used as alt text in the final book.</p>
					<blockquote>
					<div class="wrong">
					<div class="sourceCode" id="cb3"><pre class="sourceCode html"><code class="sourceCode html"><span id="cb3-1"><a href="#cb3-1" aria-hidden="true" tabindex="-1"></a><span class="kw">&lt;?xml</span> version=&quot;1.0&quot; encoding=&quot;UTF-8&quot; standalone=&quot;no&quot;<span class="kw">?&gt;</span></span>
					<span id="cb3-2"><a href="#cb3-2" aria-hidden="true" tabindex="-1"></a><span class="dt">&lt;!DOCTYPE </span>svg PUBLIC &quot;-//W3C//DTD SVG 1.1//EN&quot; &quot;http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd&quot;<span class="dt">&gt;</span></span>
					<span id="cb3-3"><a href="#cb3-3" aria-hidden="true" tabindex="-1"></a><span class="kw">&lt;svg</span> <span class="er">width</span><span class="ot">=</span><span class="st">&quot;100%&quot;</span> <span class="er">height</span><span class="ot">=</span><span class="st">&quot;100%&quot;</span> <span class="er">viewBox</span><span class="ot">=</span><span class="st">&quot;0 0 2100 320&quot;</span> <span class="er">version</span><span class="ot">=</span><span class="st">&quot;1.1&quot;</span> <span class="er">xmlns</span><span class="ot">=</span><span class="st">&quot;http://www.w3.org/2000/svg&quot;</span> <span class="er">xmlns:xlink</span><span class="ot">=</span><span class="st">&quot;http://www.w3.org/1999/xlink&quot;</span> <span class="er">xml:space</span><span class="ot">=</span><span class="st">&quot;preserve&quot;</span> <span class="er">xmlns:serif</span><span class="ot">=</span><span class="st">&quot;http://www.serif.com/&quot;</span> <span class="er">style</span><span class="ot">=</span><span class="st">&quot;fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:bevel;&quot;</span><span class="kw">&gt;</span></span></code></pre></div>
					</div>
					<div class="corrected">
					<div class="sourceCode" id="cb4"><pre class="sourceCode html"><code class="sourceCode html"><span id="cb4-1"><a href="#cb4-1" aria-hidden="true" tabindex="-1"></a><span class="kw">&lt;?xml</span> version=&quot;1.0&quot; encoding=&quot;utf-8&quot;<span class="kw">?&gt;</span></span>
					<span id="cb4-2"><a href="#cb4-2" aria-hidden="true" tabindex="-1"></a><span class="kw">&lt;svg</span> <span class="er">xmlns</span><span class="ot">=</span><span class="st">&quot;http://www.w3.org/2000/svg&quot;</span> <span class="er">version</span><span class="ot">=</span><span class="st">&quot;1.2&quot;</span> <span class="er">viewBox</span><span class="ot">=</span><span class="st">&quot;0 0 2100 320&quot;</span><span class="kw">&gt;</span></span>
					<span id="cb4-3"><a href="#cb4-3" aria-hidden="true" tabindex="-1"></a><span class="kw">&lt;title&gt;</span>8 bars of musical notation for two violins, a viola, and a cello.<span class="kw">&lt;/title&gt;</span></span></code></pre></div>
					</div>
					</blockquote>
			</li>
			<li>
				<h2>Insert the music as a figure and add a List of Illustrations</h2>
				<p>Once the music is ready, it is inserted like any other image. For full guidance, see <a href="https://standardebooks.org/manual/1.5.0/7-high-level-structural-patterns#7.8">section 7.8 of the manual.</p>
				<p>As this is an illustration, a List of Illustrations is needed. For full guidance, see <a href="https://standardebooks.org/manual/1.5.0/7-high-level-structural-patterns#7.9">section 7.9 of the manual</a>.</p>
			</li>
		</ol>
	</article>
</main>
<?= Template::Footer() ?>
