<?
/**
 * @var int $ebooksThisYear
 */
?>
Hello,

Last year, your generous donation to Standard Ebooks made it possible for us to continue producing beautiful ebook editions for free distribution.

It also allowed me to add you to our Patrons Circle, a group of donors who are honored on our masthead, and who have a direct voice in the future of our ebook catalog, for one year.

Now that a year has passed, will you renew your membership to our Patrons Circle?

<? if($ebooksThisYear > 0){ ?>In that year, our volunteers produced <?= number_format($ebooksThisYear) ?> new free ebooks. We need the financial support of literature lovers like you in order to keep up that pace.<? }else{ ?>We need the financial support of literature lovers like you in order to keep producing free ebooks.<? } ?> *Your membership in our Patrons Circle is what makes Standard Ebooks possible.*

Now that a year has passed, will you [renew your membership to our Patrons Circle?](<?= SITE_URL ?>/donate#patrons-circle) **We can’t do it without you!**

Renew your Patrons Circle membership here: <<?= SITE_URL ?>/donate#patrons-circle>

I hope to see you back!


Alex Cabal

S.E. Editor-in-Chief
