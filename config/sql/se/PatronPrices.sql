create table if not exists PatronPrices (
	EffectiveAt datetime not null,
	MonthlyAmount decimal(8,2) unsigned not null,
	YearlyAmount decimal(8,2) unsigned not null,
	primary key (EffectiveAt)
) engine=InnoDB default charset=utf8mb4 collate=utf8mb4_unicode_520_ci;

-- Preserve historical prices and add a new effective date whenever prices change.
insert ignore into PatronPrices (EffectiveAt, MonthlyAmount, YearlyAmount)
values ('2010-01-01 00:00:00', 10, 100),
	('2024-12-05 00:00:00', 15, 150);
