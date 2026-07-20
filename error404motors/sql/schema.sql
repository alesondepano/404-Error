CREATE DATABASE IF NOT EXISTS error404_motors
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE error404_motors;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    complete_name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    complete_address TEXT NOT NULL,
    contact_numbers VARCHAR(80) NOT NULL,
    role ENUM('buyer', 'admin') NOT NULL DEFAULT 'buyer',
    email_verified TINYINT(1) NOT NULL DEFAULT 0,
    confirmation_token VARCHAR(128) NULL,
    status ENUM('active', 'disabled') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL UNIQUE,
    description VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    sku VARCHAR(40) NOT NULL UNIQUE,
    name VARCHAR(120) NOT NULL,
    model_year INT NOT NULL,
    mileage INT NOT NULL DEFAULT 0,
    transmission VARCHAR(40) NOT NULL,
    fuel_type VARCHAR(40) NOT NULL,
    color VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    image_url VARCHAR(255) NULL,
    price DECIMAL(12,2) NOT NULL,
    stock_quantity INT NOT NULL DEFAULT 0,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_by INT NULL,
    updated_by INT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id),
    CONSTRAINT fk_products_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_products_updated_by FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    order_number VARCHAR(40) NOT NULL UNIQUE,
    total_amount DECIMAL(12,2) NOT NULL,
    payment_method VARCHAR(60) NOT NULL,
    payment_reference VARCHAR(120) NULL,
    status ENUM('pending', 'approved', 'released', 'cancelled') NOT NULL DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    contact_number VARCHAR(80) NOT NULL,
    created_at DATETIME NOT NULL,
    CONSTRAINT fk_orders_buyer FOREIGN KEY (buyer_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(12,2) NOT NULL,
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(120) NOT NULL,
    table_name VARCHAR(80) NULL,
    record_id INT NULL,
    details TEXT NULL,
    ip_address VARCHAR(45) NULL,
    created_at DATETIME NOT NULL,
    CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO categories (name, description) VALUES
('Sedans', 'Comfortable daily-drive cars for city and highway use.'),
('SUVs', 'Family-ready vehicles with flexible space and road presence.'),
('Pickup Trucks', 'Work-capable vehicles for hauling and outdoor trips.'),
('Electric Cars', 'Efficient electric units for modern buyers.')
ON DUPLICATE KEY UPDATE description = VALUES(description);

INSERT INTO products
(category_id, sku, name, model_year, mileage, transmission, fuel_type, color, description, image_url, price, stock_quantity, status, created_by, updated_by, created_at, updated_at)
SELECT id, 'SED-001', 'Astra LX Sedan', 2023, 12000, 'Automatic', 'Gasoline', 'Graphite Gray',
       'A clean sedan with a quiet cabin, efficient engine, and complete service history.',
       'assets/hero-red-car.jpg', 1688000.00, 3, 'active', NULL, NULL, NOW(), NOW()
FROM categories WHERE name = 'Sedans'
ON DUPLICATE KEY UPDATE price = VALUES(price), stock_quantity = VALUES(stock_quantity), updated_at = NOW();

INSERT INTO products
(category_id, sku, name, model_year, mileage, transmission, fuel_type, color, description, image_url, price, stock_quantity, status, created_by, updated_by, created_at, updated_at)
SELECT id, 'SUV-002', 'Northline Sport SUV', 2022, 18500, 'Automatic', 'Diesel', 'Pearl White',
       'A spacious SUV with seven-seat capacity, parking sensors, and strong long-drive comfort.',
       'assets/hero-red-car.jpg', 2395000.00, 2, 'active', NULL, NULL, NOW(), NOW()
FROM categories WHERE name = 'SUVs'
ON DUPLICATE KEY UPDATE price = VALUES(price), stock_quantity = VALUES(stock_quantity), updated_at = NOW();

INSERT INTO products
(category_id, sku, name, model_year, mileage, transmission, fuel_type, color, description, image_url, price, stock_quantity, status, created_by, updated_by, created_at, updated_at)
SELECT id, 'TRK-003', 'Hauler Pro Pickup', 2021, 24000, 'Manual', 'Diesel', 'Jet Black',
       'A durable pickup with cargo bed liner, tow-ready stance, and practical worksite utility.',
       'assets/hero-red-car.jpg', 1890000.00, 4, 'active', NULL, NULL, NOW(), NOW()
FROM categories WHERE name = 'Pickup Trucks'
ON DUPLICATE KEY UPDATE price = VALUES(price), stock_quantity = VALUES(stock_quantity), updated_at = NOW();

INSERT INTO products
(category_id, sku, name, model_year, mileage, transmission, fuel_type, color, description, image_url, price, stock_quantity, status, created_by, updated_by, created_at, updated_at)
SELECT id, 'EV-004', 'VoltEdge EV Hatch', 2024, 5200, 'Single Speed', 'Electric', 'Arctic Silver',
       'A smooth electric hatch with quick acceleration, low running cost, and modern driver displays.',
       'assets/hero-red-car.jpg', 2140000.00, 2, 'active', NULL, NULL, NOW(), NOW()
FROM categories WHERE name = 'Electric Cars'
ON DUPLICATE KEY UPDATE price = VALUES(price), stock_quantity = VALUES(stock_quantity), updated_at = NOW();
