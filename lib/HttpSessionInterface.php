<?
use function Safe\session_start;

class HttpSessionInterface{
	use Traits\GetHttpVariable;

	/**
	 * Return a request variable converted to the requested scalar type, or an object matching the requested type. If the variable is not present *or* is present but empty (i.e. an empty string), return `null`.
	 *
	 * @template T of object
	 *
	 * @param string $variable The name of the variable to get.
	 * @param 'array'|'bool'|'float'|'int'|'string'|'empty-string'|'date'|'DateTimeImmutable'|class-string<T>|list<class-string<T>>|list<'array'|'bool'|'float'|'int'|'string'|'empty-string'|'date'|'DateTimeImmutable'> $type The type of value to return, or a list of acceptable types to check in order. The special type `empty-string` returns an empty string instead of `null` if the variable exists but is empty.
	 *
	 * @return ($type is 'array' ? array<string>|null : ($type is 'bool' ? bool|null : ($type is 'float' ? float|null : ($type is 'int' ? int|null : ($type is 'string' ? string|null : ($type is 'empty-string' ? string|null : ($type is 'date' ? \Safe\DateTimeImmutable|null : ($type is 'DateTimeImmutable' ? \Safe\DateTimeImmutable|null : ($type is class-string<T> ? T|null : ($type is list<class-string<T>> ? T|null : mixed))))))))))
	 */
	public function Get(string $variable, string|array $type = 'string'): mixed{
		if(session_status() === PHP_SESSION_NONE){
			session_start();
		}

		return $this->GetHttpVariable($variable, $_SESSION, $type);
	}
}
