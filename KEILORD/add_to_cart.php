<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please login first']);
    exit;
}

$userId = $_SESSION['id'];
$productId = intval($_POST['product_id']);

// Get product name from products table
$productQuery = $conn->query("SELECT name FROM products WHERE id = $productId");
if ($productQuery->num_rows == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Product not found']);
    exit;
}
$productName = $productQuery->fetch_assoc()['name'];

// Check if product already in cart
$check = $conn->query("SELECT * FROM cart WHERE user_id = $userId AND product_id = $productId");
if ($check->num_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Product already in cart']);
    exit;
}

// Insert product into cart
$conn->query("INSERT INTO cart (user_id, product_id, product_name, quantity) VALUES ($userId, $productId, '$productName', 1)");

echo json_encode(['status' => 'success', 'message' => 'Product added to cart successfully']);
