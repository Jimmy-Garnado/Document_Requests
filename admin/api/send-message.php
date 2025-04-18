<?php
include 'connection.php';
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$request_id = $_POST['request_id'];
$message = $_POST['message'];
$email = "";

$result = $conn->query("SELECT email FROM v2_requests WHERE request_id = '$request_id'");
    
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $email = $row['email'];
}

SendMessage($email, $request_id, $message);

function SendMessage($email, $request_id, $message)
{
  $mail = new PHPMailer(true);

  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'support@bpceregistrar.online';
    $mail->Password = 'fA5X07~:JT$';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->isHTML(true);
    $mail->setFrom('support@bpceregistrar.online', 'BPC E-Registrar');


    $mail->addAddress($email);

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Message from Registrar to #' . $request_id;

    $mail->Body = '
      <p>Dear Client,</p>
      <br />
      <p>'.$message.'</p>
      <br />
      <p>If you have any questions, please feel free to contact us.</p>
      <p>Best regards,<br>BPC Registrar</p>
    ';

    // Send the email
    $mail->send();

    echo json_encode(['status' => 'success', 'message' => 'Email sent successfully']);
  } catch (Exception $e) {
    return false;
  }
}

?>