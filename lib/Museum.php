<?
use function Safe\parse_url;

class Museum extends PropertiesBase{
	public int $MuseumId;
	public string $Name;
	public string $Domain;

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
