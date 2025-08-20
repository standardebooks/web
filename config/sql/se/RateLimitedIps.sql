CREATE TABLE IF NOT EXISTS `RateLimitedIps` (
  `IpAddress` inet6 NOT NULL,
  `Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `idxUnique` (`IpAddress`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
