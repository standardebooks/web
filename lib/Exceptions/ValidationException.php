<?
namespace Exceptions;

class ValidationException extends AppException{
	/** @var array<\Exception> $Exceptions */
	public array $Exceptions = [];
	public bool $HasExceptions = false;
	public bool $IsFatal = false;

	public function __toString(): string{
		if($this->message != ''){
			return $this->message;
		}

		$output = '';

		foreach($this->Exceptions as $exception){
			$output .= $exception->getMessage() . "\n";
		}

		return rtrim($output);
	}

	public function Add(\Exception $exception, bool $isFatal = false): void{
		if(is_a($exception, self::class)){
			foreach($exception->Exceptions as $childException){
				$this->Add($childException);
			}
		}
		else{
			$this->Exceptions[] = $exception;
		}

		// Don't set `$this->IsFatal` directly, so that a non-fatal exception added later won't overwrite the fatality of a previous exception.
		if($isFatal){
			$this->IsFatal = true;
		}

		$this->HasExceptions = true;
	}

	/**
	 * @param string|array<string> $exception
	 *
	 * @return bool **`TRUE`** if this `ValidationException` contains *any* of the passed `Exception`s.
	 */
	public function Has(string|array $exception): bool{
		if(!is_array($exception)){
			$exception = [$exception];
		}

		foreach($exception as $ex){
			foreach($this->Exceptions as $childException){
				if(is_a($childException, $ex)){
					return true;
				}
			}
		}

		return false;
	}

	public function Remove(string $exception): void{
		$newExceptions = [];

		foreach($this->Exceptions as $childException){
			if(!is_a($childException, $exception)){
				$newExceptions[] = $childException;
			}
		}

		$this->Exceptions = $newExceptions;
	}
}
