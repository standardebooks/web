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
		require('/standardebooks.org/web/www/ebook-placeholders/post.php');
		exit();
	}

	// POSTing published `Ebooks` is not supported.
	Template::Emit404();
}
catch(Exceptions\EbookNotFoundException){
	Template::Emit404();
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::Emit403();
}

