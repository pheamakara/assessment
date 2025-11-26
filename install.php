<?php
// Installation script for Server Assessment System

echo "Server Assessment System - Installation Script\n";
echo "=============================================\n\n";

// Database configuration
$host = 'localhost';
$dbname = 'server_assessment';
$username = 'root';
$password = '';

try {
    // Create database connection
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    echo "✓ Database '$dbname' created or already exists\n";
    
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create tables
    echo "Creating tables...\n";
    
    // Users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id VARCHAR(36) PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('ADMIN', 'CLOUD_MANAGER', 'CLOUD_ENGINEER', 'SECURITY', 'AUDITOR') NOT NULL,
            last_login DATETIME NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Users table created\n";
    
    // Servers table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS servers (
            id VARCHAR(36) PRIMARY KEY,
            name VARCHAR(100) UNIQUE NOT NULL,
            ip VARCHAR(45) NOT NULL,
            os VARCHAR(50) NOT NULL,
            site VARCHAR(100) NOT NULL,
            type ENUM('Virtual', 'Physical') NOT NULL,
            env ENUM('PROD', 'DEV', 'UAT', 'DR') NOT NULL,
            owner VARCHAR(100) NOT NULL,
            pic VARCHAR(100) NOT NULL,
            vendor VARCHAR(100),
            cpu VARCHAR(50),
            ram VARCHAR(50),
            disk VARCHAR(50),
            asset_class ENUM('Critical', 'High', 'Medium', 'Low') NOT NULL,
            asset_type ENUM('Server', 'Storage', 'Network', 'Database') NOT NULL,
            deploy_date DATETIME NULL,
            hypervisor VARCHAR(100) NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Servers table created\n";
    
    // Checklists table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS checklists (
            id VARCHAR(36) PRIMARY KEY,
            server_id VARCHAR(36) NOT NULL,
            type VARCHAR(20) NOT NULL,
            status ENUM('DRAFT', 'PENDING_SECURITY', 'PENDING_CLOUD', 'APPROVED', 'REJECTED') NOT NULL DEFAULT 'DRAFT',
            items JSON NOT NULL,
            requested_by VARCHAR(50) NULL,
            approved_by VARCHAR(50) NULL,
            rejection_reason TEXT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (server_id) REFERENCES servers(id) ON DELETE CASCADE
        )
    ");
    echo "✓ Checklists table created\n";
    
    // Audit logs table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS audit_logs (
            id VARCHAR(36) PRIMARY KEY,
            username VARCHAR(50) NOT NULL,
            action VARCHAR(50) NOT NULL,
            entity ENUM('SERVER', 'CHECKLIST', 'USER', 'SETTING') NOT NULL,
            entity_id VARCHAR(36) NULL,
            details JSON NULL,
            timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Audit logs table created\n";
    
    // Notifications table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS notifications (
            id VARCHAR(36) PRIMARY KEY,
            user_id VARCHAR(36) NOT NULL,
            type ENUM('CHECKLIST_APPROVED', 'CHECKLIST_REJECTED', 'SYSTEM') NOT NULL,
            message TEXT NOT NULL,
            related_id VARCHAR(36) NULL,
            read BOOLEAN DEFAULT FALSE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "✓ Notifications table created\n";
    
    // Report templates table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS report_templates (
            id VARCHAR(36) PRIMARY KEY,
            name VARCHAR(100) UNIQUE NOT NULL,
            description TEXT,
            type ENUM('SERVER_LIST', 'CHECKLIST') NOT NULL,
            created_by VARCHAR(50) NOT NULL,
            filters JSON NOT NULL,
            columns JSON NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Report templates table created\n";
    
    // Report schedules table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS report_schedules (
            id VARCHAR(36) PRIMARY KEY,
            template_id VARCHAR(36) NOT NULL,
            frequency ENUM('DAILY', 'WEEKLY', 'MONTHLY') NOT NULL,
            day_of_week INT NULL,
            day_of_month INT NULL,
            time VARCHAR(5) NOT NULL,
            recipients JSON NOT NULL,
            is_active BOOLEAN DEFAULT TRUE,
            last_run DATETIME NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (template_id) REFERENCES report_templates(id) ON DELETE CASCADE
        )
    ");
    echo "✓ Report schedules table created\n";
    
    // Help documents table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS help_docs (
            id VARCHAR(36) PRIMARY KEY,
            title VARCHAR(200) NOT NULL,
            content TEXT NOT NULL,
            category ENUM('GETTING_STARTED', 'USER_GUIDE', 'ADMIN_GUIDE', 'TROUBLESHOOTING') NOT NULL,
            sort_order INT NOT NULL,
            created_by VARCHAR(50) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Help documents table created\n";
    
    // Settings table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS settings (
            id VARCHAR(36) PRIMARY KEY,
            smtp_host VARCHAR(100),
            smtp_port INT,
            smtp_user VARCHAR(100),
            smtp_pass VARCHAR(100),
            smtp_from VARCHAR(100),
            company_logo VARCHAR(255),
            company_name VARCHAR(100)
        )
    ");
    echo "✓ Settings table created\n";
    
    // Insert default admin user
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = 'admin'");
    $stmt->execute();
    $userExists = $stmt->fetchColumn();
    
    if (!$userExists) {
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            INSERT INTO users (id, username, email, password, role) 
            VALUES (UUID(), 'admin', 'admin@company.com', :password, 'ADMIN')
        ");
        $stmt->bindParam(':password', $adminPassword);
        $stmt->execute();
        echo "✓ Default admin user created (username: admin, password: admin123)\n";
    } else {
        echo "✓ Default admin user already exists\n";
    }
    
    // Insert default settings
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM settings");
    $stmt->execute();
    $settingsExist = $stmt->fetchColumn();
    
    if (!$settingsExist) {
        $stmt = $pdo->prepare("
            INSERT INTO settings (id, company_name) 
            VALUES (UUID(), 'Company Name')
        ");
        $stmt->execute();
        echo "✓ Default settings created\n";
    } else {
        echo "✓ Settings already exist\n";
    }
    
    echo "\nInstallation completed successfully!\n";
    echo "You can now access the application at http://localhost:8000\n";
    echo "Login with username 'admin' and password 'admin123'\n";
    
} catch(PDOException $e) {
    echo "✗ Installation failed: " . $e->getMessage() . "\n";
}
?>
