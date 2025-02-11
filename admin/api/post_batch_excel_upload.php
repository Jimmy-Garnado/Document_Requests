<?php
  include("connection.php");
  require "../vendor/autoload.php";

  use PhpOffice\PhpSpreadsheet\IOFactory;

  // Check if file was uploaded
  if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {

    $filePath = $_FILES['file']['tmp_name'];
    // Load the Excel file
    try {
      $spreadsheet = IOFactory::load($filePath);
      $sheet = $spreadsheet -> getActiveSheet();

      // Loop through rows, starting from the second row if the first contains headers
      foreach ($sheet -> getRowIterator(2) as $row) {
        $studentId = $sheet -> getCell('A' . $row->getRowIndex())->getValue();
        $lastname = $sheet -> getCell('B' . $row->getRowIndex())->getValue();
        $firstname = $sheet -> getCell('C' . $row->getRowIndex())->getValue();
        $middlename = $sheet -> getCell('D' . $row->getRowIndex())->getValue();
        $studentemail = $sheet -> getCell('E' . $row->getRowIndex())->getValue();

        $studentfullname = "$lastname, $firstname $middlename";

        // Prepare and bind the SQL insert statement
        $conn -> query("INSERT INTO users(stuid, stuemail, stuname, stupassword) VALUES(
        '$studentId',
        '$studentemail',
        '$studentfullname',
        'password$studentId')");
      }
    } catch (Exception $e) {
      echo "Error loading file: " . $e->getMessage();
    }
  } else {
    echo "File upload error.";
  }


  $conn -> close();
?>