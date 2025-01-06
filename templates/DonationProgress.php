<?
$donationDrive = DonationDrive::GetByIsRunning();

if(
	!DONATION_DRIVES_ENABLED // Drives aren't enabled.
	||
	($autoHide ?? (HttpInput::Bool(COOKIE, 'hide-donation-alert') ?? false)) // If the user has hidden the box.
	||
	Session::$User !== null // If a user is logged in.
	||
	$donationDrive === null // There is no donation drive running right now.
){
	return;
}

$autoHide = $autoHide ?? true;
$showDonateButton = $showDonateButton ?? true;

$deadline = $donationDrive->End->format('F j');
$timeLeft = NOW->diff($donationDrive->End);
$timeString = '';
if($timeLeft->days < 1 && $timeLeft->h < 20){
	$timeString = 'Just hours';
}
elseif($timeLeft->days >=  1 && $timeLeft->h <= 12){
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

if($donationDrive->IsStretchEnabled){
	$stretchStartingPosition = round( ($donationDrive->BaseTargetDonationCount / $donationDrive->TargetDonationCount) * 100, 0, PHP_ROUND_HALF_DOWN);
}
?>
<aside class="donation closable">
	<? if($autoHide){ ?>
		<form action="/settings" method="<?= Enums\HttpMethod::Post->value ?>">
			<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Patch->value ?>" />
			<input type="hidden" name="hide-donation-alert" value="true" />
			<button class="close" title="Close this box">Close this box</button>
		</form>
	<? } ?>
	<? if(!$donationDrive->IsStretchEnabled){ ?>
		<header>
			<? if($timeLeft->days > 5){ ?>
				<p>Help us reach <?= number_format($donationDrive->TargetDonationCount) ?> new patrons by <?= $deadline ?></p>
			<? }else{ ?>
				<p><?= $timeString ?> left to help us reach <?= number_format($donationDrive->TargetDonationCount) ?> new patrons!</p>
			<? } ?>
		</header>
	<? }else{ ?>
		<header>
			<p><? if($timeLeft->days <= 1){ ?><?= $timeString ?> left—<br/>Help us meet our stretch goal of<br/> <?= number_format($donationDrive->TargetDonationCount) ?> new patrons<? }else{ ?>Help us meet our stretch goal of<br/> <?= number_format($donationDrive->TargetDonationCount) ?> new patrons by <?= $deadline ?><? } ?></p>
		</header>
	<? } ?>
	<div class="progress"<? if($donationDrive->IsStretchEnabled){ ?> style="--stretch-starting-position: <?= $stretchStartingPosition ?>%; --stretch-base-counter-position: <?= $stretchStartingPosition - 1 ?>%"<? } ?>>
		<div aria-hidden="true">
			<p class="start">0</p>
			<p><?= number_format($donationDrive->DonationCount) ?>/<?= number_format($donationDrive->TargetDonationCount) ?></p>
			<? if($donationDrive->IsStretchEnabled){ ?>
				<p class="stretch-base"><?= number_format($donationDrive->BaseTargetDonationCount) ?></p>
			<? } ?>
			<p class="target"><?= number_format($donationDrive->TargetDonationCount) ?></p>
		</div>
		<progress max="<?= $donationDrive->TargetDonationCount ?>" value="<?= $donationDrive->DonationCount - $donationDrive->StretchDonationCount ?>"></progress>
		<? if($donationDrive->IsStretchEnabled){ ?>
			<progress class="stretch" max="<?= $donationDrive->StretchTargetDonationCount ?>" value="<?= $donationDrive->StretchDonationCount ?>"></progress>
		<? } ?>
	</div>
	<? if($donationDrive->IsStretchEnabled){ ?>
		<p>When we started this drive, we set a goal of <?= number_format($donationDrive->BaseTargetDonationCount) ?> Patrons Circle members by <?= $deadline ?>. Thanks to the incredible generosity of literature lovers like you, we hit that goal faster than we hoped!</p>
		<p>Since there’s still some time left in our drive, we thought we’d challenge our readers to help us reach our stretch goal of <?= number_format($donationDrive->TargetDonationCount) ?> patrons, so that we can continue on a rock-solid financial footing. Will you help us with a donation, and support free and unrestricted digital literature?</p>
	<? }else{ ?>
		<p>It takes a huge amount of resources and highly-skilled work to create and distribute each of our free ebooks, and we need your support to keep it up. That’s why we want to welcome <?= number_format($donationDrive->TargetDonationCount) ?> new patrons by <?= $deadline ?>. It’s our patrons who keep us on the stable financial footing we need to continue producing and giving away beautiful ebooks.</p>
		<p>Will you become a patron, and support free and unrestricted digital literature?</p>
	<? } ?>
	<? if($showDonateButton){ ?>
		<p class="donate-button">
			<a class="button" href="/donate#patrons-circle">Join the patrons circle</a>
		</p>
	<? } ?>
</aside>
