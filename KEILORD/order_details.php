<?php
session_start();
include 'includes/db.php';

// ✅ Ensure user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['id'];
$orderId = intval($_GET['id'] ?? 0);

if ($orderId <= 0) {
    exit("Invalid order.");
}

// ✅ Fetch order info
$orderQ = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$orderQ->bind_param("ii", $orderId, $userId);
$orderQ->execute();
$order = $orderQ->get_result()->fetch_assoc();

if (!$order) {
    exit("Order not found.");
}

// Fetch items in the order
$itemsQ = $conn->prepare("
    SELECT oi.product_id, oi.quantity, oi.price, p.name, p.image
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$itemsQ->bind_param("i", $orderId);
$itemsQ->execute();
$items = $itemsQ->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order #<?= $orderId ?> - Details</title>
  <link rel="stylesheet" href="assets/css/details.css">
</head>
<body>

  <div class="order-details">
    <h2>🧾 Order #<?= $order['id'] ?></h2>

    <div class="info">
      <p><strong>Date:</strong> <?= date("F j, Y, g:i A", strtotime($order['order_date'])) ?></p>
      <p><strong>Status:</strong> <span class="status <?= strtolower($order['status']) ?>"><?= ucfirst($order['status']) ?></span></p>
      <p><strong>Total Paid:</strong> ₱<?= number_format($order['final_total'], 2) ?></p>
    </div>

    <h3>📦 Items</h3>
    <table>
      <thead>
        <tr>
          <th>Product</th>
          <th>Name</th>
          <th>Price</th>
          <th>Qty</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($item = $items->fetch_assoc()): ?>
          <tr>
            <td><img src="assets/images/products/<?= htmlspecialchars($item['image']) ?>" class="product-img" alt=""></td>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td>₱<?= number_format($item['price'], 2) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>₱<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <a href="index.php" class="back-btn">←Home</a>
  </div>

</body>
</html>
