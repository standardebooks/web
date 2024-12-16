<?
use Safe\DateTimeImmutable;

class ProjectReminder{
	public int $ProjectId;
	public DateTimeImmutable $Created;
	public Enums\ProjectReminderType $Type;

	public function Create(): void{
		$this->Created = NOW;
		Db::Query('
				INSERT
				into ProjectReminders
				(
					ProjectId,
					Created,
					Type
				)
				values(
					?,
					?,
					?
				)
			', [$this->ProjectId, $this->Created, $this->Type]);
	}
}
