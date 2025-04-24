<?php
session_start();
include 'connection.php';

$userid = $_SESSION['stuid']; // adjust to your session system

if (!isset($_FILES['profile_picture'])) {
  echo json_encode(["status" => "error", "message" => "No Image File", "description" => "Please select and try again uploading image"]);
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
    echo json_encode(["status" => "error", "message" => "Unsupported Image Format", "description" => "Select only accepted image file format."]);
    exit;
}

// Compress and save the image
if ($img) {
  imagejpeg($img, ".." . $targetPath, 75); // 75% quality
  imagedestroy($img);

  // Save path to database
  $stmt = $conn->prepare("UPDATE users SET image_url = ? WHERE stuid = ?");
  $stmt->bind_param("ss", $targetPath, $userid);
  $stmt->execute();
  $stmt->close();

  echo json_encode(["status" => "success", "message" => "Picture Change Successfully", "description" => "Image has been imported and updated successfully"]);
} else {
  echo json_encode(["status" => "error", "message" => "Image Upload Error", "description" => "An error occured while updating image"]);
}
