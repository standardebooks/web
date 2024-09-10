<?
namespace Enums;

enum HttpCode: int{
	case Ok = 200;
	case Created = 201;
	case Accepted = 202;
	case NoContent = 204;

	case MovedPermanently = 301; // Permanent redirect
	case Found = 302; // Temporary redirect
	case SeeOther = 303;

	case BadRequest = 400;
	case Unauthorized = 401;
	case PaymentRequired = 402;
	case Forbidden = 403;
	case NotFound = 404;
	case MethodNotAllowed = 405;
	case Conflict = 409;
	case Gone = 410;
	case UnprocessableContent = 422;

	case InternalServerError = 500;
	case ServiceUnavailable = 503;
}
