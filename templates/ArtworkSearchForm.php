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
				<option value="<?= SORT_COVER_ARTIST_ALPHA ?>"<? if ($sort == SORT_COVER_ARTIST_ALPHA){ ?> selected="selected"<? } ?>>Artist name  (a &#x2192; z)</option>
				<option value="<?= SORT_COVER_ARTWORK_COMPLETED_NEWEST ?>"<? if($sort == SORT_COVER_ARTWORK_COMPLETED_NEWEST){ ?> selected="selected"<? } ?>>Completed date (new &#x2192; old)</option>
			</select>
		</span>
	</label>
	<label>
		<span>Per page</span>
		<span>
			<select name="per-page">
				<option value="48"<? if($perPage == 48){ ?> selected="selected"<? } ?>>48</option>
				<option value="96"<? if($perPage == 96){ ?> selected="selected"<? } ?>>96</option>
				<option value="192"<? if($perPage == 192){ ?> selected="selected"<? } ?>>192</option>
			</select>
		</span>
	</label>
	<button>Filter</button>
</form>
