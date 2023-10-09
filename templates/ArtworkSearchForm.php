<form action="/artworks" method="get" rel="search">
	<label>Status
		<select name="status" size="1">
			<option value="all">All</option>
			<option value="<?= COVER_ARTWORK_STATUS_APPROVED ?>"<? if($status == COVER_ARTWORK_STATUS_APPROVED){ ?> selected="selected"<? } ?>>Approved</option>
			<option value="<?= COVER_ARTWORK_STATUS_IN_USE ?>"<? if($status == COVER_ARTWORK_STATUS_IN_USE){ ?> selected="selected"<? } ?>>In use</option>
		</select>
	</label>
	<label class="search">Keywords
		<input type="search" name="query" value="<?= Formatter::ToPlainText($query ?? '') ?>"/>
	</label>
	<label>
		<span>Sort</span>
		<span>
			<select name="sort">
				<option value="<?= SORT_COVER_ARTWORK_CREATED_NEWEST ?>"<? if($sort == SORT_COVER_ARTWORK_CREATED_NEWEST){ ?> selected="selected"<? } ?>>Added date (new &#x2192; old)</option>
				<option value="<?= SORT_COVER_ARTIST_ALPHA ?>"<? if($sort == SORT_COVER_ARTIST_ALPHA){ ?> selected="selected"<? } ?>>Artist name (a &#x2192; z)</option>
				<option value="<?= SORT_COVER_ARTWORK_COMPLETED_NEWEST ?>"<? if($sort == SORT_COVER_ARTWORK_COMPLETED_NEWEST){ ?> selected="selected"<? } ?>>Completed date (new &#x2192; old)</option>
			</select>
		</span>
	</label>
	<label>
		<span>Per page</span>
		<span>
			<select name="per-page">
				<option value="50"<? if($perPage == 50){ ?> selected="selected"<? } ?>>50</option>
				<option value="100"<? if($perPage == 100){ ?> selected="selected"<? } ?>>100</option>
				<option value="200"<? if($perPage == 200){ ?> selected="selected"<? } ?>>200</option>
			</select>
		</span>
	</label>
	<button>Filter</button>
</form>
