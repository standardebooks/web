<?
use function Safe\exec;
use function Safe\file_get_contents;
use function Safe\json_decode;
use function Safe\glob;
use function Safe\shell_exec;

// This script makes various calls to external scripts using exec() (and when called via Apache, as the www-data user).
// These scripts are allowed using the /etc/sudoers.d/www-data file. Only the specific scripts
// in that file may be executed by this script.
try{
	$log = new Log(GITHUB_WEBHOOK_LOG_FILE_PATH);
	$lastPushHashFlag = '';

	HttpInput::ValidateRequestMethod([HttpMethod::Post]);

	$log->Write('Received GitHub webhook.');

	$post = file_get_contents('php://input');

	// Validate the GitHub secret.
	$splitHash = explode('=', $_SERVER['HTTP_X_HUB_SIGNATURE']);
	$hashAlgorithm = $splitHash[0];
	$hash = $splitHash[1];

	/** @var string $gitHubWebhookSecret */
	$gitHubWebhookSecret = get_cfg_var('se.secrets.github.se_vcs_bot.secret');

	if(!hash_equals($hash, hash_hmac($hashAlgorithm, $post, $gitHubWebhookSecret))){
		throw new Exceptions\InvalidCredentialsException();
	}

	// Sanity check before we continue.
	if(!array_key_exists('HTTP_X_GITHUB_EVENT', $_SERVER)){
		throw new Exceptions\WebhookException('Couldn\'t understand HTTP request.', $post);
	}

	/** @var array<array<string>> $data */
	$data = json_decode($post, true);

	// Decide what event we just received.
	switch($_SERVER['HTTP_X_GITHUB_EVENT']){
		case 'ping':
			// Silence on success.
			$log->Write('Event type: ping.');
			throw new Exceptions\NoopException();
		case 'push':
			$log->Write('Event type: push.');

			// Get the ebook ID. PHP doesn't throw exceptions on invalid array indexes, so check that first.
			if(!array_key_exists('repository', $data) || !array_key_exists('name', $data['repository'])){
				throw new Exceptions\WebhookException('Couldn\'t understand HTTP POST data.', $post);
			}

			$repoName = trim($data['repository']['name'], '/');

			if(in_array($repoName, GITHUB_IGNORED_REPOS)){
				$log->Write('Repo is in ignore list, no action taken.');
				throw new Exceptions\NoopException();
			}

			// Get the filesystem path for the ebook.
			$dir = REPOS_PATH . '/' . $repoName . '.git';

			// Confirm we're looking at a Git repo in our filesystem
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

			$log->Write('Processing ebook `' . $repoName . '` located at `' . $dir . '`.');

			// Check the local repo's last commit. If it matches this push, then don't do anything; we're already up to date.

			$lastCommitSha1 = trim(shell_exec('git -C ' . escapeshellarg($dir) . ' rev-parse HEAD 2>&1'));

			if($lastCommitSha1 == ''){
				$log->Write('Error getting last local commit. Output: ' . $lastCommitSha1);
				throw new Exceptions\WebhookException('Couldn\'t process ebook.', $post);
			}
			else{
				if($data['after'] == $lastCommitSha1){
					// This commit is already in our local repo, so silent success
					$log->Write('Local repo already in sync, no action taken.');
					throw new Exceptions\NoopException();
				}
			}

			// Get the current HEAD hash and save for later
			$output = [];
			exec('sudo --set-home --user se-vcs-bot git -C ' . escapeshellarg($dir) . ' rev-parse HEAD', $output, $returnCode);
			if($returnCode != 0){
				$log->Write('Couldn\'t get last commit of local repo. Output: ' . implode("\n", $output));
			}
			else{
				$lastPushHashFlag = ' --last-push-hash ' . escapeshellarg($output[0]);
			}

			// Now that we have the ebook filesystem path, pull the latest commit from GitHub.
			$output = [];
			exec('sudo --set-home --user se-vcs-bot ' . SITE_ROOT . '/scripts/pull-from-github ' . escapeshellarg($dir) . ' 2>&1', $output, $returnCode);
			if($returnCode != 0){
				$log->Write('Error pulling from GitHub. Output: ' . implode("\n", $output));
				throw new Exceptions\WebhookException('Couldn\'t process ebook.', $post);
			}
			else{
				$log->Write('`git pull` from GitHub complete.');
			}

			// Our local repo is now updated. Build the ebook!
			$output = [];
			exec('sudo --set-home --user se-vcs-bot tsp ' . SITE_ROOT . '/web/scripts/deploy-ebook-to-www' . $lastPushHashFlag . ' ' . escapeshellarg($dir) . ' 2>&1', $output, $returnCode);
			if($returnCode != 0){
				$log->Write('Error queueing ebook for deployment to web. Output: ' . implode("\n", $output));
				throw new Exceptions\WebhookException('Couldn\'t process ebook.', $post);
			}
			else{
				$log->Write('Queue for deployment to web complete.');
			}

			break;
		default:
			throw new Exceptions\WebhookException('Unrecognized GitHub webhook event.', $post);
	}

	// "Success, no content"
	http_response_code(204);
}
catch(Exceptions\InvalidCredentialsException){
	// "Forbidden"
	http_response_code(403);
}
catch(Exceptions\WebhookException $ex){
	// Uh oh, something went wrong!
	// Log detailed error and debugging information locally.
	$log->Write('Webhook failed! Error: ' . $ex->getMessage());
	$log->Write('Webhook POST data: ' . $ex->PostData);

	// Print less details to the client.
	print($ex->getMessage());

	// "Client error"
	http_response_code(400);
}
catch(Exceptions\NoopException){
	// We arrive here because a special case required us to take no action for the request, but execution also had to be interrupted.
	// For example, we received a request for a known repo for which we must ignore requests.

	// "Success, no content"
	http_response_code(204);
}
?>
