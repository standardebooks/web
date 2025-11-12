<?
namespace Enums;

enum EmailProviderType: string{
	case Postmark = 'postmark'; // Required for continued access to legacy `EmailBounce`s.
	case Ses = 'ses';
}
