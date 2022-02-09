<?
use function Safe\substr;

abstract class PropertiesBase extends OrmBase{
	/**
	* @param mixed $var
	* @return mixed
	*/
	public function __get($var){
		$function = 'Get' . $var;

		if(method_exists($this, $function)){
			return $this->$function();
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
