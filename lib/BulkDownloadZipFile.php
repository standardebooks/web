<?

use Safe\DateTimeImmutable;

/**
 * @property string $DownloadFileSizeFormatted
 */
class BulkDownloadZipFile{
	use Traits\Accessor;

	public Enums\BulkDownloadLabelType $LabelType;
	public string $LabelName;
	public Enums\BulkDownloadFormatType $Format;
	public string $DownloadUrl;
	public int $DownloadByteCount;

	protected string $_DownloadFileSizeFormatted;

	protected function GetDownloadFileSizeFormatted(): string{
		return $this->_DownloadFileSizeFormatted ??= Formatter::ToFileSize($this->DownloadByteCount);
	}

	/**
	 * @throws Exceptions\BulkDownloadZipFileInvalidException
	 */
	public function Validate(): void{
		$error = new Exceptions\BulkDownloadZipFileInvalidException();

		$this->LabelName = trim($this->LabelName ?? '');
		if($this->LabelName == ''){
			$error->Add(new Exceptions\BulkDownloadZipFileLabelNameRequiredException());
		}

		$this->DownloadUrl = trim($this->DownloadUrl ?? '');
		if($this->DownloadUrl == ''){
			$error->Add(new Exceptions\BulkDownloadZipFileDownloadUrlRequiredException());
		}

		if($this->DownloadByteCount <= 0){
			$error->Add(new Exceptions\BulkDownloadZipFileDownloadByteCountInvalidException('Invalid BulkDownloadZipFile DownloadByteCount: ' . $this->DownloadByteCount));
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * @throws Exceptions\BulkDownloadZipFileInvalidException
	 */
	public function Create(): void{
		$this->Validate();

		Db::Query('
			INSERT into BulkDownloadZipFiles (LabelType, LabelName, Format, DownloadUrl, DownloadByteCount)
			values (?,
				?,
				?,
				?,
				?)
			on duplicate key update
				DownloadUrl = value(DownloadUrl),
				DownloadByteCount = value(DownloadByteCount)
		', [$this->LabelType, $this->LabelName, $this->Format, $this->DownloadUrl, $this->DownloadByteCount]);
	}

}
