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

  if (isset($_FILES['batchstudent']) && $_FILES['batchstudent']['error'] == 0) {
    $filePath = $_FILES['batchstudent']['tmp_name'];

    try {
      $spreadsheet = IOFactory::load($filePath);
      $sheet = $spreadsheet -> getActiveSheet();
      $count = 2;

      foreach ($sheet -> getRowIterator(2) as $row) {
        $stuid = $sheet -> getCell('A' . $row->getRowIndex())->getValue();
        $stuname = $sheet -> getCell('B' . $row->getRowIndex())->getValue();
        $stuemail = $sheet -> getCell('C' . $row->getRowIndex())->getValue();

        if (empty($stuid)) {
          echo json_encode([
            "status" => "error",
            "title" => "No Student Id",
            "description" => "Student ID is missing. Import process stopped. [ROW: $count]"
          ]);

          exit(); // Stop further processing
        }

        if (empty($stuname)) {
          echo json_encode([
            "status" => "error",
            "title" => "No Student Name",
            "description" => "Student name is missing. Import process stopped. [ROW: $count]"
          ]);

          exit(); // Stop further processing
        }

        if (empty($stuemail)) {
          echo json_encode([
            "status" => "error",
            "title" => "No Student Email",
            "description" => "Student name is missing. Import process stopped. [ROW: $count]"
          ]);

          exit(); // Stop further processing
        }
        // Check if email already exists
        $check = $conn->query("SELECT * FROM users WHERE stuemail = '$stuemail'");
        if ($check->num_rows > 0) {
          echo json_encode([
            "status" => "error",
            "title" => "Duplicate Email",
            "description" => "The email [$stuemail] already exists in the system. Import process stopped. [ROW: $count]"
          ]);

          exit(); // Stop further processing
        }

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

        $count = $count + 1;
      }

    } catch (Exception $e) {
      echo json_encode(["status" => "error", "title" => "Incorrect Template", "description" => "Incorrect template, please download template from the website."]);
      exit(); // Stop further processing
    }

    echo json_encode(["status" => "success", "title" => "Upload Success", "description" => "Student batch successfully added to the list"]);
  } else {
    echo json_encode(["status" => "error", "title" => "File Upload Error", "description" => "Re-upload file again after a few minutes."]);
  }

  $conn -> close();
?>