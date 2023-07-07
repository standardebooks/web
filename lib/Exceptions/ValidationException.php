<?
namespace Exceptions;

class ValidationException extends SeException{
	public $Exceptions = [];
	public $HasExceptions = false;
	public $IsFatal = false;

	public function __toString(): string{
		$output = '';

		foreach($this->Exceptions as $exception){
			$output .= $exception->getMessage() . "\n";
		}

		return rtrim($output);
	}

	public function Add(\Exception $exception, bool $isFatal = false): void{
		if(is_a($exception, static::class)){
			foreach($exception->Exceptions as $childException){
				$this->Add($childException);
			}
		}
		else{
			$this->Exceptions[] = $exception;
		}

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

	public function Clear(): void{
		unset($this->Exceptions);
		$this->Exceptions = [];
		$this->HasExceptions = false;
	}
}
