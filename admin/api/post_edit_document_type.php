<?php
  include("connection.php");

  $id = $_POST['eid'];
  $name = $_POST['ename'];
  $processing_time = $_POST['eprocessing_time'];
  $price = $_POST['eprice'];
  $is_active = $_POST['eisactive'];


  $update = $conn -> query("UPDATE supported_documents 
  SET name = '$name',  processing_time = $processing_time,  price = $price, is_active = $is_active WHERE id=$id");

  if($update){
    echo "ok";
  }else {
    echo 0;
  }

  $conn -> close();
?>