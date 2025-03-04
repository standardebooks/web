<?= Template::EmailHeader(hasLetterhead: true) ?>
<p>Hello,</p>
<p>I couldn’t help but notice that your monthly donation to Standard Ebooks has recently ended. Your generous donation allowed me to add you to our <a href="<?= SITE_URL ?>/about#patrons-circle">Patrons Circle</a>, a group of donors who are honored on our masthead, and who have a direct voice in the future of our <a href="<?= SITE_URL ?>/ebooks">ebook catalog</a>.</p>
<p>Oftentimes credit cards will expire, or recurring charges will get accidentally canceled for any number of nebulous administrative reasons. If you didn’t mean to cancel your recurring donation—and thus your Patrons Circle membership—now’s a great time to <a href="<?= SITE_URL ?>/donate#patrons-circle">renew it</a>.</p>
<p>We need the financial support of literature lovers like you in order to keep producing free ebooks. <em>Your membership in our Patrons Circle is what makes Standard Ebooks possible.</em></p>
<p><strong>We can’t do it without you.</strong> Will you rejoin us in our journey of producing free beautiful digital literature?</p>
<p class="button-row">
	<a href="<?= SITE_URL ?>/donate#patrons-circle" class="button">Renew your Patrons Circle membership</a>
</p>
<p>I hope to see you back!</p>
<footer>
	<p><img width="125" height="57" src="https://standardebooks.org/images/alex-cabal-signature.png" alt="" /></p>
	<p>Alex Cabal</p>
	<p>S.E. Editor-in-Chief</p>
</footer>
<?= Template::EmailFooter() ?>
