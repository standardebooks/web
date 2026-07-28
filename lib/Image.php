<?
use function Safe\exec;
use function Safe\glob;
use function Safe\imagecopyresampled;
use function Safe\imagecreatefrombmp;
use function Safe\imagecreatefromjpeg;
use function Safe\imagecreatefrompng;
use function Safe\imagecreatefromgif;
use function Safe\imagecreatefromwebp;
use function Safe\imagecreatetruecolor;
use function Safe\imageflip;
use function Safe\imagejpeg;
use function Safe\imagepng;
use function Safe\imagerotate;
use function Safe\imagesx;
use function Safe\imagesy;
use function Safe\imagewebp;
use function Safe\preg_match;
use function Safe\unlink;

class Image{
	public string $Path;
	public ?Enums\ImageMimeType $MimeType = null;

	private ?\GdImage $_ImageHandle = null;

	public function __construct(?string $path = null){
		if($path !== null){
			$this->Path = $path;
			$this->MimeType = Enums\ImageMimeType::FromFile($path);
		}
	}

	/**
	 * Write an image handle to a supported destination image format.
	 *
	 * @throws Exceptions\ImageOperationFailedException If the destination image extension is unsupported.
	 */
	private function WriteImage(\GdImage $destinationImageHandle, string $destinationImagePath): void{
		if(preg_match('/\.jpe?g$/iu', $destinationImagePath)){
			imagejpeg($destinationImageHandle, $destinationImagePath);
		}
		elseif(preg_match('/\.png$/iu', $destinationImagePath)){
			imagepng($destinationImageHandle, $destinationImagePath);
		}
		elseif(preg_match('/\.webp$/iu', $destinationImagePath)){
			imagewebp($destinationImageHandle, $destinationImagePath, 75);
		}
		else{
			throw new Exceptions\ImageOperationFailedException();
		}
	}

	/**
	 * Resize this image to the specified dimensions, without stretching or squashing, and save it as a JPG file.
	 *
	 * @throws Exceptions\ImageOperationFailedException If cropping the `Image` failed.
	 */
	public function Crop(string $destinationImagePath, int $width, int $height): void{
		try{
			$sourceImageHandle = $this->GetImageHandle();

			$imageWidth = imagesx($sourceImageHandle);
			$imageHeight = imagesy($sourceImageHandle);

			if($imageWidth <= 0 || $imageHeight <= 0 || $width <= 0 || $height <= 0){
				throw new Exceptions\ImageOperationFailedException();
			}
			$destinationImageHandle = imagecreatetruecolor($width, $height);

			$widthRatio = $imageWidth / $width;
			$heightRatio = $imageHeight / $height;
			$halfHeight = floor($height / 2);
			$halfWidth = floor($width / 2);

			if($imageWidth > $imageHeight){
				$adjustedWidth = intval(floor($imageWidth / $heightRatio));
				$adjustedHalfWidth = floor($adjustedWidth / 2);
				$finalWidth = intval($adjustedHalfWidth - $halfWidth);
				imagecopyresampled($destinationImageHandle, $sourceImageHandle, -$finalWidth, 0, 0, 0, $adjustedWidth, $height, $imageWidth, $imageHeight);
			}
			elseif(($imageWidth < $imageHeight) || ($imageWidth == $imageHeight)) {
				$adjustedHeight = intval(floor($imageHeight / $widthRatio));
				$adjustedHalfHeight = floor($adjustedHeight / 2);
				$finalHeight = intval($adjustedHalfHeight - $halfHeight);
				imagecopyresampled($destinationImageHandle, $sourceImageHandle, 0, -$finalHeight, 0, 0, $width, $adjustedHeight, $imageWidth, $imageHeight);
			}
			else{
				imagecopyresampled($destinationImageHandle, $sourceImageHandle, 0, 0, 0, 0, $width, $height, $imageWidth, $imageHeight);
			}

			$this->WriteImage($destinationImageHandle, $destinationImagePath);
		}
		catch(\Throwable){ // May throw a `DivisionByZero` `Error`.
			throw new Exceptions\ImageOperationFailedException();
		}
	}

	/**
	 * Return a GD image handle for the image file.
	 *
	 * @throws Exceptions\ImageUploadInvalidException
	 */
	private function GetImageHandle(): \GdImage{
		if($this->_ImageHandle !== null){
			return $this->_ImageHandle;
		}

		try{
			switch($this->MimeType){
				case Enums\ImageMimeType::JPG:
					$imageHandle = imagecreatefromjpeg($this->Path);
					break;
				case Enums\ImageMimeType::BMP:
					$imageHandle = imagecreatefrombmp($this->Path);
					break;
				case Enums\ImageMimeType::PNG:
					$imageHandle = imagecreatefrompng($this->Path);
					break;
				case Enums\ImageMimeType::WEBP:
					$imageHandle = imagecreatefromwebp($this->Path);
					break;
				case Enums\ImageMimeType::TIFF:
					$imageHandle = $this->GetAutoOrientedTiffImageHandle();
					break;
				case Enums\ImageMimeType::GIF:
					$imageHandle = imagecreatefromgif($this->Path);
					break;
				default:
					throw new \Exceptions\ImageUploadInvalidException();
			}
		}
		catch(\Safe\Exceptions\ImageException){
			throw new \Exceptions\ImageUploadInvalidException();
		}

		if($this->MimeType == Enums\ImageMimeType::TIFF){
			$this->_ImageHandle = $imageHandle;
		}
		else{
			$this->_ImageHandle = $this->Reorient($imageHandle);
		}

		return $this->_ImageHandle;
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
				$imageHandle = imagecreatefromjpeg($pagedFilename);
			}
			elseif(is_file($tempFilename)){
				// Regular TIFF.
				$imageHandle = imagecreatefromjpeg($tempFilename);
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

		return $imageHandle;
	}

