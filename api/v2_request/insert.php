<?php
date_default_timezone_set('Asia/Manila');
include_once("../connection.php");
session_start();

$stuid = $_SESSION['stuid'];

$user = $conn->query("SELECT stuemail, stuname, street, barangay, city, province, contact_number FROM users WHERE stuid='$stuid' LIMIT 1");
$user = $user->fetch_assoc();

$randomDigits = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT); // Random 5-digit number
$request_id = "REQ-" . date('Y') . "-" . date('dm') . "-" . $randomDigits;
function compressAndSaveImage($fileTmpPath, $destination, $quality = 60)
{
  $info = getimagesize($fileTmpPath);
  $mime = $info['mime'];

  switch ($mime) {
    case 'image/jpeg':
      $image = imagecreatefromjpeg($fileTmpPath);
      break;
    case 'image/png':
      $image = imagecreatefrompng($fileTmpPath);
      break;
    case 'image/webp':
      $image = imagecreatefromwebp($fileTmpPath);
      break;
    case 'image/gif':
      $image = imagecreatefromgif($fileTmpPath);
      break;
    default:
      return false;
  }

  return imagejpeg($image, $destination, $quality);
}

$uploadBasePath = "../../images/requests/" . $request_id;
if (!is_dir($uploadBasePath)) {
  mkdir($uploadBasePath, 0777, true);
}


$receiptPath = '';
if (!empty($_FILES['receipt']['name'])) {
    if ($_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
        $fileName = "PAYMENT_RECEIPT.jpg"; // Use uniqid for a unique name
        $destinationPath = $uploadBasePath . "/" . $fileName;

        if (compressAndSaveImage($_FILES['receipt']['tmp_name'], $destinationPath)) {
            $receiptPath = $destinationPath;
        }
    }
}

// --- Authorized Person Attachments ---
$authAttachments = [];
if (!empty($_FILES['authorized_person_attachments']['name'][0])) {
  foreach ($_FILES['authorized_person_attachments']['tmp_name'] as $index => $tmpName) {
    if ($_FILES['authorized_person_attachments']['error'][$index] === UPLOAD_ERR_OK) {
      $fileName = "AUTHORIZED_PERSON_" . ($index + 1) . ".jpg";
      $destinationPath = $uploadBasePath . "/" . $fileName;

      if (compressAndSaveImage($tmpName, $destinationPath)) {
        $authAttachments[] = $destinationPath;
      }
    }
  }
}


// --- Other Attachments ---
$attachmentsArr = [];
if (!empty($_FILES['attachments']['name'][0])) {
    foreach ($_FILES['attachments']['tmp_name'] as $index => $tmpName) {
        if ($_FILES['attachments']['error'][$index] === UPLOAD_ERR_OK) {
            $fileName = "ATTACHMENT_" . ($index + 1) . ".jpg";
            $destinationPath = $uploadBasePath . "/" . $fileName;

            if (compressAndSaveImage($tmpName, $destinationPath)) {
                $attachmentsArr[] = $destinationPath;
            }
        }
    }
}

// Convert to JSON for DB
$authorized_person_attachments = json_encode($authAttachments);
$attachments = json_encode($attachmentsArr);

$email = $user['stuemail'];
$name = $user['stuname'];
$sex = $_POST['sex'];
$birthday = $_POST['birthday'];
$permanent_address = $user['street'] . ", " . $user['barangay'] . ", " . $user["city"] . ", " . $user['province'];
$contact_number = $user['contact_number'];
$school_last_attended = $_POST['school_last_attended'];
$school_last_attended_graduated = $_POST['school_last_attended_graduated'];
$student_id = $stuid;
$student_course = $_POST['student_course'];
$date_of_graduation = $_POST['date_of_graduation'];
$request_purpose = $_POST['request_purpose'];
$pickup_type = $_POST['pickup_type'];
$authorized_person_name = $_POST['authorized_person_name'];
$authorized_person_relationship = $_POST['authorized_person_relationship'];

$total_price = 0;
$document_to_request = $_POST['document_to_request']; // assuming it's an array

if (!empty($document_to_request)) {
  $placeholders = implode(',', array_fill(0, count($document_to_request), '?'));

  $stmt = $conn->prepare("SELECT name, price FROM supported_documents WHERE name IN ($placeholders)");

  $stmt->bind_param(str_repeat('s', count($document_to_request)), ...$document_to_request);

  $stmt->execute();
  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
    $total_price += (float) $row['price'];
  }

  $stmt->close();
}

$stmt = $conn->prepare("INSERT INTO v2_requests (
    request_id, 
    email,
    name, 
    sex, 
    birthday, 
    permanent_address, 
    contact_number,
    school_last_attended,
    school_last_attended_completed_date,
    student_id, 
    student_course, 
    date_of_graduation, 
    document_to_request,
    request_purpose, 
    pickup_type, 
    authorized_person_name, 
    authorized_person_relationship,
    authorized_person_attachments, 
    attachments, 
    total_price, 
    release_date,
    payment_status
) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

$documents = json_encode($document_to_request);
$payment_status = "Not Paid";
$stmt->bind_param(
  "sssssssssssssssssssdss",
  $request_id,
  $email,
  $name,
  $sex,
  $birthday,
  $permanent_address,
  $contact_number,
  $school_last_attended,
  $school_last_attended_graduated,
  $student_id,
  $student_course,
  $date_of_graduation,
  $documents,
  $request_purpose,
  $pickup_type,
  $authorized_person_name,
  $authorized_person_relationship,
  $authorized_person_attachments,
  $attachments,
  $total_price,
  $release_date,
  $payment_status
);

if ($stmt->execute()) {
  echo json_encode([
      "message" => "Success",
      "description" => "Request submitted successfully.",
      "status" => true
  ]);
} else {
  echo json_encode([
      "message" => "Error",
      "description" => "Failed to submit request. " . $stmt->error,
      "status" => false
  ]);
}

$stmt->close();
$conn->close();

?>