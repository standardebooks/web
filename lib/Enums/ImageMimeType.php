<?
namespace Enums;

use function Safe\mime_content_type;

enum ImageMimeType: string{
	case JPG = 'image/jpeg';
	case BMP = 'image/bmp';
	case PNG = 'image/png';
	case TIFF = 'image/tiff';

	public function GetFileExtension(): string{
		return match($this){
			self::JPG => '.jpg',
			self::BMP => '.bmp',
			self::PNG => '.png',
			self::TIFF => '.tif',
		};
	}

	public static function FromFile(?string $path): ?ImageMimeType{
		if($path === null || $path == ''){
			return null;
		}

		try{
			$mimeType = mime_content_type($path);
		}
		catch(\Safe\Exceptions\FileinfoException){
			return null;
		}

		$mimeType = match($mimeType){
			'image/x-ms-bmp', 'image/x-bmp' => 'image/bmp',
			default => $mimeType,
		};

		if(!$mimeType){
			return null;
		}

		return ImageMimeType::tryFrom($mimeType);
	}

	/**
	 * @return array<string>
	 */
	public static function Values(): array{
		return array_map(function(ImageMimeType $case){
			return $case->value;
		}, ImageMimeType::cases());
	}

	/**
	 * Return a string like `jpeg, tiff, and bmp`.
	 */
	public static function ValuesString(): string{
		$acceptedMimeTypes = '';
		$mimeTypeValues = self::Values();

		for($i = 0; $i < sizeof($mimeTypeValues); $i++){
			$acceptedMimeTypes .= str_replace('image/', '', $mimeTypeValues[$i]);

			if($i < sizeof($mimeTypeValues) - 1){
				if(sizeof($mimeTypeValues) == 2){
					$acceptedMimeTypes .= ' and ';
				}
				elseif(sizeof($mimeTypeValues) - $i - 2 <= 0){
					$acceptedMimeTypes .= ', and ';
				}
				else{
					$acceptedMimeTypes .= ', ';
				}
			}
		}

		return $acceptedMimeTypes;
	}
}
