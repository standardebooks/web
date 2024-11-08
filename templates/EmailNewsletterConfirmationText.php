<?
/**
 * @var bool $isSubscribedToNewsletter
 * @var bool $isSubscribedToSummary
 * @var NewsletterSubscription $subscription
 */
?>
# Confirm your newsletter subscription

Thank you for subscribing to the Standard Ebooks newsletter!

You subscribed to:

<? if($isSubscribedToNewsletter){ ?>- The occasional Standard Ebooks newsletter
<? } ?>
<? if($isSubscribedToSummary){ ?>- A monthly summary of new ebook releases
<? } ?>

Please use the link below to confirm your subscription—you won’t receive email from us until you do.

<<?= SITE_URL . $subscription->Url ?>/confirm>

If you didn’t subscribe, or you’re not sure why you received this email, you can safely delete it and you won’t receive any more email from us.

<?= Template::EmailFooterText() ?>
