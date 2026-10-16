CREATE TABLE IF NOT EXISTS `BannedIpAddresses` (
  `IpAddress` inet6 NOT NULL,
  `Reason` text NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`IpAddress`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