	/**
	 * Downscale the image while retaining enough height and width for any subsequent resizing or cropping.
	 *
	 * @throws Exceptions\ImageOperationFailedException If the image could not be downscaled.
	 */
	public function Downscale(int $maximumWidth, int $maximumHeight, int $minimumCropWidth, int $minimumCropHeight): void{
		try{
			$sourceImageHandle = $this->GetImageHandle();

			$imageWidth = imagesx($sourceImageHandle);
			$imageHeight = imagesy($sourceImageHandle);

			if($imageWidth <= 0 || $imageHeight <= 0 || $maximumWidth <= 0 || $maximumHeight <= 0 || $minimumCropWidth <= 0 || $minimumCropHeight <= 0){
				throw new Exceptions\ImageOperationFailedException();
			}

			// Retain the largest fitted output and enough pixels in both dimensions for the largest crop.
			$scale = $imageHeight > $imageWidth ? $maximumHeight / $imageHeight : $maximumWidth / $imageWidth;
			$scale = min(1, max($scale, $minimumCropWidth / $imageWidth, $minimumCropHeight / $imageHeight));

			if($scale == 1){
				return;
			}

			$destinationWidth = intval(round($imageWidth * $scale));
			$destinationHeight = intval(round($imageHeight * $scale));
			$destinationImageHandle = imagecreatetruecolor($destinationWidth, $destinationHeight);

			imagecopyresampled($destinationImageHandle, $sourceImageHandle, 0, 0, 0, 0, $destinationWidth, $destinationHeight, $imageWidth, $imageHeight);
			$this->_ImageHandle = $destinationImageHandle;
		}
		catch(\Throwable){
			throw new Exceptions\ImageOperationFailedException();
		}
	}

	/**
	 * Rotate the real image handle to match its exif rotation metadata.
	 */
	public function Reorient(\GdImage $imageHandle): \GdImage{
		$orientation = $this->GetExifOrientation();

		switch($orientation){
			case 2:
				imageflip($imageHandle, IMG_FLIP_HORIZONTAL);
				break;
			case 3:
				$imageHandle = imagerotate($imageHandle, 180, 0);
				break;
			case 4:
				imageflip($imageHandle, IMG_FLIP_VERTICAL);
				break;
			case 5:
				imageflip($imageHandle, IMG_FLIP_HORIZONTAL);
				$imageHandle = imagerotate($imageHandle, -90, 0);
				break;
			case 6:
				$imageHandle = imagerotate($imageHandle, -90, 0);
				break;
			case 7:
				imageflip($imageHandle, IMG_FLIP_HORIZONTAL);
				$imageHandle = imagerotate($imageHandle, 90, 0);
				break;
			case 8:
				$imageHandle = imagerotate($imageHandle, 90, 0);
				break;
		}

		return $imageHandle;
	}

	/**
	 * Resize this image to the specified dimensions, maintaining aspect ratio, and save it as a JPG file.
	 *
	 * @throws Exceptions\ImageOperationFailedException If the image could not be resized.
	 */
	public function Resize(string $destinationImagePath, int $width, int $height): void{
		try{
			$sourceImageHandle = $this->GetImageHandle();

			$imageWidth = imagesx($sourceImageHandle);
			$imageHeight = imagesy($sourceImageHandle);

			if($imageWidth <= 0 || $imageHeight <= 0 || $width <= 0 || $height <= 0){
				throw new Exceptions\ImageOperationFailedException();
			}

			if($imageHeight > $imageWidth){
				$destinationHeight = $height;
				$destinationWidth = intval($destinationHeight * ($imageWidth / $imageHeight));
			}
			else{
				$destinationWidth = $width;
				$destinationHeight = intval($destinationWidth * ($imageHeight / $imageWidth));
			}

			$destinationImageHandle = imagecreatetruecolor($destinationWidth, $destinationHeight);

			imagecopyresampled($destinationImageHandle, $sourceImageHandle, 0, 0, 0, 0, $destinationWidth, $destinationHeight, $imageWidth, $imageHeight);

			$this->WriteImage($destinationImageHandle, $destinationImagePath);
		}
		catch(\Throwable){
			throw new Exceptions\ImageOperationFailedException();
		}
	}
}
