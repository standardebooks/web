<?= Template::EmailHeader() ?>
<h1>Confirm your newsletter subscription</h1>
<p>Thank you for subscribing to the Standard Ebooks newsletter!</p>
<p>Please use the button below to confirm your newsletter subscription. You won’t receive our newsletters until you confirm your subscription.</p>
<p class="button-row">
	<a href="<?= $subscriber->Url ?>/confirm" class="button">Yes, subscribe me to the newsletter</a>
</p>
<p>If you didn’t subscribe to our newsletter, or you’re not sure why you received this email, you can safely delete it and you won’t receive any more email from us.</p>
<?= Template::EmailFooter() ?>
