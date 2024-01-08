<?

class Museum extends PropertiesBase{
	public $MuseumId;
	public $Name;
	public $Domain;

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

		if($result[0] === null){
			throw new Exceptions\MuseumNotFoundException();
		}

		return $result[0];
	}
}
