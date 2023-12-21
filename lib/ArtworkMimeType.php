<?

enum ArtworkMimeType: string{
	case JPG = "image/jpeg";
	case BMP = "image/bmp";
	case PNG = "image/png";
	case TIFF = "image/tiff";

	public function GetFileExtension(): string{
		return match($this){
			self::JPG => ".jpg",
			self::BMP => ".bmp",
			self::PNG => ".png",
			self::TIFF => ".tif",
		};
	}

	/**
	 * @return resource
	 * @throws \Safe\Exceptions\ImageException
	 */
	public function ImageCreateFromMimeType(string $filename){
		return match($this){
			self::JPG => \Safe\imagecreatefromjpeg($filename),
			self::BMP => \Safe\imagecreatefrombmp($filename),
			self::PNG => \Safe\imagecreatefrompng($filename),
			self::TIFF => ArtworkMimeType::imagecreatefromtiff($filename),
		};
	}

	private static function imagecreatefromtiff(string $filename){
		exec("convert $filename -sampling-factor 4:2:0 -strip -quality 80 -colorspace RGB -interlace JPEG $filename.thumb.jpg", $shellOutput, $resultCode);
		if($resultCode !== 0){
			throw new Exceptions\InvalidImageUploadException("Failed to convert TIFF to JPEG\n");
		}
		return \Safe\imagecreatefromjpeg("$filename.thumb.jpg");
	}

	public static function FromUploadedFile(array $uploadedFile): null|ArtworkMimeType{
		if($uploadedFile['error'] > UPLOAD_ERR_OK){
			return null;
		}

		$mimeType = mime_content_type($uploadedFile['tmp_name']);
		$mimeType = match($mimeType){
			"image/x-ms-bmp", "image/x-bmp" => "image/bmp",
			default => $mimeType,
		};

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
