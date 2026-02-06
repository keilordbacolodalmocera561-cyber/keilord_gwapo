<?php
session_start();
include '../includes/db.php'; // adjust path if needed

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $conn->real_escape_string(trim($_POST['username']));
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        // Check hashed password
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['username'];
            unset($_SESSION['login_error']);
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Incorrect password.";
        }
    } else {
        $_SESSION['login_error'] = "Username not found.";
    }

    // Redirect to show error popup
    header("Location: admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
* { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
body { height: 100vh; display: flex; justify-content: center; align-items: center; background: linear-gradient(to right, #1cddff, #1100ff);}
main { width: 100%; max-width: 400px; padding: 40px; }
.form-section { background: rgba(255,255,255,0.05); backdrop-filter: blur(15px); border-radius: 15px; padding: 40px 30px; box-shadow: 0 8px 20px rgba(0,0,0,0.3); }
.form-title { text-align: center; color: #fff; font-weight: 600; font-size: 28px; margin-bottom: 30px; letter-spacing: 1px; }
.form-card { display: flex; flex-direction: column; gap: 20px; }
.form-group { position: relative; }
.form-group input { width: 100%; padding: 14px 12px; border-radius: 8px; border: 1px solid #ccc; background: rgba(255,255,255,0.1); color: #fff; font-size: 14px; outline: none; }
.form-group input:focus { border-color: #ffa800; box-shadow: 0 0 8px rgba(255,168,0,0.5); }
.form-group label { position: absolute; top: 50%; left: 12px; transform: translateY(-50%); color: #ccc; pointer-events: none; transition: 0.3s ease; }
.form-group input:focus + label, .form-group input:not(:placeholder-shown) + label { top: -10px; left: 10px; font-size: 12px; color: #ffa800; background: rgba(0,0,0,0.3); padding: 0 4px; border-radius: 4px; }
button { padding: 14px; border: none; border-radius: 8px; background: #ffa800; color: #000; font-weight: 600; cursor: pointer; transition: all 0.3s ease; letter-spacing: 0.5px; }
button:hover { background: #fff; color: #000; box-shadow: 0 4px 15px rgba(255,168,0,0.6); transform: translateY(-2px); }
/* Popup */
.popup-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 1000; display: none;}
.popup-box { background: #fff; color: #000; padding: 25px 30px; border-radius: 10px; min-width: 300px; text-align: center; box-shadow: 0 6px 20px rgba(0,0,0,0.3); }
.popup-box button { margin-top: 15px; padding: 10px 18px; background: #ffa800; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; transition: 0.3s; }
.popup-box button:hover { background: #000; color: #ffa800; }
</style>
</head>
<body>
<main>
<section class="form-section">
<h2 class="form-title">Admin Log In</h2>
<form class="form-card" method="post" action="admin_login.php">
<div class="form-group">
<input type="text" name="username" placeholder=" " required>
<label>Username</label>
</div>
<div class="form-group">
<input type="password" name="password" placeholder=" " required>
<label>Password</label>
</div>
<button type="submit" name="login">Login</button>
</form>
</section>
</main>

<!-- Popup -->
<div id="popup" class="popup-overlay">
<div class="popup-box">
<p id="popup-message"></p>
<button onclick="closePopup()">OK</button>
</div>
</div>

<script>
function showPopup(message) {
    const popup = document.getElementById('popup');
    const msg = document.getElementById('popup-message');
    msg.textContent = message;
    popup.style.display = 'flex';
}
function closePopup() {
    const popup = document.getElementById('popup');
    popup.style.display = 'none';
}
<?php if (isset($_SESSION['login_error']) && $_SESSION['login_error'] != ''): ?>
window.onload = function() {
    showPopup("<?= htmlspecialchars($_SESSION['login_error']) ?>");
};
<?php unset($_SESSION['login_error']); endif; ?>
</script>
</body>
</html>
