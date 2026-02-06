<?php
session_start(); // make sure session is started
include '../includes/db.php';

$isLoggedIn = isset($_SESSION['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Women's Collection | Klord</title>
  <link rel="stylesheet" href="../assets/css/product.css">
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
        <?php
        $query = "SELECT * FROM products WHERE category = 'kids' ORDER BY created_at DESC";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()): ?>
          <article class="product-card">
            <figure>
              <img src="../assets/images/products/<?= htmlspecialchars($row['image']) ?>" 
                   alt="<?= htmlspecialchars($row['name']) ?>">
              <figcaption>
                <h3 class="product-name"><?= htmlspecialchars($row['name']) ?></h3>
                <p class="product-description"><?= htmlspecialchars($row['description']) ?></p>
                <span class="product-price">₱<?= number_format($row['price'], 2) ?></span>

                <?php if ($isLoggedIn): ?>
                  <div class="cart-form">
                    <input type="hidden" value="<?= $row['id'] ?>">
                    <input type="number" value="1" min="1" class="quantity-input">
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

              </figcaption>
            </figure>
          </article>
        <?php endwhile; ?>
      </div>
    </section>
  </main>

  <script>
  // ✅ Attach event listeners to all add-to-cart buttons
  document.querySelectorAll('.add-to-cart').forEach(button => {
      button.addEventListener('click', () => {
          const productId = button.dataset.productId;

          fetch('../add_to_cart.php', {
              method: 'POST',
              headers: {'Content-Type': 'application/x-www-form-urlencoded'},
              body: 'product_id=' + productId
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

  // ✅ Show popup message
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
