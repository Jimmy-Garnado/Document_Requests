<?php
  include("connection.php");
  include("emailer.php");

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
  $request_date = $_POST['request_date'];

  $docu = $conn -> query("SELECT price FROM supported_documents WHERE name='$document_type' LIMIT 1");
  $docu = $docu -> fetch_assoc();

  $price = $docu['price'];

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
    request_date, 
    price,
    date_created,
    status
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
    '$document_type', 
    '$academic_year', 
    '$purpose', 
    '$request_date', 
    $price,
    NOW(),
    'Pending'
  )";

  if($conn -> query($insert)){
    if(sendEmail($client_email, $document_type)){
      echo json_encode(['status' => 'success', 'message' => 'Request Submitted', 'description' => 'Request submitted, please wait atleast 3-5 business days to complete your request.']);
    }
  }else {
    echo json_encode(['status' => 'error', 'message' => 'Request Submission Failed', 'description' => 'Were having a problem submitting your request. Please contact the registrar or try again requesting after couple of minutes or hour.']);
  }

  $conn -> close();
?>