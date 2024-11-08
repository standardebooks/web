<?
/**
 * @var bool $isAnonymous
 * @var bool $isReturning
 */
?>
Hello,

I wanted to thank you personally for your recent donation to Standard Ebooks. Your donation will go towards continuing our mission of producing and distributing high-quality ebooks that are free of cost and free of copyright restrictions. Donations like yours help ensure that the world’s literature is available in beautiful editions made for the digital age.

<? if($isAnonymous){ ?>
I’m pleased to be able to <? if($isReturning){ ?>welcome you back to<? }else{ ?>include you in<? } ?> our Patrons Circle. Since you indicated you want your donation to remain anonymous, I haven’t listed your name on our masthead. If you do prefer to have your name listed, just let me know.
<? }else{ ?>
I’m pleased to be able to <? if($isReturning){ ?>welcome you back to<? }else{ ?>include you in<? } ?> our Patrons Circle, with your name listed on our masthead for the duration of your donation. If you’d like to use a different name than the one you entered on our donation form, just let me know.
<? } ?>

As a Patron, once per quarter you may suggest a book for inclusion in our Wanted Ebooks list. Before submitting a suggestion, please review our collections policy, at <https://standardebooks.org/contribute/collections-policy>; then you can contact me directly at <?= EDITOR_IN_CHIEF_EMAIL_ADDRESS ?> with your selection.

You also get access to our ebook feeds, at <https://standardebooks.org/feeds>, for use in your ereading app or RSS reader, and bulk downloads, at <https://standardebooks.org/bulk-downloads>, to download collections of ebooks easily. To use the feeds, enter your email address when prompted, and leave the password field empty.

<? if($isReturning){ ?>As always, please<? }else{ ?>Please<? } ?> don’t hesitate to contact me if you have questions or suggestions. Thanks again for your donation, and for supporting the literate arts!


Alex Cabal

S.E. Editor-in-Chief
