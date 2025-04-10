<?php
  date_default_timezone_set('Asia/Manila');

  // FOR LOCAL
  // $servername = "localhost";
  // $username = "root";
  // $password = "";
  // $database = "bpc";

  // FOR REMOTE
  $servername = "194.59.164.68";
  $username = "u994347109_bpc_username";
  $password = "tY;W2HWxmK/9";
  $database = "u994347109_bpc_main";

  $conn = new mysqli($servername, $username, $password, $database);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
?>