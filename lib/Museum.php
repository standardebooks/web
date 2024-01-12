<?
use function Safe\parse_url;
use function Safe\preg_match;
use function Safe\preg_replace;

class Museum extends PropertiesBase{
	public int $MuseumId;
	public string $Name;
	public string $Domain;

	public static function NormalizeUrl(string $url): string{
		$outputUrl = $url;

		try{
			$parsedUrl = parse_url($url);
		}
		catch(Exception){
			throw new Exceptions\InvalidUrlException($url);
		}

		if(!is_array($parsedUrl)){
			throw new Exceptions\InvalidUrlException($url);
		}

		if(stripos($parsedUrl['host'], 'rijksmuseum.nl') !== false){
			// Rijksmuseum has several URL variants we want to resolve
			// https://www.rijksmuseum.nl/en/search/objects?q=hals&p=6&ps=12&st=Objects&ii=2#/SK-A-1246,59
			// https://www.rijksmuseum.nl/nl/collectie/SK-A-1246

			$exampleUrl = 'https://www.rijksmuseum.nl/en/collection/SK-A-1246';

			if($parsedUrl['host'] != 'www.rijksmuseum.nl'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(preg_match('|^/en/search/objects$|ius', $parsedUrl['path']) && isset($parsedUrl['fragment'])){
				$outputUrl = 'https://' . $parsedUrl['host'] . '/en/collection/' . preg_replace('/,+$/ius', '', $parsedUrl['fragment']);
			}

			if(preg_match('|^/nl/collectie/[^/]+$|ius', $parsedUrl['path'])){
				$outputUrl = 'https://' . $parsedUrl['host'] . preg_replace('|^/nl/collectie/([^/]+)$|ius', '/en/collection/\1', $parsedUrl['path']);
			}

			if(preg_match('|^/en/collection/[^/]+$|ius', $parsedUrl['path'])){
				$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];
			}

			if(!preg_match('|^https://www.rijksmuseum.nl/en/collection/[^/]+$|ius', $outputUrl)){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'metmuseum.org') !== false){
			$exampleUrl = 'https://www.metmuseum.org/art/collection/search/13180';

			if($parsedUrl['host'] != 'www.metmuseum.org'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/art/collection/search/\d+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'nationalmuseum.se') !== false){
			$exampleUrl = 'https://collection.nationalmuseum.se/eMP/eMuseumPlus?objectId=18217';

			if($parsedUrl['host'] != 'collection.nationalmuseum.se'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if($parsedUrl['path'] != '/eMP/eMuseumPlus'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if($parsedUrl['path'] != '/eMP/eMuseumPlus'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			parse_str($parsedUrl['query'] ?? '', $vars);

			if(!isset($vars['objectId']) || is_array($vars['objectId'])){
				throw new Exceptions\InvalidPageScanUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'] . '?objectId=' . $vars['objectId'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'artsmia.org') !== false){
			$exampleUrl = 'https://collections.artsmia.org/art/3729/castle-and-watermill-by-a-river-jacob-van-ruisdael';

			if($parsedUrl['host'] != 'collections.artsmia.org'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/art/\d+/[^/]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'thewalters.org') !== false){
			$exampleUrl = 'https://art.thewalters.org/detail/4695/boston-street-scene-boston-common/';

			if($parsedUrl['host'] != 'art.thewalters.org'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/detail/\d+/[^/]+/$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'artic.edu') !== false){
			$exampleUrl = 'https://www.artic.edu/artworks/133864/the-defense-of-paris';

			if($parsedUrl['host'] != 'www.artic.edu'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/artworks/\d+/[^/]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'clevelandart.org') !== false){
			$exampleUrl = 'https://www.clevelandart.org/art/1969.54';

			if($parsedUrl['host'] != 'www.clevelandart.org'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/art/[^/]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'paris.fr') !== false){
			// Variant: https://www.parismuseescollections.paris.fr/en/musee-carnavalet/oeuvres/portrait-del-singer-simon-chenard-1758-1832-wearing-the-clothes-of-a-sans

			$exampleUrl = 'https://www.parismuseescollections.paris.fr/en/node/226154';

			if($parsedUrl['host'] != 'www.parismuseescollections.paris.fr'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/en/node/[^/]+$|ius', $parsedUrl['path']) && !preg_match('|^/en/[^/]+/oeuvres/[^/]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'www.si.edu') !== false){
			// URLs can look like <https://www.si.edu/object/cave-scene:saam_XX98H> in which the text before : is for SEO and can be cut
			$exampleUrl = 'https://www.si.edu/object/saam_1983.95.90';

			if(!preg_match('|/object/[^/]+?:[^/:]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$path = preg_replace('^|/object/[^/]+:([^/:]+)$|ius', '/object/\1', $parsedUrl['path']);

			$outputUrl = 'https://' . $parsedUrl['host'] . $path;

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'americanart.si.edu') !== false){
			$exampleUrl = 'https://americanart.si.edu/artwork/study-apotheosis-washington-rotunda-united-states-capitol-building-84517';

			$path = $parsedUrl['path'];
			if(!preg_match('|/object/[^/]+?:[^/:]+$|ius', $path)){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/artwork/[^/]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'collections.si.edu') !== false){
			// These URLs can actually be normalized to a www.si.edu URL by pulling out the object ID
			$exampleUrl = 'https://collections.si.edu/search/detail/edanmdm:saam_1981.146.1';

			$path = $parsedUrl['path'];
			if(!preg_match('|/search/detail/[^/]+?:[^/:]+$|ius', $path)){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$path = preg_replace('|/search/detail/[^/]+:([^/:]+)$|ius', '/object/\1', $parsedUrl['path']);

			$outputUrl = 'https://www.si.edu' . $path;

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'npg.si.edu') !== false){
			// These URLs can actually be normalized to a www.si.edu URL by pulling out the object ID
			$exampleUrl = 'https://npg.si.edu/object/npg_NPG.2008.5';

			$path = $parsedUrl['path'];
			if(!preg_match('|/object/[^/]+$|ius', $path)){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://www.si.edu' . $path;

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'birminghammuseums.org.uk') !== false){
			$exampleUrl = 'https://dams.birminghammuseums.org.uk/asset-bank/action/viewAsset?id=6726';

			if($parsedUrl['host'] != 'dams.birminghammuseums.org.uk'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if($parsedUrl['path'] != '/asset-bank/action/viewAsset'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			parse_str($parsedUrl['query'] ?? '', $vars);

			if(!isset($vars['id']) || is_array($vars['id'])){
				throw new Exceptions\InvalidPageScanUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'] . '?id=' . $vars['id'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'mnk.pl') !== false){
			$exampleUrl = 'https://zbiory.mnk.pl/en/search-result/catalog/333584';

			if($parsedUrl['host'] != 'zbiory.mnk.pl'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/en/search-result/catalog/[^/]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'smk.dk') !== false){
			$exampleUrl = 'https://open.smk.dk/artwork/image/KMS1884';

			if($parsedUrl['host'] != 'open.smk.dk'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/artwork/image/[^/]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'kansallisgalleria.fi') !== false){
			$exampleUrl = 'https://www.kansallisgalleria.fi/en/object/429609';

			if($parsedUrl['host'] != 'www.kansallisgalleria.fi'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/en/object/[^/]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'nga.gov') !== false){
			$exampleUrl = 'https://www.nga.gov/collection/art-object-page.46522.html';

			if($parsedUrl['host'] != 'www.nga.gov'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/collection/art-object-page\.[^/]+\.html$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'nivaagaard.dk') !== false){
			$exampleUrl = 'https://www.nivaagaard.dk/en/vare/lundstroem-vilhelm/';

			if($parsedUrl['host'] != 'www.nivaagaard.dk'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/en/vare/[^/]+/$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'risdmuseum.org') !== false){
			$exampleUrl = 'https://risdmuseum.org/art-design/collection/portrait-christiana-carteaux-bannister-2016381';

			if($parsedUrl['host'] != 'risdmuseum.org'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/art-design/collection/[^/]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'aberdeencity.gov.uk') !== false){
			// All we need is the int object ID, the last slug is SEO
			$exampleUrl = 'https://emuseum.aberdeencity.gov.uk/objects/3215/james-cromar-watt-lld';

			if($parsedUrl['host'] != 'emuseum.aberdeencity.gov.uk'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/objects/[^/]+(/[^/]+)?$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$path = preg_replace('|^/objects/([^/]+)(/[^/]+)?$|ius', '/objects/\1', $parsedUrl['path']);

			$outputUrl = 'https://' . $parsedUrl['host'] . $path;

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'brightonmuseums.org.uk') !== false){
			// All we need is the int object ID, the last slug is SEO
			$exampleUrl = 'https://collections.brightonmuseums.org.uk/records/63caa90083d50a00184b8e90';

			if($parsedUrl['host'] != 'collections.brightonmuseums.org.uk'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/records/[^/]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'grpmcollections.org') !== false){
			// All we need is the int object ID, the last slug is SEO
			$exampleUrl = 'https://www.grpmcollections.org/Detail/objects/130684';

			if($parsedUrl['host'] != 'www.grpmcollections.org'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/Detail/objects/[^/]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'thorvaldsensmuseum.dk') !== false){
			// All we need is the int object ID, the last slug is SEO
			$exampleUrl = 'https://kataloget.thorvaldsensmuseum.dk/en/B122';

			if($parsedUrl['host'] != 'kataloget.thorvaldsensmuseum.dk'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/en/[^/]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'museabrugge.be') !== false){
			// All we need is the int object ID, the last slug is SEO
			$exampleUrl = 'https://collectie.museabrugge.be/en/collection/work/id/2013_GRO0013_I';

			if($parsedUrl['host'] != 'collectie.museabrugge.be'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/en/collection/work/id/[^/]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'britishart.yale.edu') !== false){
			// All we need is the int object ID, the last slug is SEO
			$exampleUrl = 'https://collections.britishart.yale.edu/catalog/tms:1010';

			if($parsedUrl['host'] != 'collections.britishart.yale.edu'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/catalog/[^/]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'kunsthalle-karlsruhe.de') !== false){
			// All we need is the int object ID, the last slug is SEO
			$exampleUrl = 'https://www.kunsthalle-karlsruhe.de/kunstwerke/Ferdinand-Keller/K%C3%BCstenlandschaft-bei-Rio-de-Janeiro/C066F030484D7D09148891B0E70524B8/';

			if($parsedUrl['host'] != 'www.kunsthalle-karlsruhe.de'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/kunstwerke/[^/]+?/[^/]+?/[^/]+?/$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'getty.edu') !== false){
			// All we need is the int object ID, the last slug is SEO
			$exampleUrl = 'https://www.getty.edu/art/collection/object/103RG0';

			if($parsedUrl['host'] != 'www.getty.edu'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/art/collection/object/[^/]+?$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		if(stripos($parsedUrl['host'], 'artgallery.yale.edu') !== false){
			// All we need is the int object ID, the last slug is SEO
			$exampleUrl = 'https://artgallery.yale.edu/collections/objects/44306';

			if($parsedUrl['host'] != 'artgallery.yale.edu'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/collections/objects/[^/]+?$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}

		return $outputUrl;
	}

	public static function GetByUrl(?string $url): Museum{
		if($url === null){
			throw new Exceptions\MuseumNotFoundException();
		}

		try{
			$parsedUrl = parse_url($url);
		}
		catch(Exception){
			throw new Exceptions\InvalidUrlException($url);
		}

		if(!isset($parsedUrl['host'])){
			throw new Exceptions\InvalidUrlException($url);
		}

		$result = Db::Query('
			SELECT *
			from Museums
			where ? like concat("%", Domain, "%")
			limit 1;
		', [$parsedUrl['host']], 'Museum');

		if(sizeof($result) == 0){
			throw new Exceptions\MuseumNotFoundException();
		}

		return $result[0];
	}
}
