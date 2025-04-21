<?php
session_start();

if (!isset($_GET['request_id'])) {
  header("location: request.php");
} else {
  include("api/connection.php");

  $requestID = $_GET['request_id'];

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
  <?php include("static-loader.php"); ?>
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>
</head>

<body>
  <style>
    main {
      min-height: 100vh;
    }

    section>div {
      background-color: #fff;
    }

    @media (max-width: 992px) {
      section>div {
        padding: 1rem;
      }

      .submitrequest-desktop {
        display: none;
      }
    }

    .fc-col-header-cell-cushion {
      text-decoration: none !important;
      font-weight: 600 !important;
      color: #000 !important;
    }

    .fc-day-disabled {
      background-color: rgb(212, 212, 212) !important;
      pointer-events: none;
      opacity: 0.25;
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

  <!-- REJECT REQUEST MODAL -->

  <div class="modal fade" id="RejectModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="fw-semibold mb-2">Select Reason</h5>
        </div>
        <form id="rejectForm">
          <div class="modal-body">
            <div class="form-group d-flex flex-column 4">
              <input type="hidden" name="request_id" />
              <select name="reason" class="form-control">
                <option value="Invalid Information">Invalid Information</option>
                <option value="Student didn't exist">Student didn't exist</option>
                <option value="Unsettled Balance">Unsettled Balance</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger">Reject</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- RELEASE MODAL -->
  <div class="modal fade" id="releaseModal" tabindex="-1" aria-labelledby="releaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="releaseModalLabel">Select Release Date</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="releaseForm">
          <div class="modal-body">
            <div class="w-100 p-2">
              <div id="calendar"></div>
            </div>
            <input type="hidden" name="release_date" id="release_date" />
            <input type="hidden" name="request_id" id="request_id" />
            <div class="card mt-4">
              <div class="card-body">
                <h2 id="selected_date" class="text-center">No Selected Date</h2>
                <h4 id="slot_available" class="text-center mt-2">No Selected Date</h4>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-success" type="submit">Release</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <main class="container-fluid d-flex flex-lg-row flex-column p-0">
    <?php include("../reusables/admin-sidebar.php"); ?>
    <div class="modal fade" id="processPaymentModal" tabindex="-1" aria-labelledby="processPaymentModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="processPaymentModalLabel">Process Payment</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="paymentForm">

            <div class="modal-body">
              <div class="mb-3">
                <label for="paymentReceipt" class="form-label">Payment Receipt</label>
                <input type="hidden" name="request_id" value="<?php echo $requestID; ?>">
                <input type="file" class="form-control" id="paymentReceipt" name="paymentReceipt" accept="image/*"
                  required>
                <div id="receiptPreview" class="mt-2" style="display: none;">
                  <img id="receiptImg" src="" alt="Payment Receipt Preview" class="img-fluid"
                    style="max-width: 100%;" />
                </div>
              </div>

              <div class="mb-3">
                <label for="transactionNumber" class="form-label">Transaction Number</label>
                <input type="text" class="form-control" id="transactionNumber" name="transactionNumber" required>
              </div>

              <div class="mb-3">
                <label for="paymentDate" class="form-label">Payment Date</label>
                <input type="date" class="form-control" id="paymentDate" name="paymentDate" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Submit Payment</button>
            </div>
          </form>

        </div>
      </div>
    </div>

    <div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="processPaymentModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="processPaymentModalLabel">Send Message</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="sendMessageForm">
            <div class="modal-body">
              <input type="hidden" name="request_id" value="<?php echo $requestID; ?>">
              <textarea class="form-control" name="message" rows="4" placeholder="Enter your message here..."></textarea>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Send Message</button>
            </div>
          </form>

        </div>
      </div>
    </div>

    <section class="col-12 col-lg-10 p-2">
      <div class="d-flex flex-row mb-4">
        <a href="javascript:history.back()" class="btn btn-primary">
          <i class='fas fa-arrow-left me-1'></i> Back
        </a>

        <div class="ms-auto">
        <?php
        if ($_SESSION['staffrole'] === "Cashier") {
          if ($row['payment_status'] == "Not Paid") {
            echo "
                <button class='btn btn-success ms-auto' data-bs-toggle='modal' data-bs-target='#processPaymentModal'>
                  <i class='fas fa-cash-register me-1'></i> Process Payment
                </button>
              ";
          }
        }
        ?>

          <?php
          if ($_SESSION['staffrole'] === "Staff") {
            echo "
            <button class='btn btn-primary ms-auto send-message-button' data-bs-toggle='modal' data-bs-target='#sendEmailModal'>
              <i class='fas fa-envelope me-1'></i> Send Message
            </button>";

            if ($row['status'] === "Pending") {
              echo "
                  <button class='btn btn-danger ms-auto reject-button' data-request-id='{$row['request_id']}'>
                    <i class='fas fa-x me-1'></i> Reject
                  </button>

                  <button class='btn btn-success ms-auto approve-button' data-request-id='{$row['request_id']}'>
                    <i class='fas fa-check me-1'></i> Approve
                  </button>
                ";
            }

            if ($row['status'] === "Processing") {
              if ($row['payment_status'] == "Not Paid") {
                echo "
                    <button class='btn btn-success inform-button' data-request-id='{$row['request_id']}'>
                      <i class='fas fa-circle-info me-1'></i> Inform Student For Payment
                    </button>
                  ";
              } else {
                echo "
                    <button class='btn btn-success schedule-release-button' data-request-id='{$row['request_id']}'>
                      <i class='fas fa-clock me-1'></i> Schedule Release
                    </button>
                  ";
              }
            }

            if ($row['status'] === "For Release") {
              echo "
                  <button class='btn btn-success ms-auto complete-request-button' data-request-id='{$row['request_id']}'>
                    <i class='fas fa-check me-1'></i> Complete Request
                  </button>
                ";
            }
          }
          ?>
        </div>


      </div>

      <style>
        .grid-2-col {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
          gap: 1rem;
        }
      </style>
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
                <div class="col-md-5"><strong>Year:</strong></div>
                <div class="col-md-7"><?php echo $row["student_year"]; ?></div>
              </div>
              <div class="row mb-2">
                <div class="col-md-5"><strong>Section:</strong></div>
                <div class="col-md-7"><?php echo $row["student_section"]; ?></div>
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
                <div class="col-md-8"><?php echo date('F j, Y g:ia', strtotime($row['date_created'])); ?></div>
              </div>
            </div>
          </div>

          <div class="card mb-3">
            <div class="card-header">
              <h5>Requested Documents</h5>
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
          $requestFolder = "../images/requests/" . $row['request_id'];
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

  <!-- FullCalendar logic -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const calendarEl = document.getElementById('calendar');
      const releaseModal = document.getElementById('releaseModal');
      const ReleaseDateInput = document.getElementById("release_date")

      const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        themeSystem: 'bootstrap5',
        weekends: false,
        selectable: true,
        events: async function (info, successCallback, failureCallback) {
          try {
            const res = await fetch('api/get_all_available_dates.php');
            const data = await res.json();

            // Convert to FullCalendar event format
            const events = data.map(item => ({
              id: item.date, // Use the date as the unique id
              title: `${item.slot_available} slots`,
              start: item.date,
              allDay: true,
              display: 'background',
              extendedProps: {
                slot_available: item.slot_available
              }
            }));

            successCallback(events);
          } catch (error) {
            console.error('Error fetching available dates:', error);
            failureCallback(error);
          }
        },
        validRange: function (nowDate) {
          const start = nowDate; // today
          const end = new Date(nowDate);
          end.setMonth(end.getMonth() + 2); // add 2 months

          return {
            start: start,
            end: end
          };
        },
        dateClick: function (info) {
          const today = new Date().setHours(0, 0, 0, 0);
          const date = info.date;
          const dateStr = date.toISOString().split('T')[0];

          const event = calendar.getEventById(info.dateStr);
          const slotAvailable = event.extendedProps.slot_available;

          if (date < today || date.getDay() === 0 || date.getDay() === 6) {
            return; // Do nothing if it's a past date or weekend or no available slots
          }

          const formattedDate = date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
          });

          document.getElementById("selected_date").innerText = formattedDate;
          document.getElementById("slot_available").innerText = slotAvailable + " slots available";
          document.getElementById("selected_date").style.color = 'green';

          ReleaseDateInput.value = info.date;
        }
      });

      releaseModal.addEventListener('shown.bs.modal', function () {
        if (calendar) {
          calendar.render();
        }
      });
    });
  </script>


  <script>
    $("#releaseForm").on("submit", function (event) {
      event.preventDefault();

      var formdata = new FormData(this);

      $.ajax({
        type: "post",
        url: "api/schedule_release.php",
        data: formdata,
        processData: false, // Don't process data, because FormData will handle it
        contentType: false, // Don't set content type, because FormData will handle it
        beforeSend: () => {
          $("#releaseModal").modal('toggle')
          toggleLoadingModal()
        },
        success: function (response) {
          const json = JSON.parse(response);

          if (json.status === "success") {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: json.message,
            }).then(() => {
              location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: jsonResponse.message || 'An error occurred while scheduling the release.',
            });
          }

          toggleLoadingModal()
        }
      })
    })

    $("#sendMessageForm").on("submit", function (event) {
      event.preventDefault();

      var formdata = new FormData(this);

      $.ajax({
        type: "post",
        url: "api/send-message.php",
        data: formdata,
        processData: false, // Don't process data, because FormData will handle it
        contentType: false, // Don't set content type, because FormData will handle it
        beforeSend: () => {
          $("#sendEmailModal").modal('toggle')
          toggleLoadingModal()
        },
        success: function (response) {
          const json = JSON.parse(response);

          if (json.status === "success") {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: json.message,
            }).then(() => {
              location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: jsonResponse.message || 'An error occurred while scheduling the release.',
            });
          }

          toggleLoadingModal()
        }
      })
    })

    $(document).on("click", ".schedule-release-button", function () {
      let requestId = $(this).data("request-id");

      $("#request_id").val(requestId);
      $("#releaseModal").modal("toggle");
    });

    $(document).on("click", ".reject-button", function () {
      let requestId = $(this).data("request-id");

      $("input[name='request_id']").val(requestId);
      $("#RejectModal").modal("toggle");
    });

    $(document).on("click", '.approve-button', function () {
      let target = $(this).data('request-id');

      Swal.fire({
        title: "Confirm Approval",
        text: "Are you sure you want to approve this request?",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Approve",
        buttonsStyling: false, // Needed to use custom Bootstrap styles
        customClass: {
          confirmButton: 'btn btn-success me-2',
          cancelButton: 'btn btn-secondary'
        }
      }).then((result) => {
        if (result.isConfirmed) {
    $.ajax({
      url: 'api/post_approve_request.php',
      type: 'POST',
      data: {
        requestid: target,
        staffname: "<?php echo $_SESSION['staffname']; ?>" // make sure `staffname` is available in JS (e.g. set via PHP echo)
      },
      success: function (response) {
        Swal.fire({
          title: 'Request Approved!',
          text: 'The request has been successfully approved.',
          icon: 'success'
        }).then(() => {
          location.reload();
        });
      },
      error: function () {
        Swal.fire({
          title: 'Error',
          text: 'There was an issue processing the approval.',
          icon: 'error'
        });
      }
    });
  }
      });
    })

    $(document).on("click", ".complete-request-button", function(){
      let requestId = $(this).data('request-id');

      Swal.fire({
        title: "Complete Request",
        text: "Are you sure you want to complete the request?",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Complete Request",
        buttonsStyling: false,
        customClass: {
          confirmButton: 'btn btn-success me-2',
          cancelButton: 'btn btn-secondary'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: 'api/post_complete_request.php',
            method: 'POST',
            data: {
              request_id: requestId
            },
            beforeSend: () => {
              toggleLoadingModal();
            },
            success: function (response) {
              let json = JSON.parse(response)

              console.log(json)

              Swal.fire({
                icon: json.status,
                title: json.message,
                confirmButtonText: 'Ok',
                buttonsStyling: false,
                customClass: {
                  confirmButton: 'btn btn-primary'
                }
              }).then(() => {
                location.reload();
              });

              toggleLoadingModal()
            }
          });
        }
      });
    })

    $(document).on("click", '.inform-button', function () {
      let requestId = $(this).data('request-id');

      Swal.fire({
        title: "Confirm Notification",
        text: "Are you sure you want to inform the student to pay?",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Send Email",
        buttonsStyling: false,
        customClass: {
          confirmButton: 'btn btn-success me-2',
          cancelButton: 'btn btn-secondary'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: 'api/send_payment_notification.php',
            method: 'POST',
            data: {
              request_id: requestId
            },
            beforeSend: () => {
              toggleLoadingModal();
            },
            success: function (response) {
              let json = JSON.parse(response)

              console.log(json)

              Swal.fire({
                icon: json.status,
                title: json.message,
                confirmButtonText: 'Ok',
                buttonsStyling: false,
                customClass: {
                  confirmButton: 'btn btn-primary'
                }
              }).then(() => {
                location.reload();
              });

              toggleLoadingModal()
            }
          });
        }
      });
    });

    $("#rejectForm").on("submit", function (e) {
      e.preventDefault();

      let request_id = $("input[name='request_id']").val();
      let reason = $("select[name='reason']").val();

      Swal.fire({
        title: "Confirm Reject",
        text: "Are you sure you want to reject this request?",
        icon: "info",
        showCancelButton: false,
        confirmButtonText: "Reject Request"
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: 'post',
            url: 'api/post_reject_request.php',
            data: {
              requestid: request_id,
              reason: reason
            },
            success: response => {
              let json = JSON.parse(response)
              Swal.fire({
                title: json.message,
                text: json.description,
                icon: json.status,
                showCancelButton: false,
                confirmButtonText: "Reload List"
              }).then((result) => {
                if (result.isConfirmed) {
                  location.href = 'request.php'
                }
              });
            }
          })
        }
      });
    })

    $('#paymentReceipt').on('change', function (event) {
      const file = event.target.files[0];
      const reader = new FileReader();
      reader.onload = function (e) {
        $('#receiptPreview').show();
        $('#receiptImg').attr('src', e.target.result);
      };
      reader.readAsDataURL(file);
    });

    $('#paymentForm').on('submit', function (e) {
      e.preventDefault();

      var formData = new FormData(this);
      $.ajax({
        url: 'api/process_payment.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function () {
          Swal.fire({
            title: 'Processing Payment...',
            text: 'Please wait.',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading()
            }
          });
        },
        success: function (response) {
          let json = JSON.parse(response);

          if (json.status === "success") {
            Swal.fire({
              icon: json.status,
              title: json.message,
              text: json.desciption,
              confirmButtonText: 'Ok'
            }).then(result => {
              if (result.isConfirmed) {
                location.reload()
              }
            });
          }

          $('#processPaymentModal').modal('hide');
        },
        error: function (xhr, status, error) {
          alert('There was an error processing the payment.');
        }
      });
    });
  </script>
</body>

</html>