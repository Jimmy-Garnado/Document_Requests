<?php
  require '../../vendor/autoload.php';

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  // Create a new PHPMailer instance

  function sendEmailConfirmation($student_email, $student_name, $student_password){
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
    
      $mail->addAddress($student_email);

      // Email content
      $mail->isHTML(true);
      $mail->Subject = 'You Have Been Added to BPC';
      $mail->Body = "
          <p>Dear $student_name,</p>
          <p>We are pleased to inform you that your profile was added to BPC</p>
          <br>
          <p>You can use this password to login. Please reset your password on your first time login.</p>
          <p>PASSWORD: <strong>$student_password</strong></p>
          <br>
          <br>
          
          <p>Best regards,<br>BPC Registrar</p>
      ";

      // Send the email
      $mail -> send();

      return true;
    } catch (Exception $e) {
      return false;
      exit();
    }
  }
?>