<?php
  include("connection.php");

  $staffid = $_POST['staffid'];
  $staffname = $_POST['staffname'];
  $staffrole = $_POST['staffrole'];
  $staffposition = $_POST['staffposition'];
  $staffusername = $_POST['staffusername'];
  $staffpassword = $_POST['staffpassword'];

  $update = $conn -> query("UPDATE staff SET name = '$staffname',  role = '$staffrole',  position = '$staffposition', username = '$staffusername', password = '$staffpassword' WHERE id=$staffid");

  if($update){
    echo "ok";
  }else {
    echo 0;
  }

  $conn -> close();
?>