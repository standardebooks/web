<?
namespace Enums;

enum EmailBounceType: string{
	case AccountDeactivated = 'account_deactivated';
	case Hard = 'hard';
	case InvalidAddress = 'invalid_address';
	case IspBlock = 'isp_block';
	case Spam = 'spam';
	case Soft = 'soft';
}
