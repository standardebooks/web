<?
// We use a 'succes' page and don't redirect directly to the vote ID resource, because
// we don't want to reveal the vote ID to the web browser. It should only be sent via email
// confirmation link.

use function \Safe\session_unset;

session_start();

$created = false;

if(isset($_SESSION['is-subscription-created'])){
	$created = true;
	session_unset();
}

if($created){
	// HTTP 201 Created
	http_response_code(201);
}

?><?= Template::Header(['title' => 'Your subscription to the Standard Ebooks newsletter', 'highlight' => 'newsletter', 'description' => 'Your subscription to the Standard Ebooks newsletter.']) ?>
<main>
	<section class="narrow">
		<h1>Almost done!</h1>
		<p>Please check your email inbox for a confirmation email containing a link to finalize your subscription to our newsletter.</p>
		<p>Your subscription won’t be activated until you click that link—this helps us prevent spam. Thank you!</p>
	</section>
</main>
<?= Template::Footer() ?>
