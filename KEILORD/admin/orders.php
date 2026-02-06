<?php
session_start();
include '../includes/db.php';

// Only process if the form is submitted
if (isset($_POST['update'], $_POST['id'], $_POST['status'])) {
    $id = intval($_POST['id']); // Use 'order_id' instead of 'id'
    $status = $_POST['status'];

    $allowed_status = ['pending','to ship', 'to deliver','delivered'];
    if (!in_array($status, $allowed_status)) {
        die('Invalid status value');
    }

    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    header("Location: orders.php"); // Refresh page
    exit;
}
?>


<link rel="stylesheet" href="assets/style.css">
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
  <h2>Order Tracking</h2>
  <table>
    <tr>
      <th>Order ID</th>
      <th>User</th>
      <th>Product</th>
      <th>Qty</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
    <?php
   $query = "SELECT o.id AS order_id, o.status, u.username, oi.product_id, p.name AS product_name, oi.quantity
FROM orders o
JOIN users u ON o.user_id = u.id
JOIN order_items oi ON oi.order_id = o.id
JOIN products p ON oi.product_id = p.id
ORDER BY o.id DESC
";

    $result = $conn->query($query);
    while ($order = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $order['order_id'] ?></td>
        <td><?= htmlspecialchars($order['username']) ?></td>
        <td><?= htmlspecialchars($order['product_name']) ?></td>
        <td><?= $order['quantity'] ?></td>
        <td><?= htmlspecialchars($order['status']) ?></td>
        <td>
          <form method="post">
            <input type="hidden" name="id" value="<?= $order['order_id'] ?>">
            <select name="status">
              <option value="pending" <?= $order['status']=='pending'?'selected':'' ?>>Pending</option>
              <option value="to ship" <?= $order['status']=='to ship'?'selected':'' ?>>To Ship</option>
              <option value="to deliver" <?= $order['status']=='to deliver'?'selected':'' ?>>To Deliver</option>
              <option value="delivered" <?= $order['status']=='delivered'?'selected':'' ?>>Delivered</option>
            </select>
            <button name="update">Update</button>
          </form>
        </td>
      </tr>
    <?php endwhile;

    if (isset($_POST['update'])) {
      $id = $_POST['id'];
      $status = $_POST['status'];
      $conn->query("UPDATE orders SET status='$status' WHERE id=$id");
      echo "<script>location.reload();</script>";
    }
    ?>
  </table>
</main>
