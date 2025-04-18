<?php
include("connection.php");

header('Content-Type: application/json');

$TABLE_NAME = isset($_GET['table']) ? $_GET['table'] : null;
$WHERE_CONDITION = isset($_GET['where']) ? $_GET['where'] : null;

$query = "";

if ($WHERE_CONDITION) {
  $decoded = json_decode($WHERE_CONDITION, true);

  if (is_array($decoded)) {
    if (count($decoded) > 0) {
      // Quote each value to prevent SQL injection
      $safeValues = array_map(function($val) use ($conn) {
        return "'" . $conn->real_escape_string($val) . "'";
      }, $decoded);
      $query .= " WHERE status IN (" . implode(",", $safeValues) . ")";
    } else {
      // If empty array, return no records
      $query .= " WHERE 1=0";
    }
  } else {
    // Not an array, treat as a single status value
    $safeValue = $conn->real_escape_string($WHERE_CONDITION);
    $query .= " WHERE status = '$safeValue'";
  }
}

$select_all = $conn->query("SELECT * FROM $TABLE_NAME $query ");

$data = [];

while ($row = $select_all->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode([
  "data" => $data
]);
?>