<?
use Safe\DateTimeImmutable;

class ProjectReminder{
	public int $ProjectId;
	public DateTimeImmutable $CreatedAt;
	public Enums\ProjectReminderType $Type;

	public function Create(): void{
		$this->CreatedAt = NOW;
		Db::Query('
				INSERT
				into ProjectReminders
				(
					ProjectId,
					CreatedAt,
					Type
				)
				values(
					?,
					?,
					?
				)
			', [$this->ProjectId, $this->CreatedAt, $this->Type]);
	}
}
