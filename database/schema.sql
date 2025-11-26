-- Server Assessment System Database Schema

-- Create database
CREATE DATABASE IF NOT EXISTS server_assessment;
USE server_assessment;

-- Users table
CREATE TABLE users (
    id VARCHAR(36) PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('ADMIN', 'CLOUD_MANAGER', 'CLOUD_ENGINEER', 'SECURITY', 'AUDITOR') NOT NULL,
    last_login DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Servers table
CREATE TABLE servers (
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
);

-- Checklists table
CREATE TABLE checklists (
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
);

-- Audit logs table
CREATE TABLE audit_logs (
    id VARCHAR(36) PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    action VARCHAR(50) NOT NULL,
    entity ENUM('SERVER', 'CHECKLIST', 'USER', 'SETTING') NOT NULL,
    entity_id VARCHAR(36) NULL,
    details JSON NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Notifications table
CREATE TABLE notifications (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    type ENUM('CHECKLIST_APPROVED', 'CHECKLIST_REJECTED', 'SYSTEM') NOT NULL,
    message TEXT NOT NULL,
    related_id VARCHAR(36) NULL,
    read BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Report templates table
CREATE TABLE report_templates (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    type ENUM('SERVER_LIST', 'CHECKLIST') NOT NULL,
    created_by VARCHAR(50) NOT NULL,
    filters JSON NOT NULL,
    columns JSON NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Report schedules table
CREATE TABLE report_schedules (
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
);

-- Help documents table
CREATE TABLE help_docs (
    id VARCHAR(36) PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    category ENUM('GETTING_STARTED', 'USER_GUIDE', 'ADMIN_GUIDE', 'TROUBLESHOOTING') NOT NULL,
    sort_order INT NOT NULL,
    created_by VARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Settings table
CREATE TABLE settings (
    id VARCHAR(36) PRIMARY KEY,
    smtp_host VARCHAR(100),
    smtp_port INT,
    smtp_user VARCHAR(100),
    smtp_pass VARCHAR(100),
    smtp_from VARCHAR(100),
    company_logo VARCHAR(255),
    company_name VARCHAR(100)
);

-- Insert default admin user
INSERT INTO users (id, username, email, password, role) 
VALUES (
    UUID(), 
    'admin', 
    'admin@company.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'ADMIN'
);

-- Insert default settings
INSERT INTO settings (id, company_name) 
VALUES (UUID(), 'Company Name');
