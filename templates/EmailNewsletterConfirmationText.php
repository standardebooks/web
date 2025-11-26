<?
/**
 * @var User $user
 */
?>
# Confirm your newsletter subscription

Thank you for subscribing to the Standard Ebooks newsletter!

You subscribed to:

<? foreach($user->NewsletterSubscriptions as $newsletterSubscription){ ?>
<? if(!$newsletterSubscription->IsConfirmed){ ?>
- <?= Formatter::EscapeHtml($newsletterSubscription->Newsletter->Name) ?>


<? } ?>
<? } ?>
Please follow the link below to confirm your subscription—you won’t receive email from us until you do.

<<?= SITE_URL ?>/users/<?= $user->Uuid ?>/newsletter-subscriptions/confirm>

If you didn’t subscribe, or you’re not sure why you received this email, you can safely delete it and you won’t receive any more email from us.

<?= Template::EmailFooterText() ?>
