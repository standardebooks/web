<?
use function Safe\session_unset;

session_start();

if($GLOBALS['User'] !== null){
	header('Location: /');
	exit();
}

$email = HttpInput::Str(SESSION, 'email', false);
$redirect = HttpInput::Str(SESSION, 'redirect', false) ?? HttpInput::Str(GET, 'redirect', false);

$exception = $_SESSION['exception'] ?? null;

http_response_code(401);

if($exception){
	http_response_code(422);
	session_unset();
}
?><?= Template::Header(['title' => 'Log In', 'highlight' => '', 'description' => 'Log in to your Standard Ebooks Patrons Circle account.']) ?>
<main>
	<section class="narrow">
		<h1>Log in</h1>
		<p>Enter your email address to log in to Standard Ebooks. Once you’re logged in, your Patrons Circle benefits (like <a href="/polls">voting in our occasional polls</a> and access to our <a href="/bulk-downloads">bulk ebook downloads</a> and <a href="/feeds">ebook feeds</a>) will be available to you.</p>
		<p>Anyone can <a href="/donate#patrons-circle">join the Patrons Circle</a> with a small donation in support of our continuing mission to create free, beautiful digital literature.</p>
		<p><strong>Important:</strong> When making your donation, you must have selected either “List my name publicly” or “Don’t list publicly, but reveal to project” on the donation form; otherwise, your email address isn’t shared with us, and we can’t include you in our login system.</p>
		<?= Template::Error(['exception' => $exception]) ?>
		<form method="post" action="/sessions" class="single-row">
			<input type="hidden" name="redirect" value="<?= Formatter::ToPlainText($redirect) ?>" />
			<label class="email">Your email address
				<input type="email" name="email" value="<?= Formatter::ToPlainText($email) ?>" maxlength="80" required="required" />
			</label>
			<button>Log in</button>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
