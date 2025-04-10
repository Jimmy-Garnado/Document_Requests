<?php
session_start();
include 'connection.php'; // Your DB connection file

$userid = $_SESSION['clientid']; // or however you're tracking logged-in user
$currentPassword = $_POST['currentPassword'];
$newPassword = $_POST['newPassword'];

// Get current hashed password from DB
$stmt = $conn->prepare("SELECT stupassword FROM users WHERE id = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$stmt->bind_result($hashedPassword);
$stmt->fetch();
$stmt->close();

if ($currentPassword !== $hashedPassword) {
    echo "Incorrect current password.";
    exit;
}

// Hash and update new password
$newHashedPassword = $newPassword;

$updateStmt = $conn->prepare("UPDATE users SET stupassword = ? WHERE id = ?");
$updateStmt->bind_param("si", $newHashedPassword, $userid);
$updateStmt->execute();
$updateStmt->close();

echo "success";