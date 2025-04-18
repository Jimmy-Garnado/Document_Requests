<?php
  include("connection.php");
  include_once "../module/emailer.php";

  function generateRandomString($length = 12) {
    $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $specialCharacters = '!@#$%^&*()_+-=[]{}|;:,.<>?';
    
    $allCharacters = $uppercase . $numbers . $specialCharacters;
    
    $allCharacters = str_shuffle($allCharacters);
    
    $randomString = '';
    $randomString .= $uppercase[rand(0, strlen($uppercase) - 1)]; 
    $randomString .= $numbers[rand(0, strlen($numbers) - 1)]; 
    $randomString .= $specialCharacters[rand(0, strlen($specialCharacters) - 1)];
    
    for ($i = 3; $i < $length; $i++) {
      $randomString .= $allCharacters[rand(0, strlen($allCharacters) - 1)];
    }
    
    return str_shuffle($randomString);
  }

  $new_password = generateRandomString(12);


  $email_fp = $_POST['email_fp'];

  if($conn -> query("SELECT * FROM users WHERE stuemail='$email_fp'") -> num_rows > 0){
    $update = $conn -> query("UPDATE users SET 
      stupassword='$new_password' WHERE stuemail='$email_fp'
    ");

    if($update){
      sendNewPassword($email_fp, $new_password);
      echo "ok";
    }
  }else {
    echo "nok";
  }

  $conn -> close();
?>