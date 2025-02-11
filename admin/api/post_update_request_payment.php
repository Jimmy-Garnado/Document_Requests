<?php
  include("connection.php");

  $requestid = $_POST['requestid'];

  if($conn -> query("UPDATE requests SET payment_status=true WHERE request_id='$requestid'")){
    echo "ok";
  }

  $conn -> close(); 
?>