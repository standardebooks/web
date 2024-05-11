<?

use Safe\Exceptions\ImageException;
use Exceptions\InvalidImageUploadException;

use function Safe\exec;
use function Safe\imagecopyresampled;
use function Safe\imagecreatetruecolor;
use function Safe\imagejpeg;
use function Safe\getimagesize;
use function Safe\unlink;

class Image{
	public string $Path;
	public ?ImageMimeType $MimeType = null;

	public function __construct(string $path){
		$this->Path = $path;
		$this->MimeType = ImageMimeType::FromFile($path);
	}

	/**
	 * @return resource
	 * @throws \Safe\Exceptions\ImageException
	 * @throws Exceptions\InvalidImageUploadException
	 */
	private function GetImageHandle(){
		switch($this->MimeType){
			case ImageMimeType::JPG:
				$handle = \Safe\imagecreatefromjpeg($this->Path);
				break;
			case ImageMimeType::BMP:
				$handle = \Safe\imagecreatefrombmp($this->Path);
				break;
			case ImageMimeType::PNG:
				$handle = \Safe\imagecreatefrompng($this->Path);
				break;
			case ImageMimeType::TIFF:
				$handle = $this->GetImageHandleFromTiff();
				break;
			default:
				throw new \Exceptions\InvalidImageUploadException();
		}

		return $handle;
	}

	/**
	 * @return resource
	 * @throws Exceptions\InvalidImageUploadException
	 */
	private function GetImageHandleFromTiff(){
		$tempFilename = sys_get_temp_dir() . '/se-' . pathinfo($this->Path)['filename'] . '.jpg';

		try{
			exec('convert '.  escapeshellarg($this->Path) . ' ' . escapeshellarg($tempFilename), $shellOutput, $resultCode);

			if($resultCode !== 0 || !is_file($tempFilename)){
				throw new Exceptions\InvalidImageUploadException('Failed to convert TIFF to JPEG');
			}

			$handle = \Safe\imagecreatefromjpeg($tempFilename);
		}
		finally{
			try{
				unlink($tempFilename);
			}
			catch(Exception){
				// Pass if file doesn't exist
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
		}
		catch(\Safe\Exceptions\ImageException $ex){
			throw new Exceptions\InvalidImageUploadException($ex->getMessage());
		}

		$imageWidth = $imageDimensions[0];
		$imageHeight = $imageDimensions[1];

		if($imageHeight > $imageWidth){
			$destinationHeight = $height;
			try{
				$destinationWidth = intval($destinationHeight * ($imageWidth / $imageHeight));
			}
			catch(\DivisionByZeroError){
				$destinationWidth = 0;
			}
		}
		else{
			$destinationWidth = $width;
			try{
				$destinationHeight = intval($destinationWidth * ($imageHeight / $imageWidth));
			}
			catch(\DivisionByZeroError){
				$destinationHeight = 0;
			}
		}

		$srcImageHandle = $this->GetImageHandle();
		$thumbImageHandle = imagecreatetruecolor($destinationWidth, $destinationHeight);

		imagecopyresampled($thumbImageHandle, $srcImageHandle, 0, 0, 0, 0, $destinationWidth, $destinationHeight, $imageWidth, $imageHeight);

		imagejpeg($thumbImageHandle, $destImagePath);
	}
}
