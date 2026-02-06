<?php
session_start();

/*
// Redirect if not admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

*/

// Include database and header
include '../includes/db.php';

// Handle role update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $role, $id);
    $stmt->execute();

    header("Location: users.php");
    exit();
}

// Handle user deletion
if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: users.php");
    exit();
}
?>
<link rel="stylesheet" href="assets/users.css">
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
  <br>
  <br>

<main>
  <h2>Manage Users</h2>
  
  <table>
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Email</th>
      <th>Role</th>
      <th>Actions</th>
    </tr>

    <?php
    $result = $conn->query("SELECT * FROM users");
    while ($user = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $user['id'] ?></td>
        <td><?= htmlspecialchars($user['username']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['role']) ?></td>
        <td>
          <form method="post">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">
            <select name="role">
              <option value="customer" <?= $user['role'] === 'customer' ? 'selected' : '' ?>>Customer</option>
              <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
            <button type="submit" name="update">Update</button>
          </form>
          <form method="post">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">
            <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</main>
