

USE MI_CHUZITO;

-- Tabla de usuarios del sistema
CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100),
    correo VARCHAR(100) UNIQ   UE,
    telefono VARCHAR(20),
    rol ENUM('cliente', 'administrador', 'repartidor'),
    contraseña VARCHAR(255)
);

-- Tabla de direcciones para clientes (puede tener varias)
CREATE TABLE direcciones (
    id_direccion INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    direccion TEXT,
    ciudad VARCHAR(100),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- Tabla de productos (chuzos)
CREATE TABLE productos (
    id_producto INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100),
    descripcion TEXT,
    precio DECIMAL(10, 2),
    disponible BOOLEAN DEFAULT TRUE
);

-- Ingredientes (para control de inventario)
CREATE TABLE ingredientes (
    id_ingrediente INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100),
    stock INT,
    unidad_medida VARCHAR(50)
);

-- Relación producto-ingrediente
CREATE TABLE producto_ingrediente (
    id_producto INT,
    id_ingrediente INT,
    cantidad_usada DECIMAL(10,2),
    PRIMARY KEY (id_producto, id_ingrediente),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto),
    FOREIGN KEY (id_ingrediente) REFERENCES ingredientes(id_ingrediente)
);

-- Pedidos
CREATE TABLE pedidos (
    id_pedido INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    fecha_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'en preparación', 'en camino', 'entregado', 'cancelado'),
    metodo_pago ENUM('efectivo', 'tarjeta', 'transferencia'),
    direccion_entrega TEXT,
    total DECIMAL(10, 2),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- Detalle de pedidos (carrito de compras)
CREATE TABLE detalle_pedido (
    id_detalle INT PRIMARY KEY AUTO_INCREMENT,
    id_pedido INT,
    id_producto INT,
    cantidad INT,
    precio_unitario DECIMAL(10, 2),
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

-- Facturación
CREATE TABLE facturas (
    id_factura INT PRIMARY KEY AUTO_INCREMENT,
    id_pedido INT,
    fecha_emision DATETIME,
    monto_total DECIMAL(10, 2),
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido)
);

-- Gestión de delivery
CREATE TABLE entregas (
    id_entrega INT PRIMARY KEY AUTO_INCREMENT,
    id_pedido INT,
    id_repartidor INT,
    fecha_entrega DATETIME,
    estado_entrega ENUM('asignado', 'en ruta', 'entregado'),
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido),
    FOREIGN KEY (id_repartidor) REFERENCES usuarios(id_usuario)
);

DELIMITER $$

CREATE TRIGGER trg_descuento_inventario
AFTER INSERT ON detalle_pedido
FOR EACH ROW
BEGIN
    UPDATE ingredientes i
    JOIN producto_ingrediente pi 
        ON i.id_ingrediente = pi.id_ingrediente
    SET i.stock = i.stock - (pi.cantidad_usada * NEW.cantidad)
    WHERE pi.id_producto = NEW.id_producto;
END$$
DELIMITER $$
CREATE TRIGGER trg_factura_pedido
AFTER UPDATE ON pedidos
FOR EACH ROW
BEGIN
    IF NEW.estado = 'entregado' AND OLD.estado <> 'entregado' THEN
        INSERT INTO facturas (id_pedido, fecha_emision, monto_total)
        VALUES (NEW.id_pedido, NOW(), NEW.total);
    END IF;
END$$

DROP TRIGGER IF EXISTS trg_factura_pedido;
DROP TRIGGER IF EXISTS trg_descuento_inventario;

DELIMITER $$

CREATE TRIGGER trg_descuento_inventario
AFTER INSERT ON detalle_pedido
FOR EACH ROW
BEGIN
    UPDATE ingredientes i
    JOIN producto_ingrediente pi 
        ON i.id_ingrediente = pi.id_ingrediente
    SET i.stock = i.stock - (pi.cantidad_usada * NEW.cantidad)
    WHERE pi.id_producto = NEW.id_producto;
END$$

CREATE TRIGGER trg_factura_pedido
AFTER UPDATE ON pedidos
FOR EACH ROW
BEGIN
    IF NEW.estado = 'entregado' AND OLD.estado <> 'entregado' THEN
        INSERT INTO facturas (id_pedido, fecha_emision, monto_total)
        VALUES (NEW.id_pedido, NOW(), NEW.total);
    END IF;
END$$

SHOW TRIGGERS;
