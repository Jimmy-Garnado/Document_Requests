<?php
  include("connection.php");
  include("../../module/emailer.php");
  session_start();

  $requestid = $_POST['requestid'];
  $staffname = $_SESSION['staffname'];

  if($conn -> query("UPDATE v2_requests SET assigned_staff='$staffname', status='Processing' WHERE request_id='$requestid'")){
    $select = $conn -> query("SELECT email, document_to_request FROM v2_requests WHERE request_id='$requestid'");
    $row = $select -> fetch_assoc();
    
    $documentToRequest = json_decode($row['document_to_request'], true); // true to convert into an array
    $documentList = implode(", ", $documentToRequest);

    if(sendEmailApproveByStaff($staffname, $row['email'], $documentList)){
      echo json_encode(['status' => 'success', 'message' => 'Request Approved', 'description' => 'Request Successfully Approved']);
    }
  }else {
    echo json_encode(['status' => 'error', 'message' => 'Approving Request Failed', 'description' => 'Were having a problem approving this request. Please contact the registrar or try again requesting after couple of minutes or hour.']);
  }


  $conn -> close(); 
?>