DROP TABLE IF EXISTS guides, users, destinations, bookings CASCADE;

CREATE TABLE users (
  email VARCHAR(100) PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  apellido VARCHAR(50) NOT NULL,
  apellido2 VARCHAR(50),
  passport VARCHAR(50) UNIQUE,
  edad INTEGER CHECK (edad >= 18),
  password VARCHAR(255) NOT NULL, 
  admin VARCHAR(10) DEFAULT 'no' 
);

INSERT INTO users (nombre, apellido, apellido2, email, password, admin) VALUES
('Bradley','James','Burrage' , 'brad@domain.com', '1234', 'yes'),
('Hector', 'Chaparro', 'Misó','hector@domain.com', '1234', 'yes'),
('Jose Franciso', 'Muñoz','Palao', 'jose@domain.com', '1234', 'yes');

CREATE TABLE destinations (
  ciudad VARCHAR(50) PRIMARY KEY,
  pais VARCHAR(50) NOT NULL
);

INSERT INTO destinations (ciudad, pais) VALUES
('Paris', 'France'),
('Tokyo', 'Japan'),
('New York', 'USA'),
('Cancun', 'Mexico'),
('Rio de Janeiro', 'Brazil'),
('Cape Town', 'South Africa'),
('Seoul', 'South Korea'),
('Barcelona', 'Spain'),
('Florence', 'Italy'),
('Sydney', 'Australia'),
('Marrakech', 'Morocco');

CREATE TABLE guides (
  id SERIAL PRIMARY KEY, 
  nombre VARCHAR(50) NOT NULL,
  apellido VARCHAR(50) NOT NULL,
  especialidad VARCHAR(20) CHECK (especialidad IN ('Geography', 'History', 'Architecture', 'Food')),
  ciudad VARCHAR(50) REFERENCES destinations(ciudad)
);

INSERT INTO guides (id,nombre, apellido,  especialidad, ciudad) VALUES
(1,'Marie', 'Dupont', 'Geography', 'Paris'),
(2,'Hiroshi', 'Tanaka', 'History', 'Tokyo'),
(3,'John', 'Smith', 'Architecture', 'New York'),
(4,'Ana', 'Lopez', 'Food', 'Cancun'),
(5,'Lucas', 'Martín', 'Architecture', 'Barcelona'),
(6,'Sophie', 'Dubois', 'Food', 'Florence');

CREATE TABLE bookings (
    id SERIAL PRIMARY KEY,
    user_passport VARCHAR(50) REFERENCES users(passport),
    destination_ciudad VARCHAR(255) REFERENCES destinations(ciudad),
    booking_begin DATE, 
    booking_end DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);
