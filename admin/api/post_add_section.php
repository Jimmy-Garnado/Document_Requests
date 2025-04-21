<?php
  include("connection.php");

  $name = $_POST['name'];

  $insert = $conn -> query("INSERT INTO sections(name) VALUES(
    '$name'
  )");

  if ($insert) {
    echo "ok";
  } else {
    echo 0;
  }

  $conn -> close();
?>