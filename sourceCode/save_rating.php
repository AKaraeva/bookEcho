<?php
session_start();
require_once 'config/dbaccess.php'; // Include your database connection script

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

if (isset($_SESSION['user']) && isset($_POST['rating'])) {
    $rating = intval($_POST['rating']);

    // Insert or update the rating in the database
    $stmt = $conn->prepare('INSERT INTO ratings (user_id, book_id, rating, date, comment) VALUES (?, ?, ?; ?; ?) ON DUPLICATE KEY UPDATE rating = VALUES(rating)');
    $stmt->bind_param('iiiiss', $userId, $rating);
    $stmt->execute();
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
