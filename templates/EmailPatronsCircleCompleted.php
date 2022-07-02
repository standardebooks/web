<?= Template::EmailHeader(['letterhead' => true]) ?>
<p>Hello,</p>
<p>Last year, your generous donation to <a href="<?= SITE_URL ?>">Standard Ebooks</a> made it possible for us to continue producing beautiful ebook editions for free distribution. It also allowed me to add you to our <a href="<?= SITE_URL ?>/about#patrons-circle">Patrons Circle</a>, a group of donors who are honored on our masthead, and who have a direct voice in the future of our <a href="<?= SITE_URL ?>/ebooks">ebook catalog</a>, for one year.</p>
<p>Now that a year has passed, will you renew your membership to our Patrons Circle?</p>
<p><? if($ebooksThisYear > 0){ ?>In the year since you joined our Patrons Circle, our volunteers produced <?= number_format($ebooksThisYear) ?> new free ebooks. We need the financial support of literature lovers like you in order to keep up that pace. <? }else{ ?>We need the financial support of literature lovers like you in order to keep producing free ebooks. <? } ?><em>Your membership in our Patrons Circle is what makes Standard Ebooks possible.</em></p>
<p><strong>We can’t do it without you.</strong> Will you join us for another year of beautiful digital literature?</p>
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
