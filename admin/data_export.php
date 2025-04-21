<?php
  session_start();
  include_once("api/connection.php");

  if($_SESSION['staffrole'] != "Admin"){
    header("location: staffs.php");
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Export - <?php echo $CONTENT['system_name'];?></title>
  <?php include("static-loader.php"); ?>
</head>
<body>
  <main class="container-fluid d-flex flex-row p-0">
    <?php include("../reusables/admin-sidebar.php"); ?>
    
    <div class="col-10 p-4">
      <h4 class="fw-bold mb-4">Data Export</h4>
      <div class="row">

      </div>
    </div>
  </main>
  <script>
  
  </script>
</body>
</html>