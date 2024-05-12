<?
try{
	// We may use GET if we're called from an unsubscribe link in an email
	HttpInput::ValidateRequestMethod([HttpMethod::Get, HttpMethod::Delete]);

	$requestType = HttpInput::RequestType();

	$subscription = NewsletterSubscription::Get(HttpInput::Str(GET, 'uuid'));
	$subscription->Delete();

	if($requestType == HttpRequestType::Rest){
		exit();
	}
}
catch(Exceptions\NewsletterSubscriptionNotFoundException){
	if($requestType == HttpRequestType::Web){
		Template::Emit404();
	}
	else{
		http_response_code(404);
		exit();
	}
}

?><?= Template::Header(['title' => 'You’ve unsubscribed from the Standard Ebooks newsletter', 'highlight' => 'newsletter', 'description' => 'You’ve unsubscribed from the Standard Ebooks newsletter.']) ?>
<main>
	<article>
		<h1>You’ve been unsubscribed</h1>
		<p>You’ll no longer receive Standard Ebooks email newsletters. Sorry to see you go!</p>
	</article>
</main>
<?= Template::Footer() ?>
