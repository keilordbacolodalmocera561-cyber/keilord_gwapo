
<?php include '../includes/db.php'; ?>
<?php session_start(); ?>



<link rel="stylesheet" href="assets/addProduct.css">
 <header class="admin-header">
    <h1>🛠️ Admin Panel</h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="dashboard.php">Dashboard</a>
      <a href="edit_product.php">Edit Products</a>
      <a href="add_product.php">Add Products</a>
      <a href="users.php">Users</a>
      <a href="orders.php">Orders</a>
      <a href="log_out.php">Logout</a>
    </nav>
  </header>
<main>
  <h2>Add New Product</h2>
  <form method="post" enctype="multipart/form-data" class="add-product-form">
    <input type="text" name="name" placeholder="Product Name" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <input type="text" name="category" placeholder="Category">
    <input type="file" name="image" required>
    <button type="submit" name="submit">Add Product</button>
  </form>

  <?php
  if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $cat = $_POST['category'];
    $img = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    move_uploaded_file($tmp, "../assets/images/products/$img");

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $name, $desc, $price, $cat, $img);
    $stmt->execute();

    echo "<p class='success-msg'>Product added successfully!</p>";
  }
  ?>
</main>

