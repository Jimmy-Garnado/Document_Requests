<?php
  include("connection.php");

  $system_name = $_POST['system_name'];
  $system_color = $_POST['system_color'];

  $uploadDir = '../../images/';

  if (isset($_FILES['system_logo'])) {
    $file = $_FILES['system_logo'];
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFileName = 'bpc-logo.' . $fileExtension;
    $uploadPath = $uploadDir . $newFileName;

    if(move_uploaded_file($file['tmp_name'], $uploadPath)){
      $logo_url = "images/$newFileName";
      $conn -> query("UPDATE content_management SET logo_url='$logo_url'");
    }
  }

  if (isset($_FILES['system_bg'])) {
    $file = $_FILES['system_bg'];
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFileName = 'bpc-background.' . $fileExtension;
    $uploadPath = $uploadDir . $newFileName;

    if(move_uploaded_file($file['tmp_name'], $uploadPath)){
      $logo_url = "images/$newFileName";
      $conn -> query("UPDATE content_management SET background_url='$logo_url'");
    }
  }

  $update = $conn -> query("UPDATE content_management SET 
    sidebar_color='$system_color', system_name='$system_name'
  WHERE id=1");

  if($update){
    echo "ok";
  }

  $conn -> close();
?>