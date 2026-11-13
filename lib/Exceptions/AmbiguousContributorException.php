<?
namespace Exceptions;

/** An identifier matches more than one contributor. */
class AmbiguousContributorException extends AppException{
	/** @var array<\Contributor> */
	public array $Contributors;

	/**
	 * Store the contributors matching the ambiguous identifier.
	 *
	 * @param array<\Contributor> $contributors
	 */
	public function __construct(array $contributors){
		$this->Contributors = $contributors;

		parent::__construct('More than one contributor matches that identifier.');
	}
}
