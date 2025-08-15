<?php
session_start();
require_once 'config.php';

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit();
}

// Handle login
if ($_POST && !isset($_SESSION['admin_logged_in'])) {
    $username = clean_input($_POST['username']);
    $password = $_POST['password'];
    
    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit();
    } else {
        $error = "Wrong username or password!";
    }
}

// Handle book upload or deletion
if ($_POST && isset($_SESSION['admin_logged_in'])) {
    // Handle book deletion
    if (isset($_POST['delete_book'])) {
        $book_id = intval($_POST['book_id']);
        
        // Get book info
        $stmt = $pdo->prepare("SELECT pdf_file, cover_image FROM books WHERE id = ?");
        $stmt->execute([$book_id]);
        $book = $stmt->fetch();
        
        if ($book) {
            // Delete files
            if (file_exists($upload_dir . $book['pdf_file'])) {
                unlink($upload_dir . $book['pdf_file']);
            }
            if (file_exists($upload_dir . $book['cover_image'])) {
                unlink($upload_dir . $book['cover_image']);
            }
            
            // Delete from database
            $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
            if ($stmt->execute([$book_id])) {
                header('Location: admin.php?success=delete');
                exit();
            } else {
                $error = "Failed to delete book!";
            }
        }
    }
    // Handle book upload
    elseif (isset($_POST['title'], $_POST['description'], $_POST['price'])) {
        $title = clean_input($_POST['title']);
        $description = clean_input($_POST['description']);
        $price = floatval($_POST['price']);
        
        // Check inputs
        if (empty($title) || empty($description) || $price <= 0) {
            $error = "Please fill all fields correctly!";
        } else {
            // Handle file uploads
            $pdf_file = $_FILES['pdf_file'];
            $cover_image = $_FILES['cover_image'];
            
            if ($pdf_file['error'] == 0 && $cover_image['error'] == 0) {
                $pdf_ext = strtolower(pathinfo($pdf_file['name'], PATHINFO_EXTENSION));
                $image_ext = strtolower(pathinfo($cover_image['name'], PATHINFO_EXTENSION));
                
                if (in_array($pdf_ext, $allowed_types) && in_array($image_ext, $allowed_image_types)) {
                    if ($pdf_file['size'] <= $max_file_size) {
                        // Create unique filenames
                        $pdf_filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $title) . '.' . $pdf_ext;
                        $cover_filename = uniqid() . '_cover.' . $image_ext;
                        
                        $pdf_path = $upload_dir . $pdf_filename;
                        $cover_path = $upload_dir . $cover_filename;
                        
                        if (move_uploaded_file($pdf_file['tmp_name'], $pdf_path) && 
                            move_uploaded_file($cover_image['tmp_name'], $cover_path)) {
                            
                            // Save to database
                            $sql = "INSERT INTO books (title, description, price, pdf_file, cover_image) VALUES (?, ?, ?, ?, ?)";
                            $stmt = $pdo->prepare($sql);
                            
                            if ($stmt->execute([$title, $description, $price, $pdf_filename, $cover_filename])) {
                                header('Location: admin.php?success=upload');
                                exit();
                            } else {
                                $error = "Database error!";
                            }
                        } else {
                            $error = "File upload failed!";
                        }
                    } else {
                        $error = "PDF file too large! Maximum size is 10MB";
                    }
                } else {
                    $error = "Invalid file types! PDF for book, JPG/PNG/WEBP for cover image.";
                }
            } else {
                $error = "Please select both PDF and cover image!";
            }
        }
    }
}

