<?php
  include("connection.php");

  $stuid = $_POST['e_stuid'];
  $stuname = $_POST['e_stuname'];
  $stupassword = $_POST['e_stupassword'];

  $update = $conn -> query("UPDATE users SET stuname = '$stuname',  stupassword = '$stupassword' WHERE stuid='$stuid'");

  if($update){
    echo "ok";
  }else {
    echo 0;
  }

  $conn -> close();
?>