<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Allow cross-origin requests
header('Access-Control-Allow-Origin: http://localhost:5173'); // Replace with your React app's URL
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json'); // Set header to JSON

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Set up PHPMailer
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'bokwebmaster2000@gmail.com'; // Your Gmail address
    $mail->Password = 'qxepkpgupfksvpfx'; // Your Gmail App Password
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    // Set the sender and recipient
    $mail->setFrom('bokwebmaster2000@gmail.com', 'BPC Registrar');
    
    // Check if POST data is set
    if (isset($_POST['email']) && isset($_POST['document_type'])) {
        $recipientEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $documentType = $_POST['document_type'];

        // Validate email format
        if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
          throw new Exception('Invalid email format.');
        }

        $mail->addAddress($recipientEmail);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Your request for ' . $documentType . ' is ready for release';
        $mail->Body = '
            <p>Dear Recipient,</p>
            <p>We are pleased to inform you that your request for ' . htmlspecialchars($documentType) . ' is now ready for release and pickup.</p>
            <p>Please visit the registrar office to collect your document at your convenience.</p>
            <p>We appreciate your patience and understanding throughout the process.</p>
            <p>Best regards,<br>BPC Registrar</p>
        ';

        // Send the email
        $mail -> send();

        echo json_encode(['status' => 'success']);
    } else {
        throw new Exception('Email address is required.');
    }
} catch (Exception $e) {
    // Handle errors
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

?>