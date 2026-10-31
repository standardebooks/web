<?
use function Safe\session_start;

session_start();

$ebookCount = floor(Db::QueryInt('select count(*) from Ebooks where EbookCreatedAt is not null') / 10) * 10;

$exception = Http::$Request->Session->Get('donation/create/exception', Exceptions\AppException::class);
$form = Http::$Request->Session->Get('donation/create/form', HttpVariablesInterface::class) ?? new HttpVariablesInterface('', []);

$yearlySavings = (STRIPE_DONATION_AMOUNTS['month']['join']['0'] * 12) - STRIPE_DONATION_AMOUNTS['year']['join']['0'];

if($exception !== null){
	http_response_code(Enums\HttpCode::UnprocessableContent->value);
	unset($_SESSION['donation/create/exception'], $_SESSION['donation/create/form']);
}

// Restore the submitted fields and selections after any donation form error.
$email = $form->Get('email') ?? '';
$name = $form->Get('name') ?? '';
if(Session::$User !== null){
	$email = (string)Session::$User->Email;
	$name = $form->Get('name') ?? Session::$User->Name ?? '';
}

$selectedFrequency = $form->Get('frequency') ?? 'month';
$selectedMembership = $form->Get('membership') ?? 'join';
$oneTimeAmountChoice = $form->Get('amount-once-choice') ?? (string)DONATION_DEFAULT_AMOUNT;
$joinPatronsCircle = Http::$Request->QueryString->Get('join-patrons-circle', 'bool') ?? $form->Get('join-patrons-circle', 'bool') ?? false;

if($joinPatronsCircle){
	$selectedMembership = 'join';
	if(!in_array($selectedFrequency, ['month', 'year'], true)){
		$selectedFrequency = 'month';
	}
}

