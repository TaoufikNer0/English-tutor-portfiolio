<?php
require_once 'config.php';

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Database Setup - Book Selling Website</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        body { background: #f8f9fa; min-height: 100vh; }
        .setup-card { background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class='container py-5'>
        <div class='row justify-content-center'>
            <div class='col-lg-8'>
                <div class='setup-card p-5'>
                    <h1 class='text-center mb-4'>Database Setup</h1>";

// Create database if it doesn't exist
try {
    $pdo_temp = new PDO("mysql:host=$host", $username, $password);
    $pdo_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo_temp->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    echo "<div class='alert alert-success'>Database created successfully!</div>";
} catch(PDOException $e) {
    echo "<div class='alert alert-danger'>Database creation failed. Check your database settings.</div>";
    exit();
}

// Create books table
$sql = "CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    pdf_file VARCHAR(255) NOT NULL,
    cover_image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

try {
    $pdo->exec($sql);
    echo "<div class='alert alert-success'>Books table created successfully!</div>";
} catch(PDOException $e) {
    echo "<div class='alert alert-danger'>Books table creation failed.</div>";
    exit();
}

// Create orders table
$sql = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    stripe_session_id VARCHAR(255) NOT NULL,
    status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id)
)";

try {
    $pdo->exec($sql);
    echo "<div class='alert alert-success'>Orders table created successfully!</div>";
} catch(PDOException $e) {
    echo "<div class='alert alert-danger'>Orders table creation failed.</div>";
    exit();
}

// Create uploads directory
$uploads_dir = '../uploads/';
if (!file_exists($uploads_dir)) {
    mkdir($uploads_dir, 0755, true);
    echo "<div class='alert alert-success'>Uploads folder created successfully!</div>";
}

echo "<div class='alert alert-success'>Setup completed successfully!</div>

                    <div class='text-center mt-4'>
                        <h4>Next Steps:</h4>
                        <ol class='text-start'>
                            <li>Edit <code>backend/config.php</code> and add your Stripe keys</li>
                            <li>Change the admin username and password</li>
                            <li>Update the website URL to your domain</li>
                            <li>Go to the admin panel and start uploading books</li>
                        </ol>
                        
                        <div class='d-grid gap-3 mt-4'>
                            <a href='admin.php' class='btn btn-primary btn-lg'>Go to Admin Panel</a>
                            <a href='install.php' class='btn btn-outline-secondary'>Back to Setup Guide</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>";
?> 