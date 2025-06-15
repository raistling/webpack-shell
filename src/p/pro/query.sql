-- Crear la DDBB proyecto
CREATE DATABASE proyecto;
USE proyecto;

-- Crear la tabla productos:
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Código del producto (autoincremental)
    nombre VARCHAR(255) NOT NULL     -- Nombre (no puede ser nulo)
);

-- Crear la tabla usuarios:
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY, -- ID del usuario (autoincremental)
    nombre VARCHAR(255) NOT NULL,      -- Nombre del usuario (no puede ser nulo)
    contrasena VARCHAR(255) NOT NULL   -- Contraseña (no puede ser nula)
);

-- Crear la tabla intermedia valoraciones:
CREATE TABLE IF NOT EXISTS valoraciones (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- ID de la valoración (autoincremental)
    id_usuario INT NOT NULL,            -- Relación con la tabla usuarios
    id_producto INT NOT NULL,           -- Relación con la tabla productos
    valoracion TINYINT CHECK (valoracion BETWEEN 1 AND 5), -- Valoración entre 1 y 5
    UNIQUE (id_usuario, id_producto),   -- Evitar duplicados (un usuario no puede valorar el mismo producto más de una vez)
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS repartos (
    id INT AUTO_INCREMENT PRIMARY KEY,    -- ID del reparto (autoincremental)
    fecha DATE NOT NULL                  -- Fecha del reparto (no puede ser nula)
);

CREATE TABLE IF NOT EXISTS repartos_productos (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- ID del registro intermedio (autoincremental)
    id_reparto INT NOT NULL,            -- Relación con la tabla repartos
    id_producto INT NOT NULL,           -- Relación con la tabla productos
    lat_gps VARCHAR(30) NOT NULL,     -- Latitud de entrega (no puede ser nula)
    long_gps VARCHAR(30) NOT NULL,     -- Longitud de entrega (no puede ser nula)
    elevation_gps VARCHAR(30),     -- Altura (puede ser nula, Futura integracion con APIs)
    direccion VARCHAR(255),      -- Dirección de entrega (no puede ser nula)
    FOREIGN KEY (id_reparto) REFERENCES repartos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id) ON DELETE CASCADE
);

-- Crear usuarios de prueba
INSERT INTO usuarios (nombre, contrasena)
VALUES
    ('admin1', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4'),
    ('admin2', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4'),
    ('admin3', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4');

-- Crear productos de prueba
INSERT INTO productos (nombre)
VALUES
    ('Teclado mecánico RGB'),
    ('Ratón inalámbrico ergonómico'),
    ('Monitor UltraWide 34 pulgadas'),
    ('Laptop ultradelgada 16GB RAM'),
    ('Disco duro externo 1TB'),
    ('Memoria USB 128GB'),
    ('Tarjeta gráfica NVIDIA RTX 3060'),
    ('Placa base para procesadores AMD'),
    ('Procesador Intel Core i7'),
    ('Auriculares con sonido envolvente'),
    ('Router WiFi 6 de alto rendimiento'),
    ('Fuente de alimentación 600W'),
    ('SSD NVMe 512GB'),
    ('Cámara web Full HD'),
    ('Impresora láser multifunción'),
    ('Licencia de software antivirus'),
    ('Cable HDMI de 5 metros'),
    ('Estación de carga USB-C'),
    ('Silla de oficina ergonómica'),
    ('Tablet con pantalla retina');