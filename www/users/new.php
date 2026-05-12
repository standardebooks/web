<?
/**
 * GET		/users/new
 */

use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanCreateUsers){
		throw new Exceptions\PermissionsInvalidException();
	}

	$exception = Http::$Request->Session->Get('exception', Exceptions\AppException::class);
	$user = Http::$Request->Session->Get('user', User::class) ?? new User();
	$passwordAction = Http::$Request->Session->Get('password-action', Enums\PasswordActionType::class) ?? Enums\PasswordActionType::Edit;

	if($exception){
		// We got here because an operation had errors and the user has to try again.
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
	}
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden); // No permissions to create `User`s.
}
?>
<?= Template::Header(
		title: 'Create a User',
		description: 'Create a new user in the Standard Ebooks system.',
		css: ['/css/user.css']
) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
			<a href="/users">Users</a> →
		</nav>
		<h1>Create a User</h1>

		<?= Template::Error(exception: $exception) ?>

		<form method="<?= Enums\HttpMethod::Post->value ?>" action="/users" autocomplete="off">
			<?= Template::UserForm(user: $user, passwordAction: $passwordAction) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
