<?php
  require '../vendor/autoload.php';

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  function sendNewPassword($client_email, $new_password){
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
      $mail->Subject = 'Your Password Has Been Reset';
      $mail->Body = '
          <p>Dear Recipient,</p>
          <p>We are pleased to inform you that your password has been successfully reset.</p>
          <p>Your new password is: <strong>' . htmlspecialchars($new_password) . '</strong></p>
          <p>Please use this password to log in to your account. For security purposes, we recommend changing your password immediately after logging in.</p>
          <p>If you did not request this password reset, please contact our support team immediately.</p>
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

  function sendEmail($client_email, $requested_document) {
    $mail = new PHPMailer(true);

    try {
      // Set up PHPMailer
      $mail -> isSMTP();
      $mail -> Host = 'smtp.gmail.com';
      $mail -> SMTPAuth = true;
      $mail -> Username = 'bokwebmaster2000@gmail.com'; // Your Gmail address
      $mail -> Password = 'qxepkpgupfksvpfx'; // Your Gmail App Password
      $mail -> SMTPSecure = 'ssl';
      $mail -> Port = 465;
  
      $mail -> setFrom('bokwebmaster2000@gmail.com', 'BPC Registrar');
      
      $mail->addAddress($client_email);

      // Email content
      $mail -> isHTML(true);
      $mail -> Subject = 'Request for ' . $requested_document;
      $mail -> Body = '
        <p>Dear Recipient,</p>
        <p>Thank you for your request for ' . htmlspecialchars($requested_document) . '.</p>
        <p>Please be informed that it will take approximately 3-5 business days for the registrar to process and accomplish your request.</p>
        <p>We appreciate your patience and understanding.</p>
        <p>Best regards,<br>BPC Registrar</p>
      ';

      // Send the email
      $mail -> send();
      return true;
    } catch (Exception $e) {
      exit();
    }
  }

  function SendOTP($client_email, $otp) {
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

        $mail->setFrom('bokwebmaster2000@gmail.com', 'BPC Registrar');
        $mail->addAddress($client_email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "Your One-Time Password (OTP)";
        $mail->Body = '
            <p>Dear User,</p>
            <p>Your One-Time Password (OTP) for verification is: <strong>' . htmlspecialchars($otp) . '</strong></p>
            <p>Please enter this OTP to proceed. It is valid for a limited time.</p>
            <p>If you did not request this, please ignore this email.</p>
            <p>Best regards,<br><strong>BPC Registrar</strong></p>
        ';

        // Send the email
        $mail->send();
        return true;
    } catch (Exception $e) {
      echo $e;
      return false; // Return false instead of exiting to handle errors better
    }
}

?>