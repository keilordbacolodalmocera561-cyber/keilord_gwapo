<?php
include 'includes/db.php';
session_start();

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  if ($password !== $confirm_password) {
    $error = "Passwords do not match.";
  } else {
    $check = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $check->bind_param("ss", $email, $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
      $error = "Username or email already exists.";
    } else {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $username, $email, $hashed_password);
      $stmt->execute();
      $success = "Registration successful!";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="assets/css/register.css">
</head>
<body>
  <a href="index.php" class="back-button">← Back to Home</a>
  <main>
    <section class="form-section">
      <h2 class="form-title">Create Your Account</h2>

      <?php if ($success): ?>
        <p class="success-msg"><?= $success ?></p>
      <?php elseif ($error): ?>
        <p class="error-msg"><?= $error ?></p>
      <?php endif; ?>

      <form class="form-card" method="post" action="register.php">
        <div class="form-group">
          <input type="text" name="username" placeholder=" " required>
          <label for="username">Username</label>
        </div>

        <div class="form-group">
          <input type="email" name="email" placeholder=" " required>
          <label for="email">Email</label>
        </div>

        <div class="form-group">
          <input type="password" name="password" placeholder=" " required>
          <label for="password">Password</label>
        </div>

        <div class="form-group">
          <input type="password" name="confirm_password" placeholder=" " required>
          <label for="confirm_password">Confirm Password</label>
        </div>

        <button type="submit" name="register">Register</button>
      </form>
    </section>
  </main>
</body>
</html>