<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['id'];

// Only allow POST from checkout form
if (!isset($_POST['confirm_purchase'])) {
    exit("Invalid request.");
}

// Fetch user info
$userQ = $conn->query("SELECT * FROM users WHERE id = $userId");
$user = $userQ->fetch_assoc();

// Gather form data
$selected = $_POST['selected_products'] ?? [];
$quantities = $_POST['quantity'] ?? [];
$prices = $_POST['price'] ?? [];
$payment_method = $_POST['payment_method'] ?? 'cod';

// Insert order into database
$stmt = $conn->prepare("INSERT INTO orders (user_id, final_total, payment_method, status, order_date) 
                        VALUES (?, ?, ?, 'Pending', NOW())");

// We'll calculate totals first
$subtotal = 0;
$products = [];

foreach ($selected as $cartId) {
    $cartId = intval($cartId);
    $quantity = intval($quantities[$cartId] ?? 1);
    $price    = floatval($prices[$cartId] ?? 0);

    // fetch product id from cart
    $cartQ = $conn->query("SELECT product_id FROM cart WHERE id = $cartId AND user_id = $userId");
    if ($cartRow = $cartQ->fetch_assoc()) {
        $productId = $cartRow['product_id'];

        $itemSubtotal = $price * $quantity;
        $subtotal += $itemSubtotal;

        $products[] = [
            'product_id' => $productId,
            'cart_id' => $cartId,
            'name' => $conn->query("SELECT name FROM products WHERE id=$productId")->fetch_assoc()['name'],
            'price' => $price,
            'quantity' => $quantity,
            'subtotal' => $itemSubtotal
        ];
    }
}

// Shipping + voucher
$shipping_fee = 150;
$final_total = $subtotal + $shipping_fee;

// Insert order
$stmt->bind_param("ids", $userId, $final_total, $payment_method);
$stmt->execute();
$orderId = $conn->insert_id;

// Insert order items
foreach ($products as $item) {

        // 1️⃣ Insert into order_items
    $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmtItem->bind_param("iiid", $orderId, $item['product_id'], $item['quantity'], $item['price']);
    $stmtItem->execute();

    // 2️⃣ Decrease stock in inventory
    $stmtStock = $conn->prepare("
        UPDATE inventory 
        SET stock_quantity = GREATEST(stock_quantity - ?, 0) 
        WHERE product_id = ?
    ");
    $stmtStock->bind_param("ii", $item['quantity'], $item['product_id']);
    $stmtStock->execute();



}

// Clear purchased cart items
if (!empty($selected)) {
    $cartIdsStr = implode(",", array_map('intval', $selected));
    $conn->query("DELETE FROM cart WHERE id IN ($cartIdsStr) AND user_id = $userId");
}

// Receipt variables
$delivery_start = date('F j, Y', strtotime('+3 days'));
$delivery_end = date('F j, Y', strtotime('+5 days'));
$purchase_time = date('F j, Y, g:i A');

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Receipt - Klord's Clothing</title>
  <link rel="stylesheet" href="assets/css/receipt.css">
</head>
<body>
  <main>
    <section class="receipt-section">
      <h2>🧾 Purchase Receipt</h2>
      
      <p><strong>Contact:</strong> <?= htmlspecialchars($user['phone']) ?></p>
      <p><strong>Address:</strong>
        <?= htmlspecialchars($user['street']) ?>, 
        <?= htmlspecialchars($user['barangay']) ?>, 
        <?= htmlspecialchars($user['municipality']) ?>, 
        <?= htmlspecialchars($user['province']) ?>, 
        <?= htmlspecialchars($user['zipcode']) ?>
      </p>

      <p><strong>Date:</strong> <?= $purchase_time ?></p>
      <p><strong>Payment Method:</strong> <?= htmlspecialchars(ucwords(str_replace("_"," ", $payment_method))) ?></p>

      <ul class="receipt-list">
        <?php foreach ($products as $item): ?>
          <li>
            <span><?= htmlspecialchars($item['name']) ?> (x<?= $item['quantity'] ?>)</span>
            <span>₱<?= number_format($item['subtotal'], 2) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>

      <p><strong>Subtotal:</strong> ₱<?= number_format($subtotal, 2) ?></p>
      <p><strong>Shipping Fee:</strong> ₱<?= number_format($shipping_fee, 2) ?></p>
      <p><strong>Total Paid:</strong> ₱<?= number_format($final_total, 2) ?></p>

      <p><strong>Estimated Delivery:</strong> Between <?= $delivery_start ?> and <?= $delivery_end ?></p>

      <a href="index.php" class="back-button">← Continue Shopping</a>
    </section>
  </main>
</body>
</html>
