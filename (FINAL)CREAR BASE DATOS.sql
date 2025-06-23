DROP TABLE IF EXISTS guias, usuarios, destinos, bookings, pertenece_pasaporte, pasaporte CASCADE;

-- Travel_Site_DataBase

CREATE TABLE usuarios (
  id SERIAL UNIQUE,  
  email VARCHAR(100),
  nombre VARCHAR(50) NOT NULL,
  apellido VARCHAR(50) NOT NULL,
  apellido2 VARCHAR(50),
  edad INTEGER CHECK (edad >= 18),
  password VARCHAR(255) NOT NULL, 
  admin BOOLEAN DEFAULT false,
  CONSTRAINT pk_usuarios PRIMARY KEY(email)
);

INSERT INTO usuarios (nombre, apellido, apellido2, email, password, admin) VALUES
('Bradley','James','Burrage' , 'brad@domain.com', '1234', true),
('Hector', 'Chaparro', 'Misó','hector@domain.com', '1234', true),
('Jose Franciso', 'Muñoz','Palao', 'jose@domain.com', '1234', true);

CREATE TABLE destinos (
  id SERIAL,
  ciudad VARCHAR(50) NOT NULL,
  pais VARCHAR(50) NOT NULL,
  requiere_pasaporte BOOLEAN DEFAULT false,
  CONSTRAINT pk_destinos PRIMARY KEY(id)
);

INSERT INTO destinos (ciudad, pais, requiere_pasaporte) VALUES
('Paris', 'France', false),
('Tokyo', 'Japan', true),
('New York', 'USA', true),
('Cancun', 'Mexico', true),
('Rio de Janeiro', 'Brazil', true),
('Cape Town', 'South Africa', true),
('Seoul', 'South Korea', true),
('Barcelona', 'Spain', false),
('Florence', 'Italy', false),
('Sydney', 'Australia', true),
('Marrakech', 'Morocco', true);

CREATE TABLE pasaporte(
  numero_pasaporte VARCHAR(50),
  pais_expedición VARCHAR(50) NOT NULL,
  CONSTRAINT pk_pasaporte PRIMARY KEY(numero_pasaporte)
);

CREATE TABLE pertenece_pasaporte (
  email_usuario VARCHAR(100) PRIMARY KEY,  
  numero_pasaporte VARCHAR(50) UNIQUE,    
  CONSTRAINT fk_usuario FOREIGN KEY(email_usuario) REFERENCES usuarios(email)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_pasaporte FOREIGN KEY(numero_pasaporte) REFERENCES pasaporte(numero_pasaporte)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

CREATE TYPE especialidades as ENUM('Geography', 'History', 'Architecture', 'Food');
CREATE TABLE guias (
  id SERIAL, 
  nombre VARCHAR(50) NOT NULL,
  apellido VARCHAR(50) NOT NULL,
  apellido2 VARCHAR(50),
  especialidad especialidades,
  id_pais INT,
  CONSTRAINT pk_id PRIMARY KEY(id),
  CONSTRAINT fk_ciudad FOREIGN KEY (id_pais) REFERENCES destinos(id)
  		ON UPDATE CASCADE
      ON DELETE CASCADE
);

INSERT INTO guias (id,nombre, apellido, apellido2, especialidad, id_pais) VALUES
(1,'Marie', 'Dupont', '','Geography', 1),
(2,'Hiroshi', 'Tanaka', '','History', 2),
(3,'John', 'Smith', '','Architecture', 3),
(4,'Ana', 'Lopez', 'Vargas', 'Food', 4),
(5,'Lucas', 'Martín', '','Architecture', 8),
(6,'Sophie', 'Dubois', '','Food', 9);

CREATE TABLE bookings (
    id SERIAL,
    email_usuario VARCHAR(100),
    id_destino INT,
    inicio_booking DATE, 
    final_booking DATE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT pk_bookings PRIMARY KEY(id),
	CONSTRAINT fk_usuario FOREIGN KEY(email_usuario) REFERENCES usuarios(email)
		ON UPDATE CASCADE,
	CONSTRAINT fk_destino FOREIGN KEY(id_destino) REFERENCES destinos(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE
);