<?
/**
 * @var Subscription $subscription
 * @var int $ebookCount
 */

$isPatron = $subscription->HasActivePatron;
?>
# Your Yearly Donation Will Recur Soon

Hi there,

It’s been almost a year since you started a yearly donation for Standard Ebooks. I hope you’ve enjoyed<? if($isPatron){ ?> your Patrons Circle membership, and<? } ?> the <?= number_format($ebookCount) ?> new free ebooks we’ve released in that time!

**<? if($subscription->NextPaymentAt !== null){ ?>On <?= $subscription->NextPaymentAt->format(Enums\DateTimeFormat::DateWithDayOfWeek->value) ?><? }else{ ?>In a few days<? } ?> you’ll be automatically charged <?= Formatter::FormatCurrency($subscription->Amount) ?> for another year’s donation**, which will go towards continuing our mission of producing beautiful ebooks of the world’s finest literature, to distribute for free.<? if($isPatron){ ?> Your Patrons Circle membership will continue uninterrupted.<? } ?>

If you’d rather not be automatically charged for another year’s donation, simply [follow this link to cancel your automatic recurring payment](<?= SITE_URL ?><?= $subscription->DeleteUrl ?>)<? if($subscription->NextPaymentAt !== null){ ?> before <?= $subscription->NextPaymentAt->format(Enums\DateTimeFormat::DateWithDayOfWeek->value) ?><? } ?>.

Here’s to another great year of free ebooks!

Alex Cabal

S.E. Editor-in-Chief
