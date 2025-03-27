<?php
include("connection.php");
include("emailer.php");

$user_id = $_POST['user_id'];
$user_password = $_POST['user_password'];

$select = $conn->query("SELECT * FROM users WHERE stuid='$user_id' AND stupassword='$user_password' AND is_deleted=false LIMIT 1");

if ($select->num_rows > 0) {
  $row = $select->fetch_assoc();
  $email = $row['stuemail'];

  $otp = rand(100000, 999999);
  
  // UPDATE OTP
  $conn -> query("UPDATE users SET otp='$otp' WHERE stuid='$user_id'");

  if(SendOTP($email, $otp)){
    echo 1;
  }else {
    echo 0;
  }
}else {
  echo 0;
}
exit();

$conn->close();
