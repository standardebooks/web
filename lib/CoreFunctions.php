<?
// Auto-included by Composer in composer.json
// These functions are broken out of Core.php to satisfy PHPStan

use function Safe\ob_end_clean;
use function Safe\ob_start;

// Convenience alias of var_dump.
function vd(mixed $var): void{
	var_dump($var);
}

// var_dump($var) then die().
function vdd(mixed $var): void{
	var_dump($var);
	die();
}

// var_dump into a string.
function vds(mixed $var): string{
	ob_start();
	var_dump($var);
	$str = ob_get_contents();
	if($str === false){
		$str = '';
	}
	ob_end_clean();
	return $str;
}
