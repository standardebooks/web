<?
/**
 * @var Subscription $subscription
 * @var int $ebookCount
 */

$isPatron = $subscription->HasActivePatron;
?>
<?= Template::EmailHeader() ?>
<h1>Your Yearly Donation Will Recur Soon</h1>
<p>Hi there,</p>
<p>It’s been almost a year since you started a yearly donation for Standard Ebooks. I hope you’ve enjoyed<? if($isPatron){ ?> your Patrons Circle membership, and<?} ?> the <?= number_format($ebookCount) ?> new free ebooks we’ve released in that time!</p>
<p><strong><? if($subscription->NextPaymentAt !== null){ ?>On <?= $subscription->NextPaymentAt->format(Enums\DateTimeFormat::DateWithDayOfWeek->value) ?><? }else{ ?>In a few days<? } ?> you’ll be automatically charged <?= Formatter::FormatCurrency($subscription->Amount) ?> for another year’s donation</strong>, which will go towards continuing our mission of producing beautiful ebooks of the world’s finest literature, to distribute for free.<? if($isPatron){ ?> Your Patrons Circle membership will continue uninterrupted.<? } ?></p>
<p>If you’d rather not be automatically charged for another year’s donation, simply <a href="<?= SITE_URL ?><?= $subscription->DeleteUrl ?>">follow this link to cancel your automatic recurring payment</a><? if($subscription->NextPaymentAt !== null){ ?> before <?= $subscription->NextPaymentAt->format(Enums\DateTimeFormat::DateWithDayOfWeek->value) ?><? } ?>.</p>
<p>Here’s to another great year of free ebooks!</p>
<div class="signature">
	<p><img width="125" src="<?= SITE_URL ?>/images/alex-cabal-signature.png" alt="" /></p>
	<p>Alex Cabal</p>
	<p>S.E. Editor-in-Chief</p>
</div>
<?= Template::EmailFooter() ?>
