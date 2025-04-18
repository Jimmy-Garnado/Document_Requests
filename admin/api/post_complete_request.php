<?php
  include("connection.php");
  include("../../module/emailer.php");

  $requestid = $_POST['request_id'];

  if($conn -> query("UPDATE v2_requests SET status='Completed' WHERE request_id='$requestid'")){
    $select = $conn -> query("SELECT email, document_to_request FROM v2_requests WHERE request_id='$requestid'");
    $row = $select -> fetch_assoc();

    $documentToRequest = json_decode($row['document_to_request'], true); // true to convert into an array
    $documentList = implode(", ", $documentToRequest);

    if(sendRequestCompleted( $row['email'], $documentList)){
      echo json_encode(['status' => 'success', 'message' => 'Request Completed', 'description' => 'Request completed']);
    }
  }else {
    echo json_encode(['status' => 'error', 'message' => 'Completing Request Failed', 'description' => 'Were having a problem completing this request. Please contact the registrar or try again requesting after couple of minutes or hour.']);
  }


  $conn -> close(); 
?>