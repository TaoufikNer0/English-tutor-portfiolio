<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

// Create Stripe checkout session
function createStripeSession($data) {
    global $stripe_secret_key;
    
    $url = 'https://api.stripe.com/v1/checkout/sessions';
    
    $postData = http_build_query([
        'payment_method_types[]' => 'card',
        'line_items[0][price_data][currency]' => 'usd',
        'line_items[0][price_data][product_data][name]' => $data['title'],
        'line_items[0][price_data][product_data][description]' => 'Digital PDF Book',
        'line_items[0][price_data][unit_amount]' => intval($data['price'] * 100),
        'line_items[0][quantity]' => 1,
        'mode' => 'payment',
        'success_url' => $data['success_url'],
        'cancel_url' => $data['cancel_url'],
        'customer_email' => $data['customer_email'],
        'metadata[book_id]' => $data['book_id'],
        'metadata[customer_email]' => $data['customer_email']
    ]);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $stripe_secret_key,
        'Content-Type: application/x-www-form-urlencoded'
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

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $book_id = intval($input['bookId'] ?? 0);
    $customer_email = filter_var($input['customerEmail'] ?? '', FILTER_VALIDATE_EMAIL);
    
    if (!$book_id || !$customer_email) {
        throw new Exception('Missing required fields');
    }
    
    // Get book details
    $stmt = $pdo->prepare("SELECT id, title, price FROM books WHERE id = ?");
    $stmt->execute([$book_id]);
    $book = $stmt->fetch();
    
    if (!$book) {
        throw new Exception('Book not found');
    }
    
    // Create checkout session
    $sessionData = [
        'title' => $book['title'],
        'price' => $book['price'],
        'success_url' => $site_url . '/backend/success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => $site_url . '/books.html',
        'customer_email' => $customer_email,
        'book_id' => $book_id
    ];
    
    $session = createStripeSession($sessionData);
    
    // Save order
    $sql = "INSERT INTO orders (book_id, customer_email, stripe_session_id, status) VALUES (?, ?, ?, 'pending')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$book_id, $customer_email, $session['id']]);
    
    echo json_encode([
        'id' => $session['id'],
        'success' => true
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'success' => false
    ]);
}
?> 