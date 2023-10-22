CREATE TABLE `AlternateSpellings` (
  `ArtistId` int(10) unsigned NOT NULL,
  `AlternateSpelling` varchar(255) NOT NULL,
  UNIQUE KEY `idxUnique` (`ArtistId`,`AlternateSpelling`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO AlternateSpellings VALUES
((SELECT ArtistId FROM Artists WHERE Name = 'Alexander Helwig Wyant'), 'A. H. Wyant'),
((SELECT ArtistId FROM Artists WHERE Name = 'Antonio Zeno Shindler'), 'Antonion Zeno Shindler'),
((SELECT ArtistId FROM Artists WHERE Name = 'Edward Poynter'), 'Edward John Poynter'),
((SELECT ArtistId FROM Artists WHERE Name = 'Élisabeth Vigée Le Brun'), 'Élisabeth Louise Vigée Le Brun'),
((SELECT ArtistId FROM Artists WHERE Name = 'Ernest Meissonier'), 'Jean-Louis Ernest Meissonier'),
((SELECT ArtistId FROM Artists WHERE Name = 'Francisco Goya'), 'Francisco José de Goya y Lucientes'),
((SELECT ArtistId FROM Artists WHERE Name = 'Frank Schoonover'), 'Frank E. Schoonover'),
((SELECT ArtistId FROM Artists WHERE Name = 'Frederic Leighton'), 'Frederick Leighton'),
((SELECT ArtistId FROM Artists WHERE Name = 'Frédéric Bazille'), 'Jean Frédéric Bazille'),
((SELECT ArtistId FROM Artists WHERE Name = 'George Bellows'), 'George Wesley Bellows'),
((SELECT ArtistId FROM Artists WHERE Name = 'Hieronymus Bosch'), 'Hieronymous Bosch'),
((SELECT ArtistId FROM Artists WHERE Name = 'Ilya Repin'), 'Illia Riepin'),
((SELECT ArtistId FROM Artists WHERE Name = 'Ilya Repin'), 'Илья Репин'),
((SELECT ArtistId FROM Artists WHERE Name = 'Ivan Shishkin'), 'Ivan Ivanovich Shishkin'),
((SELECT ArtistId FROM Artists WHERE Name = 'J. Allen St. John'), 'James Allen St. John'),
((SELECT ArtistId FROM Artists WHERE Name = 'James McNeill Whistler'), 'James Abbott McNeill Whistler'),
((SELECT ArtistId FROM Artists WHERE Name = 'Jean-Baptiste-Camille Corot'), 'Camille Corot'),
((SELECT ArtistId FROM Artists WHERE Name = 'Jean-Baptiste-Camille Corot'), 'Jean-Baptiste Corot'),
((SELECT ArtistId FROM Artists WHERE Name = 'Joaquín Sorolla'), 'Joaquín Sorolla y Bastida'),
((SELECT ArtistId FROM Artists WHERE Name = 'John Singer Sargent'), 'John Sargent'),
((SELECT ArtistId FROM Artists WHERE Name = 'Lawrence Alma-Tadema'), 'Sir Lawrence Alma-Tadema'),
((SELECT ArtistId FROM Artists WHERE Name = 'Pierre-Auguste Renoir'), 'Auguste Renoir'),
((SELECT ArtistId FROM Artists WHERE Name = 'Raphael'), 'Raffaello Sanzio'),
((SELECT ArtistId FROM Artists WHERE Name = 'Rembrandt van Rijn'), 'Rembrandt Harmenszoon van Rijn'),
((SELECT ArtistId FROM Artists WHERE Name = 'Willem van de Velde the Younger'), 'William van de Velde the Younger');
