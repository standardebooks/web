<?
if(!isset($holidays)){
	$holidays = false;
}
?>
<aside class="donation">
	<? if($holidays){ ?>
	<p>We rely on your support to help us keep producing beautiful, free, and unrestricted editions of literature for the digital age.</p>
	<p>Will you <a href="/donate">support our efforts with a donation</a>?</p>
	<? }else{ ?>
	<p>We rely on your support to help us keep producing beautiful, free, and unrestricted editions of literature for the digital age. Will you <a href="/donate">support our efforts with a donation</a>?</p>
	<? } ?>
</aside>
