CREATE DATABASE BD2Los_Primary_Keys;
USE BD2Los_Primary_Keys;

CREATE TABLE Gato(
    XIP VARCHAR(15) PRIMARY KEY,
    nombre VARCHAR(30),
    sexo ENUM('Macho','Hembra'),
    edad SMALLINT UNSIGNED,
    aspecto VARCHAR(200),
    foto VARCHAR(20)
);

CREATE TABLE Isla(
    codiIsla INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE Voluntario(
    DNI VARCHAR(9) PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    contraseña VARCHAR(50) NOT NULL,
    telefono VARCHAR(15),
    email VARCHAR(50)
);

CREATE TABLE Ayuntamiento(
    codiAyuntamiento VARCHAR(10) PRIMARY KEY,
    municipio VARCHAR(50) NOT NULL,
    contraseña VARCHAR(50) NOT NULL,
    direccion VARCHAR(100) NOT NULL,
    telefono VARCHAR(15),
    email VARCHAR(50),
    codiIsla INT NOT NULL,
    CONSTRAINT fk_ayuntamiento_isla FOREIGN KEY (codiIsla) REFERENCES Isla(codiIsla) ON DELETE CASCADE
);
CREATE TABLE Colonia(
    idColonia INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    codiAyuntamiento VARCHAR(10) NOT NULL,
    CONSTRAINT fk_colonia_ayuntamiento FOREIGN KEY (codiAyuntamiento) REFERENCES Ayuntamiento(codiAyuntamiento) ON DELETE CASCADE
);

CREATE TABLE Pertenencia(
    id_pertenencia INT AUTO_INCREMENT PRIMARY KEY,
    XIP VARCHAR(15) NOT NULL,
    idColonia INT NOT NULL,
    fechaInicio DATE NOT NULL,
    fechaFin DATE,
    CONSTRAINT fk_pertenencia_gato FOREIGN KEY (XIP) REFERENCES Gato(XIP) ON DELETE CASCADE,
    CONSTRAINT fk_pertenencia_colonia FOREIGN KEY (idColonia) REFERENCES Colonia(idColonia) ON DELETE CASCADE
);


CREATE TABLE Ubicacion(
    idUbicacion INT AUTO_INCREMENT PRIMARY KEY,
    latitud DECIMAL(10, 4) NOT NULL,
    longitud DECIMAL(11, 4) NOT NULL,
    descripcion VARCHAR(200),
    comentarios VARCHAR(200),
    idColonia INT NOT NULL,
    UNIQUE(latitud,longitud),
    CONSTRAINT fk_ubicacion_colonia FOREIGN KEY (idColonia) REFERENCES Colonia(idColonia) ON DELETE CASCADE
);

CREATE TABLE Grupo(
    idGrupo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    codiAyuntamiento VARCHAR(10) NOT NULL,
    responsableDNI VARCHAR(9),
    CONSTRAINT fk_grupo_voluntario FOREIGN KEY (responsableDNI) REFERENCES Voluntario(DNI) ON DELETE SET NULL,
    CONSTRAINT fk_grupo_ayuntamiento FOREIGN KEY (codiAyuntamiento) REFERENCES Ayuntamiento(codiAyuntamiento) ON DELETE CASCADE
);

CREATE TABLE Ayuda(
    idAyuda INT AUTO_INCREMENT PRIMARY KEY,
    observaciones VARCHAR(200),
    idGrupo INT NOT NULL,
    DNI VARCHAR(9) NOT NULL,
    CONSTRAINT fk_ayuda_grupo FOREIGN KEY (idGrupo) REFERENCES Grupo(idGrupo) ON DELETE CASCADE,
    CONSTRAINT fk_ayuda_voluntario FOREIGN KEY (DNI) REFERENCES Voluntario(DNI) ON DELETE CASCADE,
    UNIQUE(idGrupo, DNI)
);

CREATE TABLE Reporte(
    codiReporte INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    asunto VARCHAR(100) NOT NULL,
    cuerpo VARCHAR(500) NOT NULL,
    idGrupo INT NOT NULL,
    CONSTRAINT fk_reporte_grupo FOREIGN KEY (idGrupo) REFERENCES Grupo(idGrupo) ON DELETE CASCADE
);

CREATE TABLE Borsi (
    idBorsi INT AUTO_INCREMENT PRIMARY KEY,
    codiAyuntamiento VARCHAR(10) NOT NULL,
    DNI VARCHAR(9) NOT NULL,
    fechaInscripcion DATE NOT NULL,
    CONSTRAINT fk_borsi_ayuntamiento FOREIGN KEY (codiAyuntamiento)
        REFERENCES Ayuntamiento(codiAyuntamiento) ON DELETE CASCADE,
    CONSTRAINT fk_borsi_voluntario FOREIGN KEY (DNI)
        REFERENCES Voluntario(DNI) ON DELETE CASCADE,
    UNIQUE (codiAyuntamiento, DNI)
);

CREATE TABLE Albirament (
    idAlbirament INT AUTO_INCREMENT PRIMARY KEY,
    XIP VARCHAR(15) NOT NULL,
    idColoniaNova INT NOT NULL,
    dataAlbirament TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    observacions VARCHAR(500),
    FOREIGN KEY (XIP) REFERENCES Gato(XIP) ON DELETE CASCADE,
    FOREIGN KEY (idColoniaNova) REFERENCES Colonia(idColonia) ON DELETE CASCADE
);

CREATE TABLE Deteccion (
    idDeteccion INT AUTO_INCREMENT PRIMARY KEY,
    XIP VARCHAR(15) NOT NULL,
    idColoniaDetectada INT NOT NULL,
    dataDeteccion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (XIP) REFERENCES Gato(XIP) ON DELETE CASCADE,
    FOREIGN KEY (idColoniaDetectada) REFERENCES Colonia(idColonia) ON DELETE CASCADE
);

CREATE TABLE Backup_Colonias_Poblacion (
    idBackup INT AUTO_INCREMENT PRIMARY KEY,
    fechaBackup DATE NOT NULL,
    datos_completos TEXT NOT NULL
);


-- INSERTS

INSERT INTO Gato (XIP, nombre, sexo, edad, aspecto, foto) VALUES
('XIP001', 'Luna', 'Hembra', 3, 'Gato atigrado muy sociable', 'gato1.jpg'),
('XIP002', 'Shadow', 'Macho', 2, 'Negro pequeño y nervioso', 'gato2.jpg'),
('XIP003', 'Nieve', 'Hembra', 4, 'Blanco con una mancha negra en la cabeza', 'gato3.jpg'),
('XIP004', 'Ceniza', 'Macho', 5, 'Gris claro', 'gato4.jpg'),
('XIP005', 'Simba', 'Macho', 1, NULL, 'gato5.jpg'),
('XIP006', 'Tigre', 'Macho', 6, 'Atigrado con cola corta', NULL),
('XIP007', 'Carbon', 'Macho', 3, 'Negro grande', NULL),
('XIP008', 'Misty', 'Hembra', 2, NULL, 'gato8.jpg'),
('XIP009', 'Gris', 'Macho', 7, 'Gris oscuro y desconfiado', 'gato9.jpg'),
('XIP010', 'Canela', 'Hembra', 4, 'Carey', 'gato10.jpg'),
('XIP011', 'Rayas', 'Macho', 5, 'Atigrado', 'gato11.jpg'),
('XIP012', 'Fantasma', 'Macho', 2, NULL, NULL),
('XIP013', 'Oreo', 'Macho', 3, 'Blanco y negro', 'gato13.jpg'),
('XIP014', 'Esmeralda', 'Hembra', 4, 'Gris con ojos verdes', 'gato14.jpg'),
('XIP015', 'Miel', 'Hembra', 5, 'Carey muy tranquilo', 'gato15.jpg'),
('XIP016', 'Ambar', 'Macho', 2, 'Atigrado claro', 'gato16.jpg'),
('XIP017', 'Manchas', 'Macho', 6, 'Negro con pequeña mancha blanca', 'gato17.jpg'),
('XIP018', 'Blanca', 'Hembra', 1, NULL, 'gato18.jpg'),
('XIP019', 'Garfio', 'Macho', 4, 'Gris rayado', 'gato19.jpg'),
('XIP020', 'Coco', 'Hembra', 2, 'Carey joven', 'gato20.jpg'),
('XIP021', 'Leon', 'Macho', 7, 'Atigrado grande', 'gato21.jpg'),
('XIP022', 'Midnight', 'Macho', 3, 'Negro', 'gato22.jpg'),
('XIP023', 'Nube', 'Hembra', 5, NULL, 'gato23.jpg'),
('XIP024', 'Algodon', 'Hembra', 4, 'Blanco', 'gato24.jpg'),
('XIP025', 'Humo', 'Macho', 6, 'Gris', 'gato25.jpg'),
('XIP026', 'Caramelo', 'Hembra', 3, 'Carey con carácter', 'gato26.jpg'),
('XIP027', 'Misterio', 'Macho', 2, NULL, NULL),
('XIP028', 'Flaco', 'Macho', 5, 'Atigrado muy delgado', 'gato28.jpg'),
('XIP029', 'Onyx', 'Macho', 4, 'Negro', 'gato29.jpg'),
('XIP030', 'Zafiro', 'Hembra', 3, 'Blanco con ojos azules', 'gato30.jpg'),
('XIP031', 'Titan', 'Macho', 8, 'Gris grande', NULL),
('XIP032', 'Sombra', 'Hembra', 1, NULL, 'gato32.jpg'),
('XIP033', 'Marble', 'Hembra', 4, 'Carey', 'gato33.jpg'),
('XIP034', 'Chispa', 'Macho', 2, 'Atigrado joven', 'gato34.jpg'),
('XIP035', 'Enigma', 'Hembra', 3, NULL, NULL),
('XIP036', 'Obsidian', 'Macho', 5, 'Negro', 'gato36.jpg'),
('XIP037', 'Blizzard', 'Hembra', 4, 'Blanco', 'gato37.jpg'),
('XIP038', 'Tormenta', 'Macho', 6, 'Gris rayado', NULL),
('XIP039', 'Marmol', 'Hembra', 3, 'Carey', 'gato39.jpg'),
('XIP040', 'Jaguar', 'Macho', 4, 'Atigrado', 'gato40.jpg'),
('XIP041', 'Bear', 'Macho', 7, 'Negro grande', 'gato41.jpg'),
('XIP042', 'Niebla', 'Hembra', 2, NULL, 'gato42.jpg'),
('XIP043', 'Angel', 'Hembra', 3, 'Blanco', 'gato43.jpg'),
('XIP044', 'Plata', 'Hembra', 5, 'Gris claro', NULL),
('XIP045', 'Cinnamon', 'Hembra', 4, 'Carey', 'gato45.jpg'),
('XIP046', 'Tigger', 'Macho', 3, 'Atigrado', 'gato46.jpg'),
('XIP047', 'Phantom', 'Macho', 2, NULL, NULL),
('XIP048', 'Raven', 'Macho', 6, 'Negro', 'gato48.jpg'),
('XIP049', 'Snowball', 'Hembra', 1, NULL, NULL),
('XIP050', 'Smokey', 'Macho', 4, 'Gris y muy sociable', 'gato50.jpg');


INSERT INTO Isla (nombre) VALUES
('Mallorca'),
('Menorca'),
('Ibiza'),
('Formentera');

INSERT INTO Ayuntamiento (codiAyuntamiento, municipio, contraseña, direccion, telefono, email, codiIsla) VALUES
('AY1','Palma','ay1','C/ Palma 1','971000001','palma@ay.es',1),
('AY2','Calvià','ay2','C/ Calvia 2','971000002','calvia@ay.es',1),
('AY3','Inca','ay3','C/ Inca 3','971000003','inca@ay.es',1),
('AY4','Maó','ay4','C/ Mao 4','971000004','mao@ay.es',2),
('AY5','Ciutadella','ay5','C/ Ciutadella 5','971000005','ciutadella@ay.es',2),
('AY6','Eivissa','ay6','C/ Eivissa 6','971000006','eivissa@ay.es',3),
('AY7','Sant Antoni','ay7','C/ Sant Antoni 7','971000007','santantoni@ay.es',3),
('AY8','Sant Josep','ay8','C/ Sant Josep 8','971000008','santjosep@ay.es',3),
('AY9','Formentera','ay9','C/ Formentera 9','971000009','formentera@ay.es',4),
('AY10','Sant Francesc','ay10','C/ Francesc 10','971000010','santfrancesc@ay.es',4);

INSERT INTO Voluntario (DNI, nombre, apellidos, contraseña, telefono, email) VALUES
('11111111A','Ana','López García','pass1','600000001','ana1@mail.com'),
('22222222B','Juan','Pérez Martínez','pass2','600000002','juan2@mail.com'),
('33333333C','Laura','Gómez','pass3','600000003','laura3@mail.com'),
('44444444D','Pedro','Martín Ruiz','pass4','600000004','pedro4@mail.com'),
('55555555E','Marta','Ruiz','pass5','600000005','marta5@mail.com'),
('66666666F','Luis','Sánchez Ortega','pass6','600000006','luis6@mail.com'),
('77777777G','Sara','Torres','pass7','600000007','sara7@mail.com'),
('88888888H','David','Navarro López','pass8','600000008','david8@mail.com'),
('99999999I','Elena','Romero','pass9','600000009','elena9@mail.com'),
('10101010J','Carlos','Vega Martín','pass10','600000010','carlos10@mail.com'),
('11111112K','Paula','Molina','pass11','600000011','paula11@mail.com'),
('22222223L','Jorge','Ortega López','pass12','600000012','jorge12@mail.com'),
('33333334M','Nuria','Castro','pass13','600000013','nuria13@mail.com'),
('44444445N','Iván','Guerrero','pass14','600000014','ivan14@mail.com'),
('55555556O','Clara','Ramos Díaz','pass15','600000015','clara15@mail.com'),
('66666667P','Raúl','Gil','pass16','600000016','raul16@mail.com'),
('77777778Q','Bea','Serrano','pass17','600000017','bea17@mail.com'),
('88888889R','Hugo','Blanco Pérez','pass18','600000018','hugo18@mail.com'),
('99999990S','Irene','Cruz','pass19','600000019','irene19@mail.com'),
('20202020T','Mario','Flores Sánchez','pass20','600000020','mario20@mail.com'),
('21212121U','Lucía','Prieto','pass21','600000021','lucia21@mail.com'),
('22222222V','Álvaro','León Gómez','pass22','600000022','alvaro22@mail.com'),
('23232323W','Rosa','Campos','pass23','600000023','rosa23@mail.com'),
('24242424X','Pablo','Suárez Martín','pass24','600000024','pablo24@mail.com'),
('25252525Y','Noelia','Rey','pass25','600000025','noelia25@mail.com'),
('26262626Z','Sergio','Morales','pass26','600000026','sergio26@mail.com'),
('27272727A','María','Lozano','pass27','600000027','maria27@mail.com'),
('28282828B','Andrés','Cano','pass28','600000028','andres28@mail.com'),
('29292929C','Elisa','Vega','pass29','600000029','elisa29@mail.com'),
('30303030D','Raquel','Pons','pass30','600000030','raquel30@mail.com'),
('31313131E','Tomás','Ferrer','pass31','600000031','tomas31@mail.com'),
('32323232F','Noa','Castaño','pass32','600000032','noa32@mail.com'),
('33333335G','Óscar','Beltrán','pass33','600000033','oscar33@mail.com'),
('34343434H','Lidia','Marí','pass34','600000034','lidia34@mail.com'),
('35353535I','Iván','Soler','pass35','600000035','ivan35@mail.com'),
('36363636J','Marta','Pascual','pass36','600000036','marta36@mail.com'),
('37373737K','Héctor','Soler','pass37','600000037','hector37@mail.com'),
('38383838L','Aina','Serra','pass38','600000038','aina38@mail.com'),
('12121212K', 'Carlos', 'Reyes Sánchez', 'pass39', '600000039', 'carlos39@mail.com'),
('13131313L', 'Elena', 'Méndez Cruz', 'pass40', '600000040', 'elena40@mail.com'),
('14141414M', 'Roberto', 'Fuentes Díaz', 'pass41', '600000041', 'roberto41@mail.com'),
('15151515N', 'Sandra', 'Gallego Ruiz', 'pass42', '600000042', 'sandra42@mail.com'),
('16161616O', 'Miguel', 'Costa Ferrer', 'pass43', '600000043', 'miguel43@mail.com'),
('17171717P', 'Patricia', 'Miralles Torres', 'pass44', '600000044', 'patricia44@mail.com'),
('18181818Q', 'Francisco', 'Amengual Pons', 'pass45', '600000045', 'francisco45@mail.com'),
('19191919R', 'Isabel', 'Sintes Marí', 'pass46', '600000046', 'isabel46@mail.com'),
('20202020S', 'Antonio', 'Coll Serra', 'pass47', '600000047', 'antonio47@mail.com'),
('21212121T', 'Carmen', 'Ribas Gómez', 'pass48', '600000048', 'carmen48@mail.com'),
('22222222U', 'Javier', 'Cardona Martín', 'pass49', '600000049', 'javier49@mail.com'),
('23232323V', 'Teresa', 'Bonet Llull', 'pass50', '600000050', 'teresa50@mail.com'),
('24242424W', 'Ricardo', 'Pons Vidal', 'pass51', '600000051', 'ricardo51@mail.com'),
('25252525X', 'Margarita', 'Mayol Salas', 'pass52', '600000052', 'margarita52@mail.com'),
('26262626Y', 'Gabriel', 'Ferrer Moll', 'pass53', '600000053', 'gabriel53@mail.com'),
('27272727Z', 'Cristina', 'Marí Palou', 'pass54', '600000054', 'cristina54@mail.com'),
('28282828A', 'Fernando', 'Pujols Riera', 'pass55', '600000055', 'fernando55@mail.com'),
('29292929B', 'Olga', 'Tur Ferrà', 'pass56', '600000056', 'olga56@mail.com'),
('30303030C', 'Alfonso', 'Savina Gomila', 'pass57', '600000057', 'alfonso57@mail.com'),
('31313131D', 'Silvia', 'Barbaria Torrent', 'pass58', '600000058', 'silvia58@mail.com');

INSERT INTO Colonia (nombre, codiAyuntamiento) VALUES
('Colonia Centro Palma','AY1'),
('Colonia Parque Palma','AY1'),
('Colonia Calvià Mar','AY2'),
('Colonia Inca Norte','AY3'),
('Colonia Inca Sur','AY3'),
('Colonia Maó Puerto','AY4'),
('Colonia Ciutadella Oeste','AY5'),
('Colonia Eivissa Centro','AY6'),
('Colonia Sant Antoni','AY7'),
('Colonia Sant Josep','AY8'),
('Colonia Formentera Playa','AY9'),
('Colonia Sant Francesc','AY10'),
('Colonia Palma Este','AY1'),
('Colonia Palma Oeste','AY1'),
('Colonia Maó Centro','AY4');

INSERT INTO Pertenencia (XIP, idColonia, fechaInicio, fechaFin) VALUES
-- GATO 1 (cambio)
('XIP001',1,'2023-01-01','2023-06-01'),
('XIP001',2,'2023-06-01',NULL),
-- GATO 2
('XIP002',1,'2023-01-05',NULL),
-- GATO 3
('XIP003',2,'2023-01-10',NULL),
-- GATO 4 (cambio)
('XIP004',3,'2023-02-01','2023-08-01'),
('XIP004',4,'2023-08-01','2024-03-05'),
('XIP004',3,'2024-03-05',NULL),
-- GATO 5
('XIP005',3,'2023-02-05',NULL),
-- GATO 6
('XIP006',4,'2023-02-10',NULL),
-- GATO 7
('XIP007',5,'2023-03-01',NULL),
-- GATO 8
('XIP008',5,'2023-03-03',NULL),
-- GATO 9 (cambio)
('XIP009',6,'2023-03-05','2023-10-01'),
('XIP009',7,'2023-10-01',NULL),
-- GATO 10
('XIP010',6,'2023-04-01',NULL),
-- GATO 11
('XIP011',7,'2023-04-04',NULL),
-- GATO 12
('XIP012',8,'2023-04-06',NULL),
-- GATO 13 (cambio)
('XIP013',9,'2023-05-01','2024-01-01'),
('XIP013',10,'2024-01-01',NULL),
-- GATO 14
('XIP014',10,'2023-05-03',NULL),
-- GATO 15
('XIP015',11,'2023-05-05',NULL),
-- GATO 16
('XIP016',11,'2023-06-01',NULL),
-- GATO 17
('XIP017',12,'2023-06-03',NULL),
-- GATO 18 (cambio)
('XIP018',12,'2023-06-05','2023-12-01'),
('XIP018',13,'2023-12-01',NULL),
-- GATO 19
('XIP019',13,'2023-07-01',NULL),
-- GATO 20
('XIP020',14,'2023-07-03',NULL),
-- GATO 21
('XIP021',14,'2023-07-05',NULL),
-- GATO 22
('XIP022',15,'2023-08-01',NULL),
-- GATO 23
('XIP023',15,'2023-08-03',NULL),
-- GATO 24 (cambio)
('XIP024',1,'2023-08-05','2024-02-01'),
('XIP024',2,'2024-02-01',NULL),
-- GATO 25
('XIP025',2,'2023-09-01',NULL),
-- GATO 26
('XIP026',3,'2023-09-03',NULL),
-- GATO 27
('XIP027',4,'2023-09-05',NULL),
-- GATO 28
('XIP028',5,'2023-10-01',NULL),
-- GATO 29
('XIP029',6,'2023-10-03',NULL),
-- GATO 30
('XIP030',7,'2023-10-05',NULL),
-- GATO 31
('XIP031',8,'2023-11-01',NULL),
-- GATO 32
('XIP032',9,'2023-11-03',NULL),
-- GATO 33
('XIP033',10,'2023-11-05',NULL),
-- GATO 34
('XIP034',11,'2023-12-01',NULL),
-- GATO 35
('XIP035',12,'2023-12-03',NULL),
-- GATO 36
('XIP036',13,'2023-12-05',NULL),
-- GATO 37
('XIP037',14,'2024-01-01',NULL),
-- GATO 38
('XIP038',15,'2024-01-03',NULL),
-- GATO 39
('XIP039',1,'2024-01-05',NULL),
-- GATO 40
('XIP040',2,'2024-02-01',NULL),
-- GATO 41
('XIP041',3,'2024-02-03',NULL),
-- GATO 42
('XIP042',4,'2024-02-05',NULL),
-- GATO 43
('XIP043',5,'2024-03-01',NULL),
-- GATO 44
('XIP044',6,'2024-03-03',NULL),
-- GATO 45
('XIP045',7,'2024-03-05',NULL),
-- GATO 46
('XIP046',8,'2024-04-01',NULL),
-- GATO 47
('XIP047',9,'2024-04-02',NULL),
-- GATO 48
('XIP048',10,'2024-04-03',NULL),
-- GATO 49
('XIP049',11,'2024-04-04',NULL),
-- GATO 50
('XIP050',12,'2024-04-05',NULL);

INSERT INTO Ubicacion (latitud, longitud, descripcion, comentarios, idColonia) VALUES
(39.5696, 2.6502,'Parque central','Zona arbolada',1),
(39.5689, 2.6550,'Parking hospital',NULL,1),
(39.5902, 2.4931,'Jardines públicos','Cerca de cafetería',2),
(39.5753, 2.6559,'Zona industrial','Pocos peatones',3),
(39.7191, 2.9145,'Parque norte','Zona tranquila',4),
(39.8886, 4.2648,'Puerto','Zona pescadores',5),
(39.9857, 3.8226,'Zona residencial','Casas unifamiliares',6),
(38.9089, 1.4306,'Centro histórico',NULL,8),
(38.9762, 1.3036,'Playa','Zona turística',9),
(38.9846, 1.3012,'Zona rural','Camino de tierra',10),
(38.7340, 1.4174,'Parque natural','Área protegida',11),
(38.7074, 1.4534,'Playa tranquila','Poco tránsito',12),
(39.5724, 2.6397,'Barrio este',NULL,13),
(39.5668, 2.6234,'Zona oeste','Cerca colegio',14),
(39.9066, 4.0528,'Centro urbano',NULL,15);

INSERT INTO Grupo (nombre, codiAyuntamiento, responsableDNI) VALUES
-- AY1 Palma
('Grupo Palma Centro','AY1','11111111A'),
('Grupo Palma Norte','AY1','12121212K'),
('Grupo Palma Playa','AY1','13131313L'),

-- AY2 Calvià
('Grupo Calvià','AY2','22222222B'),
('Grupo Santa Ponça','AY2','14141414M'),
('Grupo Magaluf','AY2','15151515N'),

-- AY3 Inca
('Grupo Inca','AY3','33333333C'),
('Grupo Inca Rural','AY3','16161616O'),
('Grupo Llubí','AY3','17171717P'),

-- AY4 Maó
('Grupo Maó','AY4','44444444D'),
('Grupo Es Castell','AY4','18181818Q'),
('Grupo Sant Lluís','AY4','19191919R'),

-- AY5 Ciutadella
('Grupo Ciutadella','AY5','55555555E'),
('Grupo Cala Blanes','AY5','20202020S'),
('Grupo Cala Morell','AY5','21212121T'),

-- AY6 Eivissa
('Grupo Eivissa','AY6','66666666F'),
('Grupo Vila','AY6','22222222U'),
('Grupo Talamanca','AY6','23232323V'),

-- AY7 Sant Antoni
('Grupo Sant Antoni','AY7','77777777G'),
('Grupo Port des Torrent','AY7','24242424W'),
('Grupo Cala Gració','AY7','25252525X'),

-- AY8 Sant Josep
('Grupo Sant Josep','AY8','88888888H'),
('Grupo Cala d’Hort','AY8','26262626Y'),
('Grupo Es Cubells','AY8','27272727Z'),

-- AY9 Formentera
('Grupo Formentera','AY9','99999999I'),
('Grupo Sant Ferran','AY9','28282828A'),
('Grupo Es Pujols','AY9','29292929B'),

-- AY10 Sant Francesc
('Grupo Sant Francesc','AY10','10101010J'),
('Grupo La Savina','AY10','30303030C'),
('Grupo Cap de Barbaria','AY10','31313131D');


INSERT INTO Ayuda (observaciones, idGrupo, DNI) VALUES
('No tiene conocimientos específicos','1','11111111A'),
('No puede ayudar los sábados','1','33333333C'),
('Prefiere actividades de limpieza','1','55555555E'),
('No disponible fines de semana','2','22222222B'),
('Solo puede ayudar por la tarde','2','44444444D'),
('No tiene experiencia en veterinaria','3','66666666F'),
('Se desplaza solo a colonias cercanas','3','77777777G'),
('No puede ayudar los miércoles','4','88888888H'),
('Prefiere alimentación de gatos','4','99999999I'),
('No tiene coche propio','5','10101010J'),
('Solo puede ayudar en grupos pequeños','5','11111112K'),
('No disponible lunes y martes','6','22222223L'),
('Prefiere ayudar en seguimiento de gatos','6','33333334M'),
('Solo puede ayudar de mañana','7','44444445N'),
('No puede cargar peso','7','55555556O'),
('No tiene conocimientos veterinarios','8','66666667P'),
('No puede ayudar los viernes','9','77777778Q'),
('Solo puede ayudar cuando hay un coordinador presente','10','88888889R'),
('Disponible solo fines de semana', 1, '21212121U'),
('Tiene experiencia con gatos asustadizos', 2, '22222222V'),
('Puede ayudar con transporte', 3, '23232323W'),
('Conocimientos básicos de veterinaria', 4, '24242424X'),
('Prefiere actividades administrativas', 5, '25252525Y'),
('No disponible los lunes', 6, '26262626Z'),
('Tiene furgoneta para transporte', 7, '27272727A'),
('Solo puede ayudar 2 horas al día', 8, '28282828B'),
('Experiencia en rescate de animales', 9, '29292929C'),
('Disponible por las mañanas', 10, '30303030D');

INSERT INTO Reporte (fecha, asunto, cuerpo, idGrupo) VALUES
('2025-01-10', 'Incidencia en colonia', 'Se ha observado un gato nuevo sin registrar.', 1),
('2025-01-14', 'Material necesario', 'El grupo necesita más transportines y mantas.', 1),
('2025-01-18', 'Revisión semanal', 'Todo en orden, sin incidencias destacables.', 1),

('2025-01-20', 'Solicitud veterinaria', 'Un gato presenta síntomas de infección ocular.', 2),
('2025-01-22', 'Limpieza completada', 'Se ha realizado una limpieza profunda del punto de alimentación.', 2),
('2025-01-25', 'Falta de pienso', 'Se necesita reponer el pienso en las próximas 48 horas.', 2),

('2025-01-26', 'Gato desaparecido', 'Uno de los gatos controlados no ha aparecido en tres días.', 3),
('2025-01-29', 'Alimentación irregular', 'Se ha detectado que alguien externo está alimentando fuera del horario.', 3),
('2025-02-01', 'Nuevo refugio', 'Se instaló un refugio adicional en la zona sombreada.', 3),

('2025-02-02', 'Problema de acceso', 'El acceso a la colonia está bloqueado por obras.', 4),
('2025-02-05', 'Posible abandono', 'Se ha encontrado un gato doméstico abandonado cerca de la colonia.', 4),
('2025-02-06', 'Control de camadas', 'Se ha identificado una gata posiblemente gestante.', 4),

('2025-02-10', 'Actividad sospechosa', 'Vecinos reportan presencia de perros sueltos por la zona.', 5),
('2025-02-12', 'Actualización censal', 'Se ha actualizado la ficha de dos nuevos gatos.', 5),
('2025-02-14', 'Revisión del vallado', 'El vallado ha sufrido daños y necesita reparación.', 5),

('2025-02-16', 'Visita veterinaria', 'El veterinario ha revisado cuatro gatos; uno requiere seguimiento.', 6),
('2025-02-17', 'Mejoras de refugio', 'Se han reforzado dos casetas para proteger de la lluvia.', 6),
('2025-02-19', 'Problema de convivencia', 'Se han detectado peleas entre dos machos dominantes.', 6),

('2025-02-20', 'Punto de agua', 'Se ha instalado un nuevo punto de agua.', 7),
('2025-02-21', 'Escasez de voluntarios', 'Faltan voluntarios para cubrir turnos de fin de semana.', 7),
('2025-02-23', 'Condiciones del terreno', 'El terreno está embarrado y dificulta el acceso.', 7),

('2025-02-25', 'Control sanitario', 'Dos gatos muestran signos de resfriado. Se vigilará evolución.', 8),
('2025-02-26', 'Reubicación temporal', 'Se ha trasladado un refugio para evitar inundación.', 8),
('2025-02-27', 'Aviso a vecinos', 'Se ha entregado un aviso sobre la alimentación responsable.', 8),

('2025-03-01', 'Pérdida de material', 'Un comedero ha desaparecido del punto central.', 9),
('2025-03-02', 'Ruido nocturno', 'Vecinos reportan ruido posiblemente causado por obras cercanas.', 9),
('2025-03-03', 'Temperatura baja', 'Se recomienda aumentar aislamiento de las casetas.', 9),

('2025-03-05', 'Reunión del grupo', 'Se ha realizado reunión para coordinar tareas del mes.', 10),
('2025-03-06', 'Entrega de material', 'Se ha recibido pienso, guantes y mantas.', 10),
('2025-03-07', 'Colonia estable', 'Sin incidencias relevantes esta semana.', 10);


INSERT INTO Borsi (codiAyuntamiento, DNI, fechaInscripcion) VALUES
('AY1','99999990S','2024-01-10'),
('AY1','26262626Z','2024-02-05'),

('AY2','20202020T','2024-01-15'),
('AY2','27272727A','2024-02-12'),

('AY3','21212121U','2024-01-20'),
('AY3','28282828B','2024-02-18'),

('AY4','22222222V','2024-01-25'),
('AY4','29292929C','2024-02-22'),

('AY5','23232323W','2024-01-30'),
('AY5','30303030D','2024-02-28'),

('AY6','24242424X','2024-02-02'),
('AY6','31313131E','2024-03-05'),

('AY7','25252525Y','2024-02-06'),
('AY7','32323232F','2024-03-10'),

('AY8','33333335G','2024-02-10'),
('AY8','34343434H','2024-03-15'),

('AY9','35353535I','2024-02-14'),
('AY9','36363636J','2024-03-20'),

('AY10','37373737K','2024-02-18'),
('AY10','38383838L','2024-03-25'),
('AY1', '12121212K', '2024-03-01'),
('AY1', '13131313L', '2024-03-05'),
('AY2', '14141414M', '2024-03-10'),
('AY2', '15151515N', '2024-03-12'),
('AY3', '16161616O', '2024-03-15'),
('AY3', '17171717P', '2024-03-18'),
('AY4', '18181818Q', '2024-03-20'),
('AY4', '19191919R', '2024-03-22'),
('AY5', '20202020S', '2024-03-25'),
('AY5', '21212121T', '2024-03-28'),
('AY6', '22222222U', '2024-04-01'),
('AY6', '23232323V', '2024-04-03'),
('AY7', '24242424W', '2024-04-05'),
('AY7', '25252525X', '2024-04-07'),
('AY8', '26262626Y', '2024-04-10'),
('AY8', '27272727Z', '2024-04-12'),
('AY9', '28282828A', '2024-04-15'),
('AY9', '29292929B', '2024-04-18'),
('AY10', '30303030C', '2024-04-20'),
('AY10', '31313131D', '2024-04-22');

INSERT INTO Deteccion (XIP, idColoniaDetectada) 
VALUES ('XIP024', 1);

--TRIGGER
DELIMITER //

CREATE TRIGGER trg_Deteccion_Albirament
BEFORE INSERT ON Deteccion
FOR EACH ROW
BEGIN
    DECLARE colonia_actual INT;
    
    -- Obtener la colonia actual del gato (última pertenencia con fechaFin = NULL)
    SELECT idColonia INTO colonia_actual
    FROM Pertenencia
    WHERE XIP = NEW.XIP AND fechaFin IS NULL
    ORDER BY fechaInicio DESC
    LIMIT 1;
    
    -- Si se ve en otra colonia no habitual
    IF colonia_actual IS NOT NULL AND colonia_actual != NEW.idColoniaDetectada THEN
        -- Insertar en ALBIRAMENT
        INSERT INTO ALBIRAMENT (XIP, idColoniaNova, observacions)
        VALUES (NEW.XIP, NEW.idColoniaDetectada, 
                CONCAT('Gato detectado en colonia diferente. Colonia anterior: ', colonia_actual));
        
        -- Actualizar la pertenencia del gato a la nueva colonia
        UPDATE Pertenencia 
        SET fechaFin = CURDATE()
        WHERE XIP = NEW.XIP AND fechaFin IS NULL;
        
        INSERT INTO Pertenencia (XIP, idColonia, fechaInicio)
        VALUES (NEW.XIP, NEW.idColoniaDetectada, CURDATE());
    END IF;
END //

--STORED_PROCEDURE

DELIMITER //
CREATE PROCEDURE BackupPoblacionColonias()
BEGIN
    DECLARE backup_fecha DATE;
    DECLARE contador INT DEFAULT 0;
    DECLARE acabar INT DEFAULT FALSE;
    
    -- Variables para Ayuntamiento
    DECLARE v_codiAyuntamiento VARCHAR(10);
    DECLARE v_municipio VARCHAR(50);
    DECLARE v_direccion VARCHAR(100);
    DECLARE v_telefono VARCHAR(15);
    DECLARE v_email VARCHAR(50);
    DECLARE v_codiIsla INT;
    
    -- Variables para Colonia
    DECLARE v_idColonia INT;
    DECLARE v_nombre_colonia VARCHAR(50);
    DECLARE v_codiAyuntamiento_col VARCHAR(10);
    
    -- Variables para Gato
    DECLARE v_XIP VARCHAR(15);
    DECLARE v_nombre_gato VARCHAR(30);
    DECLARE v_sexo ENUM('Macho','Hembra');
    DECLARE v_edad SMALLINT UNSIGNED;
    DECLARE v_aspecto VARCHAR(200);
    DECLARE v_foto VARCHAR(20);
    
    -- Variables para Ubicacion
    DECLARE v_idUbicacion INT;
    DECLARE v_latitud DECIMAL(10, 4);
    DECLARE v_longitud DECIMAL(11, 4);
    DECLARE v_descripcion VARCHAR(200);
    DECLARE v_comentarios VARCHAR(200);
    DECLARE v_idColonia_ub INT;
    
    -- Cursores
    DECLARE cur_ayuntamiento CURSOR FOR 
        SELECT codiAyuntamiento, municipio, direccion, telefono, email, codiIsla 
        FROM Ayuntamiento;
    
    DECLARE cur_colonia CURSOR FOR 
        SELECT idColonia, nombre, codiAyuntamiento 
        FROM Colonia;
    
    DECLARE cur_gato CURSOR FOR 
        SELECT XIP, nombre, sexo, edad, aspecto, foto 
        FROM Gato;
    
    DECLARE cur_ubicacion CURSOR FOR 
        SELECT idUbicacion, latitud, longitud, descripcion, comentarios, idColonia 
        FROM Ubicacion;
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET acabar = TRUE;
    
    SET backup_fecha = CURDATE();
    
    -- Verificar si ya se realizó la copia de hoy
    SELECT COUNT(*) INTO contador 
    FROM Backup_Colonias_Poblacion 
    WHERE fechaBackup = backup_fecha
    LIMIT 1;
    
    IF contador = 0 THEN
        -- ========== COPIAR AYUNTAMIENTOS ==========
        OPEN cur_ayuntamiento;
        etiqueta_ayuntamiento: LOOP
            FETCH cur_ayuntamiento INTO v_codiAyuntamiento, v_municipio, v_direccion, v_telefono, v_email, v_codiIsla;
            IF acabar THEN LEAVE etiqueta_ayuntamiento; END IF;
            
            INSERT INTO Backup_Colonias_Poblacion (fechaBackup, datos_completos)
            VALUES (
                backup_fecha,
                CONCAT(
                    'AYUNTAMIENTO|',
                    v_codiAyuntamiento, '|',
                    v_municipio, '|',
                    IFNULL(v_direccion, ''), '|',
                    IFNULL(v_telefono, ''), '|',
                    IFNULL(v_email, ''), '|',
                    v_codiIsla
                )
            );
        END LOOP;
        CLOSE cur_ayuntamiento;
        SET acabar = FALSE;
        
        -- ========== COPIAR COLONIAS ==========
        OPEN cur_colonia;
        etiqueta_colonia: LOOP
            FETCH cur_colonia INTO v_idColonia, v_nombre_colonia, v_codiAyuntamiento_col;
            IF acabar THEN LEAVE etiqueta_colonia; END IF;
            
            INSERT INTO Backup_Colonias_Poblacion (fechaBackup, datos_completos)
            VALUES (
                backup_fecha,
                CONCAT(
                    'COLONIA|',
                    v_idColonia, '|',
                    v_nombre_colonia, '|',
                    v_codiAyuntamiento_col
                )
            );
        END LOOP;
        CLOSE cur_colonia;
        SET acabar = FALSE;
        
        -- ========== COPIAR GATOS ==========
        OPEN cur_gato;
        etiqueta_gato: LOOP
            FETCH cur_gato INTO v_XIP, v_nombre_gato, v_sexo, v_edad, v_aspecto, v_foto;
            IF acabar THEN LEAVE etiqueta_gato; END IF;
            
            INSERT INTO Backup_Colonias_Poblacion (fechaBackup, datos_completos)
            VALUES (
                backup_fecha,
                CONCAT(
                    'GATO|',
                    v_XIP, '|',
                    IFNULL(v_nombre_gato, ''), '|',
                    v_sexo, '|',
                    IFNULL(v_edad, ''), '|',
                    IFNULL(v_aspecto, ''), '|',
                    IFNULL(v_foto, '')
                )
            );
        END LOOP;
        CLOSE cur_gato;
        SET acabar = FALSE;
        
        -- ========== COPIAR UBICACIONES ==========
        OPEN cur_ubicacion;
        etiqueta_ubicacion: LOOP
            FETCH cur_ubicacion INTO v_idUbicacion, v_latitud, v_longitud, v_descripcion, v_comentarios, v_idColonia_ub;
            IF acabar THEN LEAVE etiqueta_ubicacion; END IF;
            
            INSERT INTO Backup_Colonias_Poblacion (fechaBackup, datos_completos)
            VALUES (
                backup_fecha,
                CONCAT(
                    'UBICACION|',
                    v_idUbicacion, '|',
                    v_latitud, '|',
                    v_longitud, '|',
                    IFNULL(v_descripcion, ''), '|',
                    IFNULL(v_comentarios, ''), '|',
                    v_idColonia_ub
                )
            );
        END LOOP;
        CLOSE cur_ubicacion;
        
        SELECT 'Copia de seguridad realizada exitosamente' AS Resultado;
    ELSE
        SELECT 'La copia de seguridad para hoy ya fue realizada' AS Resultado;
    END IF;
END //

--EVENT
--REPAIR TABLE mysql.event;

SET GLOBAL event_scheduler = ON;

DELIMITER //

CREATE EVENT event_BackupDiarioPoblacion
ON SCHEDULE EVERY 1 DAY
STARTS TIMESTAMP(CURRENT_DATE, '23:59:59')
DO
BEGIN
    CALL BackupPoblacionColonias();
END //

