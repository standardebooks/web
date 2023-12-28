<?
use function Safe\substr;

abstract class PropertiesBase{
	/**
	* @return mixed
	*/
	public function __get(string $var){
		$function = 'Get' . $var;
		$privateVar = '_' . $var;

		if(method_exists($this, $function)){
			return $this->$function();
		}
		elseif(property_exists($this, $var . 'Id') && property_exists($this, $privateVar) && method_exists($var, 'Get')){
			// If we're asking for a private `_Var` property,
			// and we have a public `VarId` property,
			// and the `Var` class also has a `Var::Get` method,
			// call that method and return the result.
			if($this->$privateVar === null && $this->{$var . 'Id'} !== null){
				$this->$privateVar = $var::Get($this->{$var . 'Id'});
			}

			return $this->$privateVar;
		}
		elseif(property_exists($this, $privateVar)){
			return $this->{$privateVar};
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
		$privateVar = '_' . $var;

		if(method_exists($this, $function)){
			$this->$function($val);
		}
		elseif(property_exists($this, $privateVar)){
			$this->$privateVar = $val;
		}
		else{
			$this->$var = $val;
		}
	}
}
