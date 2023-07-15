CREATE TABLE `Museums` (
  `MuseumId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Domain` varchar(255) NOT NULL,
  PRIMARY KEY (`MuseumId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO Museums (Name, Domain)
VALUES
('Rijksmuseum', 'rijksmuseum.nl'),
('Met Museum', 'metmuseum.org'),
('National Museum Sweden', 'nationalmuseum.se'),
('Minneapolis Institute of Art', 'collections.artsmia.org'),
('The Walters Art Museum', 'art.thewalters.org'),
('Art Institute of Chicago', 'artic.edu'),
('Cleveland Museum of Art', 'clevelandart.org'),
('Paris Musées', 'parismuseescollections.paris.fr'),
('The Smithsonian', 'si.edu'),
('Birmingham Museums', 'dams.birminghammuseums.org.uk'),
('National Museum in Krakow', 'zbiory.mnk.pl'),
('National Gallery of Denmark', 'open.smk.dk'),
('Finnish National Gallery', 'kansallisgalleria.fi'),
('National Gallery of Art', 'nga.gov'),
('Nivaagaards Malerisamling', 'nivaagaard.dk'),
('RISD Museum', 'risdmuseum.org'),
('Aberdeen Archives, Gallery & Museums', 'emuseum.aberdeencity.gov.uk'),
('Brighton & Hove Museums', 'collections.brightonmuseums.org.uk'),
('Grand Rapids Public Museum', 'grpmcollections.org'),
('Thorvaldsens Museum', 'kataloget.thorvaldsensmuseum.dk'),
('Musea Brugge', 'museabrugge.be'),
('Yale Center for British Art', 'collections.britishart.yale.edu'),
('Staatliche Kunsthalle Karlsruhe', 'kunsthalle-karlsruhe.de');
