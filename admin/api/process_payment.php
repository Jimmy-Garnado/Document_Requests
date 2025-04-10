<?php
include 'connection.php';

require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function SendEmailConfirmation($client_email, $request_id, $transaction_number, $payment_date)
{
  $mail = new PHPMailer(true);

  try {
    // Set up PHPMailer
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'bokwebmaster2000@gmail.com'; // Your Gmail address
    $mail->Password = 'qxepkpgupfksvpfx'; // Your Gmail App Password
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    // Set the sender and recipient
    $mail->setFrom('bokwebmaster2000@gmail.com', 'BPC Registrar');

    $mail->addAddress($client_email);

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Payment Confirmation for Request #' . $request_id;

    $mail->Body = '
      <p>Dear Client,</p>
      <p>We are pleased to confirm that your payment for Request 
      <strong>
        <a href="http://localhost/bpc-main/view.php?r=' . urlencode($request_id) . '">
          #' . htmlspecialchars($request_id) . '
        </a>
      </strong> has been successfully processed.</p>
      <ul>
        <li><strong>Transaction Number:</strong> ' . htmlspecialchars($transaction_number) . '</li>
        <li><strong>Payment Date:</strong> ' . htmlspecialchars(date("F d, Y", strtotime($payment_date))) . '</li>
      </ul>
      <p>If you have any questions, please feel free to contact us.</p>
      <p>Best regards,<br>BPC Registrar</p>
    ';

    // Send the email
    $mail->send();

    return true;
  } catch (Exception $e) {
    return false;
    exit();
  }
}

function process_payment($request_id, $file, $transaction_number, $payment_date)
{
  $uploadDir = "../../images/requests/{$request_id}/";

  if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }

  $fileType = mime_content_type($file['tmp_name']);

  if (strpos($fileType, 'image') === false) {
    return ['status' => 'error', 'message' => 'The uploaded file is not an image.'];
  }

  $fileName = 'receipt.' . pathinfo($file['name'], PATHINFO_EXTENSION);
  $filePath = $uploadDir . $fileName;

  if (!move_uploaded_file($file['tmp_name'], $filePath)) {
    return ['status' => 'error', 'message' => 'Failed to upload the image.'];
  }

  try {
    $image = null;
    $imageInfo = getimagesize($filePath);
    if ($imageInfo['mime'] == 'image/jpeg') {
      $image = imagecreatefromjpeg($filePath);
    } elseif ($imageInfo['mime'] == 'image/png') {
      $image = imagecreatefrompng($filePath);
    } elseif ($imageInfo['mime'] == 'image/gif') {
      $image = imagecreatefromgif($filePath);
    }
    if (!$image) {
      return ['status' => 'error', 'message' => 'Invalid image type.'];
    }
    $jpgFilePath = pathinfo($filePath, PATHINFO_DIRNAME) . '/' . pathinfo($filePath, PATHINFO_FILENAME) . '.jpg';
    $width = 1024;
    $height = (int)($imageInfo[1] * ($width / $imageInfo[0])); // Maintain aspect ratio
    $compressedImage = imagescale($image, $width, $height);
    imagejpeg($compressedImage, $jpgFilePath, 75);
    if ($filePath !== $jpgFilePath) {
      unlink($filePath);
    }
    imagedestroy($image);
    imagedestroy($compressedImage);
  } catch (Exception $e) {
    return ['status' => 'error', 'message' => 'Error compressing or converting the image: ' . $e->getMessage()];
  }

  global $conn;

  $paymentStatus = 1;

  $stmt = $conn->prepare("UPDATE requests SET payment_status = ? WHERE request_id = ?");
  $stmt->bind_param("ii", $paymentStatus, $request_id);

  if ($stmt->execute()) {
    $getClientStmt = $conn->prepare("SELECT client_email, client_id, price FROM requests WHERE request_id = ?");
    $getClientStmt->bind_param("i", $request_id);
    $getClientStmt->execute();
    $getClientStmt->store_result();

    if ($getClientStmt->num_rows > 0) {
      $getClientStmt->bind_result($client_email, $client_id, $price);
      $getClientStmt->fetch();

      $amount = $price;
      $email = $client_email;

      $logStmt = $conn->prepare("INSERT INTO payment_logs (transaction_number, request_id, payment_date, amount, client_id) VALUES (?, ?, ?, ?, ?)");
      $logStmt->bind_param("sssdi", $transaction_number, $request_id, $payment_date, $amount, $client_id);



      if ($logStmt->execute()) {
        SendEmailConfirmation($email, $request_id, $transaction_number, $payment_date);
      }
    }
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $request_id = $_POST['request_id'];
  $transaction_number = $_POST['transactionNumber'];
  $payment_date = $_POST['paymentDate'];

  if (isset($_FILES['paymentReceipt']) && $_FILES['paymentReceipt']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['paymentReceipt'];

    process_payment($request_id, $file, $transaction_number, $payment_date);

    echo json_encode([
      "status" => "success",
      "message" => "Payment processed successfully.",
      "description" => "The payment has been processed and recorded successfully for the request. You can now view the details."
    ]);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'No file uploaded or error in upload.']);
  }
}
