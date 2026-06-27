<?
/**
 * POST		/webhooks/github
 *
 * This script makes various calls to external scripts using `exec()` (and when called via Apache, as the `www-data` user).
 * These scripts are allowed using the `/etc/sudoers.d/www-data` file. Only the specific scripts in that file may be executed by this script.
 */

use function Safe\exec;
use function Safe\file_get_contents;
use function Safe\get_cfg_var;
use function Safe\glob;
use function Safe\json_decode;
use function Safe\shell_exec;

try{
	$log = new Log(GITHUB_WEBHOOK_LOG_FILE_PATH);
	$log->Queue('Received GitHub webhook.');

	$post = file_get_contents('php://input');

	// Validate the GitHub secret.
	$githubSignature = Http::$Request->Headers['x-hub-signature-256'] ?? '';
	$hash = explode('=', $githubSignature)[1] ?? throw new Exceptions\CredentialsInvalidException();

	/** @var string $gitHubWebhookSecret */
	$gitHubWebhookSecret = get_cfg_var('se.secrets.github.se_vcs_bot.secret');

	if(!hash_equals($hash, hash_hmac('sha256', $post, $gitHubWebhookSecret))){
		throw new Exceptions\CredentialsInvalidException();
	}

	// Sanity check before we continue.
	$event = Http::$Request->Headers['x-github-event'] ?? null;
	if($event === null){
		throw new Exceptions\WebhookException('Couldn\'t understand HTTP request.', $post);
	}

	/** @var array<array<string>> $data */
	$data = json_decode($post, true);

	// Decide what event we just received.
	switch($event){
		case 'ping':
			// Silence on success.
			$log->Queue('Event type: ping.');
			break;

		case 'push':
			$log->Queue('Event type: push.');

			// Get the ebook ID. PHP doesn't throw exceptions on invalid array indexes, so check that first.
			if(!array_key_exists('repository', $data) || !array_key_exists('name', $data['repository'])){
				throw new Exceptions\WebhookException('Couldn\'t understand HTTP POST data.', $post);
			}

			$repoName = trim($data['repository']['name'], '/');

			if(in_array($repoName, GITHUB_IGNORED_REPOS)){
				$log->Queue('Repo is in ignore list, no action taken.');
				break;
			}

			// Get the filesystem path for the ebook.
			$dir = REPOS_PATH . '/' . $repoName . '.git';

			// Confirm we're looking at a Git repo in our filesystem.
			if(!file_exists($dir . '/HEAD')){
				// We might be looking for a repo whose name is so long, it was truncated for GitHub. Try to check that here by simply globbing the rest.
				$dirs = glob(REPOS_PATH . '/' . $repoName . '*');
				if(sizeof($dirs) == 1){
					$dir = rtrim($dirs[0], '/');
				}

				if(!file_exists($dir . '/HEAD')){
					throw new Exceptions\WebhookException('Couldn\'t find repo "' . $repoName . '" in filesystem at "' . $dir . '".', $post);
				}
			}

			$log->Queue('Processing ebook `' . $repoName . '` located at `' . $dir . '`.');

			// Check the local repo's last commit. If it matches this push, then don't do anything; we're already up to date.

			$lastCommitSha1 = trim(shell_exec('git -C ' . escapeshellarg($dir) . ' rev-parse HEAD 2>&1') ?? '');

			if($lastCommitSha1 == ''){
				$log->Queue('Error getting last local commit. Output: ' . $lastCommitSha1);
				throw new Exceptions\WebhookException('Couldn\'t process ebook.', $post);
			}
			else{
				if($data['after'] == $lastCommitSha1){
					// This commit is already in our local repo, so silent success.
					$log->Queue('Local repo already in sync, no action taken.');
					break;
				}
			}

			$output = [];

			// Now that we have the ebook filesystem path, pull the latest commit from GitHub.
			$output = [];
			exec('sudo --set-home --user=se-vcs-bot ' . SITE_ROOT . '/scripts/pull-from-github ' . escapeshellarg($dir) . ' 2>&1', $output, $returnCode);

			$output = $output ?? [];

			if($returnCode != 0){
				$log->Queue('Error pulling from GitHub. Output: ' . implode("\n", $output));
				throw new Exceptions\WebhookException('Couldn\'t process ebook.', $post);
			}
			else{
				$log->Queue('`git pull` from GitHub complete.');
			}

			// Our local repo is now updated. Build the ebook!
			$output = [];
			exec('sudo --set-home --user=se-vcs-bot tsp ' . SITE_ROOT . '/web/scripts/deploy-ebook-to-www ' . escapeshellarg($dir) . ' 2>&1', $output, $returnCode);

			$output = $output ?? [];

			if($returnCode != 0){
				$log->Queue('Error queuing ebook for deployment to web. Output: ' . implode("\n", $output));
				throw new Exceptions\WebhookException('Couldn\'t process ebook.', $post);
			}
			else{
				$log->Queue('Queue for deployment to web complete.');
			}

			break;
		default:
			throw new Exceptions\WebhookException('Unrecognized GitHub webhook event.', $post);
	}

	// Don't write to the log if everything was successful.

	http_response_code(Enums\HttpCode::NoContent->value);
}
catch(Exceptions\CredentialsInvalidException){
	$log->Queue('Unable to validate credentials.');
	$log->WriteQueue();
	http_response_code(Enums\HttpCode::Forbidden->value);
}
catch(Exceptions\WebhookException $ex){
	// Uh oh, something went wrong!
	// Log detailed error and debugging information locally.
	$log->Queue('Webhook failed! Error: ' . $ex->getMessage());
	$log->Queue('Webhook POST data: ' . $ex->PostData);
	$log->WriteQueue();

	// Print fewer details to the client.
	print($ex->getMessage());

	http_response_code(Enums\HttpCode::BadRequest->value);
}
