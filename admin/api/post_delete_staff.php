<?php
  include("connection.php");

  $staffid = $_POST['staffid'];

  $delete = $conn -> query("UPDATE staff SET flag=1 WHERE id=$staffid");

  if($delete){
    echo "ok";
  }

  $conn -> close();
?>