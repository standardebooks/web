<?
namespace Exceptions;

class ValidationException extends AppException{
	/** @var array<\Exception> $Exceptions */
	public array $Exceptions = [];
	public bool $HasExceptions = false;
	public bool $IsFatal = false;

	public function __toString(): string{
		$output = '';

		foreach($this->Exceptions as $exception){
			$output .= $exception->getMessage() . "\n";
		}

		return rtrim($output);
	}

	public function Add(\Exception $exception, bool $isFatal = false): void{
		/** @var ValidationException $exception */
		if($exception instanceof static){
			foreach($exception->Exceptions as $childException){
				$this->Add($childException);
			}
		}
		else{
			$this->Exceptions[] = $exception;
		}

		// Don't set $this->IsFatal directly, so that a non-fatal exception
		// added later won't overwrite the fatality of a previous exception.
		if($isFatal){
			$this->IsFatal = true;
		}

		$this->HasExceptions = true;
	}

	public function Has(string $exception): bool{
		foreach($this->Exceptions as $childException){
			if(is_a($childException, $exception)){
				return true;
			}
		}

		return false;
	}
}
