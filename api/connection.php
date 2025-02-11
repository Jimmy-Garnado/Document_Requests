<?php
  date_default_timezone_set('Asia/Manila');

  $servername = "localhost";
  $username = "root";
  $password = "";
  $database = "bpc";

  $conn = new mysqli($servername, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
?>