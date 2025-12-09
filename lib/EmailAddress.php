<?
class EmailAddress{
	private string $_Value;

	public function __construct(string $value = ''){
		$this->_Value = trim($value);
	}

	public function __toString(): string{
		return $this->_Value;
	}

	/**
	 * @throws Exceptions\InvalidEmailAddressException If the email address is invalid.
	 */
	public function Validate(): void{
		if(filter_var($this->_Value, FILTER_VALIDATE_EMAIL) === false){
			throw new Exceptions\InvalidEmailAddressException();
		}
	}
}
