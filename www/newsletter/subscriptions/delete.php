<?
try{
	// We may use GET if we're called from an unsubscribe link in an email
	HttpInput::ValidateRequestMethod([Enums\HttpMethod::Get, Enums\HttpMethod::Delete]);

	$requestType = HttpInput::GetRequestType();

	$subscription = NewsletterSubscription::Get(HttpInput::Str(GET, 'uuid'));
	$subscription->Delete();

	if($requestType == Enums\HttpRequestType::Rest){
		exit();
	}
}
catch(Exceptions\NewsletterSubscriptionNotFoundException){
	if($requestType == Enums\HttpRequestType::Web){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
	else{
		http_response_code(Enums\HttpCode::NotFound->value);
		exit();
	}
}

?><?= Template::Header(title: 'You’ve Been Unsubscribed', highlight: 'newsletter', description: 'You’ve unsubscribed from the Standard Ebooks newsletter.') ?>
<main>
	<article>
		<h1>You’ve Been Unsubscribed</h1>
		<p>You’ll no longer receive Standard Ebooks email newsletters. Sorry to see you go!</p>
	</article>
</main>
<?= Template::Footer() ?>
