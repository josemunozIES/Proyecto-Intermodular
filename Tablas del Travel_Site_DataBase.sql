
--DROP TABLE IF EXISTS eligir, guides, passports, users, destinations CASCADE;

-- Tabla de usuarios. Usaremos el email con la contraseña para el login.
CREATE TABLE users (
  email VARCHAR(100) PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  apellido VARCHAR(50) NOT NULL,
  apellido2 VARCHAR(50),
  passport VARCHAR(50) UNIQUE,
  edad INTEGER CHECK (edad >= 18),
  password VARCHAR(255) NOT NULL, -- NO HASH. LOS GUARDAMOS COMO TEXTO.
  admin VARCHAR(10) DEFAULT 'no' --Añadimos condicion de admin.
);

INSERT INTO users (nombre, apellido, apellido2, email, password, admin) VALUES
('Bradley','James','Burrage' , 'brad@domain.com', '1234', 'yes'),
('Hector', 'Chaparro', 'Misó','hector@domain.com', '1234', 'yes'),
('Jose Franciso', 'Muñoz','Palao', 'jose@domain.com', '1234', 'yes');

-- Tabla de destinos, para cuando se crea uno deba incluir los dartos del pasaporte.
--Borrado el requiere pasaporte. Todos los destinos requieren pasaporte.
CREATE TABLE destinations (
  ciudad VARCHAR(50) PRIMARY KEY,
  pais VARCHAR(50) NOT NULL,
);

INSERT INTO destinations (ciudad, pais) VALUES
('Paris', 'France'),
('Tokyo', 'Japan'),
('New York', 'USA'),
('Cancun', 'Mexico');

-- Tabla de guias.
CREATE TABLE guides (
  id SERIAL PRIMARY KEY, --Eliminado apellido2 de las guias. 
  nombre VARCHAR(50) NOT NULL,
  apellido VARCHAR(50) NOT NULL,
  especialidad VARCHAR(20) CHECK (especialidad IN ('Geography', 'History', 'Architecture', 'Food')),
  ciudad VARCHAR(50) REFERENCES destinations(ciudad)
);

INSERT INTO guides (id,nombre, apellido,  especialidad, ciudad) VALUES
(1,'Marie', 'Dupont', 'Geography', 'Paris'),
(2,'Hiroshi', 'Tanaka', 'History', 'Tokyo'),
(3,'John', 'Smith', 'Architecture', 'New York'),
(4,'Ana', 'Lopez', 'Food', 'Cancun');

-- Una tabla para guardar los datos de los pasaportes.
CREATE TABLE passports (
  numero VARCHAR(50) PRIMARY KEY,
  pais_de_expedicion VARCHAR(50) NOT NULL,
  email VARCHAR(100) UNIQUE REFERENCES users(email)
);

CREATE TABLE bookings (
    id SERIAL PRIMARY KEY,
    user_passport VARCHAR(50) REFERENCES users(passport),
    destination_ciudad VARCHAR(255) REFERENCES destinations(ciudad),
    booking_begin DATE, 
    booking_begin DATE --Añadido principio y fin del booking para que que se pueda hacer un booking si estas bookeado ya en otra fecha.
);


-- Many-to-many relationship: Users <-> Destinations. Esta no lo tengo claro todavia como lo vamos a hacer
/*CREATE TABLE eligir (
  email VARCHAR(100) REFERENCES users(email),
  ciudad VARCHAR(50) REFERENCES destinations(ciudad),
  PRIMARY KEY (email, ciudad)
);*/
