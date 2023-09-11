<?
require_once('Core.php');
?><?= Template::Header(['title' => 'How to create SVGs from maps with several colors', 'manual' => true, 'highlight' => 'contribute', 'description' => 'A guide to producing SVG from images such as maps with more than a single color.']) ?>
<main class="manual">
	<article class="step-by-step-guide">
		<h1>How to create SVGs from maps with several colors</h1>
		<p>It’s not unusual to find a map which has an additional line or lines in color showing the route of a journey. We want to preserve this color in the final SVG of the map we include with the book. We will use as an example a map reproduced in “Through the Brazilian Wilderness” by Theodore Roosevelt</p>
		<ol>
			<li>
				<h2>Finding the best quality source scan</h2>
				<p>You always want to start with the very best scan of the image you can locate. Here's a JPG of the scan from “Through the Brazilian Wilderness”:</p>
				<figure class="full-width">
					<img src="images/map-example-0.jpg" alt="The source map of Brazil showing both black and red linework."/>
				</figure>
				<p>As you can see, there is a red line showing the journey, and an accompanying legend.</p>
			</li>
			<li>
				<h2>Process with a suitable bitmap editor</h2>
				<p>I use an editor for macOS called Acorn, but most other graphic editors would be suitable. The essential requirement is for it to have a function which will let you replace one color with another, and that it support multiple layers.<p>
				<p>Open the source file in your editor and immediately duplicate the image layer. If you can rename the layers, call one of them “red-layer” and the other “black-layer”.</p> 
				<p>We are going to use the Replace Color function to replace the colors we don’t want in each layer. See this initial image which shows just a small portion of the map, with the two layers we’ve created.</p>
				<figure class="full-width">
					<img src="images/map-example-1.jpg" alt="The image open in Acorn, showing the duplicated layers."/>
				</figure>
				<p>Lock and hide “red-layer” and work on “black-layer”. Now apply the Replace Color to replace red with the background color. In the image below I’ve decreased the tolerance just enough to show you what I’m doing. You will want to increase the tolerance until the red is no longer visible at all.</p>
				<figure class="full-width">
					<img src="images/map-example-2.jpg" alt="The replace color function in action showing the red lines almost gone before tolerance is increased."/>
				</figure>
				<p>Now work on “red-layer” and do the opposite: replace color to remove the black lines.</p>
				<figure class="full-width">
					<img src="images/map-example-3.jpg" alt="The replace color function in action showing the black lines almost gone before tolerance is increased."/>
				</figure>
				<p>Tip: it’s a good idea to retain some “anchor points” at the corners of your image, the same location in each layer, so that when you vectorize, each vector will be of the same outside dimensions. The four corners of the maps would be good for this.</p>
				<figure class="full-width">
					<img src="images/map-example-4.jpg" alt="The replace color function in action showing the black lines almost gone before tolerance is increased."/>
				</figure>
				<p>When that’s done to your satisfaction, export each layer separately as a JPG or PNG image, with a suitable name.</p>
			</li>
			<li>
			<h2>Vectorize the images</h2>
			<p>I use a program called simply “Image Vectorizer” for macOS, but Inkscape would be fine for this.</p>
			<p>Use the program to vectorize each layer image separately. Save the SVG output as appropriately-named files, say “red-layer.svg” and “black-layer.svg”.</p>
			</li>
			<li>
				<h2>Import the SVGs into vector editor and color one layer</h2>
				<p>I use Affinity Designer for this, but I imagine that any good vector-based editor should work.</p>
				<p>Import each SVG onto its own layer, and position each layer carefully so they overlap exactly. Then open up “red-layer” and apply color to all of the components. If necessary, you can then edit out the anchor points you placed at the corners of each layer.</p>
				<figure class="full-width">
					<img src="images/map-example-6.jpg" alt="Modifying the red layer to apply color."/>
				</figure>
				<p>Now export the combined image as a single SVG file, and apply the various SVG optimizations we prefer.</p>
				<figure class="full-width">
					<img src="images/map-example-7.jpg" alt="Combining the two layers for export."/>
				</figure>
			</li>
			<li>
			<p>That’s it, you’re done!</p>
			</li>
		</ol>
	</article>
</main>
<?= Template::Footer() ?>
