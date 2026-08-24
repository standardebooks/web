<?
$opdsUrl = 'https://' . SITE_DOMAIN . '/feeds/opds';
$login = 'your <a href="/donate#patrons-circle">Patrons Circle</a> email address';

if(Session::$User?->Benefits->CanAccessFeeds){
	$login = '<kbd>' . Formatter::EscapeHtml(Session::$User->Email) . '</kbd>';
}
?>
<?= Template::Header(title: 'How to Add an OPDS Feed to Your Ereader', highlight: 'donate', description: 'Step-by-step instructions for adding an OPDS feed to your ereader.', css: ['/css/opds.css']) ?>
<main>
	<article>
		<h1>How to Add an OPDS Feed to Your Ereader</h1>
		<p>Use these instructions to connect our <a href="/feeds/opds">OPDS feed</a> to your ereader of choice, so that you can access our entire ebook catalog directly in your ereader.</p>
		<p>Is your ereader missing? Are any of these instructions out-of-date? <a href="/about#masthead">Contact us</a> to help us fix it!</p>
		<details id="alreader">
			<summary>AlReader<a class="heading-permalink" href="#alreader" aria-label="Permalink"></a></summary>
			<ol>
				<li>
					<p>Open AlReader’s main menu and choose <b>Open Book</b>.</p>
				</li>
				<li>
					<p>Select <b>Network Libraries</b>.</p>
				</li>
				<li>
					<p>Select <b>Add OPDS</b>.</p>
				</li>
				<li>
					<p>Enter <kbd><?= $opdsUrl ?></kbd> in the <b>Library Address</b> field and <?= $login ?> in the <b>User</b> field.</p>
				</li>
				<li>
					<p>Select the <b>checkmark</b>.</p>
				</li>
			</ol>
		</details>

		<details id="bookshelves">
			<summary>BookShelves<a class="heading-permalink" href="#bookshelves" aria-label="Permalink"></a></summary>
			<ol>
				<li>
					<p>Select the <b>Plus</b> button at the upper right.</p>
				</li>
				<li>
					<p>Select <b>Import from OPDS</b>.</p>
				</li>
				<li>
					<p>Select <b>Add Server</b>.</p>
				</li>
				<li>
					<p>Enter <kbd><?= $opdsUrl ?></kbd> in the <b>Server</b> field.</p>
				</li>
				<li>
					<p>Select <b>Requires login</b>.</p>
				</li>
				<li>
					<p>Enter <?= $login ?> in the <b>Username</b> field and any value in the <b>Password</b> field.</p>
				</li>
				<li>
					<p>Select <b>Save</b> at the upper right of the dialog box.</p>
				</li>
			</ol>
		</details>

		<details id="fbreader">
			<summary>FBReader<a class="heading-permalink" href="#fbreader" aria-label="Permalink"></a></summary>
			<ol>
				<li>
					<p>Open the app menu.</p>
				</li>
				<li>
					<p>Select <b>Network Library</b>.</p>
				</li>
				<li>
					<p>Select <b>Add catalog</b>.</p>
				</li>
				<li>
					<p>Enter <kbd><?= $opdsUrl ?></kbd>.</p>
				</li>
				<li>
					<p>Select <b>OK</b>.</p>
				</li>
				<li>
					<p>Enter <b>Standard Ebooks</b> in the <b>Title</b> field.</p>
				</li>
				<li>
					<p>Select <b>OK</b>.</p>
				</li>
				<li>
					<p>Enter <?= $login ?> when prompted.</p>
				</li>
			</ol>
		</details>

		<details id="foliate">
			<summary>Foliate<a class="heading-permalink" href="#foliate" aria-label="Permalink"></a></summary>
			<ol>
				<li>
					<p>Select <b>Add catalog...</b>.</p>
				</li>
				<li>
					<p>Enter <kbd><?= $opdsUrl ?></kbd> in the <b>URL</b> field.</p>
				</li>
				<li>
					<p>Select <b>Add</b>.</p>
				</li>
			</ol>
		</details>

		<details id="freda">
			<summary>Freda<a class="heading-permalink" href="#freda" aria-label="Permalink"></a></summary>
			<ol>
				<li>
					<p>Open the main menu.</p>
				</li>
				<li>
					<p>Select <b>sources</b>.</p>
				</li>
				<li>
					<p>Select <b>Add</b>.</p>
				</li>
				<li>
					<p>Select <b>OpdsCatalog</b>.</p>
				</li>
				<li>
					<p>Enter <?= $login ?> in the <b>user name</b> field and <kbd><?= $opdsUrl ?></kbd> in the <b>https://</b> field.</p>
				</li>
				<li>
					<p>Select <b>save</b>.</p>
				</li>
			</ol>
		</details>

		<details id="fullreader">
			<summary>FullReader<a class="heading-permalink" href="#fullreader" aria-label="Permalink"></a></summary>
			<ol>
				<li>
					<p>Open FullReader’s navigation menu.</p>
				</li>
				<li>
					<p>Select <b>Network library</b>.</p>
				</li>
				<li>
					<p>Select <b>Add custom OPDS catalogue manually</b>.</p>
				</li>
				<li>
					<p>Enter the feed URL.</p>
				</li>
				<li>
					<p>Select <b>OK</b>.</p>
				</li>
				<li>
					<p>Enter <?= $login ?>.</p>
				</li>
				<li>
					<p>Select <b>OK</b>.</p>
				</li>
			</ol>
		</details>

		<details id="koodo">
			<summary>Koodo<a class="heading-permalink" href="#koodo" aria-label="Permalink"></a></summary>
			<ol>
				<li>
					<p>Select <b>+ Add</b> at the upper right.</p>
				</li>
				<li>
					<p>Select <b>OPDS</b>.</p>
				</li>
				<li>
					<p>Select <b>Add OPDS Catalog</b>.</p>
				</li>
				<li>
					<p>In the <b>OPDS Catalog URL</b> field, enter <kbd><?= $opdsUrl ?></kbd>.</p>
				</li>
				<li>
					<p>In the <b>Username</b> field, enter <kbd><?= $login ?></kbd>.</p>
				</li>
				<li>
					<p>Select <b>Confirm</b>.</p>
				</li>
			</ol>
		</details>

		<details id="koreader">
			<summary>KOReader<a class="heading-permalink" href="#koreader" aria-label="Permalink"></a></summary>
			<ol>
				<li>
					<p>Select the magnifying glass icon.</p>
				</li>
				<li>
					<p>Select <b>OPDS catalog</b>.</p>
				</li>
				<li>
					<p>Open the top-left menu.</p>
				</li>
				<li>
					<p>Select <b>Add catalog</b>.</p>
				</li>
				<li>
					<p>Enter <?= $opdsUrl ?> in the <b>URL</b> field, <?= $login ?> in the <b>Username</b> field, and any value in the <b>Password</b> field.</p>
				</li>
				<li>
					<p>Select <b>Save</b>.</p>
				</li>
			</ol>
		</details>

		<details id="librera-reader">
			<summary>Librera Reader<a class="heading-permalink" href="#librera-reader" aria-label="Permalink"></a></summary>
			<ol>
				<li>
					<p>Select <b>Network</b>.</p>
				</li>
				<li>
					<p>Select the <b>plus</b> button.</p>
				</li>
				<li>
					<p>Enter <kbd><?= $opdsUrl ?></kbd>.</p>
				</li>
				<li>
					<p>Enter <?= $login ?> in the <b>Username</b> field and any value in the <b>Password</b> field.</p>
				</li>
			</ol>
		</details>

		<details id="mapleread">
			<summary>MapleRead<a class="heading-permalink" href="#mapleread" aria-label="Permalink"></a></summary>
			<ol>
				<li>
					<p>Select the main navigation button at the upper left.</p>
				</li>
				<li>
					<p>Select <b>Go to Exchange</b>.</p>
				</li>
				<li>
					<p>Select <b>Edit</b> at the upper right.</p>
				</li>
				<li>
					<p>In the <b>User-defined OPDS Catalogs</b> section, select <b>Add OPDS site</b>.</p>
				</li>
				<li>
					<p>Enter <kbd><?= $opdsUrl ?></kbd>.</p>
				</li>
				<li>
					<p>Select <b>Save</b>.</p>
				</li>
				<li>
					<p>Select <b>Done</b> at the upper right.</p>
				</li>
				<li>
					<p>Select the newly-added feed.</p>
				</li>
				<li>
					<p>Enter <?= $login ?> in the <b>User Name</b> field and any value in the <b>Password</b> field.</p>
				</li>
				<li>
					<p>Select <b>Done</b>.</p>
				</li>
			</ol>
		</details>

		<details id="moon-reader">
			<summary>Moon+ Reader<a class="heading-permalink" href="#moon-reader" aria-label="Permalink"></a></summary>
			<ol>
				<li>
					<p>Open the main navigation menu.</p>
				</li>
				<li>
					<p>Select <b>Net Library</b>.</p>
				</li>
				<li>
					<p>Open the three-dot menu and select <b>Add new catalog</b>.</p>
				</li>
				<li>
					<p>Enter <kbd><?= $opdsUrl ?></kbd> and select <b>OK</b>.</p>
				</li>
				<li>
					<p>Enter <?= $login ?> in the <b>Username</b> field and leave the <b>Password</b> field blank.</p>
				</li>
				<li>
					<p>Select <b>OK</b>.</p>
				</li>
			</ol>
		</details>

		<details id="pageback">
			<summary>PageBack<a class="heading-permalink" href="#pageback" aria-label="Permalink"></a></summary>
			<ol>
				<li>
					<p>Select <b>Settings</b>.</p>
				</li>
				<li>
					<p>Under the <b>OPDS &amp; Calibre</b> section, enter <b>Standard Ebooks</b> in the <b>Source name</b> field, enter <kbd><?= $opdsUrl ?></kbd> in the <b>URL</b> field.</p>
				</li>
				<li>
					<p>Select <b>Basic auth</b>.</p>
				</li>
				<li>
					<p>Enter your <b><?= $login ?></b> in the <b>Username</b> field.</p>
				</li>
				<li>
					<p>Select <b>Save source</b>.</p>
				</li>
			</ol>
		</details>

		<details id="pocketbook">
			<summary>PocketBook<a class="heading-permalink" href="#pocketbook" aria-label="Permalink"></a></summary>
			<ol>
				<li>
					<p>Select the main menu button at the upper left.</p>
				</li>
				<li>
					<p>Under <b>E-books</b>, select <b>Network Library</b>.</p>
				</li>
				<li>
					<p>Select the <b>Folder-plus</b> icon at the upper right.</p>
				</li>
				<li>
					<p>Enter <?= $opdsUrl ?> in the <b>URL</b> field and an optional title in the <b>Title</b> field.</p>
				</li>
				<li>
					<p>Select <b>OK</b>.</p>
				</li>
				<li>
					<p>Select the newly-added feed.</p>
				</li>
				<li>
					<p>Enter <?= $login ?> in the <b>Login</b> field and any value in the <b>Password</b> field.</p>
				</li>
				<li>
					<p>Select <b>Sign in</b>.</p>
				</li>
			</ol>
		</details>

		<details id="readest">
			<summary>Readest<a class="heading-permalink" href="#readest" aria-label="Permalink"></a></summary>
			<ol>
				<li>
					<p>Open the main navigation menu.</p>
				</li>
				<li>
					<p>Select <b>Settings</b>.</p>
				</li>
				<li>
					<p>Select <b>OPDS Catalogs</b>.</p>
				</li>
				<li>
					<p>Select <b>Add Catalog</b>.</p>
				</li>
				<li>
					<p>Enter <b>Standard Ebooks</b> in the <b>Catalog Name</b> field and <kbd><?= $opdsUrl ?></kbd> in the <strong>URL</strong> field.</p>
				</li>
				<li>
					<p>Select <b>Add Catalog</b>.</p>
				</li>
			</ol>
		</details>

		<details id="thorium-reader">
			<summary>Thorium Reader<a class="heading-permalink" href="#thorium-reader" aria-label="Permalink"></a></summary>
			<ol>
				<li>
					<p>Select <b>Catalogs</b>.</p>
				</li>
				<li>
					<p>Select <b>Add OPDS feed</b>.</p>
				</li>
				<li>
					<p>Enter <b>Standard Ebooks</b> in the <b>Name</b> field and <kbd><?= $opdsUrl ?></kbd> in the <b>Link</b> field.</p>
				</li>
				<li>
					<p>Select <b>Add</b>.</p>
				</li>
				<li>
					<p>Select the newly-added feed.</p>
				</li>
				<li>
					<p>Enter <?= $login ?> in the <b>Username</b> field and any value in the <b>Password</b> field.</p>
				</li>
				<li>
					<p>Select <b>Login</b>.</p>
				</li>
			</ol>
		</details>

		<details id="yomu">
			<summary>Yomu<a class="heading-permalink" href="#yomu" aria-label="Permalink"></a></summary>
			<ol>
				<li>
					<p>Select the <b>Plus</b> button at the upper right</p>
				</li>
				<li>
					<p>Select <b>OPDS</b>.</p>
				</li>
				<li>
					<p>Select <b>Edit</b>.</p>
				</li>
				<li>
					<p>Select <b>Add</b>.</p>
				</li>
				<li>
					<p>Enter <b>Standard Ebooks</b> in the <b>Name</b> field, <kbd><?= $opdsUrl ?></kbd> in the <b>https://www.domain.com:8080/opds</b> field, <?= $login ?> in the <b>Login</b> field, and any value in the <b>Password</b> field.</p>
				</li>
				<li>
					<p>Select <b>Add</b>.</p>
				</li>
				<li>
					<p>Select <b>Save</b>.</p>
				</li>
			</ol>
		</details>
	</article>
</main>
<?= Template::Footer() ?>
