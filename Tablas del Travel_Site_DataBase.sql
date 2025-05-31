
--DROP TABLE IF EXISTS eligir, guides, passports, users, destinations CASCADE;

-- Tabla de usuarios. Usaremos el email con la contraseña para el login.
CREATE TABLE users (
  email VARCHAR(100) PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  apellido VARCHAR(50) NOT NULL,
  apellido2 VARCHAR(50),
  edad INTEGER CHECK (edad >= 18),
  password VARCHAR(255) NOT NULL -- NO HASH. LOS GUARDAMOS COMO TEXTO.
  admin VARCHAR(10) DEFAULT 'no' --Añadimos condicion de admin. Y nos insertamos ya.
);

INSERT INTO users (nombre, apellido, apellido2, email, password, admin) VALUES
('Bradley','James','Burrage' , 'brad@domain.com', '1234', 'yes'),
('Hector', 'Chaparro', 'Misó','hector@domain.com', '1234', 'yes'),
('Jose Franciso', 'Muñoz','Palao', 'jose@domain.com', '1234', 'yes');

-- Tabla de destinos, para cuando se crea uno deba incluir los dartos del pasaporte.
CREATE TABLE destinations (
  ciudad VARCHAR(50) PRIMARY KEY,
  pais VARCHAR(50) NOT NULL,
  requiere_pasaporte BOOLEAN NOT NULL
);

-- Tabla de guias.
CREATE TABLE guides (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  apellido VARCHAR(50) NOT NULL,
  apellido2 VARCHAR(50),
  especialidad VARCHAR(20) CHECK (especialidad IN ('Geografía', 'Historia', 'Arquitectura', 'Comida')),
  ciudad VARCHAR(50) REFERENCES destinations(ciudad)
);

-- Una tabla para guardar los datos de los pasaportes.
CREATE TABLE passports (
  numero VARCHAR(50) PRIMARY KEY,
  pais_de_expedicion VARCHAR(50) NOT NULL,
  email VARCHAR(100) UNIQUE REFERENCES users(email)
);

-- Many-to-many relationship: Users <-> Destinations. Esta no lo tengo claro todavia como lo vamos a hacer
/*CREATE TABLE eligir (
  email VARCHAR(100) REFERENCES users(email),
  ciudad VARCHAR(50) REFERENCES destinations(ciudad),
  PRIMARY KEY (email, ciudad)
);*/
