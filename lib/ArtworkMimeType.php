<?

enum ArtworkMimeType: string{
	case JPG = "image/jpeg";
	case BMP = "image/bmp";
	case PNG = "image/png";

	public function GetFileExtension(): string{
		return match ($this){
			self::JPG => ".jpg",
			self::BMP => ".bmp",
			self::PNG => ".png",
		};
	}

	/**
	 * @return resource
	 * @throws \Safe\Exceptions\ImageException
	 */
	public function ImageCreateFromMimeType(string $filename){
		return match ($this){
			self::JPG => \Safe\imagecreatefromjpeg($filename),
			self::BMP => \Safe\imagecreatefrombmp($filename),
			self::PNG => \Safe\imagecreatefrompng($filename),
		};
	}

	public static function FromUploadedFile(array $uploadedFile): null|ArtworkMimeType{
		$filePath = $uploadedFile['tmp_name'];
		$fileInfo = finfo_open(FILEINFO_MIME_TYPE);

		$mimeType = finfo_file($fileInfo, $filePath);
		$mimeType = match ($mimeType){
			"image/x-ms-bmp", "image/x-bmp" => "image/bmp",
			default => $mimeType,
		};

		finfo_close($fileInfo);

		if(!$mimeType){
			return null;
		}

		return ArtworkMimeType::tryFrom($mimeType);
	}

	public static function Values(): array{
		return array_map(function (ArtworkMimeType $case){
			return $case->value;
		}, ArtworkMimeType::cases());
	}
}
