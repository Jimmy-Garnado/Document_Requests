<?php
  include("connection.php");

  header('Content-Type: application/json');

  $select_all = $conn->query("SELECT id, name FROM sections");

  $data = [];
  while ($row = $select_all->fetch_assoc()) {
      $data[] = $row;
  }
  
  echo json_encode([
    "data" => $data
  ]);
?>