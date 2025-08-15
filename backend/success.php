<?php
session_start();
require_once 'config.php';

// Get Stripe session
function getStripeSession($session_id) {
    global $stripe_secret_key;
    
    $url = 'https://api.stripe.com/v1/checkout/sessions/' . $session_id;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $stripe_secret_key
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        return json_decode($response, true);
    } else {
        throw new Exception('Stripe API error: ' . $response);
    }
}

$session_id = $_GET['session_id'] ?? '';
$error = '';
$order = null;

if ($session_id) {
    try {
        // Get session details
        $session = getStripeSession($session_id);
        
        if ($session['payment_status'] === 'paid') {
            // Get order info
            $stmt = $pdo->prepare("SELECT o.*, b.title, b.pdf_file FROM orders o 
                                  JOIN books b ON o.book_id = b.id 
                                  WHERE o.stripe_session_id = ?");
            $stmt->execute([$session_id]);
            $order = $stmt->fetch();
            
            if ($order) {
                // Mark order as completed
                $stmt = $pdo->prepare("UPDATE orders SET status = 'completed' WHERE stripe_session_id = ?");
                $stmt->execute([$session_id]);
                
                // Set download session
                $_SESSION['download_pdf'] = $order['pdf_file'];
                $_SESSION['book_title'] = $order['title'];
            } else {
                $error = "Order not found!";
            }
        } else {
            $error = "Payment not completed!";
        }
    } catch (Exception $e) {
        $error = "Error retrieving payment information!";
    }
} else {
    $error = "No session ID provided!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success - Book Purchase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background: #f8f9fa; 
            min-height: 100vh; 
        }
        .success-card { 
            background: white; 
            border-radius: 20px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.1); 
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
        }
        .download-btn {
            background: #007bff;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 123, 255, 0.3);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="success-card p-5 text-center">
                    <?php if ($error): ?>
                        <!-- Error State -->
                        <div class="success-icon">
                            <span class="text-white fs-1">!</span>
                        </div>
                        <h2 class="text-danger mb-3">Payment Error</h2>
                        <p class="text-muted mb-4"><?php echo $error; ?></p>
                        <a href="../books.html" class="btn btn-primary">
                            Back to Books
                        </a>
                        
                    <?php else: ?>
                        <!-- Success State -->
                        <div class="success-icon">
                            <span class="text-white fs-1">✓</span>
                        </div>
                        <h2 class="text-success mb-3">Payment Successful!</h2>
                        <p class="text-muted mb-4">
                            Thank you for your purchase! Your book is ready for download.
                        </p>
                        
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($order['title']); ?></h5>
                                <p class="card-text text-muted">
                                    Receipt sent to: <?php echo htmlspecialchars($order['customer_email']); ?>
                                </p>
                                <p class="card-text">
                                    <small class="text-muted">
                                        Purchased on: <?php echo date('F j, Y \a\t g:i A', strtotime($order['created_at'])); ?>
                                    </small>
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-3">
                            <a href="download.php?session_id=<?php echo $session_id; ?>" 
                               class="btn download-btn text-white">
                                Download PDF
                            </a>
                            
                            <a href="../books.html" class="btn btn-outline-primary">
                                Browse More Books
                            </a>
                        </div>
                        
                        <div class="mt-4 p-3 bg-light rounded">
                            <h6>Important Notes:</h6>
                            <ul class="text-start text-muted small">
                                <li>You can download this book anytime by visiting this page</li>
                                <li>Keep your email receipt for future reference</li>
                                <li>For support, contact us with your order details</li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 