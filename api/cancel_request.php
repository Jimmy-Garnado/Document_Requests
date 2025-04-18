<?php
include 'connection.php';  // Include your database connection

if (isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];

    // Prepare the SQL query to update the request status to 'Cancelled'
    $stmt = $conn->prepare("UPDATE v2_requests SET status = 'Cancelled' WHERE request_id = ?");
    $stmt->bind_param('s', $request_id);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Your request has been cancelled.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to cancel the request. Please try again later.'
        ]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request.'
    ]);
}
?>