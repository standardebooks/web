<?
use Safe\DateTimeImmutable;

$start = new DateTimeImmutable(DONATION_DRIVE_START);
$end = new DateTimeImmutable(DONATION_DRIVE_END);
$now = new DateTimeImmutable();
$totalCurrent = 0;
$baseTarget = 50;
$stretchCurrent = 0;
$stretchTarget = 20;

// Hide the alert if the user has closed it
if(!DONATION_DRIVE_ON || ($autoHide ?? $_COOKIE['hide-donation-alert'] ?? false) || $GLOBALS['User'] !== null || $now > $end){
	return;
}

$autoHide = $autoHide ?? true;
$showDonateButton = $showDonateButton ?? true;
$totalCurrent = Db::QueryInt('
	SELECT sum(cnt)
	from
	(
		(
			# Anonymous patrons, i.e. from AOGF
			select count(*) cnt from Payments
			where
			UserId is null
			and
			(
				(IsRecurring = true and Amount >= 10 and Created >= ?)
				or
				(IsRecurring = false and Amount >= 100 and Created >= ?)
			)
		)
		union all
		(
			# All non-anonymous patrons
			select count(*) as cnt from Patrons
			where Created >= ?
		)
	) x
	', [$start, $start, $start]);

$totalTarget = $baseTarget;
$deadline = $end->format('F j');
$timeLeft = $now->diff($end);
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

$stretchOn = false;
if($stretchTarget > 0 && $totalCurrent >= $baseTarget){
	$stretchOn = true;
	$stretchCurrent = $totalCurrent - $baseTarget;
	$totalTarget = $baseTarget + $stretchTarget;
}

?>
<aside class="donation closable">
	<? if($autoHide){ ?>
	<form action="/settings" method="post">
		<input type="hidden" name="hide-donation-alert" value="1" />
		<button class="close" title="Close this box">Close this box</button>
	</form>
	<? } ?>
	<? if(!$stretchOn){ ?>
	<header>
		<? if($timeLeft->days > 5){ ?>
		<p>Help us reach <?= number_format($baseTarget) ?> new patrons by <?= $deadline ?></p>
		<? }else{ ?>
		<p><?= $timeString ?> left to help us reach <?= number_format($baseTarget) ?> new patrons!</p>
		<? } ?>
	</header>
	<? }else{ ?>
	<header>
		<p>Help us meet our stretch goal of<br/> <?= number_format($totalTarget) ?> new patrons by <?= $deadline ?></p>
	</header>
	<? } ?>
	<div class="progress">
		<div aria-hidden="true">
			<p class="start">0</p>
			<p><?= number_format($totalCurrent) ?>/<?= number_format($totalTarget) ?></p>
			<? if($stretchOn){ ?>
				<p class="stretch-base"><?= number_format($baseTarget) ?></p>
			<? } ?>
			<p class="target"><?= number_format($totalTarget) ?></p>
		</div>
		<progress max="<?= $baseTarget ?>" value="<?= $totalCurrent - $stretchCurrent ?>"></progress>
		<? if($stretchOn){ ?><progress class="stretch" max="<?= $stretchTarget ?>" value="<?= $stretchCurrent ?>"></progress><? } ?>
	</div>
	<? if($stretchOn){ ?>
	<p>When we started this drive, we set a goal of <?= number_format($baseTarget) ?> Patrons Circle members by <?= $deadline ?>. Thanks to the incredible generosity of literature lovers from all walks of life, we hit that goal in just over a week!</p>
	<p>Since there are still weeks left in our drive, we thought we’d challenge our readers to help us reach our stretch goal of <?= number_format($totalTarget) ?> patrons, so that we can start the year off on a rock-solid financial footing. Will you help us with a donation, and support free and unrestricted digital literature?</p>
	<? }else{ ?>
	<p>It takes a huge amount of resources and highly-skilled work to create and distribute each of our free ebooks, and we need your support to keep it up. That’s why we want to welcome <?= number_format($baseTarget) ?> new patrons by <?= $deadline ?>. It’s our patrons who keep us on the stable financial footing we need to continue producing and giving away beautiful ebooks.</p>
	<p>Will you become a patron, and support free and unrestricted digital literature?</p>
	<? } ?>
	<? if($showDonateButton){ ?><p class="donate-button"><a class="button" href="/donate#patrons-circle">Join the patrons circle</a></p><? } ?>
</aside>
