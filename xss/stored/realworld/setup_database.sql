-- Database setup script for Real-World Stored XSS Labs
-- Run this script to create the database and tables

-- Create database
CREATE DATABASE IF NOT EXISTS xss_labs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE xss_labs;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    bio TEXT,
    website VARCHAR(255),
    location VARCHAR(100),
    avatar VARCHAR(255),
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Comments table
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    post_id INT,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Blog posts table
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT,
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Support tickets table
CREATE TABLE IF NOT EXISTS support_tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subject VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('open', 'in_progress', 'resolved', 'closed') DEFAULT 'open',
    priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Site settings table
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_by INT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert default admin user
INSERT INTO users (username, email, password, full_name, role) 
VALUES ('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin');

-- Insert default site settings
INSERT INTO site_settings (setting_key, setting_value) VALUES 
('site_title', 'KrazePlanetLabs - XSS Testing Platform'),
('site_description', 'A platform for learning about XSS vulnerabilities'),
('welcome_message', 'Welcome to our XSS testing platform!'),
('footer_text', '© 2024 KrazePlanetLabs. All rights reserved.');

-- Insert some sample users for testing
INSERT INTO users (username, email, password, full_name, bio, website, location, role) VALUES 
('john_doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Doe', 'Security researcher and developer', 'https://johndoe.com', 'New York, USA', 'user'),
('jane_smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Smith', 'Web security enthusiast', 'https://janesmith.dev', 'London, UK', 'user'),
('bob_wilson', 'bob@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Bob Wilson', 'Penetration tester', 'https://bobwilson.net', 'San Francisco, USA', 'user');

-- Insert some sample comments
INSERT INTO comments (user_id, content) VALUES 
(2, 'Great platform for learning XSS!'),
(3, 'This is really helpful for understanding web vulnerabilities.'),
(4, 'Looking forward to more advanced labs.');

-- Insert some sample blog posts
INSERT INTO blog_posts (user_id, title, content, excerpt, status) VALUES 
(2, 'Understanding XSS Vulnerabilities', 'Cross-Site Scripting (XSS) is one of the most common web application vulnerabilities...', 'A comprehensive guide to XSS attacks and prevention', 'published'),
(3, 'Best Practices for Web Security', 'Web security is crucial in today\'s digital landscape...', 'Essential security practices every developer should know', 'published'),
(4, 'Advanced XSS Techniques', 'Once you understand the basics of XSS, it\'s time to explore advanced techniques...', 'Advanced XSS exploitation methods', 'published');

-- Insert some sample support tickets
INSERT INTO support_tickets (user_id, subject, description, priority, status) VALUES 
(2, 'Login Issues', 'I am having trouble logging into my account. The password reset is not working.', 'medium', 'open'),
(3, 'Feature Request', 'It would be great to have more advanced XSS labs with different filter bypasses.', 'low', 'open'),
(4, 'Bug Report', 'The comment system seems to have some issues with special characters.', 'high', 'in_progress');

-- Show success message
SELECT 'Database setup completed successfully!' as message;
