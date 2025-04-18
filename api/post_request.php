<?php
  include("connection.php");
  include_once "../module/emailer.php";

  session_start();

  $stuid = $_SESSION['stuid'];

  $user = $conn -> query("SELECT * FROM users WHERE stuid='$stuid' LIMIT 1");
  $user = $user -> fetch_assoc();

  $uniqueId = uniqid('R-', true);
  $shortenedId = substr($uniqueId, 0, 8);
  $finalId = strtoupper($shortenedId);

  $client_name = $user['stuname'];
  $client_id = $stuid;
  $request_id = $finalId;
  $student_number = $stuid;
  $program = $_POST['program'];
  $year_graduated = $_POST['year_graduated'];
  $client_email = $user['stuemail'];
  $client_contact_number1 = $user['contact_number'];
  $client_contact_number2 = $user['contact_number'];
  $street = $user['street'];
  $barangay = $user['barangay'];
  $city = $user['city'];
  $province = $user['province'];
  $document_type = $_POST['document_type'];
  $academic_year = $_POST['academic_year'];
  $purpose = $_POST['purpose'];
  $other_document = $_POST['other_document'] ?? "None";
  $auth_person = "";
  $auth_relationship = "";

  if($_POST['pickupType'] !== "myself"){
    $auth_person = $_POST['authorized_person'];
    $auth_relationship = $_POST['authorized_relationship'];

    $uploadDir = "../images/requests/$finalId/";
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }
  
    if (!empty($_FILES['authorized_id']['name'][0])) {
      $responses = [];
  
      foreach ($_FILES['authorized_id']['tmp_name'] as $key => $tmp_name) {
          $fileName = basename($_FILES['authorized_id']['name'][$key]);
          $targetFile = $uploadDir . $fileName;
  
          // Move uploaded file to the folder
          if (move_uploaded_file($tmp_name, $targetFile)) {
              $responses[] = "Uploaded: $fileName";
          } else {
              $responses[] = "Failed: $fileName";
          }
      }
    }
  }

  $total_price = 0;

  foreach ($document_type as $doc_type) {
    $docu = $conn -> query("SELECT price FROM supported_documents WHERE name='$doc_type' LIMIT 1");
    $docu = $docu -> fetch_assoc();

    $price = $docu['price'];

    $total_price = $total_price + $price;
  }

  $doc_json = json_encode($document_type, true);

  $insert = "INSERT INTO requests (
    client_name,
    client_id,
    request_id,
    student_number, 
    program_degree, 
    year_graduated, 
    client_email, 
    client_contact_number1, 
    client_contact_number2, 
    street_name, 
    barangay, 
    city, 
    document_type, 
    academic_year, 
    purpose, 
    price,
    date_created,
    status,
    other_documents,
    authorized_person,
    relationship
    )
  VALUES (
    '$client_name', 
    '$client_id',
    '$request_id',
    '$student_number', 
    '$program', 
    '$year_graduated', 
    '$client_email', 
    '$client_contact_number1', 
    '$client_contact_number2', 
    '$street', 
    '$barangay', 
    '$city', 
    '$doc_json', 
    '$academic_year', 
    '$purpose', 
    $total_price,
    NOW(),
    'Pending',
    '$other_document',
    '$auth_person',
    '$auth_relationship'
  )";

  $list_of_document = implode(", ", $document_type);

  if($conn -> query($insert)){
    if(sendEmail($client_email, $list_of_document)){
      echo json_encode(['status' => 'success', 'message' => 'Request Submitted', 'description' => 'Request submitted, please wait atleast 3-5 business days to complete your request.']);
    }
  }else {
    echo json_encode(['status' => 'error', 'message' => 'Request Submission Failed', 'description' => 'Were having a problem submitting your request. Please contact the registrar or try again requesting after couple of minutes or hour.']);
  }

  $conn -> close();
?>