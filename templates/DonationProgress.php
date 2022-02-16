<?

// Hide the alert if the user has closed it
if($autoHide ?? $_COOKIE['hide-donation-alert'] ?? false){
	return;
}

$autoHide = $autoHide ?? true;
$current = 0;
$target = 50;
$stretchCurrent = 0;
$stretchTarget = 20;
$totalCurrent = $current;
$totalTarget = $target;
$deadline = 'Feb. 15';

$stretchOn = false;
if($stretchTarget > 0 && $current >= $target){
	$stretchOn = true;
	$totalCurrent = $current + $stretchCurrent;
	$totalTarget = $target + $stretchTarget;
}

?>
<aside class="donation">
	<? if($autoHide){ ?>
	<form action="/settings" method="post">
		<input type="hidden" name="hide-donation-alert" value="1" />
		<button class="close">Close this alert</button>
	</form>
	<? } ?>
	<? if(!$stretchOn){ ?>
	<header>
		<p>Help us reach <?= number_format($target) ?> patrons by <?= $deadline ?></p>
	</header>
	<? }else{ ?>
	<header>
		<p>Help us meet our stretch goal of <?= number_format($totalTarget) ?> patrons by <?= $deadline ?></p>
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
	<p>When we started this drive, we set a goal of <?= number_format($target) ?> Patrons Circle members by Feb. 15. Thanks to the incredible generosity of literature lovers from all walks of life, we hit that goal in just over a week!</p>
	<p>Since there are still weeks left in our drive, we thought we’d challenge our readers to help us reach our stretch goal of 70 patrons, so that we can start the year off on a rock-solid financial footing. Will you help us with a donation, and support free and unrestricted digital literature?</p>
	<? }else{ ?>
	<p>We want to make Standard Ebooks a sustainable project that can support the huge amount of work it takes to maintain and operate. Welcoming <?= number_format($target) ?> new Patrons Circle members by <?= $deadline ?> will help put us on the stable financial footing we need to continue producing beautiful ebooks as we enter the new year.</p>
	<p>Will you help us reach that goal, and support free and unrestricted digital literature?</p>
	<? } ?>
	<p><a class="button" href="/donate#patrons-circle">Join the patrons circle</a></p>
</aside>
