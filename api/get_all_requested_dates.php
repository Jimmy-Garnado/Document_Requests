<?php
  include("connection.php");

  $currentDate = date("Y-m-d");
  
  $getall = $conn -> query("SELECT id, request_date FROM requests WHERE request_date  >= $currentDate");

  $events = [];

  while ($row = $getall->fetch_assoc()) {
    $events[] = [
      'id' => $row['id'],
      'title' => "rq-" . $row['id'],
      'start' => $row['request_date']
    ];
  }

  $conn -> close();

  header('Content-Type: application/json');
  echo json_encode($events);
?>