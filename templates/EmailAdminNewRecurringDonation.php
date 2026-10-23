<?
/**
 * @var Payment $payment
 * @var Enums\CycleType $cycleType
 */
?><!DOCTYPE html>
<html>
<head>
	<title>New recurring donation</title>
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
				<td><? if($payment->User?->Name === null){ ?>Anonymous<? }else{ ?><? if(isset($payment->UserId)){ ?><a href="<?= SITE_URL ?><?= $payment->User->Url ?>"><?= Formatter::EscapeHtml($payment->User->Name) ?></a> (#<?= $payment->UserId ?>)<? }else{ ?><?= Formatter::EscapeHtml($payment->User->Name) ?><? } ?><? } ?></td>
			</tr>
			<tr>
				<td>Frequency:</td>
				<td><?= Formatter::EscapeHtml(ucfirst($cycleType->value)) ?></td>
			</tr>
			<tr>
				<td>Donation amount:</td>
				<td class="currency"><?= Formatter::EscapeHtml(Formatter::FormatCurrency($payment->Amount)) ?></td>
			</tr>
			<tr>
				<td>Donation fee:</td>
				<td class="currency"><? if($payment->Fee === null){ ?>Pending<? }else{ ?><?= Formatter::EscapeHtml(Formatter::FormatCurrency($payment->Fee)) ?><? } ?></td>
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
