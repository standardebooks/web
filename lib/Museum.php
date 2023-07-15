<?

class Museum extends PropertiesBase{
	public $MuseumId;
	public $Name;
	public $Domain;

	public static function FindMatch(string $url): ?Museum{
		$result = Db::Query('
			SELECT *
			FROM Museums
			WHERE ? LIKE CONCAT("%", Domain, "%")
			LIMIT 1;
		', [$url], 'Museum');

		return $result[0] ?? null;
	}
}
