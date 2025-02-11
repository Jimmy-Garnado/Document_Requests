<?php
  include("connection.php");

  $stuid = $_POST['id'];

  $delete = $conn -> query("UPDATE users SET is_deleted=true WHERE id=$stuid");

  if($delete){
    echo "ok";
  }

  $conn -> close();
?>