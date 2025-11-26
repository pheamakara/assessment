<?php
// Simple test script to verify the application components

echo "Server Assessment System - Test Script\n";
echo "=====================================\n\n";

// Test database connection
echo "1. Testing database connection...\n";
try {
    $host = 'localhost';
    $dbname = 'server_assessment';
    $username = 'root';
    $password = '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "   ✓ Database connection successful\n\n";
} catch(PDOException $e) {
    echo "   ✗ Database connection failed: " . $e->getMessage() . "\n\n";
}

// Test file structure
echo "2. Testing file structure...\n";
$required_files = [
    'app/init.php',
    'app/config/database.php',
    'app/core/App.php',
    'app/core/Controller.php',
    'app/core/Model.php',
    'app/controllers/Auth.php',
    'app/models/User.php',
    'index.php',
    'public/index.php'
];

$all_files_exist = true;
foreach ($required_files as $file) {
    if (file_exists($file)) {
        echo "   ✓ $file exists\n";
    } else {
        echo "   ✗ $file missing\n";
        $all_files_exist = false;
    }
}

if ($all_files_exist) {
    echo "   ✓ All required files exist\n\n";
} else {
    echo "   ✗ Some required files are missing\n\n";
}

// Test directory structure
echo "3. Testing directory structure...\n";
$required_dirs = [
    'app',
    'app/controllers',
    'app/models',
    'app/views',
    'app/config',
    'app/core',
    'public',
    'public/uploads',
    'database'
];

$all_dirs_exist = true;
foreach ($required_dirs as $dir) {
    if (is_dir($dir)) {
        echo "   ✓ $dir exists\n";
    } else {
        echo "   ✗ $dir missing\n";
        $all_dirs_exist = false;
    }
}

if ($all_dirs_exist) {
    echo "   ✓ All required directories exist\n\n";
} else {
    echo "   ✗ Some required directories are missing\n\n";
}

echo "Test completed.\n";
