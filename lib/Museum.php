<?

class Museum extends PropertiesBase{
	public $MuseumId;
	public $Name;
	public $Domain;

	public static function GetByUrl(string $url): ?Museum{
		$result = Db::Query('
			SELECT *
			from Museums
			where ? like concat("%", Domain, "%")
			limit 1;
		', [$url], 'Museum');

		return $result[0] ?? null;
	}
}
