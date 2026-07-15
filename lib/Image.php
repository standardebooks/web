<?
use function Safe\exec;
use function Safe\glob;
use function Safe\imagecopyresampled;
use function Safe\imagecreatetruecolor;
use function Safe\imageflip;
use function Safe\imagejpeg;
use function Safe\imagerotate;
use function Safe\unlink;

class Image{
	public string $Path;
	public ?Enums\ImageMimeType $MimeType = null;

	public function __construct(string $path){
		$this->Path = $path;
		$this->MimeType = Enums\ImageMimeType::FromFile($path);
	}

	/**
	 * Return a GD image handle for the image file.
	 *
	 * @throws Exceptions\ImageUploadInvalidException
	 */
	private function GetImageHandle(): \GdImage{
		try{
			switch($this->MimeType){
				case Enums\ImageMimeType::JPG:
					$handle = $this->GetAutoOrientedJpegImageHandle();
					break;
				case Enums\ImageMimeType::BMP:
					$handle = \Safe\imagecreatefrombmp($this->Path);
					break;
				case Enums\ImageMimeType::PNG:
					$handle = \Safe\imagecreatefrompng($this->Path);
					break;
				case Enums\ImageMimeType::WEBP:
					$handle = \Safe\imagecreatefromwebp($this->Path);
					break;
				case Enums\ImageMimeType::TIFF:
					$handle = $this->GetAutoOrientedTiffImageHandle();
					break;
				default:
					throw new \Exceptions\ImageUploadInvalidException();
			}
		}
		catch(\Safe\Exceptions\ImageException){
			throw new \Exceptions\ImageUploadInvalidException();
		}

		return $handle;
	}

	/**
	 * Return the EXIF orientation value for this image.
	 */
	private function GetExifOrientation(): int{
		$exifData = @exif_read_data($this->Path);

		if(!is_array($exifData)){
			return 1;
		}

		$orientation = $exifData['Orientation'] ?? 1;

		if(is_int($orientation)){
			return $orientation;
		}

		if(is_string($orientation) && ctype_digit($orientation)){
			return intval($orientation);
		}

		return 1;
	}

	/**
	 * Return a GD image handle with JPEG EXIF orientation applied to the pixel data.
	 *
	 * @throws Exceptions\ImageUploadInvalidException
	 */
	private function GetAutoOrientedJpegImageHandle(): \GdImage{
		$handle = \Safe\imagecreatefromjpeg($this->Path);

		try{
			switch($this->GetExifOrientation()){
				case 2:
					imageflip($handle, IMG_FLIP_HORIZONTAL);
					break;
				case 3:
					$handle = imagerotate($handle, 180, 0);
					break;
				case 4:
					imageflip($handle, IMG_FLIP_VERTICAL);
					break;
				case 5:
					imageflip($handle, IMG_FLIP_HORIZONTAL);
					$handle = imagerotate($handle, -90, 0);
					break;
				case 6:
					$handle = imagerotate($handle, -90, 0);
					break;
				case 7:
					imageflip($handle, IMG_FLIP_HORIZONTAL);
					$handle = imagerotate($handle, 90, 0);
					break;
				case 8:
					$handle = imagerotate($handle, 90, 0);
					break;
			}
		}
		catch(\Safe\Exceptions\ImageException){
			throw new Exceptions\ImageUploadInvalidException('Failed to orient JPEG.');
		}

		return $handle;
	}

	/**
	 * Return a GD image handle with TIFF orientation applied to the pixel data.
	 *
	 * @throws Exceptions\ImageUploadInvalidException
	 */
	private function GetAutoOrientedTiffImageHandle(): \GdImage{
		$tempFilename = sys_get_temp_dir() . '/' . uniqid('se-image-', true) . '.jpg';
		$tempFilePathInfo = pathinfo($tempFilename);
		$tempFileGlob = $tempFilePathInfo['dirname'] . '/' . $tempFilePathInfo['filename'] . '*.jpg';

		try{
			exec('convert -auto-orient ' . escapeshellarg($this->Path) . ' ' . escapeshellarg($tempFilename), $shellOutput, $resultCode);

			if($resultCode !== 0){
				throw new Exceptions\ImageUploadInvalidException('Failed to convert image to JPG.');
			}

			// Sometimes TIFF files can have multiple images, or "pages" in one file. In that case, `convert` outputs multiple files named `<file>-0.jpg`, `<file>-1.jpg`, etc., instead of `<file>.jpg`.
			// Test for that case here.
			$pagedFilename = $tempFilePathInfo['dirname'] . '/' . $tempFilePathInfo['filename'] . '-0.jpg';
			if(is_file($pagedFilename)){
				// This TIFF has pages!
				$handle = \Safe\imagecreatefromjpeg($pagedFilename);
			}
			elseif(is_file($tempFilename)){
				// Regular TIFF.
				$handle = \Safe\imagecreatefromjpeg($tempFilename);
			}
			else{
				throw new Exceptions\ImageUploadInvalidException('Failed to convert TIFF to JPEG.');
			}
		}
		finally{
			foreach(glob($tempFileGlob) as $filename){
				try{
					@unlink($filename);
				}
				catch(Exception){
					// Pass.
				}
			}
		}

		return $handle;
	}

	/**
	 * Resize this image and write it to the destination path.
	 *
	 * @throws Exceptions\ImageUploadInvalidException
	 */
	public function Resize(string $destImagePath, int $width, int $height): void{
		try{
			$srcImageHandle = $this->GetImageHandle();
			$imageWidth = imagesx($srcImageHandle);
			$imageHeight = imagesy($srcImageHandle);

			if($imageHeight > $imageWidth){
				$destinationHeight = $height;
				$destinationWidth = intval($destinationHeight * ($imageWidth / $imageHeight));
			}
			else{
				$destinationWidth = $width;
				$destinationHeight = intval($destinationWidth * ($imageHeight / $imageWidth));
			}

			$thumbImageHandle = imagecreatetruecolor($destinationWidth, $destinationHeight);

			imagecopyresampled($thumbImageHandle, $srcImageHandle, 0, 0, 0, 0, $destinationWidth, $destinationHeight, $imageWidth, $imageHeight);

			imagejpeg($thumbImageHandle, $destImagePath);
		}
		catch(\Safe\Exceptions\ImageException $ex){
			throw new Exceptions\ImageUploadInvalidException($ex->getMessage());
		}
	}
}
