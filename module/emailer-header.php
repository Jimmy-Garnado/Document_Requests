<?php
  $mail->isSMTP();
  $mail->Host = 'smtp.hostinger.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'support@bpceregistrar.online'; 
  $mail->Password = 'fA5X07~:JT$';
  $mail->SMTPSecure = 'ssl';
  $mail->Port = 465;
  $mail->isHTML(true);
  $mail->setFrom('support@bpceregistrar.online', 'BPC E-Registrar');
?>