<?php
include("connection.php");
include_once "../module/emailer.php";

$email_fp = $_POST['email_fp'];

$result = $conn->query("SELECT default_password, stuemail FROM users WHERE stuid='$email_fp'");

if ($result && $result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $default_password = $row['default_password'];
  $student_email = $row['stuemail'];

  $update = $conn->query("UPDATE users SET stupassword='$default_password' WHERE stuid='$email_fp'");

  if ($update) {
    sendNewPassword($student_email, $default_password);
    echo "ok";
  } else {
    echo "update_failed";
  }
} else {
  echo "nok"; // user not found
}

$conn->close();
?>