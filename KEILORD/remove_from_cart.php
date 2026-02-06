<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please login first']);
    exit;
}

$userId = $_SESSION['id'];
$cartId = intval($_POST['cart_id']);

// Delete the item
$conn->query("DELETE FROM cart WHERE id = $cartId AND user_id = $userId");

echo json_encode(['status' => 'success', 'message' => 'Item removed from cart']);
