<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['id'])) {
    exit("Please login to view orders.");
}

$userId = $_SESSION['id'];
$status = $_GET['status'] ?? ''; // pending, shipped, delivered, etc.

// ✅ Base query
$query = "SELECT id, order_date, status, final_total 
          FROM orders 
          WHERE user_id = ?";

$params = [$userId];
$types = "i";

if (!empty($status)) {
    $query .= " AND status = ?";
    $params[] = $status;
    $types .= "s";
}

$query .= " ORDER BY order_date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders</title>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: #f5f6fa;
      margin: 0;
      padding: 30px;
    }

    h2 {
      margin-bottom: 20px;
      color: #333;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      border-radius: 10px;
      overflow: hidden;
    }

    th, td {
      padding: 15px 20px;
      text-align: left;
    }

    th {
      background: #1f2937;
      color: white;
      font-weight: 600;
    }

    tr:nth-child(even) {
      background: #f9fafb;
    }

    tr:hover {
      background: #eef2ff;
    }

    .status {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.9em;
      font-weight: 600;
    }

    .status.pending { background: #fff3cd; color: #856404; }
    .status.shipped { background: #cce5ff; color: #004085; }
    .status.delivered { background: #d4edda; color: #155724; }
    .status.cancelled { background: #f8d7da; color: #721c24; }

    button {
      background: #4f46e5;
      color: white;
      border: none;
      padding: 8px 14px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 0.9em;
      transition: 0.2s ease;
    }

    button:hover {
      background: #4338ca;
    }

    .no-orders {
      text-align: center;
      padding: 30px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      color: #555;
      font-size: 1.1em;
    }
  </style>
</head>
<body>

  <h2>🛒 My Orders</h2>

  <?php if ($result->num_rows > 0): ?>
    <table>
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Date</th>
          <th>Status</th>
          <th>Total</th>
          <th>Details</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td>#<?= $row['id'] ?></td>
            <td><?= date("F j, Y", strtotime($row['order_date'])) ?></td>
            <td><span class="status <?= strtolower($row['status']) ?>"><?= ucfirst($row['status']) ?></span></td>
            <td>₱<?= number_format($row['final_total'], 2) ?></td>
            <td>
  <a href="order_details.php?id=<?= $row['id'] ?>" 
     style="background:#4f46e5;color:white;padding:8px 14px;border-radius:6px;text-decoration:none;">
     View
  </a>
</td>

          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="no-orders">📭 No orders to display in this section</div>
  <?php endif; ?>

  <script>
    function viewOrderDetails(orderId) {
      window.location.href = "order_details.php?id=" + orderId;
    }
  </script>
</body>
</html>
