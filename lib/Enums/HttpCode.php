<?
namespace Enums;

enum HttpCode: int{
	case Ok = 200;
	case Created = 201;
	case Accepted = 202;
	case NoContent = 204;

	/** Permanent redirect. */
	case MovedPermanently = 301;

	/** Temporary redirect. */
	case Found = 302;

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
	case TooManyRequests = 429;

	case InternalServerError = 500;
	case ServiceUnavailable = 503;
}
