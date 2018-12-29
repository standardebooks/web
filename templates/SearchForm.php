<form action="/ebooks/" method="get">
	<label class="search">
		Search ebooks: <input type="search" name="query" placeholder="Search ebooks..." value="<?= Formatter::ToPlainText($query ?? '') ?>" />
	</label>
</form>
