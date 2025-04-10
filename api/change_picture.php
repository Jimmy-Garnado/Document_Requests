<?php
session_start();
include 'connection.php';

$userid = $_SESSION['clientid']; // adjust to your session system

if (!isset($_FILES['profile_picture'])) {
  echo "No file uploaded.";
  exit;
}

$file = $_FILES['profile_picture'];
$filename = $userid . '.jpg';
$targetPath = '/images/users/' . $filename;

// Load the uploaded image
$img = null;
switch ($file['type']) {
  case 'image/jpeg':
    $img = imagecreatefromjpeg($file['tmp_name']);
    break;
  case 'image/png':
    $img = imagecreatefrompng($file['tmp_name']);
    break;
  case 'image/webp':
    $img = imagecreatefromwebp($file['tmp_name']);
    break;
  default:
    echo "Unsupported image type.";
    exit;
}

// Compress and save the image
if ($img) {
  imagejpeg($img, ".." . $targetPath, 75); // 75% quality
  imagedestroy($img);

  // Save path to database
  $stmt = $conn->prepare("UPDATE users SET image_url = ? WHERE id = ?");
  $stmt->bind_param("si", $targetPath, $userid);
  $stmt->execute();
  $stmt->close();

  echo "success";
} else {
  echo "Image processing failed.";
}
