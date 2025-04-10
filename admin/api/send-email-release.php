<?php
require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function sendEmailRelease($client_email, $document_type, $release_date){
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

    $formatted_release_date = date('F j, Y', strtotime($release_date));

    // Ensure $document_type is an array. If it's a string, wrap it in an array
    if (!is_array($document_type)) {
      $document_type = [$document_type];
  }

    // Implode the document_type array to a string (separate items with commas)
    $document_types = implode(', ', $document_type);
    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Your Document Request Is Ready For Release';
    $mail->Body = '
            <p>Dear Recipient,</p>
            <p>We are pleased to inform you that your request for <strong>' . $document_types  . '</strong> is now ready for release and pickup on <strong>' . $formatted_release_date . '</strong>.</p>
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