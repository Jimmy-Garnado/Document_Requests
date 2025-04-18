<?php
include("connection.php");
include("../module/emailer.php");

function getRedirectLocation($role)
{
  switch ($role) {
    case "Admin":
      return "staffs.php";
    case "Staff":
      return "request.php";
    case "Cashier":
      return "cashier.php";
    default:
      return "";
  }
}


// Check if 'otp' is set in the POST request
if (isset($_POST['otp'])) {
  $user_id = $_POST['stuid_v'];
  $user_password = $_POST['stupassword_v'];
  $otp = $_POST['otp'];

  $check_otp = $conn->query(
    "SELECT stuid, id FROM users 
    WHERE stuid='$user_id' 
    AND stupassword='$user_password' 
    AND otp='$otp' 
    AND is_deleted=false 
    LIMIT 1"
  );

  if ($check_otp->num_rows > 0) {
    $student = $check_otp->fetch_assoc();

    session_start();

    $_SESSION['stuid'] = $student['stuid'];
    $_SESSION['clientid'] = $student['id'];

    echo json_encode(array("status" => "success", "message" => "Successfully Logged In", "description" => "Redirecting to Dashhboard."));
  } else {
    echo json_encode(array("status" => "error", "message" => "Wrong Code", "description" => "You put the wrong otp. Page will refresh."));
  }

  exit();
}

$user_id = $_POST['user_id'];
$user_password = $_POST['user_password'];

$check_student = $conn->query("SELECT stuemail FROM users WHERE stuid='$user_id' AND stupassword='$user_password' AND is_deleted=false LIMIT 1");

$check_staff = $conn->query("SELECT role, id, name, image_url FROM staff WHERE username='$user_id' AND password='$user_password' AND flag=false LIMIT 1");

if ($check_student->num_rows > 0) {
  $student = $check_student->fetch_assoc();
  $email = $student['stuemail'];

  $otp = rand(100000, 999999);

  if (SendOTP($email, $otp)) {
    $conn->query("UPDATE users SET otp='$otp' WHERE stuid='$user_id'");

    echo json_encode(array("status" => "otp-sent", "message" => "OTP Sent", "description" => "Please check your email for the OTP code."));
  } else {
    echo json_encode(array("status" => "error", "message" => "Failed to send OTP", "description" => "Please try again."));
  }
} else if ($check_staff->num_rows > 0) {
  $staff = $check_staff->fetch_assoc();

  session_start();

  $_SESSION['staffrole'] = $staff['role'];
  $_SESSION['staffid'] = $staff['id'];
  $_SESSION['staffname'] = $staff['name'];
  $_SESSION['staffimg'] = $staff['image_url'];

  // print_r($_SESSION);
  echo json_encode(array(
    "status" => "success",
    "message" => "Welcome " . $staff['role'] . " " . $staff['name'],
    "description" => "Redirecting to Dashboard.",
    "location" => "./admin/" . getRedirectLocation($staff['role']) // Add location in JSON response
  ));
} else {
  echo  json_encode(array("status" => "not-found", "message" => "Invalid Login Credential", "description" => "Please try again. Make sure your User ID and Password are matched."));
}

$conn->close();
