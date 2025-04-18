<?php
  include("connection.php");

  header('Content-Type: application/json');

  $select_all = $conn->query("SELECT request_id, total_price, payment_status, status, date_created, name FROM v2_requests");

  $data = [];
  while ($row = $select_all->fetch_assoc()) {
      $data[] = $row;
  }
  
  echo json_encode([
    "data" => $data
  ]);
?>