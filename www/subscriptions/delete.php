<?
/**
 * GET		/users/:user-identifier/subscriptions/:subscription-id/delete
 */

try{
	$identifier = Http::$Request->QueryString->Get('user-identifier');
	$user = User::GetByIdentifier($identifier);
	$subscription = Subscription::Get(Http::$Request->QueryString->Get('subscription-id', 'int'));

	if($subscription->UserId != $user->UserId || $subscription->EndedAt !== null){
		throw new Exceptions\SubscriptionNotFoundException();
	}

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(Session::$User->UserId !== $user->UserId && !Session::$User->Benefits->CanEditUsers){
		throw new Exceptions\PermissionsInvalidException();
	}

	$isAdmin = Session::$User->UserId != $user->UserId && Session::$User->Benefits->CanEditUsers;

	if($subscription->Processor == Enums\PaymentProcessorType::FracturedAtlas){
		header('location: /help/managing-your-recurring-donation');
		exit();
	}
}
catch(Exceptions\UserNotFoundException | Exceptions\SubscriptionNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\AmbiguousUserException){
	Template::RedirectToDisambiguation($identifier);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
?><?= Template::Header(title: 'Cancel Your Recurring Donation - ' . $subscription->User->DisplayName, css: ['/css/user.css']) ?>
<main>
	<section class="narrow">
		<? if(Session::$User->UserId !== $subscription->User->UserId){ ?>
			<nav class="breadcrumbs" aria-label="Breadcrumbs">
				<a href="/users">Users</a> → <a href="<?= $subscription->User->Url ?>"><?= Formatter::EscapeHtml($subscription->User->DisplayName) ?></a> →
			</nav>
		<? } ?>
		<? if($isAdmin){ ?>
			<h1>Cancel Recurring Donation</h1>
			<p>Are you sure you want to cancel <?= Formatter::EscapeHtml($subscription->User->NamePossessive) ?> recurring donation of <?= Formatter::FormatCurrency($subscription->Amount, true) ?>/<? if($subscription->CycleType == Enums\CycleType::Monthly){ ?>month<? }else{ ?>year<? } ?>?</p>
		<? }else{ ?>
			<h1>Cancel Your Recurring Donation</h1>
			<p>Are you sure you want to cancel your recurring donation of <?= Formatter::FormatCurrency($subscription->Amount, true) ?>/<? if($subscription->CycleType == Enums\CycleType::Monthly){ ?>month<? }else{ ?>year<? } ?>?</p>
		<? } ?>
		<form method="post" action="<?= $subscription->User->Url ?>/subscriptions/<?= $subscription->SubscriptionId ?>">
			<input type="hidden" name="_method" value="DELETE"/>
			<div class="footer">
				<button class="delete">Cancel recurring donation</button>
			</div>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
