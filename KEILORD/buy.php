<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['id'];

// Restore pending checkout from session if present and no POST data
if (!isset($_POST['selected_products']) && isset($_SESSION['pending_checkout'])) {
    $pending = $_SESSION['pending_checkout'];

    $selected_ids = array_map('intval', $pending['selected_products'] ?? []);
    $quantities = $pending['quantities'] ?? [];
    $prices = $pending['prices'] ?? [];
    $from_session = true;
} else {
    $from_session = false;
    $selected_ids = isset($_POST['selected_products']) ? array_map('intval', $_POST['selected_products']) : [];
    $quantities = $_POST['quantity'] ?? [];
    $prices = $_POST['price'] ?? [];
}

// If still no selection, redirect back to cart
if (empty($selected_ids)) {
    header("Location: cart.php?error=NoProductsSelected");
    exit;
}

// Fetch user address to check completeness
$userQ = $conn->query("SELECT street, barangay, municipality, province, zipcode, phone FROM users WHERE id = $userId");
$user = $userQ->fetch_assoc();

$required_fields = ['street', 'barangay', 'municipality', 'province', 'zipcode', 'phone'];
$missing = false;
foreach ($required_fields as $f) {
    if (empty($user[$f])) { $missing = true; break; }
}

if ($missing && !$from_session) {
    // save current selection to session so we can restore after user adds address
    $_SESSION['pending_checkout'] = [
        'selected_products' => $selected_ids,
        'quantities' => $quantities,
        'prices' => $prices
    ];
    header("Location: address_form.php?redirect=checkout.php");
    exit;
}


if ($from_session && isset($_SESSION['pending_checkout'])) {
    unset($_SESSION['pending_checkout']);
}

$ids_str = implode(',', array_map('intval', $selected_ids));
$products = [];
$subtotal = 0;

$cartQ = $conn->query("
    SELECT c.id AS cart_id, p.id AS product_id, p.name, p.price, p.image, c.quantity 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = $userId AND c.id IN ($ids_str)
");

while ($row = $cartQ->fetch_assoc()) {
    $cart_id = $row['cart_id'];

    // for QUANTITY 
    if (isset($quantities[$cart_id])) {
        $row['quantity'] = intval($quantities[$cart_id]);
    } else {
        $row['quantity'] = intval($row['quantity']);
    }

    $row['subtotal'] = $row['price'] * $row['quantity'];
    $subtotal += $row['subtotal'];
    $products[] = $row;
}

$shipping_fee = 50;
$total_amount = $subtotal + $shipping_fee;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout - Klord's Clothing</title>
  <link rel="stylesheet" href="assets/css/buy.css">
</head>
<body>
  <main>
    <h2 class="section-title">🧾 Confirm Your Purchase</h2>

    <?php if (empty($products)): ?>
        <p>Your cart is empty. <a href="shop.php">Go back to shop</a></p>
    <?php else: ?>
    <table class="checkout-table">
      <thead>
        <tr>
          <th>Product</th>
          <th>Name</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product): ?>
          <tr>
            <td><img src="assets/images/products/<?= htmlspecialchars($product['image']) ?>" 
                     alt="<?= htmlspecialchars($product['name']) ?>" width="60"></td>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td>₱<?= number_format($product['price'], 2) ?></td>
            <td><?= $product['quantity'] ?></td>
            <td>₱<?= number_format($product['subtotal'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="checkout-summary">
      <p>Subtotal: <strong>₱<?= number_format($subtotal, 2) ?></strong></p>
      <p>Shipping Fee: <strong>₱<?= number_format($shipping_fee, 2) ?></strong></p>
      <p>Total: <strong>₱<?= number_format($total_amount, 2) ?></strong></p>
    </div>

    <form method="post" action="process_orders.php" class="checkout-form">
      <?php foreach ($products as $product): ?>
        <input type="hidden" name="selected_products[]" value="<?= $product['cart_id'] ?>">
        <input type="hidden" name="quantity[<?= $product['cart_id'] ?>]" value="<?= $product['quantity'] ?>">
        <input type="hidden" name="price[<?= $product['cart_id'] ?>]" value="<?= $product['price'] ?>">
        <input type="hidden" name="subtotal[<?= $product['cart_id'] ?>]" value="<?= $product['subtotal'] ?>">
      <?php endforeach; ?>

      <input type="hidden" name="order_subtotal" value="<?= $subtotal ?>">
      <input type="hidden" name="shipping_fee" value="<?= $shipping_fee ?>">
      <input type="hidden" name="final_total" value="<?= $total_amount ?>">

      <label for="voucher">Voucher Code:</label>
      <input type="text" name="voucher" id="voucher" placeholder="Enter voucher if any">

      <div class="payment-methods">
        <label><input type="radio" name="payment_method" value="credit_card" required> Credit/Debit Card</label>
        <label><input type="radio" name="payment_method" value="gcash"> GCash</label>
        <label><input type="radio" name="payment_method" value="cod"> Cash on Delivery</label>
      </div>

      <div id="payment-extra"></div>
      <button type="submit" name="confirm_purchase">Confirm & Pay</button>
    </form>
    <?php endif; ?>
  </main>

<script>
const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
const confirmButton = document.querySelector('button[name="confirm_purchase"]');
const paymentExtra = document.getElementById('payment-extra');

confirmButton.disabled = true;
confirmButton.style.opacity = 0.4;

paymentRadios.forEach(radio => {
  radio.addEventListener('change', function() {
    confirmButton.disabled = false;
    confirmButton.style.opacity = 1;
    paymentExtra.innerHTML = '';

    if (this.value === 'gcash') {
      paymentExtra.innerHTML = `<label>Enter your GCash Number:</label>
        <input type="tel" name="gcash_number" placeholder="09xxxxxxxxx" required>`;
    }

    if (this.value === 'credit_card') {
      paymentExtra.innerHTML = `<label>Enter Credit/Debit Card Number:</label>
        <input type="text" name="card_number" placeholder="1234 5678 9012 3456" required>`;
    }
  });
});
</script>
</body>
</html>
