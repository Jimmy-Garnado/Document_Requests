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
      $count = 2;
      // Loop through rows, starting from the second row if the first contains headers
      foreach ($sheet -> getRowIterator(2) as $row) {
        $name = $sheet -> getCell('A' . $row->getRowIndex())->getValue();
        $position = $sheet -> getCell('B' . $row->getRowIndex())->getValue();
        $role = $sheet -> getCell('C' . $row->getRowIndex())->getValue();
        $username = $sheet -> getCell('D' . $row->getRowIndex())->getValue();
        $password = $sheet -> getCell('E' . $row->getRowIndex())->getValue();

        if (empty($name)) {
          echo json_encode([
            "status" => "error",
            "title" => "No Staff Name",
            "description" => "Name is missing. Import process stopped. [ROW: $count]"
          ]);

          exit(); // Stop further processing
        }

        if (empty($position)) {
          echo json_encode([
            "status" => "error",
            "title" => "No Staff Position",
            "description" => "Position is missing. Import process stopped. [ROW: $count]"
          ]);

          exit(); // Stop further processing
        }

        if (empty($role)) {
          echo json_encode([
            "status" => "error",
            "title" => "No Staff Role",
            "description" => "Role is missing. Import process stopped. [ROW: $count]"
          ]);

          exit(); // Stop further processing
        }

        if (empty($username)) {
          echo json_encode([
            "status" => "error",
            "title" => "No Staff Username",
            "description" => "Username is missing. Import process stopped. [ROW: $count]"
          ]);

          exit(); // Stop further processing
        }

        $check = $conn->query("SELECT * FROM staff WHERE username = '$username'");
        if ($check->num_rows > 0) {
          echo json_encode([
            "status" => "error",
            "title" => "Duplicate Username",
            "description" => "The username [$username] already exists in the system. Import process stopped. [ROW: $count]"
          ]);

          exit(); // Stop further processing
        }

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

         $count = $count + 1;
      }
    } catch (Exception $e) {
      echo json_encode(["status" => "error", "title" => "Incorrect Template", "description" => "Incorrect template, please download template from the website."]);
      exit(); // Stop further processing
    }

    echo json_encode(["status" => "success", "title" => "Upload Success", "description" => "Staff batch successfully added to the list"]);
  } else {
    echo json_encode(["status" => "error", "title" => "File Upload Error", "description" => "Re-upload file again after a few minutes."]);
  }


  $conn -> close();
?>