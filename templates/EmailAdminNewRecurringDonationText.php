<?
/**
 * @var Payment $payment
 * @var Enums\CycleType $cycleType
 */
?>
Name: <? if($payment->User?->Name === null){ ?>Anonymous<? }else{ ?><? if(isset($payment->UserId)){ ?>[<?= $payment->User->Name ?>](<?= SITE_URL ?><?= $payment->User->Url ?>) (#<?= $payment->UserId ?>)<? }else{ ?><?= $payment->User->Name ?><? } ?><? } ?>

Frequency: <?= ucfirst($cycleType->value) ?>

Donation amount: <?= Formatter::FormatCurrency($payment->Amount) ?>

Donation fee: <? if($payment->Fee === null){ ?>Pending<? }else{ ?><?= Formatter::FormatCurrency($payment->Fee) ?><? } ?>

Transaction ID: [<?= $payment->TransactionId ?>](<?= $payment->ProcessorUrl ?>)
