-- Create the database
CREATE DATABASE IF NOT EXISTS idor_labs;
USE idor_labs;

-- Table for users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    full_name VARCHAR(255),
    phone VARCHAR(50),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Table for documents (Lab 1 & 2)
CREATE TABLE IF NOT EXISTS documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    file_type VARCHAR(50),
    file_size INT,
    confidential BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table for orders (Lab 3)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT,
    payment_method VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table for user profiles (Lab 1)
CREATE TABLE IF NOT EXISTS user_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,
    bio TEXT,
    website VARCHAR(255),
    location VARCHAR(255),
    social_media JSON,
    preferences JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table for system settings (Lab 4)
CREATE TABLE IF NOT EXISTS system_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(255) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    description TEXT,
    updated_by INT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Table for API keys (Lab 5)
CREATE TABLE IF NOT EXISTS api_keys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    api_key VARCHAR(255) NOT NULL UNIQUE,
    permissions JSON,
    is_active BOOLEAN DEFAULT TRUE,
    last_used TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table for security logs
CREATE TABLE IF NOT EXISTS security_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    event VARCHAR(255) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert sample users
INSERT IGNORE INTO users (username, email, password, role, full_name, phone, address) VALUES 
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Admin User', '+1-555-0001', '123 Admin St, City, State'),
('user1', 'user1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'John Doe', '+1-555-0002', '456 User Ave, City, State'),
('user2', 'user2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'Jane Smith', '+1-555-0003', '789 Customer St, City, State'),
('user3', 'user3@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'Bob Johnson', '+1-555-0004', '321 Client Rd, City, State');

-- Insert sample documents
INSERT IGNORE INTO documents (user_id, title, content, file_type, file_size, confidential) VALUES 
(1, 'Admin Notes', 'Confidential admin notes and system information...', 'TXT', 1024, TRUE),
(1, 'System Documentation', 'Internal system documentation and procedures...', 'PDF', 2048, TRUE),
(2, 'Project Alpha Report', 'Project Alpha development report and findings...', 'DOCX', 1536, FALSE),
(2, 'Personal Notes', 'Personal notes and reminders...', 'TXT', 512, FALSE),
(3, 'Financial Report', 'Q1 financial analysis and projections...', 'XLSX', 3072, TRUE),
(3, 'Meeting Minutes', 'Weekly team meeting minutes...', 'DOCX', 768, FALSE),
(4, 'Research Data', 'Market research data and analysis...', 'CSV', 4096, TRUE);

-- Insert sample orders
INSERT IGNORE INTO orders (user_id, product_name, quantity, price, status, shipping_address, payment_method) VALUES 
(2, 'Premium Software License', 1, 299.99, 'delivered', '456 User Ave, City, State', 'credit_card'),
(2, 'Hardware Upgrade Kit', 2, 149.99, 'shipped', '456 User Ave, City, State', 'paypal'),
(3, 'Basic Software License', 1, 99.99, 'processing', '789 Customer St, City, State', 'credit_card'),
(3, 'Training Course', 1, 199.99, 'pending', '789 Customer St, City, State', 'bank_transfer'),
(4, 'Enterprise License', 1, 999.99, 'delivered', '321 Client Rd, City, State', 'credit_card'),
(4, 'Support Package', 1, 499.99, 'shipped', '321 Client Rd, City, State', 'paypal');

-- Insert sample user profiles
INSERT IGNORE INTO user_profiles (user_id, bio, website, location, social_media, preferences) VALUES 
(1, 'System administrator with 10+ years experience', 'https://admin.example.com', 'New York, NY', '{"twitter": "@admin", "linkedin": "admin-user"}', '{"theme": "dark", "notifications": true}'),
(2, 'Software developer passionate about security', 'https://johndoe.dev', 'San Francisco, CA', '{"github": "johndoe", "twitter": "@johndoe"}', '{"theme": "light", "notifications": false}'),
(3, 'Business analyst and data enthusiast', 'https://janesmith.biz', 'Chicago, IL', '{"linkedin": "jane-smith", "twitter": "@janesmith"}', '{"theme": "auto", "notifications": true}'),
(4, 'Marketing specialist and content creator', 'https://bobjohnson.marketing', 'Austin, TX', '{"instagram": "@bobjohnson", "twitter": "@bobjohnson"}', '{"theme": "dark", "notifications": true}');

-- Insert sample system settings
INSERT IGNORE INTO system_settings (setting_key, setting_value, description, updated_by) VALUES 
('site_name', 'IDOR Labs Platform', 'Main site name', 1),
('maintenance_mode', 'false', 'Whether site is in maintenance mode', 1),
('max_file_size', '10485760', 'Maximum file upload size in bytes', 1),
('session_timeout', '3600', 'Session timeout in seconds', 1),
('backup_frequency', 'daily', 'How often to backup data', 1),
('security_level', 'high', 'Current security level', 1);

-- Insert sample API keys
INSERT IGNORE INTO api_keys (user_id, api_key, permissions, is_active) VALUES 
(1, 'admin_key_12345', '["read", "write", "admin"]', TRUE),
(2, 'user1_key_67890', '["read", "write"]', TRUE),
(3, 'user2_key_11111', '["read"]', TRUE),
(4, 'user3_key_22222', '["read", "write"]', FALSE);

-- Insert sample security logs
INSERT IGNORE INTO security_logs (user_id, event, details, ip_address) VALUES 
(1, 'login', 'Admin user logged in', '192.168.1.1'),
(2, 'login', 'User1 logged in', '192.168.1.2'),
(3, 'login', 'User2 logged in', '192.168.1.3'),
(4, 'login', 'User3 logged in', '192.168.1.4'),
(2, 'document_access', 'Accessed document ID 1', '192.168.1.2'),
(3, 'order_view', 'Viewed order ID 1', '192.168.1.3'),
(4, 'profile_update', 'Updated profile information', '192.168.1.4');
