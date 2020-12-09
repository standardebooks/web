<?
$allSelected = sizeof($tags) == 0 || in_array('all', $tags);
?>
<form action="/ebooks" method="get">
	<label class="tags">Subjects <span>(select many with <? if(strpos($_SERVER['HTTP_USER_AGENT'], 'Mac') === false){ ?>ctrl<? }else{ ?>⌘<? } ?>)</span>
		<select multiple="multiple" name="tags[]">
			<option value="all">All</option>
		<? foreach(Library::GetTags() as $tag){
			$lcTag = mb_strtolower($tag); ?>
			<option value="<?= Formatter::ToPlainText($lcTag) ?>"<? if(!$allSelected && in_array($lcTag, $tags)){ ?> selected="selected"<? } ?>><?= Formatter::ToPlainText($tag) ?></option>
		<? } ?>
		</select>
	</label>
	<label class="search">Keywords
		<input type="search" name="query" value="<?= Formatter::ToPlainText($query ?? '') ?>"/>
	</label>
	<label class="select sort">
		<span>Sort</span>
		<span>
			<select name="sort">
				<option value="<?= SORT_NEWEST ?>"<? if($sort == SORT_NEWEST){ ?> selected="selected"<? } ?>>Release date (new &#x2192; old)</option>
				<option value="<?= SORT_AUTHOR_ALPHA ?>"<? if($sort == SORT_AUTHOR_ALPHA){ ?> selected="selected"<? } ?>>Author name  (a &#x2192; z)</option>
				<option value="<?= SORT_READING_EASE ?>"<? if($sort == SORT_READING_EASE){ ?> selected="selected"<? } ?>>Reading ease (easy &#x2192; hard)</option>
				<option value="<?= SORT_LENGTH ?>"<? if($sort == SORT_LENGTH){ ?> selected="selected"<? } ?>>Length (short &#x2192; long)</option>
			</select>
		</span>
	</label>
	<label class="select view">
		<span>View</span>
		<span>
			<select name="view">
				<option value="<?= VIEW_GRID ?>"<? if($view == VIEW_GRID){ ?> selected="selected"<? } ?>>Grid</option>
				<option value="<?= VIEW_LIST ?>"<? if($view == VIEW_LIST){ ?> selected="selected"<? } ?>>List</option>
			</select>
		</span>
	</label>
	<label class="select per-page">
		<span>Per page</span>
		<span>
			<select name="per-page">
				<option value="12"<? if($perPage == 12){ ?> selected="selected"<? } ?>>12</option>
				<option value="24"<? if($perPage == 24){ ?> selected="selected"<? } ?>>24</option>
				<option value="48"<? if($perPage == 48){ ?> selected="selected"<? } ?>>48</option>
			</select>
		</span>
	</label>
	<button>Filter</button>
</form>
