<?
try{
	$users = [];
	$identifier = HttpInput::Str(GET, 'user-identifier');

	try{
		$user = User::GetByIdentifier($identifier);

		http_response_code(Enums\HttpCode::Found->value);
		header('Location: ' . $user->Url);
		exit();
	}
	catch(Exceptions\AmbiguousUserException $ex){
		$users = $ex->Users;
	}

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditUsers){
		throw new Exceptions\InvalidPermissionsException();
	}
}
catch(Exceptions\UserNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}

http_response_code(Enums\HttpCode::MultipleChoices->value);
?><?= Template::Header(
	title: 'Disambiguation - ' . $users[0]->DisplayName,
) ?>
<main>
	<section class="narrow">
		<h1><?= Formatter::EscapeHtml($users[0]->DisplayName) ?></h1>

		<p>There are <?= number_format(sizeof($users)) ?> users with that name:</p>
		<ul>
			<? foreach($users as $user){ ?>
				<li><a href="<?= $user->Url ?>"><?= Formatter::EscapeHtml($user->Name) ?> (#<?= $user->UserId ?>), created <?= $user->Created->format(Enums\DateTimeFormat::ShortDate->value) ?></a></li>
			<? } ?>
		</ul>
	</section>
</main>
<?= Template::Footer() ?>
