<?

// Hide the alert if the user has closed it
if(!DONATION_DRIVE_ON || ($autoHide ?? $_COOKIE['hide-donation-alert'] ?? false) || $GLOBALS['User'] !== null){
	return;
}

$start = new DateTime('July 1, 2022 00:00:00 America/New_York');
$end = new DateTime('August 7, 2022 23:59:00 America/New_York');
$now = new DateTime();
$autoHide = $autoHide ?? true;
$showDonateButton = $showDonateButton ?? true;
$current = Db::QueryInt('SELECT count(*) from Patrons where Created >= ?', [$start]);
$target = 70;
$stretchCurrent = 0;
$stretchTarget = 20;
$totalCurrent = $current;
$totalTarget = $target;
$deadline = $end->format('F j');
$timeLeft = $now->diff($end);
$timeString = '';
if($timeLeft->d < 1 && $timeLeft->h < 20){
	$timeString = 'Just hours';
}
elseif($timeLeft->d >=  1 && $timeLeft->h <= 12){
	$timeString = $timeLeft->d . ' day';
	if($timeLeft->d > 1){
		$timeString .= 's';
	}
	else{
		$timeString = 'Only ' . $timeString;
	}
}
else{
	$timeString = ($timeLeft->d + 1) . ' day';
	if($timeLeft->d + 1 > 1){
		$timeString .= 's';
	}
	else{
		$timeString = 'Only ' . $timeString;
	}
}

$stretchOn = false;
if($stretchTarget > 0 && $current >= $target){
	$stretchOn = true;
	$totalCurrent = $current + $stretchCurrent;
	$totalTarget = $target + $stretchTarget;
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
		<? if($timeLeft->d > 5){ ?>
		<p>Help us reach <?= number_format($target) ?> new patrons by <?= $deadline ?></p>
		<? }else{ ?>
		<p><?= $timeString ?> left to help us reach <?= number_format($target) ?> new patrons!</p>
		<? } ?>
	</header>
	<? }else{ ?>
	<header>
		<p>Help us meet our stretch goal of <?= number_format($totalTarget) ?> new patrons by <?= $deadline ?></p>
	</header>
	<? } ?>
	<div class="progress">
		<div aria-hidden="true">
			<p class="start">0</p>
			<p><?= number_format($totalCurrent) ?>/<?= number_format($totalTarget) ?></p>
			<? if($stretchOn){ ?>
				<p class="stretch-base"><?= number_format($target) ?></p>
			<? } ?>
			<p class="target"><?= number_format($totalTarget) ?></p>
		</div>
		<progress max="<?= $target ?>" value="<?= $current ?>"></progress>
		<? if($stretchOn){ ?><progress class="stretch" max="<?= $stretchTarget ?>" value="<?= $stretchCurrent ?>"></progress><? } ?>
	</div>
	<? if($stretchOn){ ?>
	<p>When we started this drive, we set a goal of <?= number_format($target) ?> Patrons Circle members by <?= $deadline ?>. Thanks to the incredible generosity of literature lovers from all walks of life, we hit that goal in just over a week!</p>
	<p>Since there are still weeks left in our drive, we thought we’d challenge our readers to help us reach our stretch goal of 70 patrons, so that we can start the year off on a rock-solid financial footing. Will you help us with a donation, and support free and unrestricted digital literature?</p>
	<? }else{ ?>
	<p>We want to make Standard Ebooks a sustainable project that can support the huge amount of work it takes to maintain and operate. Welcoming <?= number_format($target) ?> new Patrons Circle members by <?= $deadline ?> will help keep us on the stable financial footing we need to continue producing beautiful ebooks.</p>
	<p>Will you help us reach that goal, and support free and unrestricted digital literature?</p>
	<? } ?>
	<? if($showDonateButton){ ?><p class="donate-button"><a class="button" href="/donate#patrons-circle">Join the patrons circle</a></p><? } ?>
</aside>
