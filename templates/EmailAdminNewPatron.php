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
				<td><?= Formatter::ToPlainText($patron->Name) ?></td>
			</tr>
			<tr>
				<td>Donation type:</td>
				<td><? if($payment->IsRecurring){ ?>Recurring<? }else{ ?>One-time<? } ?></td>
			</tr>
			<tr>
				<td>Donation amount:</td>
				<td><?= Formatter::ToPlainText(number_format($payment->Amount)) ?></td>
			</tr>
			<tr>
				<td>Donation fee:</td>
				<td><?= Formatter::ToPlainText(number_format($payment->Fee)) ?></td>
			</tr>
			<tr>
				<td>Transaction ID:</td>
				<td><?= Formatter::ToPlainText($payment->TransactionId) ?></td>
			</tr>
		</tbody>
	</table>
</body>
</html>
