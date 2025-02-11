<?php
  include("connection.php");

  $stuid = $_POST['stuid'];
  $stupassword = $_POST['stupassword'];
  $contact_number = $_POST['contact_number'];
  $email = $_POST['email'];
  $street = $_POST['street'];
  $barangay = $_POST['barangay'];
  $city = $_POST['city'];
  $province = $_POST['province'];

  $update = $conn -> query("UPDATE users SET 
    stupassword='$stupassword',
    contact_number='$contact_number',
    email='$email',
    street='$street',
    barangay='$barangay',
    city='$city',
    province='$province'
    WHERE stuid='$stuid'
  ");

  if($update){
    echo "ok";
  }

  $conn -> close();
?>