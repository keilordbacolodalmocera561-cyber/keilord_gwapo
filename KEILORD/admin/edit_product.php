<?php 
include '../includes/db.php'; 
session_start(); 
?>

<link rel="stylesheet" href="assets/editProducts.css">

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
<h2>Edit Products</h2>

<form method="get" class="product-filter">
  <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
  <button type="submit">Filter</button>
</form>

<?php
$search = $_GET['search'] ?? '';
$search_query = "";

if (!empty($search)) {
    $search_query = " WHERE name LIKE ?";
    $search_param = "%$search%";
    $stmt = $conn->prepare("SELECT * FROM products" . $search_query);
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM products");
}

while ($row = $result->fetch_assoc()):
    // Fetch current stock for this product
    $stockQ = $conn->prepare("SELECT stock_quantity FROM inventory WHERE product_id = ?");
    $stockQ->bind_param("i", $row['id']);
    $stockQ->execute();
    $stockRes = $stockQ->get_result();
    $stockRow = $stockRes->fetch_assoc();
    $stock_quantity = $stockRow['stock_quantity'] ?? 0; // default 0 if not exists
?>
<div class="product-edit">
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">
    <input type="text" name="name" value="<?= $row['name'] ?>" placeholder="Product Name" required>
    <input type="number" step="0.01" name="price" value="<?= $row['price'] ?>" placeholder="Price" required>
    <input type="number" name="stock_quantity" value="<?= $stock_quantity ?>" min="0" placeholder="Stock Quantity" required>
    <input type="file" name="image">
    <button type="submit" name="update">Update</button>
    <button type="submit" name="delete">Delete</button>
  </form>
</div>
<?php endwhile; ?>

<?php
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock_quantity = intval($_POST['stock_quantity']);
    $image = $_FILES['image']['name'];

    if (!empty($image)) {
        $target_dir = "../assets/images/products/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    } else {
        $currentImg = $conn->query("SELECT image FROM products WHERE id=$id")->fetch_assoc()['image'];
        $image = $currentImg;
    }

    // Update products table
    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sdsi", $name, $price, $image, $id);
    $stmt->execute();

    // Update inventory table (insert if doesn't exist)
    $invCheck = $conn->query("SELECT id FROM inventory WHERE product_id = $id")->fetch_assoc();
    if ($invCheck) {
        $stmtInv = $conn->prepare("UPDATE inventory SET stock_quantity = ? WHERE product_id = ?");
        $stmtInv->bind_param("ii", $stock_quantity, $id);
        $stmtInv->execute();
    } else {
        $stmtInv = $conn->prepare("INSERT INTO inventory (product_id, stock_quantity) VALUES (?, ?)");
        $stmtInv->bind_param("ii", $id, $stock_quantity);
        $stmtInv->execute();
    }

    echo "<p>✅ Product updated!</p>";
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "<p>❌ Product deleted!</p>";
}
?>

</main>

<?php include '../includes/footer.php'; ?>
