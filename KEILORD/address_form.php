<?php
session_start();
include 'includes/db.php';

$userId = $_SESSION['id'] ?? 0;
$err = '';
$success = '';

if (!$userId) {
    header("Location: login.php");
    exit;
}

// Save posted address
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $street = $conn->real_escape_string(trim($_POST['street'] ?? ''));
    $barangay = $conn->real_escape_string(trim($_POST['barangay'] ?? ''));
    $municipality = $conn->real_escape_string(trim($_POST['municipality'] ?? ''));
    $province = $conn->real_escape_string(trim($_POST['province'] ?? ''));
    $zipcode = $conn->real_escape_string(trim($_POST['zipcode'] ?? ''));
    $phone = $conn->real_escape_string(trim($_POST['phone'] ?? ''));

    // Validations
    if (!$street || !$barangay || !$municipality || !$province || !$zipcode || !$phone) {
        $err = "Please fill all fields.";
    } elseif (!preg_match('/^09\d{9}$/', $phone)) {
      
        $err = "Phone number must start with '09' and be exactly 11 digits.";
    } else {
        $sql = "UPDATE users SET
                  street = '$street',
                  barangay = '$barangay',
                  municipality = '$municipality',
                  province = '$province',
                  zipcode = '$zipcode',
                  phone = '$phone'
                WHERE id = $userId";
        if ($conn->query($sql)) {
            $return = $_GET['return'] ?? 'buy.php';
            header("Location: $return?addr_saved=1");
            exit;
        } else {
            $err = "Failed to save details. Try again.";
        }
    }
}

// Load user's current info
$userQ = $conn->query("SELECT street, barangay, municipality, province, zipcode, phone FROM users WHERE id = $userId");
$user = $userQ->fetch_assoc() ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Enter Address - Klord's Clothing</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    main { max-width:700px; margin:40px auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.08); }
    label { display:block; margin:10px 0 6px; font-weight:600; }
    input { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; }
    .btn { background:red; color:#fff; padding:12px; border-radius:6px; border:none; cursor:pointer; margin-top:12px; }
    .note { color:#555; margin-bottom:10px; }
    .error { color:#c00; margin-bottom:10px; }
    .success { color:green; margin-bottom:10px; }
  </style>
</head>
<body>
<main>
  <h2>Confirm your address</h2>
  <p class="note">We need your shipping details to complete checkout. These will be used to deliver your order.</p>

  <?php if ($err): ?><div class="error"><?= htmlspecialchars($err) ?></div><?php endif; ?>
  <?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

  <form method="post" id="addressForm">
    <label for="street">Street / House No.</label>
    <input id="street" name="street" value="<?= htmlspecialchars($user['street'] ?? '') ?>" required>

    <label for="barangay">Barangay</label>
    <input id="barangay" name="barangay" value="<?= htmlspecialchars($user['barangay'] ?? '') ?>" required>

    <label for="municipality">Municipality / City</label>
    <input id="municipality" name="municipality" value="<?= htmlspecialchars($user['municipality'] ?? '') ?>" required>

    <label for="province">Province</label>
    <input id="province" name="province" value="<?= htmlspecialchars($user['province'] ?? '') ?>" required>

    <label for="zipcode">ZIP Code</label>
    <input id="zipcode" name="zipcode" value="<?= htmlspecialchars($user['zipcode'] ?? '') ?>" required>

    <label for="phone">Phone Number</label>
    <input id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="09XXXXXXXXX" required>

    <button type="submit" class="btn">Save & Continue</button>
  </form>
</main>

<script>
document.getElementById('addressForm').addEventListener('submit', function(e){
    const phone = document.getElementById('phone').value.trim();
    const regex = /^09\d{9}$/;
    if (!regex.test(phone)) {
        e.preventDefault();
        alert("Phone number must start with '09' and be exactly 11 digits.");
        return false;
    }
});
</script>
</body>
</html>
