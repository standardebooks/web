<?
use Safe\DateTimeImmutable;

use function Safe\json_encode;
use function Safe\json_decode;

class QueuedEmailMessage extends EmailMessage{
	use Traits\FromRow;

	public int $QueuedEmailMessageId;
	public DateTimeImmutable $Created;
	public Enums\Priority $Priority = Enums\Priority::Normal;
	public Enums\EmailProviderType $Provider = Enums\EmailProviderType::Ses;

	public function Create(): void{
		try{
			$this->Validate();

			$this->Created = NOW;

			$attachments = sizeof($this->Attachments ?? []) > 0 ? serialize($this->Attachments) : null;
			$metadata = json_encode($this->Metadata);

			// Warning: `To` and `From` have to be in ticks because they're SQL keywords.
			Db::Query('insert into QueuedEmailMessages (`To`, ToName, `From`, FromName, ReplyTo, Subject, BodyHtml, BodyText, Priority, UnsubscribeUrl, Created, Provider, Attachments, Metadata) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [$this->To, $this->ToName, $this->From, $this->FromName, $this->ReplyTo, $this->Subject, $this->BodyHtml, $this->BodyText, $this->Priority, $this->UnsubscribeUrl, $this->Created, $this->Provider, $attachments, $metadata]);
		}
		catch(Exceptions\InvalidEmailMessageException $ex){
			Log::WriteErrorLogEntry('Failed validating `QueuedEmailMessage`. Exception: ' . $ex->getMessage() . "\n" . 'Email: ' . vds($this));
		}
	}

	/**
	 * Override the parent class's `Send()` method to prevent us from accidentally sending an email without queueing it.
	 */
	public function Send(): void{
		$this->Create();
	}

	/**
	 * Queue a batch of `QueuedEmailMessage`s.
	 *
	 * @param array<QueuedEmailMessage> $queuedEmailMessages
	 */
	public static function CreateBatch(array $queuedEmailMessages): void{
		// MariaDB has a hard limit on the number of `?` placeholders in a query, so chunk to 100 inserts at a time.
		$chunks = array_chunk($queuedEmailMessages, 100);

		foreach($chunks as $chunk){
			$sql = 'insert into QueuedEmailMessages (`To`, ToName, `From`, FromName, ReplyTo, Subject, BodyHtml, BodyText, Priority, UnsubscribeUrl, Created, Provider, Attachments, Metadata) values ';

			$arguments = [];
			foreach($chunk as $em){
				try{
					$em->Validate();

					$sql .= '(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?),';

					$attachments = sizeof($em->Attachments) > 0 ? serialize($em->Attachments) : null;
					$metadata = json_encode($em->Metadata);

					$arguments = array_merge($arguments, [$em->To, $em->ToName, $em->From, $em->FromName, $em->ReplyTo, $em->Subject, $em->BodyHtml, $em->BodyText, $em->Priority,  $em->UnsubscribeUrl, NOW, \Enums\EmailProviderType::Ses, $attachments, $metadata]);
				}
				catch(Exceptions\InvalidEmailMessageException $ex){
					Log::WriteErrorLogEntry('Failed validating email. Exception: ' . $ex->getMessage() . "\n" . 'Email: ' . vds($em));
				}
			}

			$sql = rtrim($sql, ',');

			Db::Query($sql, $arguments);
		}
	}

	public static function FromRow(stdClass $row): QueuedEmailMessage{
		$attachments = $row->Attachments;
		unset($row->Attachments);
		if($attachments !== null){
			$attachments = unserialize($attachments);
		}
		else{
			$attachments = [];
		}

		/** @var array<array{contents: string, filename: string}> $attachments */

		/** @var array<string, string> $metadata */
		$metadata = json_decode($row->Metadata, true);
		unset($row->Metadata);

		$object = self::FillObject(new QueuedEmailMessage(), $row);

		$object->Attachments = $attachments;
		$object->Metadata = $metadata;

		return $object;
	}

	public function Delete(): void{
		Db::Query('DELETE from QueuedEmailMessages where QueuedEmailMessageId = ?', [$this->QueuedEmailMessageId]);
	}
}
