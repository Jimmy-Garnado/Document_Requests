<?php
  include("connection.php");

  header('Content-Type: application/json');

  $select_all = $conn->query("SELECT * FROM users");

  $data = [];
  while ($row = $select_all->fetch_assoc()) {
    $row["stupassword"] = str_repeat('*', strlen($row["stupassword"])); // Mask password
    $data[] = $row;
  }
  
  echo json_encode([
    "data" => $data
  ]);
?>