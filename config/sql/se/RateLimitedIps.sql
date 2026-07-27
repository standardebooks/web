CREATE TABLE IF NOT EXISTS `RateLimitedIps` (
  `IpAddress` inet6 NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `IpAddress` (`IpAddress`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
