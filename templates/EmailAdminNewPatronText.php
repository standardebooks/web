Name: <? if($patron->User->Name === null){ ?>Anonymous <? }else{ ?><?= Formatter::ToPlainText($patron->User->Name) ?><? if($patron->IsAnonymous){ ?> (Anonymous)<? } ?><? } ?>

Donation type: <? if($payment->IsRecurring){ ?>Recurring<? }else{ ?>One-time<? } ?>

Donation amount: <?= Formatter::ToPlainText(number_format($payment->Amount, 2)) ?>

Donation fee: <?= Formatter::ToPlainText(number_format($payment->Fee, 2)) ?>

Transaction ID: <?= Formatter::ToPlainText($payment->TransactionId) ?>
