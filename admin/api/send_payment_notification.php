<?php
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Create a new PHPMailer instance
function sendPaymentNotification($request_id){
  include_once("connection.php");

  $query = "SELECT client_name, client_email, price FROM requests WHERE request_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $request_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 0) {
    return false; // No such request_id
  }

  $row = $result->fetch_assoc();

  $client_name = $row['client_name'];
  $client_email = $row['client_email'];
  $price = $row['price'];

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

    $mail->Subject = 'Payment Required for Approved Request';
    $mail->Body = '
      <p>Dear ' . htmlspecialchars($client_name) . ',</p>
      <p>We are pleased to inform you that your document request has been approved.</p>
      <p><strong>Total Amount Due:</strong> ₱' . number_format($price, 2) . '</p>
      <p>Please proceed with the payment at your earliest convenience so we can begin processing your request.</p>
      <p>If you have any questions or need assistance, feel free to reply to this email.</p>
      <br>
      <p>Thank you and best regards,</p>
      <p><strong>BPC Registrar</strong></p>
    ';

    // Send the email
    $mail -> send();

    echo json_encode(['status' => 'success', 'message' => 'Email sent successfully']);
  } catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to send email']);
  }
}

sendPaymentNotification($_POST['request_id']);
?>