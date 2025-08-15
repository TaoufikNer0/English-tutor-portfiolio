<?php
// Simple setup guide for the Book Selling Website
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Selling Website - Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background: #f8f9fa; 
            min-height: 100vh; 
        }
        .install-card { 
            background: white; 
            border-radius: 15px; 
            box-shadow: 0 5px 20px rgba(0,0,0,0.1); 
        }
        .step-item {
            padding: 1rem;
            border-left: 4px solid #dee2e6;
            margin-bottom: 1rem;
        }
        .step-item.completed {
            border-left-color: #28a745;
            background-color: #f8fff9;
        }
        .step-item.current {
            border-left-color: #007bff;
            background-color: #f8f9ff;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="install-card p-5">
                    <div class="text-center mb-5">
                        <h1 class="display-4 mb-3">Book Selling Website</h1>
                        <p class="text-muted">Simple Setup Guide</p>
                    </div>

                    <div class="mb-4">
                        <h4>Setup Steps</h4>
                        
                        <div class="step-item completed">
                            <h6>Step 1: Upload Files</h6>
                            <p class="text-muted mb-0">Upload all files to your web server</p>
                        </div>
                        
                        <div class="step-item current">
                            <h6>Step 2: Create Database</h6>
                            <p class="text-muted mb-0">Set up the database for your books</p>
                        </div>
                        
                        <div class="step-item">
                            <h6>Step 3: Configure Settings</h6>
                            <p class="text-muted mb-0">Add your Stripe keys and admin password</p>
                        </div>
                        
                        <div class="step-item">
                            <h6>Step 4: Start Selling</h6>
                            <p class="text-muted mb-0">Upload books and start making sales</p>
                        </div>
                    </div>

                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h5>What You Need</h5>
                            <ul class="text-muted">
                                <li>A web server with PHP and MySQL</li>
                                <li>A Stripe account for payments</li>
                                <li>Your digital books in PDF format</li>
                            </ul>
                        </div>
                    </div>

                    <div class="d-grid gap-3 mt-4">
                        <a href="setup.php" class="btn btn-primary btn-lg">
                            Create Database Now
                        </a>
                        
                        <a href="admin.php" class="btn btn-outline-primary">
                            Go to Admin Panel
                        </a>
                        
                        <a href="README.md" class="btn btn-outline-secondary">
                            View Setup Guide
                        </a>
                    </div>

                    <div class="mt-4 p-3 bg-warning bg-opacity-10 rounded">
                        <h6>Important</h6>
                        <ul class="text-muted small">
                            <li>Change the admin password after setup</li>
                            <li>Use HTTPS for security</li>
                            <li>Keep your Stripe keys private</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 