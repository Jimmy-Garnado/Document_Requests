<?php

include("connection.php");

$today = new DateTime();
$endDate = (clone $today)->modify('+2 months');

$data = [];

$interval = new DateInterval('P1D');
$period = new DatePeriod($today, $interval, $endDate);

foreach ($period as $date) {
  $formattedDate = $date->format('Y-m-d');

  $sql = "SELECT COUNT(*) as slots_used FROM requests WHERE release_date = ?";

  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param('s', $formattedDate); // Bind the date parameter
    $stmt->execute();
    $stmt->bind_result($slots_used); // Store the result in $slots_used
    $stmt->fetch();
    $stmt->close();
  } else {
    $slots_used = 0; // Default to 0 if query preparation fails
  }
  
  $remaining_slots = 10 - $slots_used;

  $data[] = [
    'date' => $formattedDate,
    'slot_available' => $remaining_slots >= 0 ? $remaining_slots : 0  // Avoid negative slots
  ];
}

header('Content-Type: application/json');
echo json_encode($data);