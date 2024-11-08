<?
/**
 * @var Patron $patron
 * @var Payment $payment
 */
?><!DOCTYPE html>
<html>
<head>
	<title>New Patrons Circle member</title>
	<style>
		table td{
			padding: .5em;
		}

		table td:first-child{
			text-align: right;
		}
	</style>
</head>
<body>
	<table>
		<tbody>
			<tr>
				<td>Name:</td>
				<td><? if($patron->User->Name === null){ ?>Anonymous <? }else{ ?><?= Formatter::EscapeHtml($patron->User->Name) ?><? if($patron->IsAnonymous){ ?> (Anonymous)<? } ?><? } ?></td>
			</tr>
			<tr>
				<td>Donation type:</td>
				<td><? if($payment->IsRecurring){ ?>Recurring<? }else{ ?>One-time<? } ?></td>
			</tr>
			<tr>
				<td>Donation amount:</td>
				<td><?= Formatter::EscapeHtml(number_format($payment->Amount, 2)) ?></td>
			</tr>
			<tr>
				<td>Donation fee:</td>
				<td><?= Formatter::EscapeHtml(number_format($payment->Fee, 2)) ?></td>
			</tr>
			<tr>
				<td>Transaction ID:</td>
				<td><a href="https://fundraising.fracturedatlas.org/admin/donations?query=<?= urlencode($payment->TransactionId) ?>"><?= Formatter::EscapeHtml($payment->TransactionId) ?></a></td>
			</tr>
		</tbody>
	</table>
</body>
</html>
