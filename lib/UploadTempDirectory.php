<?
use function Safe\mkdir;
use function Safe\rename;
use function Safe\rmdir;
use function Safe\session_id;
use function Safe\tempnam;
use function Safe\unlink;

final class UploadTempDirectory{
	/**
	 * Create a token for a temporary upload path to for use in submission forms.
	 *
	 * @throws Exceptions\TempDirectoryException If the upload path is invalid.
	 */
	public static function CreateToken(string $uploadPath, string $secret): string{
		try{
			if(dirname($uploadPath) !== self::GetPath() || !is_file($uploadPath)){
				throw new Exceptions\TempDirectoryException('Temporary path is invalid.');
			}

			$payload = basename($uploadPath) . '|' . (NOW->getTimestamp() + 21600);
			$signature = hash_hmac('sha256', $payload . '|' . session_id(), $secret);

			return $payload . '|' . $signature;
		}
		catch(\Safe\Exceptions\SessionException){
			throw new Exceptions\TempDirectoryException('Couldn\'t create temporary token.');
		}
	}

	/**
	 * Validate a session-bound temporary upload token and return its full path.
	 *
	 * @throws Exceptions\TempDirectoryException If the token is invalid, expired, or refers to a missing upload.
	 */
	private static function GetUploadPathFromToken(string $token, string $secret): string{
		try{
			$parts = explode('|', $token);
			if(sizeof($parts) != 3 || !ctype_digit($parts[1])){
				throw new Exceptions\TempDirectoryException('Temporary token is invalid.');
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
				throw new Exceptions\TempDirectoryException('Temporary token is invalid.');
			}

			$uploadPath = self::GetPath() . '/' . $filename;
			if(!is_file($uploadPath)){
				throw new Exceptions\TempDirectoryException('Temporary file doesn\'t exist.');
			}

			return $uploadPath;
		}
		catch(\Safe\Exceptions\SessionException){
			throw new Exceptions\TempDirectoryException('Couldn\'t validate temporary token.');
		}
	}

	/**
	 * Return the staged upload path represented by a token, or null if no token was provided.
	 *
	 * @throws Exceptions\FileUploadInvalidException If the token secret is missing or the token is invalid.
	 */
	public static function GetStagedUploadPath(?string $stagedUploadToken, ?string $uploadTokenSecret): ?string{
		if($stagedUploadToken === null){
			return null;
		}

		if($uploadTokenSecret === null){
			throw new Exceptions\FileUploadInvalidException('Please re-upload the file.');
		}

		try{
			return self::GetUploadPathFromToken($stagedUploadToken, $uploadTokenSecret);
		}
		catch(Exceptions\TempDirectoryException){
			throw new Exceptions\FileUploadInvalidException('Please re-upload the file.');
		}
	}

	/**
	 * Move the specified upload to the temporary upload directory, and return its new path.
	 *
	 * @throws Exceptions\TempDirectoryException If the temporary file couldn't be created.
	 */
	public static function AddUpload(string $uploadPath): string{
		try{
			$tempName = tempnam(self::GetPath(), 'file-');

			rename($uploadPath, $tempName);

			return $tempName;
		}
		catch(\Throwable $ex){
			if($ex instanceof Exceptions\TempDirectoryException){
				throw $ex;
			}

			throw new Exceptions\TempDirectoryException('Couldn\'t create temporary file.');
		}
	}

	/**
	 * Remove a temporary upload file.
	 */
	public static function RemoveUpload(?string $uploadPath): void{
		if($uploadPath === null){
			return;
		}

		try{
			if(dirname($uploadPath) === self::GetPath() && is_file($uploadPath)){
				@unlink($uploadPath);
			}
		}
		catch(\Exception){
			// Pass.
		}
	}

	/**
	 * Replace an old upload with a new upload in the cache.
	 *
	 * @return ?string The path of the new upload in the cache, or `null` if `$newUploadPath` is `null`.
	 *
	 * @throws Exceptions\FileUploadInvalidException If an error occurs.
	 */
	public static function ReplaceUpload(?string $oldUploadPath, ?string $newUploadPath): ?string{
		if($newUploadPath === null && $oldUploadPath !== null){
			return $oldUploadPath;
		}

		if($newUploadPath === null){
			return null;
		}

		try{
			UploadTempDirectory::RemoveUpload($oldUploadPath);

			$oldUploadPath = UploadTempDirectory::AddUpload($newUploadPath);
		}
		catch(Exceptions\TempDirectoryException){
			throw new Exceptions\FileUploadInvalidException('Failed to save uploaded file.');
		}

		return $oldUploadPath;
	}

	/**
	 * Return the cache directory path, creating the directory if necessary.
	 *
	 * @return string The full path to the cache temporary directory, without a trailing slash.
	 *
	 * @throws Exceptions\TempDirectoryException If the temporary directory couldn't be created.
	 */
	public static function GetPath(): string{
		$tempDirectory = sys_get_temp_dir() . (PHP_SAPI == 'cli' ? '/se-cli' : '/se-fpm');

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
	 * Delete temporary cache files older than the specified age and remove empty staging directories.
	 *
	 * @throws Exceptions\TempDirectoryException If deleting files failed.
	 */
	public static function DeleteFilesOlderThan(\DateInterval $maximumAge): void{
		$expirationTimestamp = NOW->sub($maximumAge)->getTimestamp();
		self::DeleteFilesOlderThanRecursive(self::GetPath(), $expirationTimestamp);
	}

	/**
	 * Delete expired files recursively from a temporary cache directory.
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
