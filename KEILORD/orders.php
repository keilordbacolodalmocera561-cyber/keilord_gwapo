<?php
session_start();
include 'includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['id'];

// Fetch orders for this user
$query = "SELECT id, order_date, status, final_total FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders - Klord’s Clothing</title>
  <link rel="stylesheet" href="assets/css/style.css">

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f4f9;
      margin: 0;
      padding: 0;
    }

    header {
      background: #1f2937;
      color: #fff;
      padding: 20px;
      text-align: center;
    }

    header h2 {
      margin: 0;
      font-size: 1.8em;
      letter-spacing: 1px;
    }

    main {
      max-width: 900px;
      margin: 30px auto;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th, td {
      text-align: center;
      padding: 12px 15px;
    }

    th {
      background: #ef4444; /* red header */
      color: #fff;
      text-transform: uppercase;
      font-size: 14px;
      letter-spacing: 1px;
    }

    tr:nth-child(even) {
      background: #f9f9f9;
    }

    tr:hover {
      background: #ffeaea;
      transition: 0.3s;
    }

    td {
      font-size: 15px;
      color: #333;
    }

    .status {
      font-weight: bold;
      padding: 5px 10px;
      border-radius: 6px;
    }

    .status.pending {
      background: #facc15;
      color: #000;
    }

    .status.to_ship {
      background: #3b82f6;
      color: #fff;
    }

    .status.to_receive {
      background: #10b981;
      color: #fff;
    }

    .status.completed {
      background: #6b7280;
      color: #fff;
    }

    .btn-details {
      display: inline-block;
      padding: 6px 12px;
      background: #ef4444;
      color: #fff;
      text-decoration: none;
      border-radius: 6px;
      font-size: 14px;
      transition: background 0.3s;
    }

    .btn-details:hover {
      background: #b91c1c;
    }

    p {
      text-align: center;
      font-size: 16px;
      color: #555;
    }
  </style>
</head>
<body>
  <header>
    <h2>📦 My Orders</h2>
  </header>
  <main>
    <?php if ($result->num_rows > 0): ?>
      <table>
        <tr>
          <th>Order ID</th>
          <th>Date</th>
          <th>Status</th>
          <th>Total</th>
          <th>Details</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td>#<?php echo $row['id']; ?></td>
            <td><?php echo date("F j, Y", strtotime($row['order_date'])); ?></td>
            <td>
              <span class="status <?php echo strtolower(str_replace(' ', '_', $row['status'])); ?>">
                <?php echo ucfirst($row['status']); ?>
              </span>
            </td>
            <td>₱<?php echo number_format($row['total_amount'], 2); ?></td>
            <td><a href="order_details.php?id=<?php echo $row['id']; ?>" class="btn-details">View</a></td>
          </tr>
        <?php endwhile; ?>
      </table>
    <?php else: ?>
      <p>You don’t have any orders yet.</p>
    <?php endif; ?>
  </main>
</body>
</html>