// Get existing books
$books = [];
if (isset($_SESSION['admin_logged_in'])) {
    $stmt = $pdo->query("SELECT * FROM books ORDER BY created_at DESC");
    $books = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Book Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background: #f8f9fa; 
            min-height: 100vh; 
        }
        .admin-card { 
            background: white; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        }
        .book-card { 
            transition: all 0.3s ease; 
            border: 1px solid #dee2e6; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.1); 
        }
        .book-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 8px 20px rgba(0,0,0,0.15); 
        }
        .file-input-wrapper { 
            position: relative; 
            overflow: hidden; 
            display: inline-block; 
        }
        .file-input-wrapper input[type=file] { 
            position: absolute; 
            left: -9999px; 
        }
        .file-input-wrapper label { 
            background: #f8f9fa; 
            border: 2px dashed #dee2e6; 
            padding: 20px; 
            text-align: center; 
            cursor: pointer; 
            border-radius: 10px; 
        }
        .file-input-wrapper label:hover { 
            border-color: #6c757d; 
            background: #e9ecef; 
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="admin-card p-5">
                    <div class="text-center mb-5">
                        <h1 class="display-4 mb-3">Book Management Admin Panel</h1>
                        <p class="text-muted">Upload and manage your digital books</p>
                    </div>
                    
                    <?php if (!isset($_SESSION['admin_logged_in'])): ?>
                        <!-- Login Form -->
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="card border-0 shadow">
                                    <div class="card-body p-5">
                                        <h3 class="text-center mb-4">Admin Login</h3>
                                        <form method="POST">
                                            <div class="mb-3">
                                                <label class="form-label">Username</label>
                                                <input type="text" name="username" class="form-control form-control-lg" required>
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label">Password</label>
                                                <input type="password" name="password" class="form-control form-control-lg" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                                Login
                                            </button>
                                        </form>
                                        
                                        <?php if (isset($error)): ?>
                                            <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    <?php else: ?>
                        <!-- Admin Dashboard -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h3>Dashboard</h3>
                                <p class="text-muted mb-0">Welcome back! You have <?php echo count($books); ?> books uploaded.</p>
                            </div>
                            <a href="?logout=1" class="btn btn-outline-danger">
                                Logout
                            </a>
                        </div>
                        
                        <?php if (isset($_GET['success'])): ?>
                            <?php if ($_GET['success'] === 'upload'): ?>
                                <div class="alert alert-success alert-dismissible fade show">
                                    Book uploaded successfully!
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php elseif ($_GET['success'] === 'delete'): ?>
                                <div class="alert alert-success alert-dismissible fade show">
                                    Book deleted successfully!
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Upload Form -->
                        <div class="card border-0 shadow mb-5">
                            <div class="card-header bg-dark text-white">
                                <h4 class="mb-0">Upload New Book</h4>
                            </div>
                            <div class="card-body p-4">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Book Title</label>
                                            <input type="text" name="title" class="form-control" required>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Price ($)</label>
                                            <input type="number" name="price" class="form-control" step="0.01" min="0" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="4" required placeholder="Enter a detailed description of the book..."></textarea>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">PDF File (Max 10MB)</label>
                                            <div class="file-input-wrapper w-100">
                                                <input type="file" name="pdf_file" id="pdf_file" accept=".pdf" required>
                                                <label for="pdf_file" class="w-100">
                                                    Choose PDF file
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Cover Image</label>
                                            <div class="file-input-wrapper w-100">
                                                <input type="file" name="cover_image" id="cover_image" accept="image/*" required>
                                                <label for="cover_image" class="w-100">
                                                    Choose cover image
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-dark btn-lg w-100">
                                        Upload Book
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Existing Books -->
                        <div class="card border-0 shadow">
                            <div class="card-header bg-secondary text-white">
                                <h4 class="mb-0">Existing Books (<?php echo count($books); ?>)</h4>
                            </div>
                            <div class="card-body p-4">
                                <?php if (!empty($books)): ?>
                                    <div class="row">
                                        <?php foreach ($books as $book): ?>
                                            <div class="col-lg-6 mb-4">
                                                <div class="card book-card h-100">
                                                    <div class="row g-0">
                                                        <div class="col-md-4">
                                                            <img src="../uploads/<?php echo htmlspecialchars($book['cover_image']); ?>" 
                                                                 class="img-fluid rounded-start h-100" 
                                                                 style="object-fit: cover; height: 200px;">
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="card-body">
                                                                <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                                                                <p class="card-text text-muted">
                                                                    <?php echo htmlspecialchars(substr($book['description'], 0, 100)) . '...'; ?>
                                                                </p>
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <span class="badge bg-primary fs-6">$<?php echo number_format($book['price'], 2); ?></span>
                                                                    <small class="text-muted">
                                                                        <?php echo date('M j, Y', strtotime($book['created_at'])); ?>
                                                                    </small>
                                                                </div>
                                                                <div class="mt-3">
                                                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this book?')">
                                                                        <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                                                        <button type="submit" name="delete_book" class="btn btn-sm btn-outline-danger">
                                                                            Delete
                                                                        </button>
                                                                    </form>
                                                                    <a href="../uploads/<?php echo htmlspecialchars($book['pdf_file']); ?>" 
                                                                       class="btn btn-sm btn-outline-primary" target="_blank">
                                                                        View PDF
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <p class="text-muted">No books uploaded yet. Start by uploading your first book!</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // File input labels
        document.getElementById('pdf_file').addEventListener('change', function() {
            const label = this.nextElementSibling;
            if (this.files.length > 0) {
                label.textContent = this.files[0].name;
            } else {
                label.textContent = 'Choose PDF file';
            }
        });
        
        document.getElementById('cover_image').addEventListener('change', function() {
            const label = this.nextElementSibling;
            if (this.files.length > 0) {
                label.textContent = this.files[0].name;
            } else {
                label.textContent = 'Choose cover image';
            }
        });
    </script>
</body>
</html> 