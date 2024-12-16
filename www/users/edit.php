<?
use function Safe\session_unset;

session_start();

$exception = HttpInput::SessionObject('exception', Exceptions\AppException::class);
$user = HttpInput::SessionObject('user', User::class);
$generateNewUuid = HttpInput::Bool(SESSION, 'generate-new-uuid') ?? false;
$passwordAction = HttpInput::SessionObject('password-action', Enums\PasswordActionType::class) ?? Enums\PasswordActionType::None;

try{
	if($user === null){
		$user = User::GetByIdentifier(HttpInput::Str(GET, 'user-identifier'));
	}

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanReviewOwnArtwork){
		throw new Exceptions\InvalidPermissionsException();
	}

	// We got here because a `User` update had errors and the user has to try again.
	if($exception){
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
	}
}
catch(Exceptions\UserNotFoundException){
	Template::Emit404();
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::Emit403(); // No permissions to edit artwork.
}
?>
<?= Template::Header(
	[
		'title' => 'Edit user #' . $user->UserId,
		'canonicalUrl' => $user->Url . '/edit',
		'css' => ['/css/user.css'],
		'highlight' => ''
	]
) ?>
<main>
	<section class="narrow">
		<h1>Edit <?= Formatter::EscapeHtml($user->DisplayName) ?></h1>

		<?= Template::Error(['exception' => $exception]) ?>

		<form class="create-update-artwork" method="<?= Enums\HttpMethod::Post->value ?>" action="<?= $user->Url ?>" autocomplete="off">
			<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Patch->value ?>" />
			<?= Template::UserForm(['user' => $user, 'isEditForm' => true, 'generateNewUuid' => $generateNewUuid, 'passwordAction' => $passwordAction]) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
