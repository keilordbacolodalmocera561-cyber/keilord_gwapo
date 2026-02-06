<?php
session_start();
include 'includes/db.php';

// Only allow logged-in users
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['id'];

// Fetch cart items for this user
$cartQuery = $conn->query("SELECT c.id as cart_id, c.quantity, p.* 
                           FROM cart c 
                           JOIN products p ON c.product_id = p.id 
                           WHERE c.user_id = $userId");

$cart_items = [];
while ($row = $cartQuery->fetch_assoc()) {
    $cart_items[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart</title>
  <link rel="stylesheet" href="assets/css/cart.css">
</head>
<body>
  <a href="index.php" class="back-button">← Back to Home</a>

  <main>
    <section class="cart-section">
      <h2 class="section-title">🛒 Your Cart</h2>

      <?php if (empty($cart_items)): ?>
        <p class="empty-cart-msg">Your cart is empty.</p>
      <?php else: ?>
        <form method="post" action="buy.php">
          <div class="cart-grid">
            <?php foreach ($cart_items as $item): ?>
              <article class='cart-card' id="cart-item-<?= $item['cart_id'] ?>">
                <label class='cart-select'>
                 <input type='checkbox' name='selected_products[]' value='<?= $item['cart_id'] ?>'>


                  <div class='cart-details'>
                    <img src='assets/images/products/<?= htmlspecialchars($item['image']) ?>' 
                         alt='<?= htmlspecialchars($item['name']) ?>'>

                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                    <p>₱<?= number_format($item['price'], 2) ?></p>
                    <div class='cart-controls'>
                      <h4>Quantity:</h4>
                      <input type='number' name='quantity[<?= $item['cart_id'] ?>]' 
                             min='1' value='<?= $item['quantity'] ?>' class='quantity-input'>
                      <button type="button" class="remove-btn" data-id="<?= $item['cart_id'] ?>">Remove</button>
                    </div>
                  </div>
                </label>
              </article>
            <?php endforeach; ?>
          </div>

          <div class="cart-summary">
            <button type="submit" name="checkout" class="checkout-button">Buy Selected</button>
          </div>
        </form>
      <?php endif; ?>
    </section>
  </main>

<script>
// ✅ Remove cart item via AJAX
document.querySelectorAll('.remove-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const cartId = btn.dataset.id;

        fetch('remove_from_cart.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'cart_id=' + cartId
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                document.getElementById('cart-item-' + cartId).remove();
                alert(data.message);
                if(document.querySelectorAll('.cart-card').length === 0){
                    location.reload();
                }
            } else {
                alert(data.message);
            }
        });
    });
});

// ✅ Disable quantity for unselected products before submit
document.querySelector("form").addEventListener("submit", function() {
    document.querySelectorAll(".cart-card").forEach(card => {
        const checkbox = card.querySelector("input[type=checkbox]");
        const qtyInput = card.querySelector(".quantity-input");

        if (!checkbox.checked) {
            qtyInput.disabled = true;
        }
    });
});
</script>
</body>
</html>
