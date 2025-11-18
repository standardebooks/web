<?
try{
	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanCreateUsers && !Session::$User->Benefits->CanEditUsers){
		throw new Exceptions\InvalidPermissionsException();
	}
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden); // No permissions to create `User`s.
}
?>
<?= Template::Header(
		title: 'Users',
		css: ['/css/user.css'],
		description: 'Manage users in the Standard Ebooks system.'
) ?>
<main>
	<section class="narrow has-hero">
		<h1>Users</h1>
		<picture data-caption="Rohan Road Combat, 29 July 1830. Hippolyte Lecomte, 1831">
			<source srcset="/images/rohan-road-combat@2x.avif 2x, /images/rohan-road-combat.avif 1x" type="image/avif"/>
			<source srcset="/images/rohan-road-combat@2x.jpg 2x, /images/rohan-road-combat.jpg 1x" type="image/jpg"/>
			<img src="/images/rohan-road-combat@2x.jpg" alt="Rioters shooting guns during the French Revolution."/>
		</picture>
		<? if(Session::$User->Benefits->CanCreateUsers){ ?>
			<ul>
				<li>
					<p>
						<a href="/users/new">Create a user</a>
					</p>
				</li>
			</ul>
		<? } ?>
		<section>
			<h2>Jump to user</h2>
			<form action="/users/get" method="<?= Enums\HttpMethod::Get->value ?>" autocomplete="off">
				<label class="icon user">
					<span>Identifier</span>
					<span>Can be a user ID, email, UUID, or name.</span>
					<input type="text" name="user-identifier" />
				</label>
				<div class="footer">
					<button>Go</button>
				</div>
			</form>
		</section>
	</section>
</main>
<?= Template::Footer() ?>
