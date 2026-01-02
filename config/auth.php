<?php
session_start();

// Include database connection
require_once __DIR__ . '/db.php';

// Create users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    email VARCHAR(255) NOT NULL UNIQUE,
    role ENUM('admin', 'client') NOT NULL DEFAULT 'client',
    google_id VARCHAR(255) UNIQUE,
    google_profile_picture VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

$conn->query($sql);

// Add Google columns if they don't exist (for existing databases)
$checkGoogleId = $conn->query("SHOW COLUMNS FROM users LIKE 'google_id'");
if ($checkGoogleId->num_rows == 0) {
    $conn->query("ALTER TABLE users ADD COLUMN google_id VARCHAR(255) UNIQUE");
    $conn->query("ALTER TABLE users ADD COLUMN google_profile_picture VARCHAR(500)");
}

// Insert default admin user if not exists
$default_admin = "INSERT INTO users (username, password, email, role) 
                 SELECT 'admin', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 'admin@nashamukti.com', 'admin'
                 WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'admin')";
$conn->query($default_admin);

// Authentication helper functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: index.php');
        exit();
    }
}

function loginUser($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
}

function logoutUser() {
    session_destroy();
    header('Location: login.php');
    exit();
}
?> 