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

  $name = $_POST['addstaffname'];
  $role = $_POST['addstaffrole'];
  $position = $_POST['addstaffposition'];

  $sanitizedName = strtolower(preg_replace('/\s+/', '', $name));
  $sanitizedRole = strtolower($role);
  $username = $sanitizedName . '.' . $sanitizedRole;
  
  $password = generatePassword();


  $insert = $conn -> query("INSERT INTO staff(name, role, position, username, password) VALUES(
    '$name','$role','$position', '$username', '$password'
  )");

  if ($insert) {
    echo "ok";
  } else {
    echo 0;
  }

  $conn -> close();
?>