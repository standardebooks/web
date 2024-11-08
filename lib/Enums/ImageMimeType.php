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

		$mimeType = mime_content_type($path);

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
}
