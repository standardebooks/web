<?
use Safe\DateTimeImmutable;

try{
	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanViewReports){
		throw new Exceptions\InvalidPermissionsException();
	}

	$filterFrom = HttpInput::Date(GET, 'from');
	$filterTo = HttpInput::Date(GET, 'to');
	$hasDateFilters = $filterFrom !== null || $filterTo !== null;
	$localNow = NOW->setTime(0, 0, 0, 0);
	$from = $filterFrom ?? $localNow->sub(new DateInterval('P30D'));
	$to = $filterTo ?? $localNow;

	if($from > $to){
		[$from, $to] = [$to, $from];
	}

	if($to > $localNow){
		$to = $localNow;
	}

	// Calculate Patron count graph.
	$patronCounts = Patron::GetActivePatronCountsByDay($from, $to);

	$patronCountMonthlyValues = [];
	$patronCountYearlyValues = [];

	foreach($patronCounts as $index => $row){
		$key = $row['date']->format('Y-m-d');
		$patronCountMonthlyValues[$key] = $row['monthlyCount'];
		$patronCountYearlyValues[$key] = $row['yearlyCount'];
	}

	$patronCountGraph = new StackedBarGraph();
	$patronCountGraph->Values = [$patronCountMonthlyValues, $patronCountYearlyValues];
	$patronCountGraph->LegendEntries = ['Monthly', 'Yearly'];
	$patronCountGraphSvg = $patronCountGraph->Render();

	// Calculate ebooks released graph.
	$ebooksReleasedFrom = $hasDateFilters ? $from : NOW->sub(new DateInterval('P1Y'))->modify('first day of this month')->setTime(0, 0);
	$ebooksReleasedTo = $hasDateFilters ? $to : NOW;
	$ebooksReleased = Ebook::GetReleaseCountByMonth($ebooksReleasedFrom, $ebooksReleasedTo);

	$ebooksReleasedGraph = new BarGraph();
	$ebooksReleasedGraph->Values = [$ebooksReleased];
	$ebooksReleasedGraphSvg = $ebooksReleasedGraph->Render();

	// Calculate payments received graph.
	$paymentsReceivedFrom = $hasDateFilters ? $from : NOW->sub(new DateInterval('P1Y'))->modify('first day of this month')->setTime(0, 0);
	$paymentsReceivedTo = $hasDateFilters ? $to : NOW;
	$paymentsReceivedValues = Payment::GetNetByMonth($paymentsReceivedFrom, $paymentsReceivedTo);

	$paymentsReceivedGraph = new StackedBarGraph();
	$paymentsReceivedGraph->Values = $paymentsReceivedValues;
	$paymentsReceivedGraph->LegendEntries = ['Recurring', 'Patron one-time', 'Other'];
	$paymentsReceivedGraph->Settings['axis_text_callback_y'] = fn(float|int $value): string => Formatter::FormatMoney($value);
	$paymentsReceivedGraph->Settings['bar_total_callback'] = fn(string $key, float|int $value): string => Formatter::FormatMoney($value);
	$paymentsReceivedGraph->Settings['data_label_callback'] = fn(int $dataset, string $key, float|int $value): string => Formatter::FormatMoney($value);
	$paymentsReceivedGraph->Settings['show_data_labels'] = false;
	$paymentsReceivedGraphSvg = $paymentsReceivedGraph->Render();
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
?><?= Template::Header(
	title: 'Reports',
	description: 'View various Standard Ebooks statistics.',
	css: ['/css/reports.css']
) ?>
<main>
	<section class="narrow reports">
		<h1>Reports</h1>

		<form method="<?= Enums\HttpMethod::Get->value ?>" action="/reports">
			<label class="icon year">
				<span>From</span>
				<span>Time zone is UTC.</span>
				<input type="date" name="from" value="<?= $from->format('Y-m-d') ?>" />
			</label>
			<label class="icon year">
				<span>To</span>
				<span>Time zone is UTC.</span>
				<input type="date" name="to" value="<?= $to->format('Y-m-d') ?>" />
			</label>
			<button>Filter</button>
		</form>

		<section>
			<h2>Patrons by day</h2>
			<?= $patronCountGraphSvg ?>
		</section>

		<section>
			<h2>Net payments by month</h2>
			<?= $paymentsReceivedGraphSvg ?>
		</section>

		<section>
			<h2>Ebooks released by month</h2>
			<?= $ebooksReleasedGraphSvg ?>
		</section>
	</section>
</main>
<?= Template::Footer() ?>
