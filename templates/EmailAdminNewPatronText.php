<?
/**
 * @var Patron $patron
 * @var Payment $payment
 */
?>
Name: <? if($patron->User->Name === null){ ?>Anonymous<? }else{ ?><? if(isset($patron->UserId)){ ?>[<?= $patron->User->Name ?>](<?= SITE_URL ?><?= $patron->User->Url ?>) (#<?= $patron->UserId ?>)<? }else{ ?><?= $patron->User->Name ?><? } ?><? } ?>

Donation type: <? if($payment->IsRecurring){ ?>Recurring<? }else{ ?>One-time<? } ?>

Donation amount: <?= Formatter::EscapeHtml(Formatter::FormatCurrency($payment->Amount)) ?>

Donation fee: <?= $payment->Fee === null ? 'Pending' : Formatter::EscapeHtml(Formatter::FormatCurrency($payment->Fee)) ?>

Processor: <?= $payment->Processor->GetDisplayName() ?>

Transaction ID: [<?= Formatter::EscapeHtml($payment->TransactionId) ?>](<?= $payment->ProcessorUrl ?>)
