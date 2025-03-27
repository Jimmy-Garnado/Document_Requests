<?php
session_start();

if (!isset($_GET['request_id'])) {
  header("location: request.php");
} else {
  include("api/connection.php");

  $requestID = $_GET['request_id'];

  $select = $conn->query("SELECT * FROM requests WHERE request_id='$requestID' LIMIT 1");
  $row = $select->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Viewing Request - BPC E-Registrar</title>
  <script src="../asset/jquery.js"></script>
  <script src="../asset/bootstrap/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="../asset/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../asset/css/main-style.css">
</head>

<body>

  <style>
    main {
      min-height: 100vh;
    }

    section>div {
      background-color: #fff;
      padding: 2rem 4rem;
      display: flex;
      flex-direction: column;
    }

    .client-profile {
      display: flex;
      flex-direction: column;
    }

    .client-profile>img {
      width: 150px;
      height: 150px;
      object-fit: cover;
      border-radius: 5px;
    }
  </style>

  <main class="container-fluid d-flex flex-row p-0">
    <?php include("../reusables/admin-sidebar.php"); ?>
    <section class="d-flex flex-row col-10">
      <div class="d-flex flex-column gap-2 col-6">
        <h5>Request Details</h5>
        <div class="row">
          <div class="col-4 fw-semibold">ID</div>
          <div class="col-8"><?php echo $row['request_id']; ?></div>
        </div>
        <div class="row">
          <div class="col-4 fw-semibold">Program/Course</div>
          <div class="col-8"><?php echo $row['program_degree']; ?></div>
        </div>
        <div class="row">
          <div class="col-4 fw-semibold">Academic Year</div>
          <div class="col-8"><?php echo $row['academic_year']; ?></div>
        </div>
        <h5 class="mt-4">Requested Documents</h5>
        <?php
        $documents = json_decode($row['document_type'], true); // Decode JSON string to an array

        if (!empty($documents)) {
          echo "<ul>"; // Start unordered list
          foreach ($documents as $document) {
            echo "<li>" . htmlspecialchars($document) . "</li>"; // List item for each document
          }
          echo "</ul>"; // End unordered list
        } else {
          echo "<p>No documents</p>";
        }
        ?>

        <h5 class="mt-4">Other Document</h5>
        <p><?php echo $row['other_documents']; ?></p>

        <h5 class="mt-4">Transaction Detail</h5>
        <div class="row">
          <div class="col-4 fw-semibold">Assigned Staff</div>
          <div class="col-8">
            <?php if ($row['assigned_staff'] === "") {
              echo "No Assigned Staff";
            } else {
              echo $row['assigned_staff'];
            } ?></div>
        </div>
        <div class="row">
          <div class="col-4 fw-semibold">Payment Status</div>
          <div class="col-8">
            <?php if ($row['payment_status'] !== 0) {
              echo "Not Paid";
            } else {
              echo "Paid";
            } ?></div>
        </div>
        <div class="row">
          <div class="col-4 fw-semibold">Status</div>
          <div class="col-8"><?php echo $row['status']; ?></div>
        </div>
        <div class="row">
          <div class="col-4 fw-semibold">Date Requested</div>
          <div class="col-8"><?php echo date("F j, Y g:iA", strtotime($row['date_created'])); ?></div>
        </div>
      </div>
      <div class="col-6 client-profile">
        <?php
        $client = $conn->query("SELECT * FROM users WHERE stuname='" . $row['client_name'] . "' LIMIT 1");
        $client = $client->fetch_assoc();
        ?>
        <h5>Client Profile</h5>
        <img class="img-fluid" src="../<?php echo $client['image_url']; ?>" />
        <p><?php echo $row['client_name']; ?></p>
        <p><?php echo $client['stuemail']; ?></p>
        <p><?php echo $client['contact_number']; ?></p>

        <h5 class="mt-4">Address Line</h5>
        <p><?php echo $client['street']; ?>, <?php echo $client['barangay']; ?>, <?php echo $client['city']; ?>, <?php echo $client['province']; ?></p>
        
        <?php
          if($row['authorized_person'] !== ""){
            echo "<h5 class='mt-4'>Authorized Person</h5>";
            echo "<p>".$row['authorized_person']."</p>";
            echo "<p>".$row['relationship']."</p>";

            $directory = "../images/requests/$requestID/";

            echo "<h5 class='mt-4'>Authorized ID</h5>";
            if (is_dir($directory)) {
                $files = glob($directory . "*.{jpg,jpeg,png,gif}", GLOB_BRACE); // Get image files
                
                if (!empty($files)) {
                    echo "<div class='d-flex flex-wrap gap-2'>";
                    foreach ($files as $file) {
                        echo "<img src='$file' class='img-thumbnail' style='width: 150px; height: auto;'>";
                    }
                    echo "</div>";
                } else {
                    echo "<p>No images uploaded.</p>";
                }
            } else {
                echo "<p>No images found.</p>";
            }
          }
          
        ?>
        </div>
      </div>
    </section>
  </main>
  <script>

  </script>
</body>

</html>