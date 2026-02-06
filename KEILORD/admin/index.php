<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>home admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="assets/home.css">
</head>
<body>

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

  <main class="dashboard-main">
    <section class="dashboard-welcome">
      <h2>Welcome Back, Admin</h2>
      <p>Manage crime reports, oversee users, and keep your community safe with ease.</p>
    </section>

<section class="dashboard-buttons">
  <button class="btn-primary" onclick="location.href='dashboard.php'">📊 Go to Dashboard</button>
  <button class="btn-outline" onclick="location.href='edit_product.php'">🛒 Manage Products</button>
  <button class="btn-outline" onclick="location.href='users.php'">👥 Manage Users</button>
</section>

<section class="dashboard-cards">
  <div class="card">
    <i class="fas fa-chart-bar"></i>
    <h3>Dashboard Insights</h3>
    <p>Monitor sales trends and popular styles over time.</p>
  </div>
  <div class="card">
    <i class="fas fa-balance-scale"></i>
    <h3>Products Management</h3>
    <p>Effortlessly add, update, or remove clothing items in your collection.</p>
  </div>
  <div class="card">
    <i class="fas fa-users"></i>
    <h3>User Control</h3>
    <p>Manage customer accounts and ensure a smooth shopping experience.</p>
  </div>
</section>


   
  </main>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>
</html>
