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
  header("location: index.php");
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
      height: auto;
      max-height: 200px;
      object-fit: cover;
    }
  </style>



  <main class="container-fluid d-flex flex-lg-row flex-column p-0">
    <?php include("reusables/client-sidebar.php"); ?>

    <form id="requestForm" class="w-100" enctype="multipart/form-data">
      <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Payment</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-4 mt-4 d-flex flex-column align-items-center">
                <p class="h5">Please Pay</p>
                <p class="h2 fw-bold" id="total_price_display">Please Pay</p>
              </div>
              <p class="mb-4">Please send your payment via the following options:</p>
              <div class="mb-4 d-flex flex-column">
                <p class="h5 fw-bold">Scan QR Code</p>
                <img src="images/sample-qr.png" alt="GCash QR Code" class="align-self-center img-fluid">
              </div>

              <div class="mb-4">
                <p class="h5 fw-bold">GCash Number</p>
                <p style="
                font-size: 18px;
                background-color: var(--bs-blue);
                color: white;
                padding: 0.5rem;
                ">09-637-205-895 - BPC Cashier</p>
              </div>

              <div>
                <p class="h5 fw-bold">Upload Receipt</p>
                <input type="file" name="receipt" class="form-control" id="receipt" required accept="image/*">
                <small class="form-text text-muted">Kindly upload a screenshot or photo of your payment receipt.</small>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" id="submit-request-btn">Submit Request</button>
            </div>
          </div>
        </div>
      </div>

      <section class="row m-0">
        <div class="d-flex flex-row justify-space-between align-items-center mb-2 mt-2">
          <button id="submitButton" class="ms-auto btn btn-success" type="button">
            <i class="fa-solid fa-check ms-1"></i>
            Proceed Payment
          </button>
        </div>

        <div class="col-12 col-md-6">
          <div class="card">
            <div class="card-header">
              <h5>Request Form</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12 col-lg-6 mb-4 mb-lg-0">
                  <div class="form-group">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text" value="<?php echo $userData['stuname']; ?>" class="form-control" disabled />
                  </div>
                </div>
                <div class="col-6 col-lg-3">
                  <div class="form-group">
                    <label class="form-label fw-semibold">Sex</label>
                    <select class="form-select" disabled>
                      <option value="<?php echo $userData['sex']; ?>"><?php echo $userData['sex']; ?></option>
                    </select>
                    <input type="hidden" name="sex" value="<?php echo $userData['sex']; ?>">
                  </div>
                </div>
                <div class="col-6 col-lg-3">
                  <div class="form-group">
                    <label class="form-label fw-semibold">Birthday</label>
                    <input type="date" class="form-control" value="<?php echo $userData['birthday']; ?>" disabled />
                    <input type="hidden" name="birthday" value="<?php echo $userData['birthday']; ?>">
                  </div>
                </div>
              </div>
              <div class="row mt-4">
                <div class="col-12 col-lg-8">
                  <div class="form-group">
                    <label class="form-label fw-semibold">Permanent Address</label>
                    <input type="text"
                      value="<?php echo $userData['street'] . ", " . $userData['barangay'] . ", " . $userData['city'] . ", " . $userData['province']; ?>"
                      class="form-control" disabled />
                  </div>
                </div>
                <div class="col-12 col-lg-4">
                  <div class="form-group">
                    <label class="form-label fw-semibold">Mobile No</label>
                    <input type="text" value="<?php echo $userData['contact_number']; ?>" class="form-control"
                      disabled />
                  </div>
                </div>
              </div>
              <div class="row mt-4">
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label class="form-label fw-semibold">School Last Attended (SLA) (Before BPC)</label>
                    <input type="text" class="form-control" name="school_last_attended" placeholder="STI College"
                      required />
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label class="form-label fw-semibold">Year Completed (SLA)</label>
                    <input type="date" class="form-control" name="school_last_attended_graduated" required />
                  </div>
                </div>
              </div>
              <div class="row mt-4">
                <div class="col-12 col-lg-2">
                  <div class="form-group">
                    <label class="form-label fw-semibold">Student ID</label>
                    <input type="text" value="<?php echo $userData['stuid']; ?>" class="form-control" disabled />
                  </div>
                </div>
                <div class="col-6 col-lg-3">
                  <div class="form-group">
                    <label class="form-label fw-semibold">Course</label>
                    <select class="form-select" name="student_course" required>
                      <option value="BSIS">BSIS</option>
                      <option value="BSOM">BSOM</option>
                      <option value="BSAIS">BSAIS</option>
                      <option value="BTVTEd">BTVTEd</option>
                      <option value="ACT">ACT</option>
                    </select>
                  </div>
                </div>
                <div class="col-6 col-lg-3">
                  <div class="form-group">
                    <label class="form-label fw-semibold">Year</label>
                    <select class="form-select" name="student_year" required>
                      <option value="1st">1st</option>
                      <option value="2nd">2nd</option>
                      <option value="3rd">3rd</option>
                      <option value="4th">4th</option>
                    </select>
                  </div>
                </div>
                <div class="col-6 col-lg-4">
                  <div class="form-group">
                    <label class="form-label fw-semibold">Section</label>
                    <select class="form-select" name="student_section">
                      <?php
                      include("api/connection.php");
                        $get_all_sections = $conn -> query("SELECT name FROM sections");
                        while($row = $get_all_sections -> fetch_assoc()){
                          echo "<option value='".$row['name']."'>".$row['name']."</option>";
                        }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="mt-4 col-6 col-lg-5">
                  <div class="form-group">
                    <label class="form-label fw-semibold">Date of Graduation</label>
                    <input type="date" class="form-control" name="date_of_graduation" required />
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
                          <input class='form-check-input' name='document_to_request[]' type='checkbox' value='" . $row['name'] . "' data-price='" . $row['price'] . "' />
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


            </div>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="card mt-2 mt-lg-0">
            <div class="card-header">
              <h5>Purpose of Request</h5>
            </div>
            <div class="card-body">
              <div class="form-group">
                <select class="form-select" name="request_purpose" id="requestPurposeSelect">
                  <option value="Enrollment Purpose">Enrollment Purpose</option>
                  <option value="Employment Purpose">Employment Purpose</option>
                  <option value="others">Others: (Please indicate)</option>
                </select>
                <input type="text" id="otherPurposeInput" class="form-control mt-2"
                  placeholder="Please indicate your purpose" style="display: none;" />
              </div>
            </div>

          </div>
          <div class="card mt-2 mt-lg-4">
            <div class="card-header">
              <h5>Pickup Type</h5>
            </div>
            <div class="card-body">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="pickup_type" id="myself" value="self" checked>
                <label class="form-check-label" for="myself">Self</label>
              </div>
              <div class="form-check mt-2">
                <input class="form-check-input" type="radio" name="pickup_type" id="authorizedPerson"
                  value="authorized_person">
                <label class="form-check-label" for="authorizedPerson">Authorized Person</label>
              </div>
              <div id="authUploadSection" class="mt-3 d-none">
                <p class="fw-semibold">AUTHORIZED PERSON NAME</p>
                <input type="text" name="authorized_person_name" value="None" class="form-control" required>
                <p class="fw-semibold mt-4">RELATIONSHIP</p>
                <select name="authorized_person_relationship" class="form-control" required>
                  <option>Sibling</option>
                  <option>Parent</option>
                  <option>Spouse</option>
                </select>
                <label for="authImages" class="form-label fw-semibold mt-4">2 VALID IDs AND RECENT SELFIE OF AUTHORIZED
                  PERSON</label>
                <input type="file" name="authorized_person_attachments[]" class="form-control" id="authImages" multiple
                  accept="image/*">
                <div class="preview mt-2"></div>
              </div>
            </div>
          </div>
          <div class="card mt-2 mt-lg-4">
            <div class="card-header">
              <h5>Attachments</h5>
            </div>
            <div class="card-body">
              <p class="mb-2">Kindly attach required documents and supporting documents if necessary.</p>
              <input type="file" name="attachments[]" class="form-control" id="authImages" multiple accept="image/*">
            </div>
          </div>
        </div>
      </section>
    </form>
  </main>

  <script>
    var REQUEST_FORM_DATA = [];
    var TOTAL_PRICE = 0;

    $("#requestForm").submit(function (e) {
      e.preventDefault();

      var formData = new FormData(this)

      $.ajax({
        url: 'api/v2_request/insert.php',
        type: 'post',
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: () => {
          $("#submit-request-btn").attr('disabled', true);
          $("#submit-request-btn").html("Submitting... (PLEASE DO NOT CLOSE OR RELOAD THE PAGE");
        },
        success: response => {
          try {
            let json = JSON.parse(response);

            if (json.status === true) {
              Swal.fire({
                title: json.message,
                text: json.description,
                icon: "success",
                confirmButtonText: "View My Request"
              }).then((result) => {
                if (result.isConfirmed) {
                  location.href = "profile.php";
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
            $("#submitButton").attr('disabled', false);
            $("#submitButton").html("Submit");
          }
        },
        error: () => {
          Swal.fire("Error", "Request failed. Please try again.", "error");
          $("#submit-request-btn").attr('disabled', false);
          $("#submit-request-btn").html("Submit");
        }
      });

    })

    $(document).ready(function () {
      $('#requestPurposeSelect').on('change', function () {
        const isOthers = $(this).val() === 'others';
        $('#otherPurposeInput').toggle(isOthers);
        $('#otherPurposeInput').prop('required', isOthers);
      });

      // Optional: On form submit, update the value of the select
      $('#requestForm').on('submit', function () {
        if ($('#requestPurposeSelect').val() === 'others') {
          // Replace select's value with the text input's value
          const otherValue = $('#otherPurposeInput').val();
          $('<input>').attr({
            type: 'hidden',
            name: 'request_purpose',
            value: otherValue
          }).appendTo(this);

          // Prevent the select value "others" from being submitted
          $('#requestPurposeSelect').prop('disabled', true);
        }
      });
    });

    $(document).ready(function () {
      $("#requestForm").on('change', function () {
        TOTAL_PRICE = 0;

        $('input[name="document_to_request[]"]:checked').each(function () {
          let price = $(this).data('price');

          if (!isNaN(parseFloat(price))) {
            TOTAL_PRICE += parseFloat(price);
          }
        });

        // Now 'total' holds the sum of the data-price of all checked checkboxes
        $("#total_price_display").text("PHP " + TOTAL_PRICE.toFixed(2))
      });

      // Show/Hide file input based on radio selection
      $('input[name="pickup_type"]').change(function () {
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
      $('#authImages').on('change', function () {
        let files = this.files;
        let previewContainer = $('.preview');
        previewContainer.empty(); // Clear previous images

        if (files.length < 2) {
          alert("Please select at least 2 images.");
          $(this).val(''); // Reset input
          return;
        }

        $.each(files, function (index, file) {
          let reader = new FileReader();
          reader.onload = function (e) {
            let img = $('<img>').attr('src', e.target.result).addClass('img-thumbnail');
            previewContainer.append(img);
          };
          reader.readAsDataURL(file);
        });
      });
    });

    $(document).on("click", "#submitButton", function () {
      const checkedDocs = $("input[name='document_to_request[]']:checked");

      if (checkedDocs.length === 0) {
        alert("Please select at least one document type.");
        return;
      }

      $("#paymentModal").modal("toggle")
    });
  </script>
</body>

</html>