<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

try {
    $stmt = $pdo->query("SELECT id, title, description, price, cover_image FROM books ORDER BY created_at DESC");
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Add full URL to cover images
    foreach ($books as &$book) {
        $book['cover'] = '../uploads/' . $book['cover_image'];
    }
    
    echo json_encode($books);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?> 