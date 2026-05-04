<?
$donationDrive = DonationDrive::GetByIsActive();
if($donationDrive === null){
	$donationDrive = DonationCounter::GetByIsActive();
}

if(
	($autoHide ?? (HttpInput::Bool(COOKIE, 'hide-donation-alert') ?? false)) // If the user has hidden the box.
	||
	Session::$User !== null // If a user is logged in.
	||
	$donationDrive === null // There is no donation drive running right now.
){
	return;
}

$autoHide ??= true;
$showDonateButton ??= true;

$deadline = $donationDrive->End->setTimezone(SITE_TZ)->format('F j');
$timeLeft = NOW->diff($donationDrive->End);
$timeString = '';
$stretchStartingPosition = 0;

if($timeLeft->days < 1 && $timeLeft->h < 20){
	$timeString = 'Just hours';
}
elseif($timeLeft->days >= 1 && $timeLeft->h <= 12){
	$timeString = $timeLeft->days . ' day';
	if($timeLeft->days > 1){
		$timeString .= 's';
	}
	else{
		$timeString = 'Only ' . $timeString;
	}
}
else{
	$timeString = ($timeLeft->days + 1) . ' day';
	if($timeLeft->days + 1 > 1){
		$timeString .= 's';
	}
	else{
		$timeString = 'Only ' . $timeString;
	}
}

if($donationDrive instanceof DonationDrive && $donationDrive->IsStretchEnabled){
	$stretchStartingPosition = round( ($donationDrive->Target / $donationDrive->CurrentTarget) * 100, 0, PHP_ROUND_HALF_DOWN);
}

if($donationDrive instanceof DonationCounter){
	$digits = str_split(str_pad((string)$donationDrive->Count, 3, "0", STR_PAD_LEFT));
}
?>
<aside class="donation closable<? if($donationDrive instanceof DonationCounter){ ?> counter<? } ?>">
	<? if($autoHide){ ?>
		<form action="/settings" method="<?= Enums\HttpMethod::Post->value ?>">
			<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Patch->value ?>" />
			<input type="hidden" name="hide-donation-alert" value="true" />
			<button class="close" title="Close this box">Close this box</button>
		</form>
	<? } ?>
	<? if($donationDrive instanceof DonationDrive){ ?>
		<? if(!$donationDrive->IsStretchEnabled){ ?>
			<header>
				<? if($timeLeft->days > 5){ ?>
					<p>Help us reach <?= number_format($donationDrive->CurrentTarget) ?> new patrons by <?= $deadline ?></p>
				<? }else{ ?>
					<p><?= $timeString ?> left to help us reach <?= number_format($donationDrive->CurrentTarget) ?> new patrons!</p>
				<? } ?>
			</header>
		<? }else{ ?>
			<header>
				<p><? if($timeLeft->days <= 1){ ?><?= $timeString ?> left—<br/>Help us meet our stretch goal of<br/> <?= number_format($donationDrive->CurrentTarget) ?> new patrons<? }else{ ?>Help us meet our stretch goal of<br/> <?= number_format($donationDrive->CurrentTarget) ?> new patrons by <?= $deadline ?><? } ?></p>
			</header>
		<? } ?>
		<div class="progress"<? if($donationDrive->IsStretchEnabled){ ?> style="--stretch-starting-position: <?= $stretchStartingPosition ?>%; --stretch-base-counter-position: <?= $stretchStartingPosition - 1 ?>%"<? } ?>>
			<div aria-hidden="true">
				<p class="start">0</p>
				<p><?= number_format($donationDrive->Count) ?>/<?= number_format($donationDrive->CurrentTarget) ?></p>
				<? if($donationDrive->IsStretchEnabled){ ?>
					<p class="stretch-base"><?= number_format($donationDrive->Target) ?></p>
				<? } ?>
				<p class="target"><?= number_format($donationDrive->CurrentTarget) ?></p>
			</div>
			<progress max="<?= $donationDrive->CurrentTarget ?>" value="<?= $donationDrive->Count - $donationDrive->StretchCount ?>"></progress>
			<? if($donationDrive->IsStretchEnabled){ ?>
				<progress class="stretch" max="<?= $donationDrive->StretchTarget ?>" value="<?= $donationDrive->StretchCount ?>"></progress>
			<? } ?>
		</div>
		<? if($donationDrive->IsStretchEnabled){ ?>
			<p>When we started this drive, we set a goal of <?= number_format($donationDrive->Target) ?> Patrons Circle members by <?= $deadline ?>. Thanks to the incredible generosity of literature lovers like you, we hit that goal faster than we hoped!</p>
			<p>Since there’s still some time left in our drive, we thought we’d challenge our readers to help us reach our stretch goal of <?= number_format($donationDrive->CurrentTarget) ?> patrons, so that we can continue on a rock-solid financial footing. Will you help us with a donation, and support free and unrestricted digital literature?</p>
		<? }else{ ?>
			<p>It takes a huge amount of resources and highly-skilled work to create and distribute each of our free ebooks, and we need your support to keep it up. That’s why we want to welcome <?= number_format($donationDrive->CurrentTarget) ?> new patrons by <?= $deadline ?>. It’s our patrons who keep us on the stable financial footing we need to continue producing and giving away beautiful ebooks.</p>
			<p>Will you become a patron, and support free and unrestricted digital literature?</p>
		<? } ?>
		<? if($showDonateButton){ ?>
			<p class="donate-button">
				<a class="button" href="/donate#patrons-circle">Join the patrons circle</a>
			</p>
		<? } ?>
	<? } ?>
	<? if($donationDrive instanceof DonationCounter){ ?>
		<header>
			<p><?= $timeString ?> left to help us win a <?= Formatter::FormatCurrency($donationDrive->MatchAmount, true) ?> grant</p>
		</header>
		<div class="flipboard">
			We have <span class="digits"><? foreach($digits as $digit){ ?><span><?= $digit ?></span><? } ?></span>
			entries
		</div>
		<p>Our fiscal sponsor, <a href="https://www.fracturedatlas.org/">Fractured Atlas</a>, is running their annual Spring Match campaign, in which they <? if($donationDrive->ExternalUrl !== null){ ?><a href="<?= Formatter::EscapeHtml($donationDrive->ExternalUrl) ?>"><? } ?>award <?= Formatter::FormatCurrency($donationDrive->MatchAmount, true) ?> to twenty different projects<? if($donationDrive->ExternalUrl !== null){ ?></a><? } ?>.</p>
		<p>The winners of this award are determined by a drawing, and through <?= $deadline ?> <strong>each one-time donation of any amount to Standard Ebooks will give us one entry in the drawing.</strong> The more one-time donations we get, the more chances we have to win <?= Formatter::FormatCurrency($donationDrive->MatchAmount, true) ?>!</p>
		<p>Will you help us with a one-time donation, in any amount? <strong>This is a great time to <a href="/donate#patrons-circle">join our Patrons Circle</a> with a donation of <?= Formatter::FormatCurrency(PATRONS_CIRCLE_YEARLY_COST, true) ?>.</strong> Not only will your donation support us directly, but it’ll give us one more entry in this big giveaway.</p>
		<p>Will you show your support for free, beautiful digital literature?</p>
		<? if($showDonateButton){ ?>
			<p class="donate-button">
				<a class="button" href="/donate">Make a one-time donation!</a>
			</p>
		<? } ?>
	<? } ?>
</aside>
