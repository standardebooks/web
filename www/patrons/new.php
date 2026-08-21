<?
/**
 * GET		/users/:user-identifier/patrons/new
 */

use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	$identifier = Http::$Request->QueryString->Get('user-identifier');
	$user = User::GetByIdentifier($identifier);

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditUsers){
		throw new Exceptions\PermissionsInvalidException();
	}

	$exception = Http::$Request->Session->Get('patron/create/exception', Exceptions\AppException::class);
	$payment = Http::$Request->Session->Get('patron/create/payment', Payment::class) ?? new Payment();
	$patron = Http::$Request->Session->Get('patron/create/patron', Patron::class) ?? new Patron();

	$payment->CreatedAt ??= NOW;
	$payment->Processor ??= Enums\PaymentProcessorType::FracturedAtlas;

	if($exception){
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
	}
}
catch(Exceptions\AmbiguousUserException){
	Template::RedirectToDisambiguation($identifier);
}
catch(Exceptions\UserNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
?>
<?= Template::Header(
	title: 'Make Patron - ' . $user->DisplayName,
	canonicalUrl: $user->Url . '/patrons/new',
	css: ['/css/user.css', '/css/patrons.css']
) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
			<a href="/users">Users</a> → <a href="<?= $user->Url ?>"><?= Formatter::EscapeHtml($user->DisplayName) ?></a> →
		</nav>
		<h1>Make Patron</h1>

		<?= Template::Error(exception: $exception) ?>

		<form method="<?= Enums\HttpMethod::Post->value ?>" action="<?= $user->Url ?>/patrons" autocomplete="off">
			<fieldset>
				<legend>Payment</legend>
				<label class="icon year">
					<span>Created</span>
					<span>UTC time.</span>
					<input type="datetime-local" name="payment-created-at" required="required" value="<?= $payment->CreatedAt->setTimezone(SITE_TZ)->format(Enums\DateTimeFormat::Html->value) ?>" />
				</label>
				<label class="icon money">
					<span>Processor</span>
					<select name="payment-processor" required="required">
						<? foreach(Enums\PaymentProcessorType::cases() as $processor){ ?>
							<option value="<?= $processor->value ?>"<? if($payment->Processor == $processor){ ?> selected="selected"<? } ?>><?= Formatter::EscapeHtml(ucwords(str_replace('_', ' ', $processor->value))) ?></option>
						<? } ?>
					</select>
				</label>
				<label class="icon id">
					<span>Transaction ID</span>
					<input type="text" name="payment-transaction-id" required="required" value="<?= Formatter::EscapeHtml($payment->TransactionId ?? '') ?>" />
				</label>
				<label class="icon dollar">
					<span>Amount</span>
					<input type="text" class="money" name="payment-amount" inputmode="decimal" pattern="[0-9]+(?:\.[0-9]{1,2})?" required="required" value="<?= $payment->Amount ?? '0.00' ?>" />
				</label>
				<label class="icon dollar">
					<span>Fee</span>
					<input type="text" class="money" name="payment-fee" inputmode="decimal" pattern="[0-9]+(?:\.[0-9]{1,2})?" required="required" value="<?= $payment->Fee ?? '0.00' ?>" />
				</label>
				<label>
					<input type="hidden" name="payment-is-recurring" value="false" />
					<input type="checkbox" name="payment-is-recurring" value="true"<? if($payment->IsRecurring ?? false){ ?> checked="checked"<? } ?> />
					<span>Is recurring</span>
				</label>
			</fieldset>
			<fieldset>
				<legend>Patron</legend>
				<label class="icon user">
					<span>Alternate name</span>
					<input type="text" name="patron-alternate-name" value="<?= Formatter::EscapeHtml($patron->AlternateName) ?>" />
				</label>
				<label>
					<input type="hidden" name="patron-is-anonymous" value="false" />
					<input type="checkbox" name="patron-is-anonymous" value="true"<? if($patron->IsAnonymous ?? false){ ?> checked="checked"<? } ?> />
					<span>Is anonymous</span>
				</label>
			</fieldset>
			<div class="footer">
				<button>Make Patron</button>
			</div>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
