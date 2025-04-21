<?php
if (!isset($_GET['r'])) {
  header("location: request.php");
} else {
  include("api/connection.php");

  $requestID = $_GET['r'];

  $select = $conn->query("SELECT * FROM v2_requests WHERE request_id='$requestID' LIMIT 1");
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

    .preview {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 10px;
    }

    .preview img {
      width: 100%;
      /* Make images responsive */
      height: auto;
      max-height: 200px;
      object-fit: cover;
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

    .attachment {
      display: flex;
      flex-direction: column;
      text-decoration: none;
    }

    .attachment-img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .attachment-text {
      background-color: black;
      color: white;
      font-size: 12px;
      padding: 5px;
    }
  </style>

  <main class="container-fluid d-flex flex-lg-row flex-column p-0">
  <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="processPaymentModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="processPaymentModalLabel">Upload Attachments</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="uploadForm" enctype="multipart/form-data">
            <div class="modal-body">
              <input type="hidden" name="request_id" value="<?php echo $requestID; ?>">
              <input type="file" name="to_upload[]" class="form-control" id="authImages" multiple accept="image/*">
              <div class="preview">

              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" id="uploadButton">Upload</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php include("reusables/client-sidebar.php"); ?>
    <section class="col-12 col-lg-10">
      <style>
        .grid-2-col {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
          gap: 1rem;
        }
      </style>
      <?php if ($row['status'] !== 'Cancelled'): ?>
        <div class="py-0 pt-3">
          <button id="cancelButton" class="btn btn-danger">
            <i class="fas fa-times-circle"></i> Cancel
          </button>
          <button class="btn btn-primary" data-bs-toggle='modal' data-bs-target='#uploadModal'>
            <i class="fas fa-upload"></i> Upload Attachments
          </button>
        </div>

      <?php endif; ?>
      <div class="grid-2-col">
        <div>
          <div class="card mb-3">
            <div class="card-header">
              <h5>Information</h5>
            </div>
            <div class="card-body">
              <div class="row mb-2">
                <div class="col-md-5"><strong>Name:</strong></div>
                <div class="col-md-7"><?php echo $row['name']; ?></div>
              </div>
              <div class="row mb-2">
                <div class="col-md-5"><strong>Email:</strong></div>
                <div class="col-md-7"><?php echo $row['email']; ?></div>
              </div>
              <div class="row mb-2">
                <div class="col-md-5"><strong>Sex:</strong></div>
                <div class="col-md-7"><?php echo $row['sex']; ?></div>
              </div>
              <div class="row mb-2">
                <div class="col-md-5"><strong>Birthdate:</strong></div>
                <div class="col-md-7"><?php echo date('F d, Y', strtotime($row['birthday'])); ?></div>
              </div>
              <div class="row mb-2">
                <div class="col-md-5"><strong>Course:</strong></div>
                <div class="col-md-7"><?php echo $row["student_course"]; ?></div>
              </div>
              <div class="row mb-2">
                <div class="col-md-5"><strong>Date of Graduation:</strong></div>
                <div class="col-md-7"><?php echo date('F d, Y', strtotime($row['date_of_graduation'])); ?></div>
              </div>
              <div class="row mb-2">
                <div class="col-md-5"><strong>Permanent Address:</strong></div>
                <div class="col-md-7"><?php echo $row['permanent_address']; ?></div>
              </div>
              <div class="row mb-2">
                <div class="col-md-5"><strong>Contact Number:</strong></div>
                <div class="col-md-7"><?php echo $row['contact_number']; ?></div>
              </div>
              <div class="row mb-2">
                <div class="col-md-5"><strong>School Last Attended (SLA):</strong></div>
                <div class="col-md-7"><?php echo $row['school_last_attended']; ?></div>
              </div>
              <div class="row mb-2">
                <div class="col-md-5"><strong>Year Completed (SLA):</strong></div>
                <div class="col-md-7">
                  <?php echo date('F d, Y', strtotime($row['school_last_attended_completed_date'])); ?>
                </div>
              </div>
            </div>
          </div>
          <div class="card mb-3">
            <div class="card-header">
              <h5>Request Details</h5>
            </div>
            <div class="card-body">
              <div class="row mb-2">
                <div class="col-md-4"><strong>Requested Documents:</strong></div>
                <div class="col-md-8"><?php echo implode(", ", json_decode($row['document_to_request'])); ?></div>
              </div>
              <div class="row mb-2">
                <div class="col-md-4"><strong>Request Purpose:</strong></div>
                <div class="col-md-8"><?php echo $row['request_purpose']; ?></div>
              </div>

              <div class="row mb-2">
                <div class="col-md-4"><strong>Total Price:</strong></div>
                <div class="col-md-8"><?php echo number_format($row['total_price'], 2, '.', ','); ?> PHP</div>
              </div>
              <div class="row mb-2">
                <div class="col-md-4"><strong>Date Requested:</strong></div>
                <div class="col-md-8"><?php echo date('M d, Y', strtotime($row['date_created'])); ?></div>
              </div>
            </div>
          </div>

          <div class="card mb-3">
            <div class="card-header">
              <h5>Document Types</h5>
            </div>
            <div class="card-body">
              <?php
              $document_type = json_decode($row['document_to_request']);
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
              <div class="row mb-2">
                <div class="col-md-4"><strong>Status:</strong></div>
                <div class="col-md-8"><?php echo $row['status']; ?></div>
              </div>

              <?php if ($row['status'] === "Rejected"): ?>
                <div class="row mb-2">
                  <div class="col-md-4"><strong>Reason:</strong></div>
                  <div class="col-md-8"><?php echo $row['reject_reason']; ?></div>
                </div>
              <?php endif; ?>

              <div class="row mb-2">
                <div class="col-md-4"><strong>Payment Status:</strong></div>
                <div class="col-md-8">
                  <?php
                  echo $row['payment_status'] === "Not Paid"
                    ? "<span class='badge bg-danger'>Unpaid</span>"
                    : "<span class='badge bg-success'>Paid</span>";
                  ?>
                </div>
              </div>

              <!-- Pickup Type Row -->
              <div class="row mb-2">
                <div class="col-md-4"><strong>Pickup Type:</strong></div>
                <div class="col-md-8">
                  <?php echo strtoupper($row['pickup_type']); ?>
                </div>
              </div>

              <?php if ($row['pickup_type'] === 'authorized_person'): ?>
                <!-- Authorized Person Name Row -->
                <div class="row mb-2">
                  <div class="col-md-4"><strong>Authorized Person Name:</strong></div>
                  <div class="col-md-8"><?php echo $row['authorized_person_name']; ?></div>
                </div>

                <!-- Authorized Person Relationship Row -->
                <div class="row mb-2">
                  <div class="col-md-4"><strong>Relationship:</strong></div>
                  <div class="col-md-8"><?php echo $row['authorized_person_relationship']; ?></div>
                </div>
              <?php endif; ?>
              <div class="row mb-2">
                <div class="col-md-4"><strong>Approved By:</strong></div>
                <div class="col-md-8">
                  <?php echo !empty($row['assigned_staff']) ? $row['assigned_staff'] : "No assigned staff"; ?>
                </div>
              </div>
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
                      <a href="<?php echo $image; ?>" target="_blank" class="attachment">
                        <img src="<?php echo $image; ?>" class="attachment-img" alt="Attachment">
                        <p class="attachment-text"><?php echo basename($image); ?></p>
                      </a>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <style>

              </style>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </main>

  <script>
    $("#uploadForm").on("submit", function(e) {
      e.preventDefault();

      var formData = new FormData(this);

      $.ajax({
        url: 'api/upload_attachments.php',
        type: 'post',
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: () => {
          $("#uploadButton").attr('disabled', true);
          $("#uploadButton").html("Uploading (do not reload the page)...");
        },
        success: response => {
          try {
            let json = JSON.parse(response);

            if (json.status === true) {
              Swal.fire({
                title: json.message,
                text: json.description,
                icon: "success",
                confirmButtonText: "Ok"
              }).then((result) => {
                if (result.isConfirmed) {
                  location.reload();
                }
              });
            } else {
              Swal.fire({
                title: json.message,
                text: json.description,
                icon: "error"
              });
            }
          } catch (err) {
            console.error("Invalid JSON response", err);
            Swal.fire("Oops", "Unexpected response from server.", "error");
          } finally {
            $("#uploadButton").attr('disabled', false);
            $("#uploadButton").html("Upload");
          }
        }
      })

    })

    $('#authImages').on('change', function () {
      let files = this.files;
      let previewContainer = $('.preview');
      previewContainer.empty(); // Clear previous images

      $.each(files, function (index, file) {
        let reader = new FileReader();
        reader.onload = function (e) {
          let img = $('<img>').attr('src', e.target.result).addClass('img-thumbnail');
          previewContainer.append(img);
        };
        reader.readAsDataURL(file);
      });
    });

    $(document).on("click", "#cancelButton", function (e) {
      e.preventDefault();

      Swal.fire({
        title: 'Do you want to cancel the request?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, cancel it!',
        cancelButtonText: 'No, keep it'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: 'api/cancel_request.php',
            type: 'POST',
            data: { request_id: '<?php echo $_GET['r']; ?>' },  // Pass the request_id dynamically
            success: function (response) {
              let json = JSON.parse(response);
              if (json.status === "success") {
                Swal.fire({
                  title: 'Cancelled!',
                  text: json.message,
                  icon: 'success',
                  confirmButtonText: 'OK'
                }).then(() => {
                  location.reload(); // Optionally reload the page to reflect the changes
                });
              } else {
                Swal.fire({
                  title: 'Error!',
                  text: json.message,
                  icon: 'error',
                  confirmButtonText: 'OK'
                });
              }
            },
            error: function () {
              Swal.fire('Error', 'Request failed. Please try again.', 'error');
            }
          });
        }
      });
    });
  </script>
</body>

</html>