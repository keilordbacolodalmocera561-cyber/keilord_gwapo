<?php
session_start();
include 'includes/db.php';

$error = "";

// Run only if form is submitted
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'customer') {
                header("Location: index.php");
            } else {
                header("Location: index.php"); // you can change this to admin.php later
            }
            exit();
        } else {
            $error = "❌ Invalid username or password.";
        }
    } else {
        $error = "❌ User not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
  
  <main>
    
    <section class="form-section">
     <a href="index.php" class="back-button">← Back to Home</a>
      <h2 class="form-title">Log In</h2>

      <?php if ($error): ?>
        <p class="error-msg"><?= $error ?></p>
      <?php endif; ?>

      <form class="form-card" method="post" action="login.php">
        <div class="form-group">
          <input type="text" name="username" placeholder=" " required>
          <label for="username">Username</label>
        </div>
        
        <div class="form-group">
          <input type="password" name="password" placeholder=" " required>
          <label for="password">Password</label>
        </div>

        <button type="submit" name="login">Login</button>
      </form>
    </section>
  </main>
</body>
</html>
