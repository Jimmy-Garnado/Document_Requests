<?php
  include("connection.php");

  $requestid = $_POST['requestid'];

  if($conn -> query("UPDATE v2_requests SET payment_status='Paid' WHERE request_id='$requestid'")){
    echo "ok";
  }

  $conn -> close(); 
?>