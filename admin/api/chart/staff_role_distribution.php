<?php
  include("../connection.php");

  header('Content-Type: application/json');

  $select_all = $conn->query("SELECT role, COUNT(*) as count FROM staff WHERE flag = 0 GROUP BY role");

  $roles = [];
  $counts = [];
  while ($row = $select_all->fetch_assoc()) {
    $roles[] = $row['role'];
    $counts[] = $row['count'];
  }
  
  echo json_encode([
    "role" => $roles,
    "count" => $counts
  ]);
?>