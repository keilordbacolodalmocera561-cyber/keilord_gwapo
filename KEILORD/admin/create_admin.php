<?php
// create_admin.php
// Run this once on your server (then delete the file).
// Make sure the path to your db.php is correct.

include '../includes/db.php'; // adjust path if needed

$username = 'admin';
$password_plain = 'admin123';

// Create bcrypt hash using PHP
$hash = password_hash($password_plain, PASSWORD_DEFAULT);

// Protect: don't accidentally run twice
$check = $conn->prepare("SELECT id FROM admins WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "Admin user '{$username}' already exists. Exiting.\n";
    exit;
}
$check->close();

// Insert admin
$insert = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$insert->bind_param("ss", $username, $hash);

if ($insert->execute()) {
    echo "Admin user created successfully.\n";
    echo "Username: {$username}\n";
    echo "Password: {$password_plain}\n";
    echo "Password hash stored in DB: {$hash}\n";
} else {
    echo "Error creating admin: " . $conn->error . "\n";
}

$insert->close();
$conn->close();
