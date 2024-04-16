<?
use function Safe\session_unset;

session_start();

if($GLOBALS['User'] !== null){
	header('Location: /');
	exit();
}

$email = HttpInput::Str(SESSION, 'email');
$redirect = HttpInput::Str(SESSION, 'redirect') ?? HttpInput::Str(GET, 'redirect');

$exception = $_SESSION['exception'] ?? null;
$passwordRequired = false;

http_response_code(401);

if($exception){
	if($exception instanceof Exceptions\PasswordRequiredException){
		// This login requires a password to proceed.
		// Prompt the user for a password.
		http_response_code(401);
		$passwordRequired = true;
		$exception = null; // Clear the exception so we don't show an error
	}
	else{
		http_response_code(422);
	}
	session_unset();
}
?><?= Template::Header(['title' => 'Log In', 'highlight' => '', 'description' => 'Log in to your Standard Ebooks Patrons Circle account.']) ?>
<main>
	<section class="narrow">
		<h1>Log in</h1>
		<?= Template::Error(['exception' => $exception]) ?>
		<? if(!$passwordRequired){ ?>
			<p>Enter your email address to log in to Standard Ebooks. Once you’re logged in, your Patrons Circle benefits (like <a href="/polls">voting in our occasional polls</a> and access to our <a href="/bulk-downloads">bulk ebook downloads</a> and <a href="/feeds">ebook feeds</a>) will be available to you.</p>
			<p>Anyone can <a href="/donate#patrons-circle">join the Patrons Circle</a> with a small donation in support of our continuing mission to create free, beautiful digital literature.</p>
			<p><strong>Important:</strong> When making your donation, you must have selected either “List my name publicly” or “Don’t list publicly, but reveal to project” on the donation form; otherwise, your email address isn’t shared with us, and we can’t include you in our login system.</p>
		<? } ?>
		<form method="post" action="/sessions" class="single-row">
			<input type="hidden" name="redirect" value="<?= Formatter::EscapeHtml($redirect) ?>" />
			<? if($passwordRequired){ ?>
				<input type="hidden" name="email" value="<?= Formatter::EscapeHtml($email) ?>" maxlength="80" required="required" />
				<label>
					<span>Your password</span>
					<span>Logging in as <?= Formatter::EscapeHtml($email) ?>.</span>
					<input type="password" name="password" value="" required="required" />
				</label>
			<? }else{ ?>
				<label>Your email address
					<input type="email" name="email" value="<?= Formatter::EscapeHtml($email) ?>" maxlength="80" required="required" />
				</label>
			<? } ?>
			<button>Log in</button>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
