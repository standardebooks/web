<?
namespace Enums;

enum ProjectReminderType: string{
	/** An email to nudge the producer on a stalled project. */
	case Stalled = 'stalled';

	/** An email to notify the producer we are considering their project abandoned. */
	case Abandoned = 'abandoned';
}
