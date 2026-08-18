<?
use function Safe\mkdir;
use function Safe\rename;
use function Safe\rmdir;
use function Safe\session_id;
use function Safe\tempnam;
use function Safe\unlink;

final class ImageTempDirectory{
	/**
	 * Create a token for a temporary image path to for use in submission forms.
	 *
	 * @throws Exceptions\TempDirectoryException If the image path is invalid.
	 */
	public static function CreateToken(string $imagePath, string $secret): string{
		try{
			if(dirname($imagePath) !== self::GetPath() || !is_file($imagePath)){
				throw new Exceptions\TempDirectoryException('Temporary image path is invalid.');
			}

			$payload = basename($imagePath) . '|' . (NOW->getTimestamp() + 21600);
			$signature = hash_hmac('sha256', $payload . '|' . session_id(), $secret);

			return $payload . '|' . $signature;
		}
		catch(\Safe\Exceptions\SessionException){
			throw new Exceptions\TempDirectoryException('Couldn\'t create temporary image token.');
		}
	}

	/**
	 * Validate a session-bound temporary image token and return its full path.
	 *
	 * @throws Exceptions\TempDirectoryException If the token is invalid, expired, or refers to a missing image.
	 */
	private static function GetImagePathFromToken(string $token, string $secret): string{
		try{
			$parts = explode('|', $token);
			if(sizeof($parts) != 3 || !ctype_digit($parts[1])){
				throw new Exceptions\TempDirectoryException('Temporary image token is invalid.');
			}

			[$filename, $expirationTimestamp, $signature] = $parts;
			$payload = $filename . '|' . $expirationTimestamp;
			if(
				basename($filename) !== $filename
				||
				intval($expirationTimestamp) < NOW->getTimestamp()
				||
				!hash_equals(hash_hmac('sha256', $payload . '|' . session_id(), $secret), $signature)
			){
				throw new Exceptions\TempDirectoryException('Temporary image token is invalid.');
			}

			$imagePath = self::GetPath() . '/' . $filename;
			if(!is_file($imagePath)){
				throw new Exceptions\TempDirectoryException('Temporary image does not exist.');
			}

			return $imagePath;
		}
		catch(\Safe\Exceptions\SessionException){
			throw new Exceptions\TempDirectoryException('Couldn\'t validate temporary image token.');
		}
	}

	/**
	 * Return the staged image path represented by a token, or null if no token was provided.
	 *
	 * @throws Exceptions\ImageUploadInvalidException If the token secret is missing or the token is invalid.
	 */
	public static function GetStagedImagePath(?string $stagedImageToken, ?string $imageTokenSecret): ?string{
		if($stagedImageToken === null){
			return null;
		}

		if($imageTokenSecret === null){
			throw new Exceptions\ImageUploadInvalidException('Please re-upload the image.');
		}

		try{
			return self::GetImagePathFromToken($stagedImageToken, $imageTokenSecret);
		}
		catch(Exceptions\TempDirectoryException){
			throw new Exceptions\ImageUploadInvalidException('Please re-upload the image.');
		}
	}

	/**
	 * Move the specified image to the temporary image directory, and return its new path.
	 *
	 * @throws Exceptions\TempDirectoryException If the temporary image file couldn't be created.
	 */
	public static function AddImage(string $imagePath): string{
		try{
			$tempName = tempnam(self::GetPath(), 'image-');

			rename($imagePath, $tempName);

			return $tempName;
		}
		catch(\Throwable $ex){
			if($ex instanceof Exceptions\TempDirectoryException){
				throw $ex;
			}

			throw new Exceptions\TempDirectoryException('Couldn\'t create temporary image.');
		}
	}

	/**
	 * Remove a temporary image file.
	 */
	public static function RemoveImage(?string $imagePath): void{
		if($imagePath === null){
			return;
		}

		try{
			if(dirname($imagePath) === self::GetPath() && is_file($imagePath)){
				@unlink($imagePath);
			}
		}
		catch(\Throwable){
			// Pass.
		}
	}

	/**
	 * Replace an old image with a new image in the image cache.
	 *
	 * @return ?string The path of the new image in the image cache, or `null` if `$newImagePath` is `null`.
	 *
	 * @throws Exceptions\ImageUploadInvalidException If an error occurs.
	 */
	public static function ReplaceImage(?string $oldImagePath, ?string $newImagePath): ?string{
		if($newImagePath === null && $oldImagePath !== null){
			return $oldImagePath;
		}

		if($newImagePath === null){
			return null;
		}

		try{
			ImageTempDirectory::RemoveImage($oldImagePath);

			$oldImagePath = ImageTempDirectory::AddImage($newImagePath);
		}
		catch(Exceptions\TempDirectoryException){
			throw new Exceptions\ImageUploadInvalidException('Failed to save uploaded image.');
		}

		return $oldImagePath;
	}

	/**
	 * Return the application image-staging directory path, creating the directory if necessary.
	 *
	 * @return string The full path to the image temporary directory, without a trailing slash.
	 *
	 * @throws Exceptions\TempDirectoryException If the temporary directory couldn't be created.
	 */
	public static function GetPath(): string{
		$tempDirectory = sys_get_temp_dir() . '/se';

		if(!is_dir($tempDirectory)){
			try{
				mkdir($tempDirectory, 0700);
			}
			catch(\Safe\Exceptions\FilesystemException){
				throw new Exceptions\TempDirectoryException('Couldn\'t create temporary directory.');
			}
		}

		return $tempDirectory;
	}

	/**
	 * Delete temporary image files older than the specified age and remove empty staging directories.
	 *
	 * @throws Exceptions\TempDirectoryException If deleting files failed.
	 */
	public static function DeleteFilesOlderThan(\DateInterval $maximumAge): void{
		$expirationTimestamp = NOW->sub($maximumAge)->getTimestamp();
		self::DeleteFilesOlderThanRecursive(self::GetPath(), $expirationTimestamp);
	}

	/**
	 * Delete expired files recursively from a temporary image directory.
	 *
	 * @throws Exceptions\TempDirectoryException If deleting files failed.
	 */
	private static function DeleteFilesOlderThanRecursive(string $directory, int $expirationTimestamp): void{
		try{
			foreach(new DirectoryIterator($directory) as $file){
				if($file->isDot()){
					continue;
				}

				if($file->isDir() && !$file->isLink()){
					self::DeleteFilesOlderThanRecursive($file->getPathname(), $expirationTimestamp);

					$directoryContents = new FilesystemIterator($file->getPathname(), FilesystemIterator::SKIP_DOTS);
					if(!$directoryContents->valid()){
						try{
							rmdir($file->getPathname());
						}
						catch(\Safe\Exceptions\FilesystemException){
							// The directory may have been modified by another process.
						}
					}
				}
				elseif($file->getMTime() < $expirationTimestamp){
					try{
						@unlink($file->getPathname());
					}
					catch(\Safe\Exceptions\FilesystemException){
						// The file may have been removed by another process.
					}
				}
			}
		}
		catch(\Throwable){
			throw new Exceptions\TempDirectoryException('Couldn\'t delete files from temporary directory.');
		}
	}
}