$hasActiveSubscription = Session::$User !== null && Db::QueryBool('select exists(select * from Subscriptions where UserId = ? and EndedAt is null)', [Session::$User->UserId]);
?>
<?= Template::Header(title: 'Make a donation', highlight: 'donate', description: 'Donate to Standard Ebooks.', css: ['/css/donations.css']) ?>
<main>
	<section class="narrow has-hero donate">
		<hgroup>
			<h1>Make a Donation</h1>
			<p>towards free literature, beautifully presented</p>
		</hgroup>
		<picture data-caption="One Dollar Silver Certificate. Victor Dubreuil, circa 1910">
			<source srcset="/images/one-dollar-silver-certificate@2x.avif 2x, /images/one-dollar-silver-certificate.avif 1x" type="image/avif"/>
			<source srcset="/images/one-dollar-silver-certificate@2x.jpg 2x, /images/one-dollar-silver-certificate.jpg 1x" type="image/jpeg"/>
			<img src="/images/one-dollar-silver-certificate@2x.jpg" alt="A painting of a one-dollar silver certificate against a dark background."/>
		</picture>
		<? if(Session::$User !== null && $hasActiveSubscription){ ?>
			<aside class="alert">
				<p>Heads up—you already have an active recurring donation! You can <a href="<?= Session::$User->Url ?>">manage it from your account page</a>.</p>
			</aside>
		<? } ?>
		<?= Template::Error(exception: $exception) ?>
		<form class="donation-form" method="<?= Enums\HttpMethod::Post->value ?>" action="/checkout-sessions">
			<? if($joinPatronsCircle){ ?>
				<input type="hidden" name="join-patrons-circle" value="true"/>
			<? } ?>
			<div class="donor-details">
				<label>
					<span>Your email address</span>
					<input type="email" name="email" maxlength="80" autocomplete="email" required="required" value="<?= Formatter::EscapeHtml($email) ?>"<? if(Session::$User !== null){ ?> readonly="readonly"<? } ?>/>
				</label>
			</div>
			<fieldset aria-labelledby="donation-form-heading">
				<legend class="question">What kind of donation would you like to make?</legend>
				<? if(!$joinPatronsCircle){ ?>
					<label>
						<input type="radio" name="frequency" value="once" required="required"<? if($selectedFrequency === 'once'){ ?> checked="checked"<? } ?>/>
						<span>Make a one-time donation</span>
					</label>
				<? } ?>
				<label>
					<input type="radio" name="frequency" value="month" required="required"<? if($selectedFrequency === 'month'){ ?> checked="checked"<? } ?>/>
					<span>Start a monthly donation</span>
				</label>
				<label>
					<input type="radio" name="frequency" value="year"<? if($selectedFrequency === 'year'){ ?> checked="checked"<? } ?>/>
					<span>Start a yearly donation<span class="yearly-savings"> — <i class="highlighted">Same impact for <?= Formatter::FormatCurrency($yearlySavings, true) ?> less than a monthly donation!</i></span></span>
				</label>
			</fieldset>
			<? if(!$joinPatronsCircle){ ?>
				<fieldset class="membership-once">
					<legend class="question">Would you like to join the Patrons Circle?</legend>
					<p>Connect your ereader to our <a href="/feeds" target="_blank">library of over <?= number_format($ebookCount) ?> ebooks</a>, access <a href="/bulk-downloads" target="_blank">bulk downloads</a> of our entire library, <a href="/polls" target="_blank">vote on future ebooks</a>, and <a href="/about#patrons-circle" target="_blank">get listed as a patron of the literate arts</a> by joining the Patrons Circle. To join, start a monthly donation of $<?= number_format(STRIPE_DONATION_AMOUNTS['month']['join'][0]) ?>/month or more or a yearly donation of $<?= number_format(STRIPE_DONATION_AMOUNTS['year']['join'][0]) ?>/year or more.</p>
					<label>
						<input type="radio" name="one-time-membership" value="join" disabled="disabled"/>
						<span>Yes, join the Patrons Circle!</span>
					</label>
					<label>
						<input type="radio" name="one-time-membership" value="donate" checked="checked"/>
						<span>No, just make a donation</span>
					</label>
				</fieldset>
			<? } ?>
			<fieldset class="membership-recurring">
				<legend class="question"><? if($joinPatronsCircle){ ?>Patrons Circle options<? }else{ ?>Would you like to join the Patrons Circle?<? } ?></legend>
				<p>Connect your ereader to our <a href="/feeds" target="_blank">library of over <?= number_format($ebookCount) ?> ebooks</a>, access <a href="/bulk-downloads" target="_blank">bulk downloads</a> of our entire library, <a href="/polls" target="_blank">vote on future ebooks</a>, and <a href="/about#patrons-circle" target="_blank">get listed as a patron of the literate arts</a> by joining the Patrons Circle. To join, start a monthly donation of $<?= number_format(STRIPE_DONATION_AMOUNTS['month']['join'][0]) ?>/month or more or a yearly donation of $<?= number_format(STRIPE_DONATION_AMOUNTS['year']['join'][0]) ?>/year or more.</p>
				<? if($joinPatronsCircle){ ?>
					<input type="hidden" name="membership" value="join"/>
				<? }else{ ?>
					<label>
						<input type="radio" name="membership" value="join" required="required"<? if($selectedMembership === 'join'){ ?> checked="checked"<? } ?> />
						<span>Yes, join the Patrons Circle!</span>
					</label>
				<? } ?>
				<fieldset class="membership-listing">
					<label>
						<input type="checkbox" name="list-name" value="yes"<? if($form->Get('list-name') === 'yes'){ ?> checked="checked"<? } ?> />
						<span>List my name publicly as a patron of the literate arts on the <a href="/about#patrons-circle" target="_blank">masthead</a></span>
					</label>
					<fieldset class="masthead-name">
						<label class="icon user">
							<span>Your name</span>
							<input type="text" name="name" maxlength="80" autocomplete="name" value="<?= Formatter::EscapeHtml($name) ?>"/>
						</label>
					</fieldset>
				</fieldset>
				<? if(!$joinPatronsCircle){ ?>
					<label>
						<input type="radio" name="membership" value="donate" required="required"<? if($selectedMembership === 'donate'){ ?> checked="checked"<? } ?>/>
						<span>No, just start a recurring donation</span>
					</label>
				<? } ?>
			</fieldset>
			<fieldset class="amount-options">
				<? if(!$joinPatronsCircle){ ?>
					<fieldset class="amount-choice" data-frequency="once">
						<legend class="question">Donation amount</legend>
						<? foreach(STRIPE_ONE_TIME_DONATION_AMOUNTS as $amount){ ?>
							<label>
								<input type="radio" name="amount-once-choice" value="<?= $amount ?>"<? if($oneTimeAmountChoice === (string)$amount){ ?> checked="checked"<? } ?>/>
								<span><?= Formatter::FormatCurrency($amount, true) ?></span>
							</label>
						<? } ?>
						<label>
							<input type="radio" name="amount-once-choice" value="custom"<? if($oneTimeAmountChoice === 'cushttps://standardebooks.org/about#patrons-circletom'){ ?> checked="checked"<? } ?>/>
							<span>Custom amount — <i>choose at checkout</i></span>
						</label>
					</fieldset>
				<? } ?>
				<? foreach(STRIPE_DONATION_AMOUNTS as $frequency => $membershipAmounts){
					foreach($membershipAmounts as $membership => $amounts){
						if($joinPatronsCircle && $membership !== 'join'){
							continue;
						}
						$defaultAmount = $amounts[0];
						if($membership === 'donate'){
							$defaultAmount = $amounts[1];
						}
						$selectedAmount = $form->Get('amount-' . $frequency . '-' . $membership, 'int') ?? $defaultAmount; ?>
						<fieldset class="amount-choice" data-frequency="<?= $frequency ?>" data-membership="<?= $membership ?>">
							<legend class="question">Amount per <?= $frequency ?></legend>
							<? foreach($amounts as $amount){ ?>
								<label>
									<input type="radio" name="amount-<?= $frequency ?>-<?= $membership ?>" value="<?= $amount ?>"<? if($amount === $selectedAmount){ ?> checked="checked"<? } ?>/>
									<span><?= Formatter::FormatCurrency($amount, true) ?>/<?= $frequency ?><? if($membership == 'join' && $amount == $amounts[0]){ ?> — <i><? if($frequency == 'year'){ ?>the minimum to join the Patrons Circle; save <?= Formatter::FormatCurrency($yearlySavings, true) ?> over a monthly membership<? }else{ ?>the minimum to join the Patrons Circle<? } ?></i><? } ?></span>
								</label>
							<? } ?>
						</fieldset>
				<? } } ?>
			</fieldset>
			<button class="next">Continue to checkout</button>
		</form>
		<aside>
			<p>Donations made via this form are not tax-deductible. To make a tax-deductible donation, <a href="https://fundraising.fracturedatlas.org/standard-ebooks/general_support">use this form</a>; to donate via a donor-advised fund, <a href="/help/donating-via-a-donor-advised-fund">see here</a>.</p>
		</aside>
	</section>
</main>
<?= Template::Footer() ?>
