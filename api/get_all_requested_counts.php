<?php
  include("connection.php");

  $currentDate = date("Y-m-d");
  
  $getall = $conn -> query("SELECT id, request_date FROM requests WHERE request_date  >= $currentDate");

  $remainingSlots = [];
  $totalSlots = 5;
  $eventCount = [];

  $startDate = new DateTime($currentDate);
  $endDate = new DateTime('next month'); // Or define a range as per your needs

  while ($startDate <= $endDate) {
    $dateStr = $startDate->format('Y-m-d');
    
    if ($startDate->format('N') <= 5) { // 1 is Monday, 5 is Friday
      $remainingSlots[$dateStr] = $totalSlots; // Set default slots for weekdays
    }

    $startDate -> modify('+1 day');
  }

  while ($row = $getall->fetch_assoc()) {
    $date = $row['request_date'];
    if (!isset($eventCount[$date])) {
        $eventCount[$date] = 0;
    }
    $eventCount[$date]++;
}

  foreach ($eventCount as $date => $count) {
    $remainingSlots[$date] = $totalSlots - $count; // Remaining slots for the date
}

  $response = [
    'eventCount' => $eventCount,
    'remainingSlots' => $remainingSlots
  ];

  $conn -> close();

  header('Content-Type: application/json');
  echo json_encode($response);
?>