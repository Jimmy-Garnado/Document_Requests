<?php
  session_start();
  include("api/connection.php");

  $clientId = $_SESSION['clientid'];
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->bind_param("i", $clientId);
  $stmt->execute();

  $result = $stmt->get_result();

  $userData = [];

  if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
  } else {
    header("location: index.html");
    exit();
  }

  $stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Request - BPC E-Registrar</title>
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
  </style>

  <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">CONFIRM DETAILS</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="d-flex flex-column" id="confirmation-body">

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="submit-request-btn">Submit Request</button>
        </div>
      </div>
    </div>
  </div>

  <main class="container-fluid d-flex flex-lg-row flex-column p-0">
    <?php include("reusables/client-sidebar.php"); ?>

    <form id="requestForm" class="w-100" enctype="multipart/form-data">
      <section class="row m-0">
        <div class="d-flex flex-row justify-space-between align-items-center mb-2 mt-2">
          <button id="submitButton" class="ms-auto btn btn-success" type="submit">
            Submit Request
            <i class="fa-solid fa-arrow-right ms-1"></i>
          </button>
        </div>
        <div class="col-6">
        <div class="card">
          <div class="card-header">
            <h5>Request Form</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-label fw-semibold">Name</label>
                  <input type="text" value="<?php echo $userData['stuname']; ?>" class="form-control" disabled />
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label class="form-label fw-semibold">Sex</label>
                  <select class="form-select" name="sex">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                  </select>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label class="form-label fw-semibold">Birthday</label>
                  <input type="date" class="form-control" name="birthdate" />
                </div>
              </div>
            </div>
            <div class="row mt-4">
              <div class="col-8">
                <div class="form-group">
                  <label class="form-label fw-semibold">Permanent Address</label>
                  <input type="text" value="<?php echo $userData['street'] . ", " . $userData['barangay']  . ", " . $userData['city']  . ", " . $userData['province']; ?>" class="form-control" disabled />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <label class="form-label fw-semibold">Mobile No</label>
                  <input type="text" value="<?php echo $userData['contact_number']; ?>" class="form-control" disabled />
                </div>
              </div>
            </div>
            <div class="row mt-4">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-label fw-semibold">School Last Attended (SLA) (Before BPC)</label>
                  <input type="text" class="form-control" />
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-label fw-semibold">Year Completed (SLA)</label>
                  <input type="text" class="form-control" />
                </div>
              </div>
            </div>
            <div class="row mt-4">
              <div class="col-2">
                <div class="form-group">
                  <label class="form-label fw-semibold">Student ID</label>
                  <input type="text" value="<?php echo $userData['stuid']; ?>" class="form-control" disabled />
                </div>
              </div>
              <div class="col-5">
                <div class="form-group">
                  <label class="form-label fw-semibold">Course</label>
                  <select class="form-select" name="program">
                    <option value="BSIS">BSIS</option>
                    <option value="BSOM">BSOM</option>
                    <option value="BSAIS">BSAIS</option>
                    <option value="BTVTEd">BTVTEd</option>
                    <option value="ACT">ACT</option>
                  </select>
                </div>
              </div>
              <div class="col-5">
                <div class="form-group">
                  <label class="form-label fw-semibold">Date of Graduation</label>
                  <select class="form-select" name="year_graduated">
                    <option value="2015">2015</option>
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group mt-4">
              <label class="form-label fw-semibold">Document To Request</label>
              <?php
              include("api/connection.php");

              $document = $conn->query("SELECT * FROM supported_documents");

              while ($row = $document->fetch_assoc()) {
                $requirements = $row["requirements"];
                if (is_string($requirements)) {
                    $decoded = json_decode($requirements, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $decoded = unserialize($requirements);
                    }
                } else {
                  $decoded = $requirements;
                }

                $requirementsText = is_array($decoded) ? implode(', ', $decoded) : '';
                echo "
                  <div class='form-check d-flex flex-column mb-4'>
                    <div class='row'>
                      <div class='col-1'>
                        <input class='form-check-input' name='document_type[]' type='checkbox' value='" . $row['name'] . "' />
                      </div>
                      <div class='col-8'>
                        <p class='form-check-label fw-semibold'>" . $row['name'] . " </p>
                      </div>
                      <div class='col-3 '>
                        <p class='fw-semibold text-right'>PHP " . $row['price'] . ".00</p>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='col-1'></div>
                      <div class='col-8'>
                        <p class='fst-italic'>{$requirementsText}</p>
                      </div>
                     <div class='col-3'></div>
                    </div>
                  </div>
                ";
              }

              $conn->close();
              ?>
            </div>

            <div class="form-group mt-4">
              <label class="form-label fw-semibold">Purpose</label>
              <select class="form-select" name="purpose">
                <option value="Enrollment Purpose">Enrollment Purpose</option>
                <option value="Employment Purpose">Employment Purpose</option>
                <option value="others">Others: (Please indicate)</option>
              </select>
            </div>
          </div>
        </div>
        </div>
        
        <div class="col-6">
          <div class="card">
            <div class="card-header">
              <h5>Pickup Type</h5>
            </div>
            <div class="card-body">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="pickupType" id="myself" value="myself" checked>
                <label class="form-check-label" for="myself">Myself</label>
              </div>
              <div class="form-check mt-2">
                <input class="form-check-input" type="radio" name="pickupType" id="authorizedPerson" value="authorized">
                <label class="form-check-label" for="authorizedPerson">Authorized Person</label>
              </div>
              <div id="authUploadSection" class="mt-3 d-none">
                <p class="fw-semibold">AUTHORIZED PERSON NAME</p>
                <input type="text" name="authorized_person" class="form-control">
                <p class="fw-semibold mt-4">RELATIONSHIP</p>
                <select name="authorized_relationship" class="form-control">
                  <option>Sibling</option>
                  <option>Parent</option>
                  <option>Spouse</option>
                </select>
                <label for="authImages" class="form-label fw-semibold mt-4">2 VALID IDs AND RECENT SELFIE OF AUTHORIZED PERSON</label>
                <input type="file" name="authorized_id[]" class="form-control" id="authImages" multiple accept="image/*">
                <div class="preview mt-2"></div>
              </div>
            </div>
          </div>
          <div class="card mt-4">
            <div class="card-header">
              <h5>Attachments</h5>
            </div>
            <div class="card-body">
              <p class="mb-2">Kindly attach required documents and supporting documents if necessary.</p>
              <input type="file" name="authorized_id[]" class="form-control" id="authImages" multiple accept="image/*">
            </div>
          </div>
        </div>
      </section>
    </form>
  </main>

  <script>
    $(document).ready(function() {
      // Show/Hide file input based on radio selection
      $('input[name="pickupType"]').change(function() {
        if ($('#authorizedPerson').is(':checked')) {
          $('#authUploadSection').removeClass('d-none');

          $("input[name='authorized_id[]']").attr('required', true)
          $("input[name='authorized_person']").attr('required', true)
          $("input[name='authorized_relationship']").attr('required', true)
        } else {
          $('#authUploadSection').addClass('d-none');
          $('.preview').empty(); // Clear preview on change
          $('#authImages').val(''); // Reset input
        }
      });

      // Handle Image Preview
      $('#authImages').on('change', function() {
        let files = this.files;
        let previewContainer = $('.preview');
        previewContainer.empty(); // Clear previous images

        if (files.length < 4) {
          alert("Please select at least 2 images.");
          $(this).val(''); // Reset input
          return;
        }

        $.each(files, function(index, file) {
          let reader = new FileReader();
          reader.onload = function(e) {
            let img = $('<img>').attr('src', e.target.result).addClass('img-thumbnail');
            previewContainer.append(img);
          };
          reader.readAsDataURL(file);
        });
      });
    });

    $("#submit-request-btn").on("click", function() {
      var formData = new FormData($("#requestForm")[0]);

      $.ajax({
        url: 'api/post_request.php',
        type: 'post',
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: () => {
          $("#submit-request-btn").attr('disabled', true)
          $("#submit-request-btn").html("Submitting")
        },
        success: response => {
          console.log(response)
          let json = JSON.parse(response);

          if (json.status === "success") {
            Swal.fire({
              title: json.message,
              text: json.description,
              icon: json.status,
              showCancelButton: false,
              confirmButtonText: "View My Request"
            }).then((result) => {
              if (result.isConfirmed) {
                location.href = "my-request.php"
              }
            });
          } else {
            Swal.fire({
              title: json.message,
              text: json.description,
              icon: json.status
            })
          }
        }
      });
    })

    $("#requestForm").on("submit", function(event) {
      event.preventDefault();

      if ($("input[name='document_type[]']:checked").length === 0) {
        alert("Please select at least one document type.");
        return;
      }

      $("#confirmationModal").modal("toggle")

      var formData = new FormData(this);

      $.ajax({
        url: 'api/post_confirm_request.php',
        type: 'post',
        data: formData,
        contentType: false,
        processData: false,
        success: response => {
          $("#confirmation-body").html(response)
        }
      });
    })
  </script>
</body>

</html>