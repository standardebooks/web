<?
/**
 * @var Patron $patron
 * @var Payment $payment
 */
?>Name: <? if($patron->User->Name === null){ ?>Anonymous <? }else{ ?><?= Formatter::EscapeHtml($patron->User->Name) ?><? if($patron->IsAnonymous){ ?> (Anonymous)<? } ?><? } ?>

Donation type: <? if($payment->IsRecurring){ ?>Recurring<? }else{ ?>One-time<? } ?>

Donation amount: <?= Formatter::EscapeHtml(number_format($payment->Amount, 2)) ?>

Donation fee: <?= Formatter::EscapeHtml(number_format($payment->Fee, 2)) ?>

Transaction ID: <?= Formatter::EscapeHtml($payment->TransactionId) ?>
