<?
require_once('Core.php');
?><?= Template::Header(['title' => 'Toolset Guidelines', 'highlight' => 'contribute', 'description' => 'Guidelines for programming language and code style used in the Standard Ebooks toolset.']) ?>
<main>
	<article>
		<h1>Toolset Guidelines</h1>
		<p>You can <a href="https://github.com/standardebooks/tools">view our toolset on Github</a>.</p>
		<p>Contributions should be in Python 3.</p>
		<p>In general we follow a relaxed version of <a href="https://www.python.org/dev/peps/pep-0008/">PEP 8</a>. In particular, we use tabs instead of spaces, and there is no line length limit.</p>
		<p>Always use the <code class="path">regex</code> module instead of the <code class="path">re</code> module.</p>
		<p>At the minimum, scripts should use programs available for installation on Ubuntu 18.04 LTS systems, either via <code class="program">apt</code> or <code class="program">pip3</code>.</p>
	</article>
</main>
<?= Template::Footer() ?>
