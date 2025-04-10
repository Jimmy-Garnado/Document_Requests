<?php
include_once("connection.php");

$query = "
  SELECT 
    logs.id,
    logs.transaction_number,
    logs.request_id,
    logs.payment_date,
    logs.amount,
    logs.client_id,
    logs.date_created,
    users.stuname
  FROM payment_logs AS logs
  LEFT JOIN users ON logs.client_id = users.stuid
  ORDER BY logs.payment_date DESC
";

$result = mysqli_query($conn, $query);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
  $data[] = [
    'request_id' => $row['request_id'],
    'client' => $row['stuname'],
    'payment_date' => date("M d, Y", strtotime($row['payment_date'])),
    'amount' => number_format($row['amount'], 2),
    'status' => '<span class="badge bg-success">Paid</span>',
  ];
}

echo json_encode(['data' => $data]);
