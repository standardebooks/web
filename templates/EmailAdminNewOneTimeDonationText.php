<?
/**
 * @var Payment $payment
 */
?>
Name: <? if($payment->User?->Name === null){ ?>Anonymous<? }else{ ?><? if(isset($payment->UserId)){ ?>[<?= $payment->User->Name ?>](<?= SITE_URL ?><?= $payment->User->Url ?>) (#<?= $payment->UserId ?>)<? }else{ ?><?= $payment->User->Name ?><? } ?><? } ?>

Donation amount: <?= Formatter::FormatCurrency($payment->Amount) ?>

Donation fee: <?= $payment->Fee === null ? 'Pending' : Formatter::FormatCurrency($payment->Fee) ?>

Processor: <?= $payment->Processor->GetDisplayName() ?>

Transaction ID: [<?= $payment->TransactionId ?>](<?= $payment->ProcessorUrl ?>)
