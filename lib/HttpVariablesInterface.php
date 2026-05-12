<?
use Safe\DateTimeImmutable;

class HttpVariablesInterface{
	use Traits\GetHttpVariable;

	// public readonly stdClass $Json; // TODO: Implement as a property hook that throws an exception on invalid JSON.
	// public readonly DOMDocument $Dom; // TODO: Implement as a property hook that throws an exception on invalid XML/XHTML/HTML.

	/** The raw body of this source, e.g. a query string or a `POST` body. */
	public readonly string $Body;
	/** @var array<string, mixed> $Variables The request body parsed into a key/value array, if the request was form data. */
	private array $Variables;

	/**
	 * @param string $body A string representing the body, e.g. a query string, a request body, or a `cookie` HTTP header value.
	 * @param array<string, mixed> $variables An key/value array representing the parsed request body, e.g. `$_POST`.
	 */
	public function __construct(string $body, array $variables){
		$this->Body = $body;
		$this->Variables = $variables;
	}

	public function __toString(): string{
		return $this->Body;
	}

	/**
	 * Return a request variable converted to the requested scalar type, or an object matching the requested type. If the variable is not present *or* is present but empty (i.e. an empty string), return `null`.
	 *
	 * @template T of object
	 * @param string $variable
	 * @param 'array'|'bool'|'float'|'int'|'string'|'empty-string'|'date'|'DateTimeImmutable'|class-string<T>|array<'array'|'bool'|'float'|'int'|'string'|'empty-string'|'date'|'DateTimeImmutable'|class-string<T>> $type The type of value to return, or a list of acceptable types to check in order. The special type `empty-string` returns an empty string instead of `null` if the variable exists but is empty.
	 *
	 * @return ($type is 'array' ? array<string>|null : ($type is 'bool' ? bool|null : ($type is 'float' ? float|null : ($type is 'int' ? int|null : ($type is 'string' ? string|null : ($type is 'empty-string' ? string|null : ($type is 'date'|'DateTimeImmutable' ? DateTimeImmutable|null : ($type is class-string<T> ? T|null : mixed))))))))
	 */
	public function Get(string $variable, string|array $type = 'string'): mixed{
		return $this->GetHttpVariable($variable, $this->Variables, $type);
	}
}
