<?
namespace Enums;

enum ProjectStatusType: string{
	case InProgress = 'in_progress';
	case Stalled = 'stalled';
	case Completed = 'completed';
	case Abandoned = 'abandoned';

	public function GetDisplayName(): string{
		return match($this){
			self::InProgress => 'in progress',
			default => $this->value
		};
	}
}
