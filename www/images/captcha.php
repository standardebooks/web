<?
use function Safe\session_start;
use Gregwar\Captcha\PhraseBuilder;
use Gregwar\Captcha\CaptchaBuilder;

session_start();

header('content-type: image/jpeg');

// Generate an image between 5-7 letters inclusive, excluding confusable letters like `O` and `0`.
$phraseBuilder = new PhraseBuilder(random_int(5, 7), 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789');

$builder = new CaptchaBuilder(null, $phraseBuilder);
$builder->build(CAPTCHA_IMAGE_WIDTH, CAPTCHA_IMAGE_HEIGHT);

$_SESSION['captcha'] = $builder->getPhrase();

$builder->output();
