<?php
  include("connection.php");
  session_start();

  $username = $_POST['username'];
  $password = $_POST['password'];

  $select = $conn -> query("SELECT * FROM staff WHERE username='$username' AND password='$password' LIMIT 1");

  if($select -> num_rows > 0){
    $staff = $select -> fetch_assoc();

    $_SESSION['staffrole'] = $staff['role'];
    $_SESSION['staffid'] = $staff['id'];
    $_SESSION['staffname'] = $staff['name'];
    $_SESSION['staffimg'] = $staff['image_url'];

    echo "ok";
  }else {
    session_destroy();
    echo "no match";
  }

  $conn -> close();
?>