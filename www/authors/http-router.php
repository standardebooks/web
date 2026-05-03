<?
/**
 * GET		/ebooks/:author-url-path
 *
 * Note that an "author" is not a single `Contributor` per se, rather it is a listing of ebooks by a *set* of authors. For example, an "author" could be a single person, i.e. `mark-twain`, or multiple people, i.e. `joseph-conrad_ford-madox-ford`. Therefore there is no map to a single `Contributor`.
 */

try{
	/** @var string $urlPath Contains the portion of the URL (without query string) that comes after `https://standardebooks.org/ebooks/`. */
	$urlPath = trim(str_replace('.', '', HttpInput::Str(GET, 'author-url-name') ?? ''), '/');

	if($urlPath == ''){
		throw new Exceptions\AuthorNotFoundException();
	}

	$ebooks = Ebook::GetAllByAuthor($urlPath);

	if(sizeof($ebooks) == 0){
		throw new Exceptions\AuthorNotFoundException();
	}

	HttpInput::RouteRequest(resource: $ebooks);
}
catch(Exceptions\NotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
