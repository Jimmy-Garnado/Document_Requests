<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function SendEmail($recipient, $subject, $body)
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
    $mail->setFrom('support@bpceregistrar.online', 'BPC E-Registrar');
    $mail->addAddress($recipient);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;

    $mail->send();
    
    return true;
  } catch (Exception $e) {
    
    return false;
  }
}
?>