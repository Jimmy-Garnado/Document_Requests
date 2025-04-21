<?php

include("connection.php");
include("../../module/emailer.php");

$stuid = $_POST['a_stuid'];
$stuname = $_POST['a_stuname'];
$stuemail = $_POST['a_stuemail'];

$default_password = "@Student1";

// Check for duplicate student ID or email
$check = $conn->prepare("SELECT * FROM users WHERE stuid = ? OR stuemail = ?");
$check->bind_param("ss", $stuid, $stuemail);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
  echo json_encode([
    "status" => "duplicate",
    "message" => "Duplicate Entry",
    "description" => "Student ID or email already exists."
  ]);
  $conn->close();
  exit;
}

// Insert new user
$insert = $conn->prepare("INSERT INTO users (stuid, stuname, stuemail, email, default_password, stupassword) VALUES (?, ?, ?, ?, ?, ?)");
$insert->bind_param("ssssss", $stuid, $stuname, $stuemail, $stuemail, $default_password, $default_password);
$insertSuccess = $insert->execute();

// Send email confirmation
if ($insertSuccess && sendEmailConfirmation($stuemail, $stuname, $default_password)) {
  echo json_encode([
    "status" => "success",
    "message" => "Student Account Created",
    "description" => "User registered and email sent."
  ]);
} else {
  echo json_encode([
    "status" => "error",
    "message" => "Account Creation Failed",
    "description" => "Registration or email sending failed."
  ]);
}

$conn->close();
?>