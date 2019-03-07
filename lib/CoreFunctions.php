<?
// Auto-included by Composer in composer.json
// These functions are broken out of Core.php to satisfy PHPStan

use function Safe\ob_end_clean;

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
?>
