<?
namespace Exceptions;

class MultiSelectMethodNotFoundException extends AppException{
	public function __construct(string $class = ''){
		if($class != ''){
			$this->message = 'Multi table select attempted, but class ' . $class . ' doesn\'t have a FromMultiTableRow() method.';
		}
		else{
			$this->message = 'Multi table select attempted, but the class doesn\'t have a FromMultiTableRow() method.';
		}

		parent::__construct();
	}
}
