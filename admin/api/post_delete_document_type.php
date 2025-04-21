<?php
  include("connection.php");

  $document_id = $_POST['document_id'];

  $delete = $conn -> query("DELETE FROM supported_documents WHERE id=$document_id");

  if($delete){
    echo "ok";
  }

  $conn -> close();
?>