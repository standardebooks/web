<form action="/contribute/cover-art" method="get" rel="search">
	<label class="tags">Status
		<select name="status" size="1">
			<option value="all">All</option>
			<option value="<?= COVER_ART_STATUS_AVAILABLE ?>"<? if($status == COVER_ART_STATUS_AVAILABLE){ ?> selected="selected"<? } ?>>Available</option>
			<option value="<?= COVER_ART_STATUS_UNAVAILABLE    ?>"<? if($status == COVER_ART_STATUS_UNAVAILABLE   ){ ?> selected="selected"<? } ?>>Unavailable</option>
		</select>
	</label>
	<label class="search">Keywords
		<input type="search" name="query" value="<?= Formatter::ToPlainText($query ?? '') ?>"/>
	</label>
	<label class="select sort">
		<span>Sort</span>
		<span>
			<select name="sort">
				<option value="<?= SORT_COVER_ART_NEWEST ?>"<? if($sort == SORT_COVER_ART_NEWEST){ ?> selected="selected"<? } ?>>Added date (new &#x2192; old)</option>
				<option value="<?= SORT_COVER_ARTIST_ALPHA ?>"<? if ($sort == SORT_COVER_ARTIST_ALPHA){ ?> selected="selected"<? } ?>>Artist name  (a &#x2192; z)</option>
				<option value="<?= SORT_COVER_ART_PAINTED_NEWEST?>"<? if($sort == SORT_COVER_ART_PAINTED_NEWEST){ ?> selected="selected"<? } ?>>Painting date (new &#x2192; old)</option>
			</select>
		</span>
	</label>
	<label class="select per-page">
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
