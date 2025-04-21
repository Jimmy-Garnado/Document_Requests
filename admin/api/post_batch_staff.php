<?php
  include("connection.php");
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
  if (isset($_FILES['batchstaff']) && $_FILES['batchstaff']['error'] == 0) {

    $filePath = $_FILES['batchstaff']['tmp_name'];
    // Load the Excel file
    try {
      $spreadsheet = IOFactory::load($filePath);
      $sheet = $spreadsheet -> getActiveSheet();

      // Loop through rows, starting from the second row if the first contains headers
      foreach ($sheet -> getRowIterator(2) as $row) {
        $name = $sheet -> getCell('A' . $row->getRowIndex())->getValue();
        $position = $sheet -> getCell('B' . $row->getRowIndex())->getValue();
        $role = $sheet -> getCell('C' . $row->getRowIndex())->getValue();
        $username = $sheet -> getCell('D' . $row->getRowIndex())->getValue();
        $password = $sheet -> getCell('E' . $row->getRowIndex())->getValue();

        if(empty($username)){
          $sanitizedName = strtolower(preg_replace('/\s+/', '', $name));
          $sanitizedRole = strtolower($role);
          $username = $sanitizedName . '.' . $sanitizedRole;
        }

        if(empty($password)){
          $password = generatePassword();
        }

        // Prepare and bind the SQL insert statement
        $conn -> query("INSERT INTO staff(name, position, role, username, password) VALUES(
        '$name',
        '$position',
        '$role',
        '$username',
        '$password')");
      }
    } catch (Exception $e) {
      echo "Error loading file: " . $e->getMessage();
    }

    echo "ok";
  } else {
    echo "File upload error.";
  }


  $conn -> close();
?>