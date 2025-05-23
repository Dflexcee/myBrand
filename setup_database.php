<?php
// Database connection parameters
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    // Create connection without database
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS flexcee_db";
    $pdo->exec($sql);
    echo "Database created successfully<br>";
    
    // Select the database
    $pdo->exec("USE flexcee_db");
    
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        full_name VARCHAR(100) NOT NULL,
        role ENUM('admin', 'editor') NOT NULL DEFAULT 'editor',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL
    )";
    $pdo->exec($sql);
    echo "Users table created successfully<br>";
    
    // Create contact_messages table
    $sql = "CREATE TABLE IF NOT EXISTS contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        subject VARCHAR(200) NOT NULL,
        message TEXT NOT NULL,
        conversation_id VARCHAR(50) UNIQUE,
        status ENUM('new', 'in_progress', 'resolved', 'closed') DEFAULT 'new',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Contact messages table created successfully<br>";
    
    // Create message_threads table
    $sql = "CREATE TABLE IF NOT EXISTS message_threads (
        id INT AUTO_INCREMENT PRIMARY KEY,
        message_id INT NOT NULL,
        sender_type ENUM('user', 'admin') NOT NULL,
        sender_id INT,
        message TEXT NOT NULL,
        email_subject VARCHAR(200),
        email_message_id VARCHAR(200),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (message_id) REFERENCES contact_messages(id) ON DELETE CASCADE,
        FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE SET NULL
    )";
    $pdo->exec($sql);
    echo "Message threads table created successfully<br>";
    
    // Create email_logs table
    $sql = "CREATE TABLE IF NOT EXISTS email_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        recipient_email VARCHAR(100) NOT NULL,
        subject VARCHAR(200) NOT NULL,
        message TEXT NOT NULL,
        conversation_id VARCHAR(50),
        message_id VARCHAR(200),
        status ENUM('sent', 'failed') NOT NULL,
        error_message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Email logs table created successfully<br>";
    
    // Create settings table
    $sql = "CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) NOT NULL UNIQUE,
        setting_value TEXT NOT NULL,
        category VARCHAR(50) DEFAULT 'general',
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Settings table created successfully<br>";
    
    // Insert default mail settings if not exists
    $default_settings = [
        ['smtp_host', 'smtp.gmail.com', 'mail'],
        ['smtp_port', '587', 'mail'],
        ['smtp_username', 'your-email@gmail.com', 'mail'],
        ['smtp_password', 'your-app-password', 'mail'],
        ['from_email', 'your-email@gmail.com', 'mail'],
        ['from_name', 'FLEXCEE Tech', 'mail'],
        ['use_hosting_mail', 'false', 'mail'],
        ['hosting_smtp_host', 'mail.yourdomain.com', 'mail'],
        ['hosting_smtp_port', '465', 'mail'],
        ['hosting_smtp_username', 'contact@yourdomain.com', 'mail'],
        ['hosting_smtp_password', 'your-hosting-email-password', 'mail'],
    ];
    foreach ($default_settings as $setting) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO settings (setting_key, setting_value, category) VALUES (?, ?, ?)");
        $stmt->execute($setting);
    }
    echo "Default mail settings inserted<br>";
    
    // Insert default admin user with plain password
    $sql = "INSERT IGNORE INTO users (username, password, email, full_name, role) 
            VALUES ('admin', 'admin123', 'admin@flexceetech.world', 'Admin User', 'admin')";
    $pdo->exec($sql);
    echo "Default admin user created successfully<br>";
    
    echo "<br>Database setup completed successfully!";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
} 