<?
try{
	$user = User::GetByIdentifier(HttpInput::Str(GET, 'user-identifier'));

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(
		!Session::$User->Benefits->CanManageProjects
		&&
		!Session::$User->Benefits->CanReviewProjects
		&&
		!Session::$User->Benefits->CanEditProjects
	){
		throw new Exceptions\InvalidPermissionsException();
	}

	$managingProjects = Project::GetAllByManagerUserId($user->UserId);
	$reviewingProjects = Project::GetAllByReviewerUserId($user->UserId);
}
catch(Exceptions\UserNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
?><?= Template::Header(
	title: 'Projects',
	canonicalUrl: $user->Url,
	css: ['/css/project.css'],
	description: 'Ebook projects currently underway at Standard Ebooks.'
) ?>
<main>
	<section>
		<nav class="breadcrumbs">
			<? if(Session::$User->Benefits->CanEditUsers){ ?>
				<a href="<?= $user->Url ?>"><?= Formatter::EscapeHtml($user->DisplayName) ?></a>
			<? }else{ ?>
				<?= Formatter::EscapeHtml($user->DisplayName) ?>
			<? } ?>
			→
		</nav>
		<h1>Projects</h1>
		<section id="managing">
			<h2>Managing</h2>
			<? if(sizeof($managingProjects) == 0){ ?>
				<p class="empty-notice">None.</p>
			<? }else{ ?>
				<?= Template::ProjectsTable(projects: $managingProjects) ?>
			<? } ?>
		</section>

		<section id="reviewing">
			<h2>Reviewing</h2>
			<? if(sizeof($reviewingProjects) == 0){ ?>
				<p class="empty-notice">None.</p>
			<? }else{ ?>
				<?= Template::ProjectsTable(projects: $reviewingProjects) ?>
			<? } ?>
		</section>
	</section>
</main>
<?= Template::Footer() ?>
