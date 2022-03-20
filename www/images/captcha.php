<?
use function Safe\session_unset;

use Gregwar\Captcha\CaptchaBuilder;

require_once('Core.php');

session_start();

header('Content-type: image/jpeg');

$builder = new CaptchaBuilder;
$builder->build(CAPTCHA_IMAGE_WIDTH, CAPTCHA_IMAGE_HEIGHT);

$_SESSION['captcha'] = $builder->getPhrase();

$builder->output();
