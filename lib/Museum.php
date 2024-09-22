<?
use function Safe\parse_url;
use function Safe\preg_match;
use function Safe\preg_replace;

class Museum{
	use Traits\Accessor;

	public int $MuseumId;
	public string $Name;
	public string $Domain;

	/**
	 * @throws Exceptions\InvalidUrlException
	 * @throws Exceptions\InvalidMuseumUrlException
	 * @throws Exceptions\InvalidPageScanUrlException
	 */
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

		$parsedUrl['path'] = $parsedUrl['path'] ?? '';

		// We can't match on TLD because extracting the TLD for double-barrel TLDs, like .gov.uk, requires a whitelist.

		if(preg_match('/\brijksmuseum\.nl$/ius', $parsedUrl['host'])){
			// Rijksmuseum has several URL variants we want to resolve
			// https://www.rijksmuseum.nl/en/search/objects?q=hals&p=6&ps=12&st=Objects&ii=2#/SK-A-1246,59
			// https://www.rijksmuseum.nl/nl/collectie/SK-A-1246
			// https://www.rijksmuseum.nl/en/rijksstudio/artists/jean-baptiste-vanmour/objects#/SK-A-1998,8

			$exampleUrl = 'https://www.rijksmuseum.nl/en/collection/SK-A-1246';

			if($parsedUrl['host'] != 'www.rijksmuseum.nl'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(	(
					preg_match('|^/en/search/objects$|ius', $parsedUrl['path'])
					||
					preg_match('|^/en/rijksstudio/[^/]+/[^/]+/objects$|ius', $parsedUrl['path'])
				)
				&&
				isset($parsedUrl['fragment'])
			){
				$id = (string)$parsedUrl['fragment'];
				$id = preg_replace('/,.+$/ius', '', $id);
				$id = preg_replace('|^/|ius', '', $id);
				$outputUrl = 'https://' . $parsedUrl['host'] . '/en/collection/' . $id;
			}

			if(preg_match('|^en/rijksstudio/[^/]+/[^/]+/objects$|ius', $parsedUrl['path']) && isset($parsedUrl['fragment'])){
				$id = (string)$parsedUrl['fragment'];
				$id = preg_replace('/,.+$/ius', '', $id);
				$id = preg_replace('|^/|ius', '', $id);
				$outputUrl = 'https://' . $parsedUrl['host'] . '/en/collection/' . $id;
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
		elseif(preg_match('/\bmetmuseum\.org$/ius', $parsedUrl['host'])){
			$exampleUrl = 'https://www.metmuseum.org/art/collection/search/13180';

			$host = preg_replace('|^metmuseum.org|ius', 'www.metmuseum.org', $parsedUrl['host']);
			if($host != 'www.metmuseum.org'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/art/collection/search/[\d]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $host . $parsedUrl['path'];

			return $outputUrl;
		}
		elseif(preg_match('/\bnationalmuseum\.se$/ius', $parsedUrl['host'])){
			$exampleUrl = 'https://collection.nationalmuseum.se/eMP/eMuseumPlus?service=ExternalInterface&module=collection&objectId=18217';

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

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'] . '?service=ExternalInterface&module=collection&objectId=' . $vars['objectId'];

			return $outputUrl;
		}
		elseif(preg_match('/\bartsmia\.org$/ius', $parsedUrl['host'])){
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
		elseif(preg_match('/\bthewalters\.org$/ius', $parsedUrl['host'])){
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
		elseif(preg_match('/\bartic\.edu$/ius', $parsedUrl['host'])){
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
		elseif(preg_match('/\bclevelandart\.org$/ius', $parsedUrl['host'])){
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
		elseif(preg_match('/\bparis\.fr$/ius', $parsedUrl['host'])){
			// Variant: https://www.parismuseescollections.paris.fr/en/musee-carnavalet/oeuvres/portrait-del-singer-simon-chenard-1758-1832-wearing-the-clothes-of-a-sans
			// May also be missing www.

			$exampleUrl = 'https://www.parismuseescollections.paris.fr/en/node/226154';

			if(!preg_match('|^(www\.)?parismuseescollections\.paris\.fr$|ius', $parsedUrl['host'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/en/node/[^/]+$|ius', $parsedUrl['path']) && !preg_match('|^/en/[^/]+/oeuvres/[^/]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . preg_replace('|^(www\.)?parismuseescollections\.paris\.fr$|ius', 'www.parismuseescollections.paris.fr', $parsedUrl['host']) . $parsedUrl['path'];

			return $outputUrl;
		}
		elseif(preg_match('/\bwww\.si\.edu$/ius', $parsedUrl['host'])){
			// URLs can look like <https://www.si.edu/object/cave-scene:saam_XX98H> in which the text before : is for SEO and can be cut
			$exampleUrl = 'https://www.si.edu/object/saam_1983.95.90';

			if(!preg_match('|^/object/[^/]+?(:[^/:]+)?$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$path = preg_replace('|^/object/[^/]+:([^/:]+)$|ius', '/object/\1', $parsedUrl['path']);

			$outputUrl = 'https://' . $parsedUrl['host'] . $path;

			return $outputUrl;
		}
		elseif(preg_match('/\bamericanart\.si\.edu$/ius', $parsedUrl['host'])){
			$exampleUrl = 'https://americanart.si.edu/artwork/study-apotheosis-washington-rotunda-united-states-capitol-building-84517';

			if(!preg_match('|^/artwork/[^/]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}
		elseif(preg_match('/\bcollections\.si\.edu$/ius', $parsedUrl['host'])){
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
		elseif(preg_match('/\bnpg\.si\.edu$/ius', $parsedUrl['host'])){
			// These URLs can actually be normalized to a www.si.edu URL by pulling out the object ID
			$exampleUrl = 'https://npg.si.edu/object/npg_NPG.2008.5';

			$path = $parsedUrl['path'];
			if(!preg_match('|/object/[^/]+$|ius', $path)){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://www.si.edu' . $path;

			return $outputUrl;
		}
		elseif(preg_match('/\bbirminghammuseums\.org\.uk$/ius', $parsedUrl['host'])){
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
		elseif(preg_match('/\bmnk\.pl$/ius', $parsedUrl['host'])){
			$exampleUrl = 'https://zbiory.mnk.pl/en/catalog/333584';

			if($parsedUrl['host'] != 'zbiory.mnk.pl'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			// Somtimes the path may have '/search-result/advance' or '/search-result' in it, cut that here
			$path = preg_replace('~^/en(/search-result/advance|/search-result)?~ius', '/en', $parsedUrl['path']);

			if(!preg_match('|^/en/catalog/[^/]+$|ius', $path)){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $path;

			return $outputUrl;
		}
		elseif(preg_match('/\bsmk\.dk$/ius', $parsedUrl['host'])){
			$exampleUrl = 'https://open.smk.dk/artwork/image/KMS1884';

			if($parsedUrl['host'] != 'open.smk.dk'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			// Somtimes the path may have 'en' in it, cut that here
			$path = preg_replace('|^/en/|ius', '/', $parsedUrl['path']);

			if(!preg_match('|^/artwork/image/[^/]+$|ius', $path)){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $path;

			return $outputUrl;
		}
		elseif(preg_match('/\bkansallisgalleria\.fi$/ius', $parsedUrl['host'])){
			$exampleUrl = 'https://www.kansallisgalleria.fi/en/object/429609';

			if($parsedUrl['host'] != 'www.kansallisgalleria.fi'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$path = preg_replace('|^/fi/|ius', '/en/', $parsedUrl['path']);

			if(!preg_match('|^/en/object/[^/]+$|ius', $path)){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $path;

			return $outputUrl;
		}
		elseif(preg_match('/\bnga\.gov$/ius', $parsedUrl['host'])){
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
		elseif(preg_match('/\bnivaagaard\.dk$/ius', $parsedUrl['host'])){
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
		elseif(preg_match('/\brisdmuseum\.org$/ius', $parsedUrl['host'])){
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
		elseif(preg_match('/\baberdeencity\.gov\.uk$/ius', $parsedUrl['host'])){
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
		elseif(preg_match('/\bbrightonmuseums\.org\.uk$/ius', $parsedUrl['host'])){
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
		elseif(preg_match('/\bgrpmcollections\.org$/ius', $parsedUrl['host'])){
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
		elseif(preg_match('/\bthorvaldsensmuseum\.dk$/ius', $parsedUrl['host'])){
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
		elseif(preg_match('/\bmuseabrugge\.be$/ius', $parsedUrl['host'])){
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
		elseif(preg_match('/\bbritishart\.yale\.edu$/ius', $parsedUrl['host'])){
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
		elseif(preg_match('/\bkunsthalle-karlsruhe\.de$/ius', $parsedUrl['host'])){
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
		elseif(preg_match('/\bgetty\.edu$/ius', $parsedUrl['host'])){
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
		elseif(preg_match('/\bartgallery\.yale\.edu$/ius', $parsedUrl['host'])){
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
		elseif(preg_match('/\bdigitaltmuseum\.no$/ius', $parsedUrl['host'])){
			$exampleUrl = 'https://digitaltmuseum.no/021048495118/fra-saxegardsgaten-maleri';

			if($parsedUrl['host'] != 'digitaltmuseum.no'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/[^/]+?/[^/]+?$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}
		elseif(preg_match('/\bart\.pl$/ius', $parsedUrl['host'])){
			$exampleUrl = 'https://cyfrowe.mnw.art.pl/en/catalog/445066';

			if($parsedUrl['host'] != 'cyfrowe.mnw.art.pl'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/en/catalog/[^/]+?$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}
		elseif(preg_match('/\blenbachhaus\.de$/', $parsedUrl['host'])){
			$exampleUrl = 'https://www.lenbachhaus.de/en/digital/collection-online/detail/hymnus-an-michelangelo-30036437';

			if($parsedUrl['host'] != 'www.lenbachhaus.de'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/en/digital/collection-online/detail/[^/]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}
		elseif(preg_match('/\bkmska\.be$/', $parsedUrl['host'])){
			$exampleUrl = 'https://kmska.be/en/masterpiece/restaurant-mille-colonnes-amsterdam';

			if($parsedUrl['host'] != 'kmska.be'){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			if(!preg_match('|^/en/masterpiece/[^/]+$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

			return $outputUrl;
		}
		// elseif(preg_match('/\bwebumenia\.sk$/ius', $parsedUrl['host'])){
		// 	// All we need is the int object ID, the last slug is SEO
		// 	$exampleUrl = 'https://www.webumenia.sk/en/dielo/SVK:SNG.O_85';

		// 	if($parsedUrl['host'] != 'www.webumenia.sk'){
		// 		throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
		// 	}

		// 	if(!preg_match('|^/en/dielo/[^/]+?$|ius', $parsedUrl['path'])){
		// 		throw new Exceptions\InvalidMuseumUrlException($url, $exampleUrl);
		// 	}

		// 	$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];

		// 	return $outputUrl;
		// }

		return $outputUrl;
	}

	/**
	* @throws Exceptions\MuseumNotFoundException
	* @throws Exceptions\InvalidUrlException
	*/
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
		', [$parsedUrl['host']], Museum::class);

		return $result[0] ?? throw new Exceptions\MuseumNotFoundException();
	}
}
