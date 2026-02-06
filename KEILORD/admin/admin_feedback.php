<?php
include '../includes/db.php';
session_start();

$result = $conn->query("SELECT * FROM admin_feedback ORDER BY submitted_at DESC");

echo "<h2>Admin Feedback Inbox</h2>";
while ($row = $result->fetch_assoc()) {
 echo "<div class='feedback-card'>";
echo "<strong>Name:</strong> " . htmlspecialchars($row['name']) . "<br>";
echo "<strong>Email:</strong> " . htmlspecialchars($row['email']) . "<br>";
echo "<strong>Message:</strong><p>" . nl2br(htmlspecialchars($row['message'])) . "</p>";
echo "<small>Submitted: " . $row['submitted_at'] . "</small>";
echo "</div>";
}
?>
<link rel="stylesheet" href="assets/feedback.css">