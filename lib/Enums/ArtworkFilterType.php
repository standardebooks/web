<?
namespace Enums;

enum ArtworkFilterType: string{
	case Admin = 'admin'; // Show all artwork, regardless of status.
	case All = 'all'; // Show all artwork, but only approved artwork if the logged-in user is not an admin.
	case Approved = 'approved'; // Show all approved artwork.
	case ApprovedInUse = 'approved_in_use'; // Show all approved that is in-use.
	case ApprovedNotInUse = 'approved_not_in_use'; // Show all approved that is not in-use.
	case ApprovedSubmitter = 'approved_submitter'; // Show all approved artwork, plus unverified artwork which the logged-in user has submitted.
	case Declined = 'declined'; // Show only declined artwork.
	case Unverified = 'unverified'; // Show only unverified artwork.
	case UnverifiedSubmitter = 'unverified_submitter'; // Show only unverified artwork that the logged-in user has submitted.
}
