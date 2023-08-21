<?= Template::Header(['title' => 'How to conquer complex drama formatting', 'manual' => true, 'highlight' => 'contribute', 'description' => 'A guide to formatting any complex plays or dramatic dialog sections.']) ?>
<main class="manual">
	<article class="step-by-step-guide">
		<h1>How to conquer complex drama formatting</h1>
		<p>Producing plays or structuring dramatic dialog sections can be daunting due to their intricate nature and unfamiliar formatting. Don’t panic! This comprehensive guide is designed to offer clear and concise explanations, along with practical examples, to help individuals navigate a wide range of situations with ease.</p>
		<aside class="alert">
			<p class="warning">Before you read</p>
			<p>
				<strong>Make sure to read the <a href="https://standardebooks.org/manual/latest">Standard Ebooks Manual of Style</a> on drama structural patterns, semantics, and CSS before consulting this guide.</strong>
			</p>
		</aside>
		<ol>
			<li>
				<h2>Dramatis personae</h2>
				<h3>Typography</h3>
				<p>Most plays have periods after each character description. Make sure to remove the ending periods of each list item, except for abbreviations. The letter case of various speakers can vary widely for stylistic purposes. Convert the speakers’ names and descriptions into sentence cases. Remove any bold, caps, or small-caps styling for personas.</p>
				<h3>Descriptions</h3>
				<p>Any descriptions are placed in <code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code> elements after the list of speakers and end with periods.</p>
				<h3>Example</h3>
				<figure class="html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">section</span> <span class="na">id</span><span class="o">=</span><span class="s">"dramatis-personae"</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:dramatis-personae"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">h2</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"title"</span><span class="p">&gt;</span>Dramatis Personae<span class="p">&lt;/</span><span class="nt">h2</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">ul</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Don Pedro, Prince of Arragon<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Don John, his bastard brother<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span>
		...
		<span class="p">&lt;</span><span class="nt">li</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Messengers, watch, attendants, <span class="p">&lt;</span><span class="nt">abbr</span> <span class="na">class</span><span class="o">=</span><span class="s">"eoc"</span><span class="p">&gt;</span>etc.<span class="p">&lt;/</span><span class="nt">abbr</span><span class="p">&gt;&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">li</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">ul</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Scene: Messina.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">section</span><span class="p">&gt;</span></code>
				</figure>
			</li>
			<li>
				<h2>Introductory scene descriptions</h2>
				<p>Speakers mentioned in scene descriptions are wrapped in <code class="html"><span class="p">&lt;</span><span class="nt">b</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span></code> elements.</p>
				<figure class="wrong html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">h3</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"label"</span><span class="p">&gt;</span>Scene<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"ordinal z3998:roman"</span><span class="p">&gt;</span>I<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">h3</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>London. The Queen’s apartments.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code>
				</figure>
				<figure class="corrected html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">h3</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"label"</span><span class="p">&gt;</span>Scene<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">span</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"ordinal z3998:roman"</span><span class="p">&gt;</span>I<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">h3</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>London. The <span class="p">&lt;</span><span class="nt">b</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span>Queen’s<span class="p">&lt;/</span><span class="nt">b</span><span class="p">&gt;</span> apartments.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span></code>
				</figure>
			</li>
			<li>
				<h2>Personas</h2>
				<h3>Typography</h3>
				<p>Names, titles, or other speakers are in title case and without ending periods.</p>
				<h3>More than one</h3>
				<p>Sometimes multiple speakers talk at the same time. The containing <code class="html"><span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span></code> element has the <code class="bash"><span class="s">together</span></code> class. The speakers are all placed in a <code class="html"><span class="p">&lt;</span><span class="nt">td</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span></code> element with <code class="html"><span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span></code> elements in between the names.</p>
				<figure class="html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">tr</span> <span class="na">class</span><span class="o">=</span><span class="s">"together"</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span>First Lord<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
	Second Lord<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>Alcibiades banished!<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span></code>
				</figure>
				<h3>Abbreviations</h3>
				<p>Expand all abbreviated personas, whether its the speakers’ names or ordinals.</p>
				<figure class="wrong html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span>1st Serv.<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span></code>
				</figure>
				<figure class="corrected html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span>First Servant<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>...<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span></code>
				</figure>
			</li>
			<li>
				<h2>Dialog</h2>
				<h3>Multiple paragraphs</h3>
				<p>When there is more than one paragraph of dialog will you use <code class="html"><span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span></code> elements.</p><figure class="html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span>Bianca<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>Now let me see if I can construe it:<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>“<span class="p">&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"la"</span><span class="p">&gt;</span>Hic ibat Simois<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>,” I know you not, “<span class="p">&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"la"</span><span class="p">&gt;</span>hic est Sigeia tellus<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>,” I trust you not; “<span class="p">&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"la"</span><span class="p">&gt;</span>Hic steterat Priami<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>,” take heed he hear us not, “<span class="p">&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"la"</span><span class="p">&gt;</span>regia<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>,” presume not, “<span class="p">&lt;</span><span class="nt">i</span> <span class="na">xml:lang</span><span class="o">=</span><span class="s">"la"</span><span class="p">&gt;</span>celsa senis<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>,” despair not.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span></code>
				</figure>
				<h3>Prose and verse</h3>
				<p>In certain complex plays, you may encounter a mix of prose and verse in a character’s speech. Verse is surrounded by <code class="html"><span class="p">&lt;</span><span class="nt">div</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:verse"</span><span class="p">&gt;</span></code>.</p>
				<figure class="html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span>Costard<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">div</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:verse"</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>My sweet ounce of man’s flesh! my incony Jew! <span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:stage-direction"</span><span class="p">&gt;</span>Exit <span class="p">&lt;</span><span class="nt">b</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span>Moth<span class="p">&lt;/</span><span class="nt">b</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
			<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">div</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span> <span class="na">class</span><span class="o">=</span><span class="s">"continued"</span><span class="p">&gt;</span>Now will I look to his remuneration. Remuneration! O, that’s the Latin word for three farthings: three farthings⁠—remuneration.⁠—“What’s the price of this inkle?”⁠—“One penny.”⁠—“No, I’ll give you a remuneration:” why, it carries it. Remuneration! why, it is a fairer name than French crown. I will never buy and sell out of this word.<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span></code>
				</figure>
				<h3>Stichomythia</h3>
				<p><i>Stichomythia</i> is a technique used in drama where two speakers engage in a rapid or intense exchange of alternating dialog. Here is an example found in William Shakespeare’s <i><a href="https://standardebooks.org/ebooks/william-shakespeare/a-midsummer-nights-dream">A Midsummer Night’s Dream</a></i>.</p>
				<figure class="full-width">
					<img src="images/drama-formatting-1.png" alt="Five sections of dialog use stichomythia. The second section of dialog continues where the first section has left off. This pattern continues and creates a staircase effect."/>
				</figure>
				<p>This highlights moments of conflict, urgency, or intense emotion and conveys dynamic interactions between characters. Unfortunately, there is no great way to format this technique with clear, predictable structuring. The text displayed has no additional indents or margins.</p>
			</li>
			<li>
				<h2>Stage directions</h2>
				<h3>Right-aligned and brackets</h3>
				<p>Exit or exeunt stage directions are traditionally shown right-aligned and bracketed. These are formatted like other inline stage directions and placed at the end of the preceding dialog. Compare how the following page scan source is structured in HTML.</p>
				<figure class="full-width">
					<img src="images/drama-formatting-2.png" alt="The stage direction “Goes.” is right-aligned and is preceded by a left bracket."/>
				</figure>
				<figure class="html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span>Festus<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:verse"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Now will I prove thee liar for that word,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
			<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>And that the very vastest out of Hell.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
			<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>With perfect condemnation I abjure<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
			<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>My soul; my nature doth abhor itself;<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
			<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>I have a soul to spare! <span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:stage-direction"</span><span class="p">&gt;</span>Goes.<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span></code>
				</figure>
				<h3>Interrupting dialog</h3>
				<p>Occasionally, there is a stage direction row in the middle of the dialog. For the second half of the dialog, the first child of the row is an empty <code class="html"><span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span></code> element. Do not use the <code class="bash"><span class="s">together</span></code> class for this, as the interrupting stage direction usually doesn’t pertain to the speaker.</p>
				<figure class="html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span>Autolycus<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>I understand the business, I hear it: to have an open ear, a quick eye, and a nimble hand, is necessary for a cut-purse; a good nose is requisite also, to smell out work for the other senses. I see this is the time that the unjust man doth thrive. What an exchange had this been without boot! What a boot is here with this exchange! Sure the gods do this year connive at us, and we may do any thing extempore. The prince himself is about a piece of iniquity, stealing away from his father with his clog at his heels: if I thought it were a piece of honesty to acquaint the king withal, I would not do’t: I hold it the more knavery to conceal it; and therein am I constant to my profession.<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:stage-direction"</span><span class="p">&gt;</span>Re-enter <span class="p">&lt;</span><span class="nt">b</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span>Clown<span class="p">&lt;/</span><span class="nt">b</span><span class="p">&gt;</span> and <span class="p">&lt;</span><span class="nt">b</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span>Shepherd<span class="p">&lt;/</span><span class="nt">b</span><span class="p">&gt;</span>.<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>Aside, aside; here is more matter for a hot brain: every lane’s end, every shop, church, session, hanging, yields a careful man work.<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span></code>
				</figure>
				<h3>Attached to personas</h3>
				<p>Some stage directions are attached to the persona. These directions are placed in <code class="html"><span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span></code> elements with the dialog, but in a separate paragraph.</p>
				<figure class="full-width">
					<img src="images/drama-formatting-3.png" alt="The stage direction “entering” is attached to the persona “Festus”."/>
				</figure>
				<figure class="html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span>Festus<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:stage-direction"</span><span class="p">&gt;</span>Entering.<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">div</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:verse"</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>It is I.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
			<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>I said we should be sure to meet thee here:<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
			<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>For I have brought one who would speak with thee.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span></code>
				</figure>
				<h3>Songs</h3>
				<p>Some songs are given a title or just labeled as “Song”. Treat these as stage direction rows.</p>
				<figure class="html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:stage-direction"</span><span class="p">&gt;</span>Song.<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span>
