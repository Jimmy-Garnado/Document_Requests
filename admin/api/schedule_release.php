<?php
include("connection.php");
include("../../module/emailer.php");

// Sanitize inputs to prevent SQL injection
$request_id = $conn->real_escape_string($_POST['request_id']);
$release_date = $conn->real_escape_string($_POST['release_date']);

// Remove unnecessary timezone text (e.g., "(Philippine Standard Time)")
$release_date = preg_replace('/\s\([^\)]+\)$/', '', $release_date);  // Removes the "(Philippine Standard Time)"

// Convert the cleaned-up release_date to DateTime
$datetime = new DateTime($release_date);

// Format the date as YYYY-MM-DD
$release_date = $datetime->format('Y-m-d'); 


// Prepare the SQL query to update the release_date
$sql = "UPDATE v2_requests SET release_date = ? WHERE request_id = ?";

// Prepare the statement
if ($stmt = $conn->prepare($sql)) {
  $stmt->bind_param('ss', $release_date, $request_id);

  if ($stmt->execute()) {

    if($conn -> query("UPDATE v2_requests SET status='For Release' WHERE request_id='$request_id'")){
      $select = $conn -> query("SELECT email, document_to_request FROM v2_requests WHERE request_id='$request_id'");

      $row = $select -> fetch_assoc();
  
      $documentToRequest = json_decode($row['document_to_request'], true); // true to convert into an array
      $documentList = implode(", ", $documentToRequest);

      if(sendEmailRelease($row['email'], $documentList, $release_date)){
        echo json_encode(['status' => 'success', 'message' => 'Date Scheduled', 'description' => 'Request ready for release.']);
      }
    }else {
      echo json_encode(['status' => 'error', 'message' => 'Completing Request Failed', 'description' => 'Were having a problem completing this request. Please contact the registrar or try again requesting after couple of minutes or hour.']);
    }
  } else {
    // Return an error response as JSON
    echo json_encode([
      'status' => 'error',
      'message' => 'Failed to update release date.'
    ]);
  }
  $stmt->close();
} else {
  // Return an error if the query preparation fails
  echo json_encode([
    'status' => 'error',
    'message' => 'Database query preparation failed.'
  ]);
}

$conn->close();
?>