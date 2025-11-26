<?php
// Authentication middleware
session_start();

// Check if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /auth/login');
    exit;
}
?>
