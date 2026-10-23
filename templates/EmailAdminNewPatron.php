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

		td.currency{
			font-variant-numeric: tabular-nums;
		}
	</style>
</head>
<body>
	<table>
		<tbody>
			<tr>
				<td>Name:</td>
				<td><? if($patron->User->Name === null){ ?>Anonymous<? }else{ ?><? if(isset($patron->UserId)){ ?><a href="<?= SITE_URL ?><?= $patron->User->Url ?>"><?= Formatter::EscapeHtml($patron->User->Name) ?></a> (#<?= $patron->UserId ?>)<? }else{ ?><?= Formatter::EscapeHtml($patron->User->Name) ?><? } ?><? } ?></td>
			</tr>
			<tr>
				<td>Donation type:</td>
				<td><? if($payment->IsRecurring){ ?>Recurring<? }else{ ?>One-time<? } ?></td>
			</tr>
			<tr>
				<td>Donation amount:</td>
				<td class="currency"><?= Formatter::EscapeHtml(Formatter::FormatCurrency($payment->Amount)) ?></td>
			</tr>
			<tr>
				<td>Donation fee:</td>
				<td class="currency"><?= $payment->Fee === null ? 'Pending' : Formatter::EscapeHtml(Formatter::FormatCurrency($payment->Fee)) ?></td>
			</tr>
			<tr>
				<td>Processor:</td>
				<td><?= Formatter::EscapeHtml($payment->Processor->GetDisplayName()) ?></td>
			</tr>
			<tr>
				<td>Transaction ID:</td>
				<td><a href="<?= Formatter::EscapeHtml($payment->ProcessorUrl) ?>"><?= Formatter::EscapeHtml($payment->TransactionId) ?></a></td>
			</tr>
		</tbody>
	</table>
</body>
</html>
