<?
namespace Enums;

enum ProjectStatusType: string{
	case InProgress = 'in_progress';
	case AwaitingReview = 'awaiting_review';
	case Reviewed = 'reviewed';
	case Stalled = 'stalled';
	case Completed = 'completed';
	case Abandoned = 'abandoned';

	public function GetDisplayName(): string{
		return match($this){
			self::InProgress => 'in progress',
			self::AwaitingReview => 'awaiting review',
			default => $this->value
		};
	}
}
