<?= Template::EmailHeader() ?>
<h1>Confirm your newsletter subscription</h1>
<p>Thank you for subscribing to the Standard Ebooks newsletter!</p>
<p>You subscribed to:</p>
<ul>
	<? if($IsSubscribedToNewsletter){ ?><li><p>The occasional Standard Ebooks newsletter</p></li><? } ?>
	<? if($isSubscribedToSummary){ ?><li><p>A monthly summary of new ebook releases</p></li><? } ?>
</ul>
<p>Please use the button below to confirm your subscription—you won’t receive email from us until you do.</p>
<p class="button-row">
	<a href="<?= $subscriber->Url ?>/confirm" class="button">Yes, confirm my subscription</a>
</p>
<p>If you didn’t subscribe, or you’re not sure why you received this email, you can safely delete it and you won’t receive any more email from us.</p>
<?= Template::EmailFooter() ?>
