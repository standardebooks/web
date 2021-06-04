<?
require_once('Core.php');
?><?= Template::Header(['title' => 'How to Add a Standard Ebooks book to Goodreads', 'manual' => true, 'highlight' => 'contribute', 'description' => 'A guide to add a Standard Ebook edition of a book to the Goodreads website.']) ?>
<main class="manual">
	<article class="goodreads">
		<h1>How to Add a Standard Ebooks Ebook to Goodreads</h1>
		<p>The Goodreads website (owned by Amazon) is a crowd-sourced repository of information about books. It’s useful to have our publications listed there so that our editions can be selected by members to add to their reading lists.</p>
		<p>In almost all cases, the book will already be in the Goodreads database; we are interested in adding our productions as new <em>editions</em> of those books.</p>
		<ol>
			<li>
						<h2>Step 1</h2>
						<p>Go to the <a href="https://goodreads.com">Goodreads website</a>.</p>
			</li>
			<li>
						<h2>Step 2</h2>
						<p>Search for the book you are adding. Try to find the closest match.</p>
						<p>As an example, we’ll add out production of <i><a href="/ebooks/thomas-paine/the-rights-of-man">The Rights of Man</a></i> by <a href="/ebooks/thomas-paine">Thomas Paine</a>. See the screenshot below.</p>
						<figure>
							<img src="/images/goodreads-1.svg"/>
						</figure>
			</li>
			<li>
						<h2>Step 3</h2>
						<p> It’s important to add your production as a new <strong>edition</strong>, not a new book.</p>
						<p>Unfortunately, Goodread has a new U.I. which makes it harder to locate the list of editions for a work. Follow the screenshots below to get to that list.</p>
						<p>Click on “More details.”</p>
						<figure><img src="/images/goodreads-2.svg"/></figure>
			</li>
			<li>
						<h2>Step 4</h2>
						<p>Now click on “See all editions.”</p>
						<figure><img src="/images/goodreads-3.svg"/></figure>
			</li>
			<li>
						<h2>Step 5</h2>
						<p>Click on “Add a new edition.”</p>
						<figure><img src="/images/goodreads-4.svg"/></figure>
			</li>
			<li>
						<h2>Step 6</h2>
						<p>You’ll see all of the data which has been put in for the particular edition you found in your original search. We’re going to overwrite most of this.</p>
						<figure><img src="/images/goodreads-5.svg"/></figure>
			</li>
						<li>
						<h2>Step 7</h2>
						<p>Fill in the details of the Standard Ebooks edition.</p>
						<p>This <a href="https://github.com/drgrigg/SE_metadata_exporter.git">quick-and-dirty Python utility</a> will help export the required metadata either as a JSON file (which some tools like Keyboard Maestro can consume and use to automate the filling in of the form), or as a tab-delimited file.</p>
						<p>You can paste raw HTML into the description field.</p>
						<figure><img src="/images/goodreads-6.svg"/></figure>
			</li>
			<li>
						<h2>Step 8</h2>
						<p><strong>Don’t forget</strong> to select the cover image, which needs to have been downloaded onto your system. Covers can be found at the ebook’s downloads folder, for example: <a href="/ebooks/thomas-paine/the-rights-of-man/downloads/cover.jpg">https://standardebooks.org/ebooks/thomas-paine/the-rights-of-man/downloads/cover.jpg</a>.</p>
						<figure><img src="/images/goodreads-7.svg"/></figure>
			</li>
			<li>
						<h2>Step 9</h2>
						<p>Click on “Create Book.” Shortly afterwards you’ll be taken to the Review page for this book. You can then click on the book title to see the new entry in the Goodreads system.</p>
						<p>Voila! Your edition is now in Goodreads.</p>
						<figure><img src="/images/goodreads-8.svg"/></figure>
			</li>
		</ol>
	</article>
</main>
<?= Template::Footer() ?>
