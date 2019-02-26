<form action="/ebooks/" method="get">
	<label class="search">
		Search ebooks: <input type="search" name="query" placeholder="Search all ebooks..." value="<?= Formatter::ToPlainText($query ?? '') ?>" />
	</label>
</form>
