<?
class HttpFilesInterface{
	/** @var array<string, mixed> $Variables The request body parsed into a key/value array if the request was form data. */
	private array $Variables;

	/**
	 * @param array<string, array<string, string>> $variables Usually `$_FILES`.
	 */
	public function __construct(array $variables){
		$this->Variables = $variables;
	}

	/**
	 * Return the absolute path of the requested file upload, or `null` if there isn't one.
	 *
	 * @param string $variable The name of the variable to get.
	 *
	 * @throws Exceptions\FileUploadInvalidException If there is a file upload present, but the upload somehow failed.
	 * @throws Exceptions\FileUploadTooLargeException If there is a file upload present, but it was larger than the size allowed by PHP.
	 */
	public function Get(string $variable): ?string{
		$filePath = null;

		if(isset($this->Variables[$variable])){
			/** @var array{'error': int, 'size': int, 'tmp_name': string} $file */
			$file = $this->Variables[$variable];

			$error = $file['error'];
			if($error == UPLOAD_ERR_INI_SIZE || $error == UPLOAD_ERR_FORM_SIZE){
				throw new Exceptions\FileUploadTooLargeException();
			}
			elseif($error != UPLOAD_ERR_NO_FILE){
				$filePath = $file['tmp_name'];
				if($error != UPLOAD_ERR_OK || $file['size'] == 0 || !is_uploaded_file($filePath)){
					throw new Exceptions\FileUploadInvalidException();
				}
			}
		}

		return $filePath;
	}
}
