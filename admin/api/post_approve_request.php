<?php
  include("connection.php");
  include("send-email-approve.php");

  $requestid = $_POST['requestid'];

  if($conn -> query("UPDATE requests SET status='Processing' WHERE request_id='$requestid'")){
    $select = $conn -> query("SELECT client_email, document_type FROM requests WHERE request_id='$requestid'");
    $row = $select -> fetch_assoc();
    
    if(sendEmailApprove($row['client_email'], $row['document_type'])){
      echo json_encode(['status' => 'success', 'message' => 'Request Approved', 'description' => 'Request Successfully Approved']);
    }
  }else {
    echo json_encode(['status' => 'error', 'message' => 'Approving Request Failed', 'description' => 'Were having a problem approving this request. Please contact the registrar or try again requesting after couple of minutes or hour.']);
  }


  $conn -> close(); 
?>