<?php
  include("connection.php");

  $stuid = $_POST['stuid'];
  $contact_number = $_POST['contact_number'];
  $email = $_POST['email'];
  $street = $_POST['street'];
  $barangay = $_POST['barangay'];
  $city = $_POST['city'];
  $province = $_POST['province'];
  $sex = $_POST['sex'];
  $birthday = $_POST['birthday'];
  $student_year = $_POST['student_year'];
  $student_section = $_POST['student_section'];

  $update = $conn -> query("UPDATE users SET 
    sex='$sex',
    birthday='$birthday',
    contact_number='$contact_number',
    email='$email',
    street='$street',
    barangay='$barangay',
    city='$city',
    province='$province',
    student_year='$student_year',
    student_section='$student_section'
    WHERE stuid='$stuid'
  ");

  if($update){
    echo "ok";
  }

  $conn -> close();
?>