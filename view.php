<?php
if (!isset($_GET['r'])) {
  header("location: request.php");
} else {
  include("api/connection.php");

  $requestID = $_GET['r'];

  $select = $conn->query("SELECT * FROM requests WHERE request_id='$requestID' LIMIT 1");
  $row = $select->fetch_assoc();

  $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Viewing Request - BPC E-Registrar</title>
  <?php include("reusables/client-static-loader.php"); ?>
</head>

<body>

  <style>
    main {
      min-height: 100vh;
    }

    section>div {
      background-color: #fff;
      padding: 1rem;
    }

    @media (max-width: 992px) {
      section>div {
        padding: 1rem;
      }

      .submitrequest-desktop {
        display: none;
      }
    }
  </style>

  <main class="container-fluid d-flex flex-lg-row flex-column p-0">
    <?php include("reusables/client-sidebar.php"); ?>
    <section class="col-12 col-lg-10">
      <style>
        .grid-2-col {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
          gap: 1rem;
        }
      </style>

      <div class="grid-2-col">
        <!-- Left Grid: Request Details -->
        <div>
          <div class="card mb-3">
            <div class="card-header">
              <h5>Client Information</h5>
            </div>
            <div class="card-body">
              <p><strong>Name:</strong> <?php echo $row['client_name']; ?></p>
              <p><strong>Email:</strong> <?php echo $row['client_email']; ?></p>
              <p><strong>Primary Contact:</strong> <?php echo $row['client_contact_number1']; ?></p>
              <p><strong>Alternate Contact:</strong> <?php echo $row['client_contact_number2']; ?></p>
            </div>
          </div>

          <div class="card mb-3">
            <div class="card-header">
              <h5>Request Details</h5>
            </div>
            <div class="card-body">
              <p><strong>Documents:</strong> <?php echo implode(", ", json_decode($row['document_type'])); ?></p>
              <p><strong>Purpose:</strong> <?php echo $row['purpose']; ?></p>
              <p><strong>Academic Year:</strong> <?php echo $row['academic_year']; ?></p>
              <p><strong>Price:</strong> <?php echo number_format($row['price'], 2, '.', ','); ?> PHP</p>
              <p><strong>Date Requested:</strong> <?php echo date('M d, Y', strtotime($row['date_created'])); ?></p>
            </div>
          </div>

          <div class="card mb-3">
            <div class="card-header">
              <h5>Document Types</h5>
            </div>
            <div class="card-body">
              <?php
              $document_type = json_decode($row['document_type']);
              foreach ($document_type as $doc_type) {
                echo "<div class='form-check'>
                  <input class='form-check-input' type='checkbox' checked disabled>
                  <label class='form-check-label'>$doc_type</label>
                </div>";
              }
              ?>
            </div>
          </div>
        </div>

        <!-- Right Grid: Status & Info -->
        <div>
          <div class="card mb-3">
            <div class="card-header">
              <h5>Status</h5>
            </div>
            <div class="card-body">
              <p><strong>Status:</strong> <?php echo $row['status']; ?></p>
              <?php if ($row['status'] === "Rejected"): ?>
                <p><strong>Reason:</strong> <?php echo $row['reject_reason']; ?></p>
              <?php endif; ?>
              <p><strong>Payment Status:</strong>
                <?php echo $row['payment_status'] == false
                  ? "<span class='badge bg-danger'>UNPAID</span>"
                  : "<span class='badge bg-success'>PAID</span>"; ?>
              </p>
              <p><strong>Approved By:</strong> <?php echo $row['assigned_staff']; ?></p>
            </div>
          </div>



          <div class="card mb-3">
            <div class="card-header">
              <h5>Address</h5>
            </div>
            <div class="card-body">
              <p><strong>Street:</strong> <?php echo $row['street_name']; ?></p>
              <p><strong>Barangay:</strong> <?php echo $row['barangay']; ?></p>
              <p><strong>City:</strong> <?php echo $row['city']; ?></p>
            </div>
          </div>

          <?php
          $requestFolder = "images/requests/" . $row['request_id'];
          $images = [];

          if (is_dir($requestFolder)) {
            $files = scandir($requestFolder);
            foreach ($files as $file) {
              if ($file !== "." && $file !== ".." && exif_imagetype("$requestFolder/$file")) {
                $images[] = "$requestFolder/$file";
              }
            }
          }
          ?>

          <?php if (!empty($images)): ?>
            <div class="card">
              <div class="card-header">
                <h5>Attachments</h5>
              </div>
              <div class="card-body">
                <div class="row g-3">
                  <?php foreach ($images as $image): ?>
                    <div class="col-4">
                      <a href="<?php echo $image; ?>" target="_blank" class="d-block">
                        <img src="<?php echo $image; ?>" class="img-thumbnail attachment-img" alt="Attachment">
                      </a>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>


              <style>
                .attachment-img {
                  width: 100%;
                  height: 180px;
                  object-fit: cover;
                }
              </style>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </main>
  <script>

  </script>
</body>

</html>