<?php
// ========================================
// CHANGE THESE 4 SETTINGS BELOW
// ========================================

// 1. STRIPE KEYS (Get from https://dashboard.stripe.com/apikeys)
$stripe_publishable_key = 'pk_test_your_publishable_key_here';
$stripe_secret_key = 'sk_test_your_secret_key_here';

// 2. ADMIN LOGIN (Change these!)
$admin_username = 'admin';
$admin_password = 'admin123';

// 3. WEBSITE URL (Change to your domain)
$site_url = 'http://localhost/000portfolio';

// 4. DATABASE (Usually don't need to change)
$host = 'localhost';
$dbname = 'english_tutor_db';
$username = 'root';
$password = '';

// ========================================
// DON'T CHANGE ANYTHING BELOW
// ========================================

// File settings
$upload_dir = '../uploads/';
$max_file_size = 10 * 1024 * 1024; // 10MB
$allowed_types = ['pdf'];
$allowed_image_types = ['jpg', 'jpeg', 'png', 'webp'];

// Connect to database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Database connection failed. Check your database settings.");
}

// Clean user input
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Create uploads folder
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

if (!is_writable($upload_dir)) {
    die("Upload folder is not writable. Set folder permissions to 755.");
}
?> 