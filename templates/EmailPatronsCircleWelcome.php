<!DOCTYPE html>
<html lang="en">
<head>
	<title>Thank you for supporting Standard Ebooks!</title>
</head>
<body>
	<p>Hello,</p>
	<p>I wanted to thank you personally for your recent donation to Standard Ebooks. Your donation will go towards continuing our mission of producing and distributing high-quality ebooks that are free of cost and free of copyright restrictions. Donations like yours help ensure that the world’s literature is available in beautiful editions made for the digital age.</p>
	<? if($isAnonymous){ ?>
	<p>I’m pleased to be able to <? if($isReturning){ ?>welcome you back to<? }else{ ?>include you in<? } ?> our Patrons Circle. Since you indicated you want your donation to remain anonymous, I haven’t listed your name on our masthead. If you do prefer to have your name listed, just let me know.</p>
	<? }else{ ?>
	<p>I’m pleased to be able to <? if($isReturning){ ?>welcome you back to<? }else{ ?>include you in<? } ?> our Patrons Circle, with your name listed on our masthead for the duration of your donation. If you’d like to use a different name than the one you entered on our donation form, just let me know.</p>
	<? } ?>
	<p>As a Patron, once per quarter you may suggest a book for inclusion in our Wanted Ebooks list. Before submitting a suggestion, please review our <a href="https://standardebooks.org/contribute/collections-policy">collections policy</a>; then you can contact me directly at <a href="mailto:<?= EDITOR_IN_CHIEF_EMAIL_ADDRESS ?>"><?= EDITOR_IN_CHIEF_EMAIL_ADDRESS ?></a> with your selection.</p>
	<? if(!$isReturning){ ?><p>If I may ask, how did you hear about Standard Ebooks? Having an idea of where our readers and supporters find out about us is extremely helpful.</p><? } ?>
	<p><? if($isReturning){ ?>As always, please<? }else{ ?>Please<? } ?> don’t hesitate to contact me if you have questions or suggestions. Thanks again for your donation, and for supporting the literate arts!</p>
	<footer style="margin-top: 2em;">
		<p>Alex Cabal</p>
		<p>S.E. Editor-in-Chief</p>
	</footer>
</body>
</html>
