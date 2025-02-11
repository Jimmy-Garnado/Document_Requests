<?php
  include("connection.php");
  include("send-email-reject.php");

  $requestid = $_POST['requestid'];
  $reason = $_POST['reason'];

  if($conn -> query("UPDATE requests SET status='Rejected', reject_reason='$reason' WHERE request_id='$requestid'")){
    $select = $conn -> query("SELECT client_email, document_type FROM requests WHERE request_id='$requestid'");
    $row = $select -> fetch_assoc();
    
    if(sendEmailReject($row['client_email'], $row['document_type'])){
      echo json_encode(['status' => 'success', 'message' => 'Request Rejected', 'description' => 'Request Rejected']);
    }
  }else {
    echo json_encode(['status' => 'error', 'message' => 'Rejecting Request Failed', 'description' => 'Were having a problem rejecting this request. Please contact the registrar or try again requesting after couple of minutes or hour.']);
  }

  $conn -> close(); 
?>