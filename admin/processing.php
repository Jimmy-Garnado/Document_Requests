<?php
session_start();
include_once("api/connection.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>In-Processing Requests - <?php echo $CONTENT['system_name']; ?></title>
  <?php include("static-loader.php"); ?>

  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>
  <!-- <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css' rel='stylesheet' /> -->
  <style>
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
  </style>
</head>

<body>
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
    <div class="col-12 col-lg-10 p-2 p-lg-4">
      <h4 class="mb-4 fw-bold">In-Processing</h4>
      <div class="table-responsive">
        <table id="example" class="display table" style="width:100%">
          <thead>
            <tr>
              <th>Request ID</th>
              <th>Client</th>
              <th>Document Type</th>
              <th>Date Requested</th>
              <th>Status</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </main>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const calendarEl = document.getElementById('calendar');
      const releaseModal = document.getElementById('releaseModal');
      const ReleaseDateInput = document.getElementById("release_date")

      const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        themeSystem: 'bootstrap5',
        weekends: false, // 👈 disable Saturday and Sunday
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


    $(document).on("click", ".releaseButton", function () {
      let requestId = $(this).data("request-id");

      $("#request_id").val(requestId);

      $("#releaseModal").modal("toggle");
    });

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

    $(document).ready(function () {
      $('#example').DataTable({
        ajax: 'api/get-all.php?table=v2_requests&where=Processing',
        columns: [{
            data: 'request_id',
            title: 'Request ID',
            render: function(data, type, row) {
              return '<a href="view.php?request_id=' + data + '">' + data + '</a>';
            }
          },
        {
          data: 'name',
          title: 'Client'
        },
        {
          data: 'document_to_request',
          title: 'Document Type',
          render: function (data) {
            try {
              let parsedData = JSON.parse(data);
              return Array.isArray(parsedData) ? parsedData.join(', ') : data;
            } catch (e) {
              return data;
            }
          }
        },
        {
          data: 'date_created',
          title: 'Date Requested',
          render: function (data) {
            return moment(data).format("MMMM D, YYYY h:mmA");
          }
        },
        {
          data: 'status',
          title: 'Status'
        }
        ]
      });
    })
  </script>
</body>

</html>