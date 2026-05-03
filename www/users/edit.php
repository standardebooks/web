<?
/**
 * GET		/users/:user-identifier/edit
 */

use PhpParser\Node\Stmt\For_;

use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	$identifier = HttpInput::Str(GET, 'user-identifier');
	$originalUser = User::GetByIdentifier($identifier);

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditUsers){
		throw new Exceptions\InvalidPermissionsException();
	}

	$exception = HttpInput::SessionObject('exception', Exceptions\AppException::class);
	$user = HttpInput::SessionObject('user', User::class) ?? $originalUser;
	$generateNewUuid = HttpInput::Bool(SESSION, 'generate-new-uuid') ?? false;
	$passwordAction = HttpInput::SessionObject('password-action', Enums\PasswordActionType::class) ?? Enums\PasswordActionType::None;

	// We got here because an operation had errors and the user has to try again.
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
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden); // No permissions to edit artwork.
}
?>
<?= Template::Header(
	title: 'Edit ' . $originalUser->DisplayName,
	canonicalUrl: $originalUser->Url . '/edit',
	css: ['/css/user.css']
) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
			<a href="/users">Users</a> → <a href="<?= $originalUser->Url ?>"><?= Formatter::EscapeHtml($originalUser->DisplayName) ?></a> →
		</nav>
		<h1>Edit <?= Formatter::EscapeHtml($originalUser->DisplayName) ?></h1>

		<?= Template::Error(exception: $exception) ?>

		<form class="create-update-artwork" method="<?= Enums\HttpMethod::Post->value ?>" action="<?= $originalUser->Url ?>" autocomplete="off">
			<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Patch->value ?>" />
			<?= Template::UserForm(user: $user, isEditForm: true, generateNewUuid: $generateNewUuid, passwordAction: $passwordAction) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
