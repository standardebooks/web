<?
namespace Enums;

use function \Safe\preg_match;

enum UserIdentifierType{
	case Email;
	case Name;
	case UserId;
	case Uuid;

	public static function FromString(?string $identifier): ?UserIdentifierType{
		if($identifier === null || $identifier == ''){
			return null;
		}

		if(ctype_digit($identifier)){
			return UserIdentifierType::UserId;
		}
		elseif(\Validator::IsValidEmail($identifier)){
			return UserIdentifierType::Email;
		}
		elseif(preg_match('/^[0-9a-f]{8}\-[0-9a-f]{4}\-[0-9a-f]{4}\-[0-9a-f]{4}\-[0-9a-f]{12}$/', $identifier)){
			return UserIdentifierType::Uuid;
		}
		else{
			return UserIdentifierType::Name;
		}
	}
}
