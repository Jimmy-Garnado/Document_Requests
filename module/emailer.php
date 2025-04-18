<?php
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendNewPassword($client_email, $new_password)
{
  $mail = new PHPMailer(true);

  try {
    require 'emailer-header.php';
    $mail->addAddress($client_email);
    $mail->Subject = 'Your Password Has Been Reset';
    $mail->Body = '
        <p>Dear Recipient,</p>
        <p>We are pleased to inform you that your password has been successfully reset.</p>
        <p>Your new password is: <strong>' . htmlspecialchars($new_password) . '</strong></p>
        <p>Please use this password to log in to your account. For security purposes, we recommend changing your password immediately after logging in.</p>
        <p>If you did not request this password reset, please contact our support team immediately.</p>
        <p>Best regards,<br>BPC Registrar</p>
      ';
    $mail->send();
    return true;
  } catch (Exception $e) {
    return false;
  }
}

function sendEmail($client_email, $requested_document)
{
  $mail = new PHPMailer(true);
  try {
    require 'emailer-header.php';
    $mail->addAddress($client_email);
    $mail->Subject = 'Request for ' . $requested_document;
    $mail->Body = '
        <p>Dear Recipient,</p>
        <p>Thank you for your request for ' . htmlspecialchars($requested_document) . '.</p>
        <p>Please be informed that it will take approximately 3-5 business days for the registrar to process and accomplish your request.</p>
        <p>We appreciate your patience and understanding.</p>
        <p>Best regards,<br>BPC Registrar</p>
      ';
    $mail->send();
    return true;
  } catch (Exception $e) {
    exit();
  }
}

function SendOTP($client_email, $otp)
{
  $mail = new PHPMailer(true);
  try {
    require 'emailer-header.php';
    $mail->addAddress($client_email);
    $mail->Subject = "Your One-Time Password (OTP)";
    $mail->Body = '
        <p>Dear User,</p>
        <p>Your One-Time Password (OTP) for verification is: <strong>' . htmlspecialchars($otp) . '</strong></p>
        <p>Please enter this OTP to proceed. It is valid for a limited time.</p>
        <p>If you did not request this, please ignore this email.</p>
        <p>Best regards,<br><strong>BPC Registrar</strong></p>
      ';
    $mail->send();
    return true;
  } catch (Exception $e) {
    return false;
  }
}

function sendEmailApprove($client_email, $document_type)
{
  $mail = new PHPMailer(true);
  try {
    require "emailer-header.php";
    $mail->addAddress($client_email);
    $mail->Subject = 'Your request for ' . $document_type . ' has been approved';
    $mail->Body = '
          <p>Dear Recipient,</p>
          <p>We are pleased to inform you that your request for ' . htmlspecialchars($document_type) . ' has been approved.</p>
          <p>The registrar will now process your request, and it will take approximately 3-5 business days to complete.</p>
          <p>We appreciate your patience and understanding.</p>
          <p>Best regards,<br>BPC Registrar</p>
      ';
    $mail->send();
    return true;
  } catch (Exception $e) {
    return false;
  }
}

function sendEmailRelease($client_email, $document_type, $release_date)
{
  $mail = new PHPMailer(true);

  try {
    require "emailer-header.php";
    $mail->addAddress($client_email);
    $formatted_release_date = date('F j, Y', strtotime($release_date));
    if (!is_array($document_type)) {
      $document_type = [$document_type];
    }
    $document_types = implode(', ', $document_type);
    $mail->Subject = 'Your Document Request Is Ready For Release';
    $mail->Body = '
            <p>Dear Recipient,</p>
            <p>We are pleased to inform you that your request for <strong>' . $document_types . '</strong> is now ready for release and pickup on <strong>' . $formatted_release_date . '</strong>.</p>
            <p>Please visit the registrar office to collect your document at your convenience.</p>
            <p>We appreciate your patience and understanding throughout the process.</p>
            <p>Best regards,<br>BPC Registrar</p>
        ';
    $mail->send();
    return true;
  } catch (Exception $e) {
    return false;
  }
}
function sendEmailReject($client_email, $document_type)
{
  $mail = new PHPMailer(true);
  try {
    require "emailer-header.php";
    $mail->addAddress($client_email);
    $mail->Subject = 'Your request for ' . $document_type . ' was rejected.';
    $mail->Body = '
            <p>Dear Recipient,</p>
            <p>We are pleased to inform you that your request for ' . htmlspecialchars($document_type) . ' was rejected.</p>
            <p>We appreciate your patience and understanding.</p>
            <p>Best regards,<br>BPC Registrar</p>
        ';
    $mail->send();
    return true;
  } catch (Exception $e) {
    return false;
  }
}

function sendRequestCompleted($client_email, $document_type) {
  $mail = new PHPMailer(true);
  try {
    require "emailer-header.php";
    $mail->addAddress($client_email);
    $mail->Subject = 'Your request for ' . $document_type . ' has been completed';
    $mail->Body = '
      <p>Dear Valued Client,</p>
      <p>We are pleased to inform you that your request for <strong>' . htmlspecialchars($document_type) . '</strong> has been <strong>completed</strong>.</p>
      <p>Thank you for choosing BPC. We appreciate your trust and patience.</p>
      <p>Best regards,<br><strong>BPC Registrar</strong></p>
    ';
    $mail->send();
    return true;
  } catch (Exception $e) {
    return false;
  }
}

function sendEmailApproveByStaff($staffname, $client_email, $document_type){
  $mail = new PHPMailer(true);
  try {
    require "emailer-header.php";
    $mail->addAddress($client_email);
    $mail->Subject = 'Your request for ' . $document_type . ' has been approved';
    $mail->Body = '
      <p>Dear Recipient,</p>
      <p>We are pleased to inform you that your request for ' . htmlspecialchars($document_type) . ' has been approved.</p>
      <p>Staff '.$staffname.' was assigned to process your request, and it will take approximately 3-5 business days to complete.</p>
      <p>
      <p>We appreciate your patience and understanding.</p>
      <p>Best regards,<br>BPC Registrar</p>
    ';
    $mail -> send();
    return true;
  } catch (Exception $e) {
    return false;
  }
}

function sendEmailConfirmation($student_email, $student_name, $student_password){
  $mail = new PHPMailer(true);
  try {
    require "emailer-header.php";
    $mail->addAddress($student_email);
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
    
    $mail -> send();
    return true;
  } catch (Exception $e) {
    return false;
  }
}
?>