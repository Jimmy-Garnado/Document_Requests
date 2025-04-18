<?php
  include("connection.php");
  include("../../module/emailer.php");

  $requestid = $_POST['requestid'];
  $reason = $_POST['reason'];

  if($conn -> query("UPDATE v2_requests SET status='Rejected', reject_reason='$reason' WHERE request_id='$requestid'")){
    $select = $conn -> query("SELECT email, document_to_request FROM v2_requests WHERE request_id='$requestid'");
    $row = $select -> fetch_assoc();
    
    $documentToRequest = json_decode($row['document_to_request'], true); // true to convert into an array
    $documentList = implode(", ", $documentToRequest);

    if(sendEmailReject($row['email'], $documentList)){
      echo json_encode(['status' => 'success', 'message' => 'Request Rejected', 'description' => 'Request Rejected']);
    }
  }else {
    echo json_encode(['status' => 'error', 'message' => 'Rejecting Request Failed', 'description' => 'Were having a problem rejecting this request. Please contact the registrar or try again requesting after couple of minutes or hour.']);
  }

  $conn -> close(); 
?>