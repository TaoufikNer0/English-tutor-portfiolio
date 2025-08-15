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

if ($session_id) {
    try {
        // Check payment status
        $session = getStripeSession($session_id);
        
        if ($session['payment_status'] === 'paid') {
            // Get order info
            $stmt = $pdo->prepare("SELECT o.*, b.title, b.pdf_file FROM orders o 
                                  JOIN books b ON o.book_id = b.id 
                                  WHERE o.stripe_session_id = ? AND o.status = 'completed'");
            $stmt->execute([$session_id]);
            $order = $stmt->fetch();
            
            if ($order && file_exists($upload_dir . $order['pdf_file'])) {
                // Send file to browser
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="' . $order['title'] . '.pdf"');
                header('Content-Length: ' . filesize($upload_dir . $order['pdf_file']));
                header('Cache-Control: no-cache, must-revalidate');
                header('Pragma: no-cache');
                
                readfile($upload_dir . $order['pdf_file']);
                exit();
            } else {
                $error = "File not found or order invalid!";
            }
        } else {
            $error = "Payment not completed!";
        }
    } catch (Exception $e) {
        $error = "Error verifying payment!";
    }
} else {
    $error = "No session ID provided!";
}

// Show error page
header('Content-Type: text/html');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background: #f8f9fa; 
            min-height: 100vh; 
        }
        .error-card { 
            background: white; 
            border-radius: 20px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.1); 
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="error-card p-5 text-center">
                    <h2 class="text-danger mb-3">Download Error</h2>
                    <p class="text-muted mb-4"><?php echo $error; ?></p>
                    <a href="../books.html" class="btn btn-primary">
                        Back to Books
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 