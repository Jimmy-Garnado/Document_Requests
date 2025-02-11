<?php
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function sendEmailRelease($client_email, $document_type){
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
    $mail->Subject = 'Your request for ' . $document_type . ' is ready for release';
    $mail->Body = '
        <p>Dear Recipient,</p>
        <p>We are pleased to inform you that your request for ' . htmlspecialchars($document_type) . ' is now ready for release and pickup.</p>
        <p>Please visit the registrar office to collect your document at your convenience.</p>
        <p>We appreciate your patience and understanding throughout the process.</p>
        <p>Best regards,<br>BPC Registrar</p>
    ';

    // Send the email
    $mail -> send();
    return true;
  } catch (Exception $e) {
    return false;
  }
}
?>