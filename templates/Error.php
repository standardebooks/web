<?
/**
 * @var ?Exception $exception
 */

if($exception === null){
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
		<?
			$message = $ex->getMessage();
			if($message == ''){
				$message = 'An error occurred.';
			}

			if(!($ex instanceof Exceptions\AppException) || $ex->MessageType == Enums\ExceptionMessageType::Text){
				$message = '<p>' . str_replace('CAPTCHA', '<abbr class="acronym">CAPTCHA</abbr>', Formatter::EscapeHtml($message)) . '</p>';
			}
		?>
		<li>
			<?= $message ?>
		</li>
	<? } ?>
</ul>
