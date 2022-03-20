<?
require_once('Core.php');

try{
	$subscriber = NewsletterSubscriber::Get(HttpInput::Str(GET, 'uuid') ?? '');
	$subscriber->Confirm();
}
catch(Exceptions\InvalidNewsletterSubscriberException $ex){
	http_response_code(404);
	include(WEB_ROOT . '/404.php');
	exit();
}
?><?= Template::Header(['title' => 'Your subscription to the Standard Ebooks newsletter has been confirmed', 'highlight' => 'newsletter', 'description' => 'Your subscription to the Standard Ebooks newsletter has been confirmed.']) ?>
<main>
	<article>
		<h1>Your subscription is confirmed!</h1>
		<p>Thank you! You’ll now receive Standard Ebooks email newsletters.</p>
		<p>To unsubscribe, simply follow the link at the bottom of any of our newsletters, or <a href="<?= $subscriber->Url ?>/delete">click here</a>.</p>
	</article>
</main>
<?= Template::Footer() ?>
