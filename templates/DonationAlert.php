<?
// Hide this alert if...
$donationDrive = DonationDrive::GetByIsRunning();

if(
	$GLOBALS['User'] !== null // If a user is logged in.
	||
	$donationDrive !== null // There is a currently-running donation drive.
	||
	rand(1, 5) <= 3 // A 3-in-5 chance occurs.
){
	return;
}

if($GLOBALS['User'] === null){
	// The Kindle browsers renders `<aside>` as an undismissable popup. Serve a `<div>` to Kindle instead.
	// See <https://github.com/standardebooks/web/issues/204>.
	$element = 'aside';

	if(stripos($_SERVER['HTTP_USER_AGENT'] ?? '', 'kindle') !== false){
		$element = 'div';
	}
?>
	<<?= $element ?> class="donation">
		<p>We rely on your support to help us keep producing beautiful, free, and unrestricted editions of literature for the digital age.</p>
		<p>Will you <a href="/donate">support our efforts with a donation</a>?</p>
	</<?= $element ?>>
<? } ?>
