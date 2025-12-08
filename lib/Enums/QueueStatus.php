<?
namespace Enums;

enum QueueStatus: string{
	case Queued = 'queued';
	case Processing = 'processing';
	case Completed = 'completed';
	case Failed = 'failed';
	case Canceled = 'canceled';
}
