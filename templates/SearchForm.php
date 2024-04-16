<?
$allSelected = sizeof($tags) == 0 || in_array('all', $tags);
?>
<form action="/ebooks" method="get" rel="search">
	<label class="tags">Subjects
		<select <? if(!Template::IsEreaderBrowser()){ ?> multiple="multiple"<? } ?> name="tags[]" size="1">
			<option value="all">All</option>
		<? foreach(Library::GetTags() as $tag){ ?>
			<option value="<?= $tag->UrlName ?>"<? if(!$allSelected && in_array($tag->UrlName, $tags)){ ?> selected="selected"<? } ?>><?= Formatter::EscapeHtml($tag->Name) ?></option>
		<? } ?>
		</select>
	</label>
	<label>Keywords
		<input type="search" name="query" value="<?= Formatter::EscapeHtml($query ?? '') ?>"/>
	</label>
	<label class="sort">
		<span>Sort</span>
		<span>
			<select name="sort">
				<option value="<?= EbookSort::Newest->value ?>"<? if($sort == EbookSort::Newest){ ?> selected="selected"<? } ?>>S.E. release date (new &#x2192; old)</option>
				<option value="<?= EbookSort::AuthorAlpha->value ?>"<? if($sort == EbookSort::AuthorAlpha){ ?> selected="selected"<? } ?>>Author name  (a &#x2192; z)</option>
				<option value="<?= EbookSort::ReadingEase->value ?>"<? if($sort == EbookSort::ReadingEase){ ?> selected="selected"<? } ?>>Reading ease (easy &#x2192; hard)</option>
				<option value="<?= EbookSort::Length->value ?>"<? if($sort == EbookSort::Length){ ?> selected="selected"<? } ?>>Length (short &#x2192; long)</option>
			</select>
		</span>
	</label>
	<label class="view">
		<span>View</span>
		<span>
			<select name="view">
				<option value="<?= VIEW_GRID ?>"<? if($view == VIEW_GRID){ ?> selected="selected"<? } ?>>Grid</option>
				<option value="<?= VIEW_LIST ?>"<? if($view == VIEW_LIST){ ?> selected="selected"<? } ?>>List</option>
			</select>
		</span>
	</label>
	<label class="per-page">
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
