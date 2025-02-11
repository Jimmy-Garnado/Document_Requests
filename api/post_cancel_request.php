<?php
  include("connection.php");

  $requestid = $_POST['requestid'];

  if($conn -> query("UPDATE requests SET status='Cancelled' WHERE id=$requestid")){
    echo json_encode(['status' => 'success', 'message' => 'Request Cancelled', 'description' => 'You cancel this request successfully.']);
  }else {
    echo json_encode(['status' => 'error', 'message' => 'Request Cancellation Failed', 'description' => 'Were having a problem cancelling your request. Please contact the registrar or try again requesting after couple of minutes or hour.']);
  }

  $conn -> close();
?>