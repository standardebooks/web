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
<ul class="error">
	<? foreach($exceptions as $ex){ ?>
	<li>
		<p><? $message = $ex->getMessage(); if($message == ''){ $message = 'An error occurred.'; } ?><?= Formatter::ToPlainText($message) ?></p>
	</li>
	<? } ?>
</ul>
