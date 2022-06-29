<?
use function Safe\substr;

abstract class PropertiesBase{
	/**
	* @param mixed $var
	* @return mixed
	*/
	public function __get($var){
		$function = 'Get' . $var;

		if(method_exists($this, $function)){
			return $this->$function();
		}
		elseif(property_exists($this, $var . 'Id') && method_exists($var, 'Get')){
			// If our object has an VarId attribute, and the Var class also has a ::Get method,
			// call it and return the result
			if($this->$var === null && $this->{$var . 'Id'} !== null){
				$this->$var = $var::Get($this->{$var . 'Id'});
			}

			return $this->$var;
		}
		elseif(substr($var, 0, 7) == 'Display'){
			// If we're asked for a DisplayXXX property and the getter doesn't exist, format as escaped HTML.
			if($this->$var === null){
				$target = substr($var, 7, strlen($var));
				$this->$var = Formatter::ToPlainText($this->$target);
			}

			return $this->$var;
		}
		else{
			return $this->$var;
		}
	}

	/**
	* @param mixed $val
	* @return mixed
	*/
	public function __set(string $var, $val){
		$function = 'Set' . $var;
		if(method_exists($this, $function)){
			$this->$function($val);
		}
		else{
			$this->$var = $val;
		}
	}
}
