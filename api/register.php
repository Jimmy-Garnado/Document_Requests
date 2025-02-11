<?php
  include("connection.php");

  $stuid = $_POST['stuid'];
  $stuname = $_POST['stuname'];
  $stuemail = $_POST['stuemail'];
  $stupassword = $_POST['stupassword'];

  $insert = $conn -> query("INSERT users(
    stuid,
    stuname,
    stuemail,
    stupassword
  ) VALUES (
    '$stuid',
    '$stuname',
    '$stuemail',
    '$stupassword'
  )");

  if($insert === true){
    echo json_encode(array("status" => "success", "message" => "Register Successfully", "description" => "You can now log in using your email and password"));
  }else {
    echo json_encode(array("status" => "error", "message" => "Error", "description" => "Please try again or contact BPC Registrar Administrator"));
  }

  $conn -> close();
?>