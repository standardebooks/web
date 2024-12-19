<?

$ebook = null;

try{
	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditEbookPlaceholders){
		throw new Exceptions\InvalidPermissionsException();
	}

	$identifier = EBOOKS_IDENTIFIER_PREFIX .  trim(str_replace('.', '', HttpInput::Str(GET, 'url-path') ?? ''), '/');

	$ebook = Ebook::GetByIdentifier($identifier);

	if($ebook->IsPlaceholder()){
		require(WEB_ROOT . '/ebook-placeholders/delete.php');
		exit();
	}

	// Deleting published `Ebooks` is not supported.
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\EbookNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}

