<!DOCTYPE html>
<html>
<head>
	<title>New Patrons Circle member</title>
</head>
<body>
	<table>
		<tbody>
			<tr>
				<td>Name:</td>
				<td><? if($patron->User->Name === null){ ?>Anonymous <? }else{ ?><?= Formatter::ToPlainText($patron->User->Name) ?><? } ?></td>
			</tr>
			<tr>
				<td>Donation type:</td>
				<td><? if($payment->IsRecurring){ ?>Recurring<? }else{ ?>One-time<? } ?></td>
			</tr>
			<tr>
				<td>Donation amount:</td>
				<td><?= Formatter::ToPlainText(number_format($payment->Amount, 2)) ?></td>
			</tr>
			<tr>
				<td>Donation fee:</td>
				<td><?= Formatter::ToPlainText(number_format($payment->Fee, 2)) ?></td>
			</tr>
			<tr>
				<td>Transaction ID:</td>
				<td><?= Formatter::ToPlainText($payment->TransactionId) ?></td>
			</tr>
		</tbody>
	</table>
</body>
</html>
