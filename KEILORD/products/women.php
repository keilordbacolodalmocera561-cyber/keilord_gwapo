<?php
session_start(); 
include '../includes/db.php';

$isLoggedIn = isset($_SESSION['id']);

// Fetch products with stock info
$query = "
    SELECT p.*, i.stock_quantity 
    FROM products p
    LEFT JOIN inventory i ON p.id = i.product_id
    WHERE p.category = 'women'
    ORDER BY p.created_at DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Women's Collection | Klord</title>
<link rel="stylesheet" href="../assets/css/product.css">
<style>
  .product-stock { font-weight: bold; margin-top: 5px; }
  .sold-out {
      display: inline-block;
      background: red;
      color: white;
      padding: 5px 10px;
      font-weight: bold;
      border-radius: 5px;
      margin-top: 5px;
  }
  .product-card { position: relative; }
  .sold-out-overlay {
      position: absolute;
      top: 10px;
      left: 10px;
      background: rgba(255,0,0,0.8);
      color: white;
      padding: 5px 10px;
      font-weight: bold;
      border-radius: 5px;
      z-index: 2;
  }
</style>
</head>
<body>

  <!-- Navigation -->
  <a href="../index.php" class="back-button">← Back to Home</a>
  <a href="../cart.php" class="cart-icon">Cart 🛒</a>

  <main>
    <section class="collection-section women-collection">
      <h2 class="section-title">Women's Collection</h2>
      <p class="section-subtitle">Curated elegance for every occasion</p>

      <div class="product-grid">
        <?php while ($row = $result->fetch_assoc()): ?>
          <article class="product-card">
            <figure>
              <img src="../assets/images/products/<?= htmlspecialchars($row['image']) ?>" 
                   alt="<?= htmlspecialchars($row['name']) ?>">

              <?php if ($row['stock_quantity'] <= 0): ?>
                  <div class="sold-out-overlay">Sold Out</div>
              <?php endif; ?>

              <figcaption>
                <h3 class="product-name"><?= htmlspecialchars($row['name']) ?></h3>
                <p class="product-description"><?= htmlspecialchars($row['description']) ?></p>
                <span class="product-price">₱<?= number_format($row['price'], 2) ?></span>
                <p class="product-stock">Stock: <?= intval($row['stock_quantity']) ?></p>

                <?php if ($row['stock_quantity'] > 0): ?>
                    <?php if ($isLoggedIn): ?>
                    <div class="cart-form">
                      <input type="hidden" value="<?= $row['id'] ?>">
                      <input type="number" value="1" min="1" max="<?= $row['stock_quantity'] ?>" class="quantity-input">
                      <button type="button" 
                              class="cart-button add-to-cart" 
                              data-product-id="<?= $row['id'] ?>">
                        Add to Cart
                      </button>
                    </div>
                    <?php else: ?>
                      <p class="login-notice">
                        <a href="../login.php" class="login-btn">Login to Add to Cart</a>
                      </p>
                    <?php endif; ?>
                <?php endif; ?>
              </figcaption>
            </figure>
          </article>
        <?php endwhile; ?>
      </div>
    </section>
  </main>

  <script>
  // Add to Cart functionality
  document.querySelectorAll('.add-to-cart').forEach(button => {
      button.addEventListener('click', () => {
          const productId = button.dataset.productId;
          const quantityInput = button.closest('.cart-form').querySelector('.quantity-input');
          const quantity = quantityInput.value;

          fetch('../add_to_cart.php', {
              method: 'POST',
              headers: {'Content-Type': 'application/x-www-form-urlencoded'},
              body: 'product_id=' + productId + '&quantity=' + quantity
          })
          .then(res => res.json())
          .then(data => {
              if (data.status === 'success') {
                  showMessage(data.message);
              } else {
                  showMessage(data.message, true);
              }
          });
      });
  });

  function showMessage(message, isError = false) {
      const msgBox = document.createElement('div');
      msgBox.textContent = message;
      msgBox.style.position = 'fixed';
      msgBox.style.top = '50%';
      msgBox.style.left = '50%';
      msgBox.style.transform = 'translate(-50%, -50%)';
      msgBox.style.background = isError ? 'red' : 'green';
      msgBox.style.color = 'white';
      msgBox.style.padding = '15px 25px';
      msgBox.style.borderRadius = '10px';
      msgBox.style.zIndex = 9999;
      msgBox.style.fontSize = '1.2em';
      document.body.appendChild(msgBox);

      setTimeout(() => msgBox.remove(), 2000);
  }
  </script>

</body>
</html>
