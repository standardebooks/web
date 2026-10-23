<?
/**
 * GET		/sessions/new
 */

use function Safe\session_start;
use function Safe\session_unset;

session_start();

if(Session::$User !== null){
	header('location: /');
	exit();
}

$email = Http::$Request->Session->Get('session/create/email');
$redirect = Http::$Request->Session->Get('session/create/redirect') ?? Http::$Request->QueryString->Get('redirect');
$codeUrl = '';

if($redirect !== null){
	$redirect = Template::SanitizeRedirectUrl($redirect);
}

$exception = Http::$Request->Session->Get('session/create/exception', \Exception::class);
$isCreated = Http::$Request->Session->Get('session/create/is-created', 'bool') ?? false;

// We can't output HTTP 401 because according to the spec, that code requires a `www-authenticate` header which doesn't make sense in this context.
// http_response_code(Enums\HttpCode::Unauthorized->value);

if($exception){
	http_response_code(Enums\HttpCode::UnprocessableContent->value);
	session_unset();
}
elseif($isCreated){
	unset($_SESSION['session/create/is-created']);

	if(SITE_STATUS == SITE_STATUS_DEV){
		$codeUrl = Http::$Request->Session->Get('session/create/code-url') ?? '';
		unset($_SESSION['session/create/code-url']);
	}
}
?><?= Template::Header(title: 'Log In', description: 'Log in to Standard Ebooks.') ?>
<main>
	<section class="narrow">
		<h1>Log In</h1>
		<? if($isCreated){ ?>
			<p class="message success">Email sent! Please check your email to log in.<? if($codeUrl != ''){ ?> <a href="<?= Formatter::EscapeHtml($codeUrl) ?>">Log in.</a><? } ?></p>
		<? } else{ ?>
			<?= Template::Error(exception: $exception) ?>
			<p>Enter your email address to receive a login link via email. Once you’re logged in, your Patrons Circle benefits (like <a href="/polls">voting in our occasional polls</a> and access to our <a href="/bulk-downloads">bulk ebook downloads</a> and <a href="/feeds">ebook feeds</a>) will be available to you.</p>
			<p>Anyone can <a href="/donate#patrons-circle">join the Patrons Circle</a> with a small donation in support of our continuing mission to create free, beautiful digital literature.</p>
			<p><strong>Important:</strong> If you made a donation via Fractured Atlas, you must have selected either “List my name publicly” or “Don’t list publicly, but reveal to project” on the donation form; otherwise, your email address isn’t shared with us, and we can’t include you in our login system.</p>
			<form method="<?= Enums\HttpMethod::Post->value ?>" action="/sessions" class="single-row">
				<input type="hidden" name="redirect" value="<?= Formatter::EscapeHtml($redirect) ?>" />
				<label>Your email address
					<input type="email" name="email" autocomplete="email" value="<?= Formatter::EscapeHtml($email) ?>" maxlength="80" required="required" />
				</label>
				<button>Send login link</button>
			</form>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
