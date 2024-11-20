<?
namespace Enums;

enum ArtworkFilterType: string{
	/** Show all artwork, regardless of status. */
	case Admin = 'admin';

	/** Show all artwork, but only approved artwork if the logged-in user is not an admin. */
	case All = 'all';

	/** Show all approved artwork. */
	case Approved = 'approved';

	/** Show all approved that is in-use. */
	case ApprovedInUse = 'approved_in_use';

	/** Show all approved that is not in-use. */
	case ApprovedNotInUse = 'approved_not_in_use';

	/** Show all approved artwork, plus unverified artwork which the logged-in user has submitted. */
	case ApprovedSubmitter = 'approved_submitter';

	/** Show only declined artwork. */
	case Declined = 'declined';

	/** Show only unverified artwork. */
	case Unverified = 'unverified';

	/** Show only unverified artwork that the logged-in user has submitted. */
	case UnverifiedSubmitter = 'unverified_submitter';
}
