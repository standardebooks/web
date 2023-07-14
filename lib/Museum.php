<?

class Museum extends PropertiesBase{
	public $MuseumId;
	public $Name;
	public $Domain;

	/**
	 * @return array<Museum>
	 */
	public static function GetAll(): array{
		return Db::Query('
			SELECT *
			FROM Museums
			ORDER BY Name', [], 'Museum');
	}

	public static function FindMatch(string $url): ?Museum{
		foreach(Museum::GetAll() as $museum){
			if(stristr($url, $museum->Domain)){
				return $museum;
			}
		}
		return null;
	}
}
