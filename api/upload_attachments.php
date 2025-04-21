<?php
try {
  if (!isset($_POST['request_id'])) {
    throw new Exception("Missing request_id");
  }

  $request_id = $_POST['request_id'];

  function compressAndSaveImage($fileTmpPath, $destination, $quality = 60)
  {
    $info = getimagesize($fileTmpPath);
    $mime = $info['mime'];

    switch ($mime) {
      case 'image/jpeg':
        $image = imagecreatefromjpeg($fileTmpPath);
        break;
      case 'image/png':
        $image = imagecreatefrompng($fileTmpPath);
        break;
      case 'image/webp':
        $image = imagecreatefromwebp($fileTmpPath);
        break;
      case 'image/gif':
        $image = imagecreatefromgif($fileTmpPath);
        break;
      default:
        return false;
    }

    return imagejpeg($image, $destination, $quality);
  }

  $uploadBasePath = "../images/requests/" . $request_id;
  if (!is_dir($uploadBasePath)) {
    if (!mkdir($uploadBasePath, 0777, true)) {
      throw new Exception("Failed to create upload directory.");
    }
  }

  $attachments = [];

  if (!empty($_FILES['to_upload']['name'][0])) {
    foreach ($_FILES['to_upload']['tmp_name'] as $index => $tmpName) {
      if ($_FILES['to_upload']['error'][$index] === UPLOAD_ERR_OK) {
        $timestamp = date("d-m-Y_H-i-s");
        $fileName = "attachment_" . $timestamp . "_" . ($index + 1) . ".jpg";
        
        // $fileName = "attachment_" . ($index + 1) . ".jpg";
        $destinationPath = $uploadBasePath . "/" . $fileName;

        if (compressAndSaveImage($tmpName, $destinationPath)) {
          $attachments[] = $destinationPath;
        } else {
          throw new Exception("Failed to compress and save image at index $index.");
        }
      } else {
        throw new Exception("Upload error at index $index.");
      }
    }
  }

  echo json_encode([
    "message" => "Success",
    "description" => "Attachment upload successfully",
    "status" => true
  ]);
} catch (Exception $e) {
  echo json_encode([
    "message" => "Error",
    "description" => $e->getMessage(),
    "status" => false
  ]);
}
?>