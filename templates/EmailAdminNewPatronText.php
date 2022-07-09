Name: <?= Formatter::ToPlainText($patron->Name) ?>

Donation type: <? if($payment->IsRecurring){ ?>Recurring<? }else{ ?>One-time<? } ?>

Donation amount: <?= Formatter::ToPlainText(number_format($payment->Amount)) ?>

Donation fee: <?= Formatter::ToPlainText(number_format($payment->Fee)) ?>

Transaction ID: <?= Formatter::ToPlainText($payment->TransactionId) ?>
