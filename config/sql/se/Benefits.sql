CREATE TABLE IF NOT EXISTS `Benefits` (
  `UserId` int(10) unsigned NOT NULL,
  `CanAccessFeeds` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `CanVote` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `CanBulkDownload` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `CanUploadArtwork` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `CanReviewArtwork` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `CanReviewOwnArtwork` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `CanEditUsers` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`UserId`),
  KEY `idxBenefits` (`CanAccessFeeds`,`CanVote`,`CanBulkDownload`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
