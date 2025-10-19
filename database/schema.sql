-- Base de datos para Sistema de Inventario y Facturación
-- Eliminar base de datos si existe
DROP DATABASE IF EXISTS sistema_inventario;

-- Crear base de datos
CREATE DATABASE sistema_inventario CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Usar la base de datos
USE sistema_inventario;

-- Tabla de productos
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    imagen VARCHAR(255),
    stock INT DEFAULT 0,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de facturas
CREATE TABLE facturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_factura VARCHAR(50) UNIQUE NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    subtotal DECIMAL(10, 2) NOT NULL,
    iva DECIMAL(10, 2) DEFAULT 0,
    total DECIMAL(10, 2) NOT NULL,
    cliente_nombre VARCHAR(200),
    cliente_identificacion VARCHAR(50),
    observaciones TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de detalle de facturas
CREATE TABLE factura_detalle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    factura_id INT NOT NULL,
    producto_id INT NOT NULL,
    producto_nombre VARCHAR(200) NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (factura_id) REFERENCES facturas(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Índices para mejorar el rendimiento
CREATE INDEX idx_factura_fecha ON facturas(fecha);
CREATE INDEX idx_factura_numero ON facturas(numero_factura);
CREATE INDEX idx_detalle_factura ON factura_detalle(factura_id);
CREATE INDEX idx_detalle_producto ON factura_detalle(producto_id);

-- Datos de ejemplo (opcional)
INSERT INTO productos (nombre, descripcion, precio, stock, imagen) VALUES
('Laptop Dell Inspiron 15', 'Laptop con procesador Intel Core i5, 8GB RAM, 256GB SSD', 899.99, 15, NULL),
('Mouse Logitech M185', 'Mouse inalámbrico con receptor USB nano', 15.99, 50, NULL),
('Teclado Mecánico RGB', 'Teclado mecánico con retroiluminación RGB', 79.99, 25, NULL),
('Monitor Samsung 24"', 'Monitor Full HD 24 pulgadas, panel IPS', 189.99, 10, NULL),
('Auriculares Sony WH-1000XM4', 'Auriculares inalámbricos con cancelación de ruido', 349.99, 8, NULL);
