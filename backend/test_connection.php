<?php
// Test database connection and books
require_once 'config.php';

echo "<h2>Database Connection Test</h2>";

try {
    echo "<p>✅ Database connection successful!</p>";
    
    // Test if books table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'books'");
    if ($stmt->rowCount() > 0) {
        echo "<p>✅ Books table exists!</p>";
        
        // Count books
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM books");
        $count = $stmt->fetch()['count'];
        echo "<p>📚 Total books in database: $count</p>";
        
        // Show all books
        if ($count > 0) {
            echo "<h3>Books in Database:</h3>";
            $stmt = $pdo->query("SELECT * FROM books ORDER BY created_at DESC");
            $books = $stmt->fetchAll();
            
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Title</th><th>Price</th><th>PDF File</th><th>Cover Image</th><th>Created</th></tr>";
            
            foreach ($books as $book) {
                echo "<tr>";
                echo "<td>{$book['id']}</td>";
                echo "<td>{$book['title']}</td>";
                echo "<td>\${$book['price']}</td>";
                echo "<td>{$book['pdf_file']}</td>";
                echo "<td>{$book['cover_image']}</td>";
                echo "<td>{$book['created_at']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>❌ No books found in database!</p>";
        }
        
    } else {
        echo "<p>❌ Books table does not exist!</p>";
    }
    
} catch(PDOException $e) {
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>Test Books API Endpoint</h3>";
echo "<p>Try accessing: <a href='books.php' target='_blank'>books.php</a></p>";
echo "<p>This should return JSON data of all books.</p>";
?> 