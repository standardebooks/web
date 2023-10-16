CREATE TABLE `AlternateSpellings` (
  `ArtistId` int(10) unsigned NOT NULL,
  `AlternateSpelling` varchar(255) NOT NULL,
  UNIQUE KEY `idxUnique` (`ArtistId`,`AlternateSpelling`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO AlternateSpellings VALUES 
((SELECT ArtistId FROM Artists WHERE Name = 'Antonio Zeno Shindler'), 'Antonion Zeno Shindler'),
((SELECT ArtistId FROM Artists WHERE Name = 'Edward John Poynter'), 'Edward Poynter'),
((SELECT ArtistId FROM Artists WHERE Name = 'Élisabeth Louise Vigée Le Brun'), 'Élisabeth Vigée Le Brun'),
((SELECT ArtistId FROM Artists WHERE Name = 'Francisco José de Goya y Lucientes'), 'Francisco Goya'),
((SELECT ArtistId FROM Artists WHERE Name = 'Frederic Leighton'), 'Frederick Leighton'),
((SELECT ArtistId FROM Artists WHERE Name = 'Ivan Ivanovich Shishkin'), 'Ivan Shishkin'),
((SELECT ArtistId FROM Artists WHERE Name = 'Joaquín Sorolla y Bastida'), 'Joaquín Sorolla'),
((SELECT ArtistId FROM Artists WHERE Name = 'John Singer Sargent'), 'John Sargent'),
((SELECT ArtistId FROM Artists WHERE Name = 'Pierre-Auguste Renoir'), 'Auguste Renoir'),
((SELECT ArtistId FROM Artists WHERE Name = 'Raphael'), 'Raffaello Sanzio'),
((SELECT ArtistId FROM Artists WHERE Name = 'Rembrandt Harmenszoon van Rijn'), 'Rembrandt van Rijn');
