<?
/**
 * GET		/users
 */

try{
	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanCreateUsers && !Session::$User->Benefits->CanEditUsers){
		throw new Exceptions\PermissionsInvalidException();
	}
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
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
		<picture data-caption="Friday at the French Artists’ Salon. Jules Alexandre Grün, 1911">
			<source srcset="/images/friday-at-the-french-artists-salon@2x.avif 2x, /images/friday-at-the-french-artists-salon.avif 1x" type="image/avif"/>
			<source srcset="/images/friday-at-the-french-artists-salon@2x.jpg 2x, /images/friday-at-the-french-artists-salon.jpg 1x" type="image/jpeg"/>
			<img src="/images/friday-at-the-french-artists-salon@2x.jpg" alt="A gathering of well-dressed artists in a grand hall."/>
		</picture>

		<? if(Session::$User->Benefits->CanCreateUsers){ ?>
			<ul role="menu">
				<li><a href="/users/new">Create a user</a></li>
			</ul>
		<? } ?>

		<section>
			<h2>Jump to user</h2>
			<form action="/users/get" method="<?= Enums\HttpMethod::Get->value ?>" autocomplete="off" rel="search" role="search">
				<label class="icon user">
					<span>Identifier</span>
					<span>Can be a user ID, email, UUID, or name.</span>
					<input type="search" name="user-identifier" />
				</label>
				<div class="footer">
					<button>Go</button>
				</div>
			</form>
		</section>
	</section>
</main>
<?= Template::Footer() ?>
