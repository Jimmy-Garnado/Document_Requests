<?php
  include("connection.php");

  $staffid = $_POST['staffid'];

  $delete = $conn -> query("DELETE FROM staff WHERE id=$staffid");

  if($delete){
    echo "ok";
  }

  $conn -> close();
?>