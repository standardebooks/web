<?

if(!$exception){
	return;
}

$exceptions = [];

if($exception instanceof Exceptions\ValidationException){
	$exceptions = $exception->Exceptions;
}
else{
	$exceptions[] = $exception;
}
?>
<ul class="message error">
	<? foreach($exceptions as $ex){ ?>
	<li>
		<p><? $message = $ex->getMessage(); if($message == ''){ $message = 'An error occurred.'; } ?><?= str_replace('CAPTCHA', '<abbr class="acronym">CAPTCHA</abbr>', Formatter::ToPlainText($message)) ?></p>
	</li>
	<? } ?>
</ul>
