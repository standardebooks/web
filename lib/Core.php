<?
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
date_default_timezone_set('UTC');

require_once('Constants.php');

// Convenience alias of var_dump.
function vd($var){
	var_dump($var);
}

// var_dump($var) then die().
function vdd($var){
	var_dump($var);
	die();
}

// var_dump into a string.
function vds($var){
	ob_start();
	var_dump($var);
	$str = ob_get_contents();
	ob_end_clean();
	return $str;
}

spl_autoload_register(function($class){
	try{
		include_once($class . '.php');
	}
	catch(\Exception $ex){
	}
});

// Custom error handler to output more details about the specific Apache request that caused an exception.
set_exception_handler(function($ex){
	$errorString = "----------------------------------------\n";
	$errorString .= trim(vds(array_intersect_key($_SERVER, array('REQUEST_URI' => '', 'QUERY_STRING' => '', 'REQUEST_METHOD' => '', 'REDIRECT_QUERY_STRING' => '', 'REDIRECT_URL' => '', 'SCRIPT_FILENAME' => '', 'REMOTE_ADDR' => '', 'HTTP_COOKIE' => '', 'HTTP_USER_AGENT' => '', 'SCRIPT_URI' => ''))));

	if(isset($_POST) && sizeof($_POST) > 0){
		$errorString .= "POST DATA:\n";
		$errorString .= vds($_POST);
	}

	error_log($errorString);

	throw $ex; // Send the exception back to PHP for its usual logging routine.
});
?>
