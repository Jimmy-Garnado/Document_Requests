<?php
  include("connection.php");

  $section_id = $_POST['section_id'];

  $delete = $conn -> query("DELETE FROM sections WHERE id=$section_id");

  if($delete){
    echo "ok";
  }

  $conn -> close();
?>