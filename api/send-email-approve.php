<?php
  require 'vendor/autoload.php';

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  // Create a new PHPMailer instance

  function sendEmailApprove($client_email, $document_type){
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
      $mail->Subject = 'Your request for ' . $document_type . ' has been approved';
      $mail->Body = '
          <p>Dear Recipient,</p>
          <p>We are pleased to inform you that your request for ' . htmlspecialchars($document_type) . ' has been approved.</p>
          <p>The registrar will now process your request, and it will take approximately 3-5 business days to complete.</p>
          <p>We appreciate your patience and understanding.</p>
          <p>Best regards,<br>BPC Registrar</p>
      ';

      // Send the email
      $mail -> send();

      return true;
    } catch (Exception $e) {
      return false;
      exit();
    }
  }
?>