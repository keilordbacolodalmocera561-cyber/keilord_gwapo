<?php
session_start();
include '../includes/db.php';

// ✅ Summary counts
$product_count = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
$user_count    = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$order_count   = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];

$revenue = $conn->query("SELECT SUM(final_total) AS total_revenue FROM orders")->fetch_assoc()['total_revenue'] ?? 0.00;

// -------------------
// 1️⃣ Revenue Over Last 30 Days
// -------------------
$revenueQuery = $conn->query("
    SELECT DATE(order_date) AS day, SUM(final_total) AS daily_total
    FROM orders
    WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY DATE(order_date)
    ORDER BY DATE(order_date)
");

$revenue_labels = [];
$revenue_totals = [];

while ($row = $revenueQuery->fetch_assoc()) {
    $revenue_labels[] = $row['day'];
    $revenue_totals[] = floatval($row['daily_total']);
}

// -------------------
// 2️⃣ Orders Per Month (last 12 months)
// -------------------
$ordersQuery = $conn->query("
    SELECT DATE_FORMAT(order_date, '%Y-%m') AS month, COUNT(*) AS orders_count
    FROM orders
    WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY DATE_FORMAT(order_date, '%Y-%m')
    ORDER BY DATE_FORMAT(order_date, '%Y-%m')
");

$order_labels = [];
$order_counts = [];

while ($row = $ordersQuery->fetch_assoc()) {
    $order_labels[] = $row['month'];
    $order_counts[] = intval($row['orders_count']);
}

// -------------------
// 3️⃣ Top 5 Products by Sales
// -------------------
$topProductsQuery = $conn->query("
    SELECT p.name, SUM(oi.quantity) AS total_sold
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    GROUP BY oi.product_id
    ORDER BY total_sold DESC
    LIMIT 5
");

$product_labels = [];
$product_sold = [];

while ($row = $topProductsQuery->fetch_assoc()) {
    $product_labels[] = $row['name'];
    $product_sold[]   = intval($row['total_sold']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Analytics Dashboard</title>
<link rel="stylesheet" href="assets/dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<header class="admin-header">
  <h1>📊 Analytics Dashboard</h1>
  <nav class="admin-nav">
    <a href="index.php">Home</a>
    <a href="dashboard.php" class="active">Dashboard</a>
    <a href="edit_product.php">Edit Products</a>
    <a href="add_product.php">Add Products</a>
    <a href="users.php">Users</a>
    <a href="orders.php">Orders</a>
    <a href="log_out.php">Logout</a>
  </nav>
</header>

<main class="dashboard-main">
  <section class="dashboard-cards">
    <div class="card"><h3>Total Products</h3><p><?= $product_count ?></p></div>
    <div class="card"><h3>Registered Users</h3><p><?= $user_count ?></p></div>
    <div class="card"><h3>Total Orders</h3><p><?= $order_count ?></p></div>
    <div class="card"><h3>Total Revenue</h3><p>₱<?= number_format($revenue, 2) ?></p></div>
  </section>

  <section class="dashboard-charts">
    

    <div class="chart-container">
      <h2>🏆 Top 5 Products by Sales</h2>
      <canvas id="topProductsChart" height="150"></canvas>
    </div>

 <div class="chart-container">
      <h2>🗓️ Orders Per Month (Last 12 Months)</h2>
      <canvas id="ordersChart" height="150"></canvas>
    </div>
    
<div class="chart-container">
      <h2>📈 Revenue Over Last 30 Days</h2>
      <canvas id="revenueChart" height="150"></canvas>
    </div>

  </section>
   
</main>

<script>
const revenueData = {
    labels: <?= json_encode($revenue_labels) ?>,
    datasets: [{
        label: 'Revenue (₱)',
        data: <?= json_encode($revenue_totals) ?>,
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        tension: 0.3
    }]
};

const ordersData = {
    labels: <?= json_encode($order_labels) ?>,
    datasets: [{
        label: 'Orders Count',
        data: <?= json_encode($order_counts) ?>,
        backgroundColor: 'rgba(54, 162, 235, 0.6)'
    }]
};

const topProductsData = {
    labels: <?= json_encode($product_labels) ?>,
    datasets: [{
        label: 'Units Sold',
        data: <?= json_encode($product_sold) ?>,
        backgroundColor: 'rgba(255, 159, 64, 0.6)'
    }]
};

// Render Charts
new Chart(document.getElementById('revenueChart'), { type: 'line', data: revenueData });
new Chart(document.getElementById('ordersChart'), { type: 'bar', data: ordersData });
new Chart(document.getElementById('topProductsChart'), { type: 'bar', data: topProductsData });
</script>
</body>
</html>