<span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:persona"</span><span class="p">&gt;</span>Amiens<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">blockquote</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:song"</span><span class="p">&gt;</span>
			<span class="p">&lt;</span><span class="nt">p</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Under the greenwood tree<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
				<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Who loves to lie with me,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
				<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>And turn his merry note<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
				<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Unto the sweet bird’s throat,<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
				<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Come hither, come hither, come hither:<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
				<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>Here shall he see<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
				<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>No enemy<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
				<span class="p">&lt;</span><span class="nt">br</span><span class="p">/&gt;</span>
				<span class="p">&lt;</span><span class="nt">span</span><span class="p">&gt;</span>But winter and rough weather.<span class="p">&lt;/</span><span class="nt">span</span><span class="p">&gt;</span>
			<span class="p">&lt;/</span><span class="nt">p</span><span class="p">&gt;</span>
		<span class="p">&lt;/</span><span class="nt">blockquote</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span></code>
				</figure>
				<h3>Parentheses</h3>
				<p>If there are parentheses inside of stage direction, leave them as is.</p>
<figure class="html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:stage-direction"</span><span class="p">&gt;</span>The neighbour (a woman) passes the hut, and listens to a call from within.<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span></code>
				</figure>
				<h3>A stage direction for a stage direction</h3>
				<p>If there is a stage direction for a stage direction, they should not be combined. Instead, each direction should be marked individually with <code class="html"><span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:stage-direction"</span><span class="p">&gt;</span></code>.</p>
<figure class="html full">
<code class="html full"><span class="p">&lt;</span><span class="nt">tr</span><span class="p">&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">/&gt;</span>
	<span class="p">&lt;</span><span class="nt">td</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:stage-direction"</span><span class="p">&gt;</span>Choristers, singing.<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>
		<span class="p">&lt;</span><span class="nt">i</span> <span class="na">epub:type</span><span class="o">=</span><span class="s">"z3998:stage-direction"</span><span class="p">&gt;</span>Music.<span class="p">&lt;/</span><span class="nt">i</span><span class="p">&gt;</span>
	<span class="p">&lt;/</span><span class="nt">td</span><span class="p">&gt;</span>
<span class="p">&lt;/</span><span class="nt">tr</span><span class="p">&gt;</span></code>
				</figure>
			</li>
		</ol>
	</article>
</main>
<?= Template::Footer() ?>
