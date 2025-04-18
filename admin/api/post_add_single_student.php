<?php
  function generatePassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
      $randomPassword .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomPassword;
  }
  
  include("connection.php");
  include("../../module/emailer.php");

  $stuid = $_POST['a_stuid'];
  $stuname = $_POST['a_stuname'];
  $stuemail = $_POST['a_stuemail'];

  $password = generatePassword();


  $insert = $conn -> query("INSERT INTO users(stuid, stuname, stuemail, stupassword) VALUES(
    '$stuid','$stuname','$stuemail', '$password'
  )");

  if ($insert  && sendEmailConfirmation($stuemail, $stuname, $password)) {
    echo "ok";
  } else {
    echo 0;
  }

  $conn -> close();
?>