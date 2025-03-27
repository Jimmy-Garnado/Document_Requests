<?php
  include("connection.php");
  session_start();

  $user_id = $_POST['user_id'] ?? ($_POST['stuid_v'] ?? null);
  $user_password = $_POST['user_password'] ?? ($_POST['stupassword_v'] ?? null);
  $role_type = $_POST['role_type'] ?? 'Student';

  $select = "";

  if($role_type === "Student"){
    $otp = $_POST['otp'];

    $select = $conn -> query("SELECT * FROM users WHERE stuid='$user_id' AND stupassword='$user_password' AND otp='$otp' AND is_deleted=false LIMIT 1");

    if($select -> num_rows > 0){
      $row = $select -> fetch_assoc();
  
      $_SESSION['stuid'] = $row['stuid'];
      $_SESSION['clientid'] = $row['id'];
  
      echo json_encode(array("status" => "success", "message" => "Successfully Logged In", "description" => "Redirecting to Dashhboard.", "role" => "Student"));
    }else {
      echo json_encode(array("status" => "error", "message" => "Wrong Code", "description" => "You put the wrong otp. Page will refresh."));
    }

    exit();
  }
  
  if($_POST['role_type'] !== "Student"){
    $select = $conn -> query("SELECT * FROM staff WHERE username='$user_id' AND password='$user_password' AND role='$role_type' LIMIT 1");

    if($select -> num_rows > 0){
      $staff = $select -> fetch_assoc();
  
      $_SESSION['staffrole'] = $staff['role'];
      $_SESSION['staffid'] = $staff['id'];
      $_SESSION['staffname'] = $staff['name'];
      $_SESSION['staffimg'] = $staff['image_url'];
  
      echo json_encode(array("status" => "success", "message" => "Successfully Logged In", "description" => "Redirecting to Dashhboard.", "role" => "Not-Student"));
    }else {
      echo json_encode(array("status" => "error", "message" => "Invalid Login Credential", "description" => "Please try again. Make sure your Student Id and Password are matched."));
    }

    exit();
  }

  $conn -> close();
?>