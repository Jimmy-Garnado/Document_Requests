<?php
  include("connection.php");
  session_start();

  header('Content-Type: application/json');

  $select_all = $conn->query("SELECT * FROM staff WHERE flag=0 AND id != {$_SESSION['staffid']}");

  $data = [];
  while ($row = $select_all->fetch_assoc()) {
      $data[] = $row;
  }
  
  echo json_encode([
    "data" => $data
  ]);
?>