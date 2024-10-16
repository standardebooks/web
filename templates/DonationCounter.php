<?
// Hide the alert if the user has closed it.
if(!DONATION_DRIVE_COUNTER_ON || ($autoHide ?? $_COOKIE['hide-donation-alert'] ?? false) || NOW > DONATION_DRIVE_COUNTER_END){
	return;
}

$autoHide = $autoHide ?? true;
$showDonateButton = $showDonateButton ?? true;
$current = 0;

if(NOW < DONATION_DRIVE_COUNTER_START || NOW > DONATION_DRIVE_COUNTER_END){
	return;
}

$deadline = DONATION_DRIVE_COUNTER_END->format('F j');
$timeLeft = NOW->diff(DONATION_DRIVE_COUNTER_END);
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

$digits = str_split(str_pad($current, 3, "0", STR_PAD_LEFT))
?>
<aside class="donation counter closable">
	<? if($autoHide){ ?>
		<form action="/settings" method="post">
			<input type="hidden" name="hide-donation-alert" value="true" />
			<button class="close" title="Close this box">Close this box</button>
		</form>
	<? } ?>
	<header>
		<p><?= $timeString ?> left to help us win $1,000</p>
	</header>
	<div class="flipboard">
		<? foreach($digits as $digit){ ?><span><?= $digit ?></span><? } ?>
		entries
	</div>
	<p>Our fiscal sponsor, <a href="https://www.fracturedatlas.org">Fractured Atlas</a>, is celebrating the twenty-year anniversary of their fiscal sponsorship program by <a href="https://media.fracturedatlas.org/what-would-you-do-with-an-extra-1000">distributing $1,000 to twenty different projects</a>.</p>
	<p><strong>Each one-time donation of any amount to Standard Ebooks through <?= $deadline ?> gives us one entry in this $1,000 giveaway.</strong> The more donations we receive through <?= $deadline ?>, the more chances we have to win!</p>
	<p><strong>This is a great time to <a href="/donate#patrons-circle">join our Patrons Circle</a> with a one-time donation of $100.</strong> Not only will your donation support us directly, but it’ll give us one more entry in this big giveaway.</p>
	<p>Will you show your support for free, beautiful digital literature?</p>
	<? if($showDonateButton){ ?>
		<p class="donate-button">
			<a class="button" href="/donate">Make a one-time donation!</a>
		</p>
	<? } ?>
</aside>
