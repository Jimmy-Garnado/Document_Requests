<?php
  include("connection.php");

  $name = $_POST['name'];
  $processing_time = $_POST['processing_time'];
  $price = $_POST['price'];

  $insert = $conn -> query("INSERT INTO supported_documents(name, processing_time, price) VALUES(
    '$name', $processing_time, $price
  )");

  if ($insert) {
    echo "ok";
  } else {
    echo 0;
  }

  $conn -> close();
?>