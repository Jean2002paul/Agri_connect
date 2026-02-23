-- Création de la base de données
CREATE DATABASE IF NOT EXISTS agriconnect_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Utilisation de la base de données
USE agriconnect_db;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) UNIQUE,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('agriculteur', 'acheteur', 'admin') DEFAULT 'agriculteur',
    profile_image VARCHAR(255),
    address TEXT,
    region VARCHAR(100),
    cooperative VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    email_verified BOOLEAN DEFAULT FALSE,
    phone_verified BOOLEAN DEFAULT FALSE
);

-- Table des produits
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT,
    price DECIMAL(10,2),
    quantity DECIMAL(10,2),
    unit VARCHAR(20) DEFAULT 'kg',
    quality_grade VARCHAR(20),
    origin VARCHAR(100),
    harvest_date DATE,
    available BOOLEAN DEFAULT TRUE,
    images JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des commandes
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    seller_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'refunded') DEFAULT 'pending',
    delivery_address TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des détails de commande
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Table des messages
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    subject VARCHAR(200),
    content TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des coopératives
CREATE TABLE IF NOT EXISTS cooperatives (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    acronym VARCHAR(20),
    description TEXT,
    address TEXT,
    region VARCHAR(100),
    contact_person VARCHAR(100),
    contact_email VARCHAR(100),
    contact_phone VARCHAR(20),
    registration_number VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des catégories de produits
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertion des catégories de base
INSERT INTO categories (name, description, icon) VALUES
('Céréales', 'Riz, maïs, sorgho, mil', 'fa-wheat'),
('Légumineuses', 'Haricots, pois, soja, arachide', 'fa-seedling'),
('Tubercules', 'Manioc, igname, patate douce', 'fa-carrot'),
('Fruits', 'Mangues, ananas, agrumes', 'fa-apple-alt'),
('Légumes', 'Tomates, oignons, poivrons', 'fa-leaf'),
('Produits transformés', 'Huile, farine, jus', 'fa-industry'),
('Élevage', 'Volaille, bétail, produits laitiers', 'fa-horse'),
('Autres', 'Autres produits agricoles', 'fa-ellipsis-h');

-- Insertion d'un utilisateur admin par défaut
INSERT INTO users (name, email, password, user_type, is_active, email_verified) VALUES
('Administrateur', 'admin@agriconnect.tg', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', TRUE, TRUE);

-- Index pour optimiser les performances
CREATE INDEX idx_products_user_id ON products(user_id);
CREATE INDEX idx_products_category ON products(category);
CREATE INDEX idx_orders_buyer_id ON orders(buyer_id);
CREATE INDEX idx_orders_seller_id ON orders(seller_id);
CREATE INDEX idx_messages_sender_id ON messages(sender_id);
CREATE INDEX idx_messages_receiver_id ON messages(receiver_id);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_phone ON users(phone);
