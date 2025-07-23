<?
use function Safe\exec;
use function Safe\glob;
use function Safe\imagecopyresampled;
use function Safe\imagecreatetruecolor;
use function Safe\imagejpeg;
use function Safe\getimagesize;
use function Safe\unlink;

class Image{
	public string $Path;
	public ?Enums\ImageMimeType $MimeType = null;

	public function __construct(string $path){
		$this->Path = $path;
		$this->MimeType = Enums\ImageMimeType::FromFile($path);
	}

	/**
	 * @return \GdImage
	 *
	 * @throws Exceptions\InvalidImageUploadException
	 */
	private function GetImageHandle(){
		try{
			switch($this->MimeType){
				case Enums\ImageMimeType::JPG:
					$handle = \Safe\imagecreatefromjpeg($this->Path);
					break;
				case Enums\ImageMimeType::BMP:
					$handle = \Safe\imagecreatefrombmp($this->Path);
					break;
				case Enums\ImageMimeType::PNG:
					$handle = \Safe\imagecreatefrompng($this->Path);
					break;
				case Enums\ImageMimeType::TIFF:
					$handle = $this->GetImageHandleFromTiff();
					break;
				default:
					throw new \Exceptions\InvalidImageUploadException();
			}
		}
		catch(\Safe\Exceptions\ImageException){
			throw new \Exceptions\InvalidImageUploadException();
		}

		return $handle;
	}

	/**
	 * @return \GdImage
	 * @throws Exceptions\InvalidImageUploadException
	 */
	private function GetImageHandleFromTiff(){
		$basename = pathinfo($this->Path)['filename'];
		$tempDirectory = sys_get_temp_dir();
		$tempFilename = $tempDirectory . '/se-' . $basename . '.jpg';

		try{
			exec('convert '. escapeshellarg($this->Path) . ' ' . escapeshellarg($tempFilename), $shellOutput, $resultCode);

			if($resultCode !== 0){
				throw new Exceptions\InvalidImageUploadException('Failed to convert TIFF to JPEG');
			}

			// Sometimes TIFF files can have multiple images, or "pages" in one file. In that case, `convert` outputs multiple files named `<file>-0.jpg`, `<file>-1.jpg`, etc., instead of `<file>.jpg`.
			// Test for that case here.
			$pagedFilename = $tempDirectory . '/se-' . $basename . '-0.jpg';
			if(is_file($pagedFilename)){
				// This TIFF has pages!
				$handle = \Safe\imagecreatefromjpeg($pagedFilename);
			}
			elseif(is_file($tempFilename)){
				// Regular TIFF.
				$handle = \Safe\imagecreatefromjpeg($tempFilename);
			}
			else{
				throw new Exceptions\InvalidImageUploadException('Failed to convert TIFF to JPEG');
			}
		}
		finally{
			foreach(glob($tempDirectory . '/se-' . $basename . '*.jpg') as $filename){
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
	 * @throws Exceptions\InvalidImageUploadException
	 */
	public function Resize(string $destImagePath, int $width, int $height): void{
		try{
			$imageDimensions = getimagesize($this->Path);

			$imageWidth = $imageDimensions[0] ?? 0;
			$imageHeight = $imageDimensions[1] ?? 0;

			if($imageHeight > $imageWidth){
				$destinationHeight = $height;
				$destinationWidth = intval($destinationHeight * ($imageWidth / $imageHeight));
			}
			else{
				$destinationWidth = $width;
				if($imageWidth == 0){
					$destinationHeight = 0;
				}
				else{
					$destinationHeight = intval($destinationWidth * ($imageHeight / $imageWidth));
				}
			}

			$srcImageHandle = $this->GetImageHandle();
			$thumbImageHandle = imagecreatetruecolor($destinationWidth, $destinationHeight);

			imagecopyresampled($thumbImageHandle, $srcImageHandle, 0, 0, 0, 0, $destinationWidth, $destinationHeight, $imageWidth, $imageHeight);

			imagejpeg($thumbImageHandle, $destImagePath);
		}
		catch(\Safe\Exceptions\ImageException $ex){
			throw new Exceptions\InvalidImageUploadException($ex->getMessage());
		}
	}
}
