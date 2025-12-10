<?
use Aws\SesV2\SesV2Client;
use Aws\Credentials\CredentialProvider;

class AwsSesApi{
	/**
	 * Remove an email address from the global SES suppression list.
	 *
	 * @throws Exceptions\AppException If the removal failed.
	 */
	public static function DeleteSuppressedEmail(string $email): void{
		$provider = CredentialProvider::ini('default', AWS_CREDENTIALS_PATH);
		$clientConfig = [
			'credentials'  => $provider,
			'version'      => 'latest',
			'region'       => AWS_SES_REGION,
			'http' => [
			// Ask for HTTP/2; if not available, falls back to HTTP/1.1 with keep-alive.
			'version'          => '2',
			'timeout'          => 15.0,
			'connect_timeout'  => 5.0,
			// Guzzle keeps connections alive by default when the client is reused.
			'headers'          => ['Connection' => 'keep-alive'],
			],
			'max_attempts' => 5,
		];

		try{
			$ses = new SesV2Client($clientConfig);
			$ses->deleteSuppressedDestination(['EmailAddress' => $email]);
		}
		catch(Aws\Exception\AwsException $ex){
			// Only bubble the exception up if it's an error other than "not in the suppression list".
			if($ex->getAwsErrorCode() != 'NotFoundException'){
				throw new Exceptions\AppException('Couldn’t delete email suppresion. AWS exception: ' . $ex->getAwsErrorMessage());
			}
		}
		catch(\Exception $ex){
			throw new Exceptions\AppException('Couldn’t delete email suppresion. Exception: ' . $ex->getMessage());
		}
	}

	/**
	 * Send any number of emails concurrently.
	 *
	 * @param array<EmailMessage|QueuedEmailMessage> $emails
	 * @param bool $forceSend If `SITE_STATUS` is `dev`, pass **`TRUE`** to actually send this request to SES; no effect if `SITE_STATUS` is `live`.
	 *
	 * @throws Exceptions\EmailSendFailedException If a batch send failed.
	 */
	public static function Send(array $emails, bool $forceSend = false): void{
		try{
			if(SITE_STATUS == SITE_STATUS_DEV && !$forceSend){
				foreach($emails as $email){
					if($email instanceof QueuedEmailMessage){
						$email->Delete();
					}
				}
				return;
			}

			$provider = CredentialProvider::ini('default', AWS_CREDENTIALS_PATH);

			$clientConfig = [
				'credentials'  => $provider,
				'version'      => 'latest',
				'region'       => AWS_SES_REGION,
				'http' => [
				// Ask for HTTP/2; if not available, falls back to HTTP/1.1 with keep-alive.
				'version'          => '2',
				'timeout'          => 15.0,
				'connect_timeout'  => 5.0,
				// Guzzle keeps connections alive by default when the client is reused.
				'headers'          => ['Connection' => 'keep-alive'],
				],
				'max_attempts' => 5,
			];

			$ses = new SesV2Client($clientConfig);
			$concurrency = AWS_SES_MAX_EMAILS_PER_SECOND;

			/** @var array<array<EmailMessage|QueuedEmailMessage>> $chunks */
			$chunks = array_chunk($emails, $concurrency);

			for($i = 0; $i < sizeof($chunks); $i++){
				$chunk = $chunks[$i];

				$startTime = microtime(true);
				$promises = [];
				foreach($chunk as $email){
					if($email->ToName !== null){
						$to = $email->ToName . ' <' . $email->To . '>';
					}
					else{
						$to = (string)$email->To;
					}

					$html = (string)$email->BodyHtml;
					$text = $email->BodyText;
					$from = (string)$email->From;
					$subject = $email->Subject;

					$result = mailparse_rfc822_parse_addresses($email->From);

					if(isset($result[0])){
						$from = $result[0]['address'];

						if($email->FromName !== null){
							$from = $email->FromName . ' <' . $from . '>';
						}
					}
					else{
						$from = (string)$email->From;
					}

					$params = [
						'FromEmailAddress' => $from,
						'Destination'      => ['ToAddresses' => [$to]],
						'Content'          => [
							'Simple' => [
								'Subject' => ['Data' => $subject, 'Charset' => 'UTF-8'],
								'Body'    => [
									'Html' => ['Data' => $html, 'Charset' => 'UTF-8']
								],
							],
						]
					];

					if($email->ReplyTo !== null){
						$params['ReplyToAddresses'] = [(string)$email->ReplyTo];
					}

					if($email->UnsubscribeUrl !== null){
						$params['Content']['Simple']['Headers'] = [
							[
								'Name' => 'List-Unsubscribe-Post',
								'Value' => 'List-Unsubscribe=One-Click'
							],
							[
								'Name' => 'List-Unsubscribe',
								'Value' => '<' . $email->UnsubscribeUrl . '>'
							]
						];
					}

					if(sizeof($email->Metadata) > 0){
						$params['EmailTags'] = [];

						foreach($email->Metadata as $key => $value){
							$params['EmailTags'][] = [
											'Name' => $key,
											'Value' => $value
										];
						}
					}

					if(sizeof($email->Attachments ?? []) > 0){
						$params['Content']['Simple']['Attachments'] = [];

						foreach($email->Attachments as $attachment){
							$array = [
								'ContentDisposition' => 'ATTACHMENT',
								'FileName' => $attachment['filename'],
								'RawContent' => $attachment['contents'],
								'ContentTransferEncoding' => 'BASE64' // SES automatically base64 encodes the attachment, but doesn't set the transfer encoding - we have to do that manually.
							];

							if(isset($attachment['mimeType'])){
								$array['ContentType'] = $attachment['mimeType'];
							}

							$params['Content']['Simple']['Attachments'][] = $array;
						}
					}

					if($text !== null){
						$params['Content']['Simple']['Body']['Text'] = ['Data' => $text, 'Charset' => 'UTF-8'];
					}

					// Kick off the async requests.
					$promises[] = $ses->sendEmailAsync($params)
						->then(
							function() use ($email){
								if($email instanceof QueuedEmailMessage){
									$email->Delete();
								}
							},
							function($reason) use ($email){
								$message  = $reason instanceof \Aws\Exception\AwsException
									? ($reason->getAwsErrorMessage() ?: $reason->getMessage())
									: (( $reason instanceof \Throwable) ? $reason->getMessage() : (string)$reason);
								$code = $reason instanceof \Aws\Exception\AwsException
									? ($reason->getAwsErrorCode() ?: $reason->getCode())
									: 'error';

								if($email instanceof QueuedEmailMessage){
									$email->Delete();
								}

								Log::WriteMailLogEntry('Failed sending email to ' . $email->To . '. SES message: ' . $message . " SES code: " . $code . "\nSubject: " . $email->Subject . "\nBody:\n" . $email->BodyHtml);
							}
						);
				}

				// Wait for this batch before launching the next.
				\GuzzleHttp\Promise\Utils::all($promises)->wait();

				if(isset($chunks[$i + 1])){
					// Pace batches to 1 second windows so we never exceed `$concurrency` req/s.
					$elapsedTime = microtime(true) - $startTime;
					if($elapsedTime < 1){
						usleep((int)ceil((1 - $elapsedTime) * 1000000));
					}
				}
			}
		}
		catch(\Exception $ex){
			throw new Exceptions\EmailSendFailedException('Failed sending mail. Exception: ' . $ex->getMessage());
		}
	}
}
