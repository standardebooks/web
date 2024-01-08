<?
class Museum extends PropertiesBase{
	public int $MuseumId;
	public string $Name;
	public string $Domain;

	public static function GetByUrl(?string $url): Museum{
		if($url === null){
			throw new Exceptions\MuseumNotFoundException();
		}

		$result = Db::Query('
			SELECT *
			from Museums
			where ? like concat("%", Domain, "%")
			limit 1;
		', [$url], 'Museum');

		if(sizeof($result) == 0){
			throw new Exceptions\MuseumNotFoundException();
		}

		return $result[0];
	}
}
