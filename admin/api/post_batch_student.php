<?php
  include("connection.php");
  include("../../module/emailer.php");
  require "../../vendor/autoload.php";

  use PhpOffice\PhpSpreadsheet\IOFactory;

  function generatePassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomPassword;
  }

  // Check if file was uploaded
  if (isset($_FILES['batchstudent']) && $_FILES['batchstudent']['error'] == 0) {

    $filePath = $_FILES['batchstudent']['tmp_name'];
    // Load the Excel file
    try {
      $spreadsheet = IOFactory::load($filePath);
      $sheet = $spreadsheet -> getActiveSheet();

      // Loop through rows, starting from the second row if the first contains headers
      foreach ($sheet -> getRowIterator(2) as $row) {
        $stuid = $sheet -> getCell('A' . $row->getRowIndex())->getValue();
        $stuname = $sheet -> getCell('B' . $row->getRowIndex())->getValue();
        $stuemail = $sheet -> getCell('C' . $row->getRowIndex())->getValue();

        $password = generatePassword();

        $default_password = "@Student1";

        // Prepare and bind the SQL insert statement
        $insert = $conn -> query("INSERT INTO users(stuid, stuname, stuemail, email, stupassword, default_password) VALUES(
          '$stuid',
          '$stuname',
          '$stuemail',
          '$stuemail',
          '$default_password',
          '$default_password')");

        if($insert){
          sendEmailConfirmation($stuemail, $stuname, $password);
        }
      }
    } catch (Exception $e) {
      echo "Error loading file: " . $e -> getMessage();
    }

    echo "ok";
  } else {
    echo "File upload error.";
  }


  $conn -> close();
?>